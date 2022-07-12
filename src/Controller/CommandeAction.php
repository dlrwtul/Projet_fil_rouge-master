<?php 

namespace App\Controller;

use App\Entity\BoissonTaille;
use App\Entity\Commande;
use App\Repository\MenuRepository;
use App\Repository\ZoneRepository;
use App\Repository\BurgerRepository;
use App\Entity\CommandeBoissonTaille;
use App\Repository\BoissonRepository;
use App\Repository\CommandeRepository;
use App\Repository\QuartierRepository;
use App\Repository\BoissonTailleRepository;
use App\Repository\CommandeBoissonTailleRepository;
use App\Repository\PortionFritesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandeAction extends AbstractController {

    public function __invoke
    (
        Request $request,
        DecoderInterface $decoder,
        DenormalizerInterface $denormalizer,
        TokenStorageInterface $tokenStorage,
        CommandeRepository $commandeRepository,
        MenuRepository $menuRepository,
        BurgerRepository $burgerRepository,
        PortionFritesRepository $portionFritesRepository,
        BoissonTailleRepository $boissonTailleRepository,
        ZoneRepository $zoneRepository,
        QuartierRepository $quartierRepository,
        ValidatorInterface $validatorInterface,
        CommandeBoissonTailleRepository $commandeBoissonTailleRepository,
    )
    {
        try {

            $json = $request->getContent();
            
            $dataArray = $decoder->decode($json,'json');


            $zone = $zoneRepository->findOneBy(array('id' => $dataArray['zone']["id"]));
            $quartier = $quartierRepository->findOneBy(array('id' => $dataArray['zone']["quartier"]));

            unset($dataArray['zone']);

            $data = $denormalizer->denormalize($dataArray,Commande::class);

            $data->setZone($zone);
            $data->setAdresseLivraison($zone->getLibelle()." ".$quartier->getLibelle());

            $user = $tokenStorage->getToken()->getUser();
            $data->setClient($user);

            $produits = $dataArray["produits"];

            $menus = $produits["menus"];

            $burgers = $produits["burgers"];

            $boissons = $produits["boissons"];

            $frites = $produits["frites"];

            $prix = 0;

            $this->addProduit($menuRepository,$menus,$data,$prix);
            $this->addProduit($burgerRepository,$burgers,$data,$prix);
            $this->addProduit($portionFritesRepository,$frites,$data,$prix);

            foreach ($boissons as $boisson) {

                $object = $boissonTailleRepository->findOneByBoissonTaille($boisson['id'],$boisson['taille']);
                $prix +=$object->getPrix();
                if (!null == $object) {
                    $check = $commandeBoissonTailleRepository->findOneCommandeBoissonTailleBy( $object->getId());
                    if ($check == null) {
                        $commandeBoissonTaille = new CommandeBoissonTaille($object,$boisson['quantite']);
                        $data->addCommandeBoissonTaille($commandeBoissonTaille);
                    } else {
                        $check->setQuantite($check->getQuantite() + $boisson['quantite']);
                        $commandeBoissonTailleRepository->add($check,true);
                    }
                    
                }
                
            }
            
            $data->setMontant($prix);

            $count = $commandeRepository->getCommandsCount()[0]["count"] + 1;

            $data->setNumero($data->generateNumero($count));

            dd($data);

            $errors = $validatorInterface->validate($data);
            $commandeRepository->add($data,true);

            return $this->json($data, 201,[],['groups' => ['commande:read']]);

        } catch (NotEncodableValueException $th) {

            return $this->json([

                "status"=> 400,
                "message" => $th->getMessage()

            ]);
        }
    }

    public function addProduit($repo,$produits,&$data,&$prix){
        foreach ($produits as $produit) {
            $object = $repo->findOneBy(array('id' => $produit["id"]));
            if (!null == $object) {
                if ($produit["quantite"] > 0) {
                    $prix += $object->getPrix();
                    $data->addCommandeProduit($object,$produit["quantite"]);
                }   
            }
        }
    }

}