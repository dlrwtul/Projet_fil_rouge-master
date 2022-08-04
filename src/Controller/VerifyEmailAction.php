<?php 

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VerifyEmailAction extends AbstractController {
    
    public function __invoke(UserRepository $userRepository,String $token): Response
    {
        $user = $userRepository->findOneBy(array('token' => $token));
        if ($user !=null && $user->getExpireAt() > new \DateTime()) {
            if ($user->isIsEtat() == false) {

                $user->setIsEtat(true);

                $userRepository->add($user,true);
    
                return $this->json("Suscribtion succes",200);

            } else {

                return $this->json("Conmpte deja activée",Response::HTTP_BAD_REQUEST);
            }

        } else {

            return $this->json("Your token is invalid",400);
        }
    }

}