<?php 

namespace App\Controller;

use App\Entity\Zone;
use App\Entity\Quartier;
use App\Repository\ZoneRepository;
use App\Repository\QuartierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ZoneController extends AbstractController
{

    public function __invoke
    (
        Request $request,
        ZoneRepository $zoneRepository,
        DecoderInterface $decoder,
        DenormalizerInterface $denormalizer,
        QuartierRepository $quartierRepository,
        int $id = null
        )
    {
        try {

            $json = $request->getContent();
            
            $array = $decoder->decode($json,'json');

            if (isset($array["montantLivraison"])) {
                $array["montantLivraison"] = floatval($array["montantLivraison"]);
            }

            $zone = $denormalizer->denormalize($array,Zone::class);

            if ($request->getMethod() == 'PUT') {
                
                $check = $zoneRepository->findOneBy(array('id' => $id));

                foreach ($check->getQuartiers() as $quartier) {
                    if (isset($array["quartiers"])) {

                        if (!in_array($quartier->getId(),$array["quartiers"])) {
                            $check->removeQuartier($quartier);
                        }
                    }
                }
                if ($zone->getLibelle() != null) {
                    $check->setLibelle($zone->getLibelle());
                }
                if ($zone->getMontantLivraison() != null) {
                    $check->setMontantLivraison($zone->getMontantLivraison());
                }

                $zone = $check;

            }

            if ($request->getMethod() == 'POST') {

                $check = $zoneRepository->findOneBy(array('libelle' => $array['libelle']));

                if ($check != null) {

                    return $this->json(["status" => 400,"message"=>"Zone already in db"],400);

                }

            }

            if (isset($array["quartiers"])) {

                foreach ($array["quartiers"] as $quartier) {
                    $check = $quartierRepository->findOneBy(array('libelle' => $quartier));

                    if ($check != null) {
                        $newQuartier = $check;
                    } else {
                        $newQuartier = new Quartier();
                        $newQuartier->setLibelle($quartier);
                    }
                    if ($newQuartier != null) {
                        $zone->addQuartier($newQuartier);
                    } else {
                        return $this->json(["status" => 400,"message"=>"Zone invalid"],400);
                    }
                }

            }

            $zoneRepository->add($zone,true);
            
            return $this->json($zone, 201,[],['groups' => ['zone:read','zone:read']]);

        } catch (NotEncodableValueException $th) {

            return $this->json([

                "status"=> 400,
                "message" => $th->getMessage()

            ]);
        }
        

    }

}