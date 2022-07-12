<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Livreur;
use App\Service\MailerService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class RegistrationsController extends AbstractController {


    public function __invoke
    (
        Request $request,
        SerializerInterface $serializer,
        UserPasswordHasherInterface $hasher,
        ValidatorInterface $validator,
        UserRepository $userRepository,
        JWTTokenManagerInterface $JWTManager,
        MailerService $mailer
    ) : Response
    {

        $data = $request->getContent();

        try {

            $route = explode('/', $request->getRequestUri());
            unset($route[0],$route[1]);
            $route = array_values($route);

            if ($route[0] == "clients") {
                $user = $serializer->deserialize($data,Client::class,'json');
            } elseif ($route[0] == "livreurs") {
                $user = $serializer->deserialize($data,Livreur::class,'json');
            } else {
                $user = $serializer->deserialize($data,User::class,'json');
            }
                
            $errors = $validator->validate($user);

            if(count($errors) == 0) {

                $check = $userRepository->findOneBy(array('login' => $user->getLogin()));
                if (null == $check) {
                    if($user->getPlanPassword() == $user->getConfirmPassword()) {

                        $hashed = $hasher->hashPassword($user,$user->getPlanPassword());
                        $user->setPassword($hashed);
                        $user->setIsEtat(false);
                        $user->eraseCredentials();
                        //$token = $JWTManager->create($user);
                        $token = $user->getToken();
                        $userRepository->add($user,true);

                        $mailer->sendMail($user->getUserIdentifier(),$token);
                    
                        return $this->json("veuiller Confirmer votre email", 200);
    
                    } else {

                        return $this->json(["status" => 400,"message"=>"Invalid password confirm"],400);

                    }
                } else {

                    return $this->json(["status" => 400,"message"=>"Email already in db"],400);

                }
                
            } else {
                return $this->json($errors,400);
            }
            
        } catch (NotEncodableValueException $th) {

            return $this->json([

                "status"=> 400,
                "message" => $th->getMessage()

            ]);
        }
    }

    //#[Route(path:'api/verify_mail/{token}',name:'app_verify_mail', methods:['GET'])]
    /* public function verifyUserEmail(Request $request,UserRepository $userRepository,String $token): Response
    {
        $tokenParts = explode(".", $token);  
        //$tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        //$jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        $user = $userRepository->findOneBy(array('login' => $jwtPayload->username));

        if ($user !=null) {

            $user->setIsEtat(true);

            return $this->json("Suscribtion succes",200);

        } else {

            return $this->json("Your token is invalid",400);
        }
    } */
}