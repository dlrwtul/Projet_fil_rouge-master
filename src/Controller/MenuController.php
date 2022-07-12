<?php 

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use App\Repository\TailleRepository;
use App\Repository\PortionFritesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MenuController extends AbstractController {

    public function __invoke
    (
        Request $request,
        MenuRepository $menuRepository,
        DecoderInterface $decoder,
        DenormalizerInterface $denormalizer,
        BurgerRepository $burgerRepository,
        TailleRepository $tailleRepository,
        PortionFritesRepository $portionFritesRepository,
        TokenStorageInterface $tokenStorage,
        int $id = null
        )
    {
        try {

            $json = $request->getContent();
            
            $dataArray = $decoder->decode($json,'json');

            $data = $denormalizer->denormalize($dataArray,Menu::class);

            if ($request->getMethod() == 'POST') {

                $check = $menuRepository->findOneBy(array('nom' => $dataArray['nom']));

                if ($check != null) {

                    return $this->json(["status" => 400,"message"=>"Menu already in db"],400);

                }

                $user = $tokenStorage->getToken()->getUser();
                $data->setUser($user);

                $prix = 0;
            }


            if ($request->getMethod() == 'PUT') {
                
                $check = $menuRepository->findOneBy(array('id' => $id));

                $this->removeForMenu("getTailles",$dataArray,"tailles","removeTaille",$check);
                $this->removeForMenu("getBurgers",$dataArray,"burgers","removeBurger",$check);
                $this->removeForMenu("getPortionfrites",$dataArray,"portionFrites","removePortionfrite",$check);

                $prix = $check->getPrix();

                $data = $check;

                $check2 = $menuRepository->findOneBy(array('nom' => $data->getNom()));

                if ($check2 !== null && $id != $check2->getId()) {
                    return $this->json(["status" => 400,"message"=>"Menu already in db"],400);
                }

            }
            
            $this->addForMenu($dataArray,"burgers",$burgerRepository,"addBurger",$data,$prix);
            $this->addForMenu($dataArray,"tailles",$tailleRepository,"addTaille",$data,$prix);
            $this->addForMenu($dataArray,"portionFrites",$portionFritesRepository,"addPortionFrite",$data,$prix);
            
            $data->setPrix($prix);
            
            $menuRepository->add($data,true);

            return $this->json($data, 201,[],['groups' => ['product:write','product:read','menu:read']]);

        } catch (NotEncodableValueException $th) {

            return $this->json([

                "status"=> 400,
                "message" => $th->getMessage()

            ]);
        }
    }

    public function removeForMenu(string $getter,array $dataArray,string $index,string $removeMethod,Menu &$menu){

        foreach ($menu->$getter() as $object) {
            if (!in_array($object->getId(),$dataArray[$index])) {

                $menu->$removeMethod($object);
                $menu->setPrix($menu->getPrix() - $object->getPrix());
            }
        }
    }

    public function addForMenu(array $dataArray,string $index,ServiceEntityRepository $repo,string $add,&$data,&$prix){
        foreach ($dataArray[$index] as $id) {
            $object = $repo->findOneBy(array('id' => $id));
            if ($object != null) {
                $data->$add($object);
                $prix+= $object->getPrix();
            } else {
                return $this->json(["status" => 400,"message"=>"taille invalid"],400);
            }
        }
    }

}