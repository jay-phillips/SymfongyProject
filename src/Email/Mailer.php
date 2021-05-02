<?php

namespace App\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
require_once 'C:\Users\romey\Documents\symfony-course\project\vendor\autoload.php';


class Mailer
{
    
    /**
     * @var MailerInterface
     */
    private $mailer;

  public function __construct(
      MailerInterface $mailer
      
  )
  {
      $this->mailer= $mailer;

  }

  public function sendConfirmationEmail(User $user)
  {
    $loader = new \Twig\Loader\FilesystemLoader('C:\Users\romey\Documents\symfony-course\project\templates');
     $twig = new \Twig\Environment($loader, [
     ]);

    $body = $twig->render(
        'email/confirmation.html.twig',
        [
            'user' => $user
        ]

        );    
        $email = (new TemplatedEmail())
        ->from('api-platform@api.com')
        ->to($user->getEmail())
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Please confirm your account!')
        ->html($body);

    $this->mailer->send($email);
  }

}

