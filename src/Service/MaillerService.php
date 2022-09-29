<?php

namespace App\Service;

use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;



class MaillerService
{
    private $replyTo;
    public function __construct(private MailerInterface $mailer, $replyTo )
    {
        $this->replyTo = $replyTo;
    }
    public function sendEmail(
        $to = 'Aukidev35@outlook.fr',
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $subject = '<p>See Twig integration for better HTML integration!</p>'
    ): Void
    {


        $email = (new Email())
            ->from('aukidevprojet@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            // ->text('Sending emails is fun again!')
            ->html($content);

        $this->mailer->send($email);
    }
}