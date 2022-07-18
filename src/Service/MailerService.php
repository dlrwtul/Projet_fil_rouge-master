<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private $mailer;
    static $EmailFrom = "6fa8f10b12-eda015@inbox.mailtrap.io";

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMailConfirmation(string $email,string $token)
    {
        $date = new \DateTime('+1 days');
        $message = (new TemplatedEmail())
                    ->from(self::$EmailFrom)
                    ->to($email)
                    ->subject('Email address confirmation')
                    ->htmlTemplate("mailer/sendMail.html.twig")
                    ->context([
                        'expireAt' => $date->format("d:m:y"),
                        'token' => $token,
                    ]);
        $this->mailer->send($message);

    }

    public function sendEmail($email) {

        $message = (new TemplatedEmail())
                    ->from(self::$EmailFrom)
                    ->to($email)
                    ->subject("Notification de Commande Brazil Burger")
                    ->htmlTemplate("mailer/sendMailCommandeTermine.html.twig")
                    ;
        $this->mailer->send($message);
    }
}