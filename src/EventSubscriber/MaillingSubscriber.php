<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\ContactRequestEvent;
use App\Event\RecipeCreatedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class MaillingSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly MailerInterface $mailer) {
       
    }
    public function onContactRequestEvent(ContactRequestEvent $event): void
    {
        $data = $event->data;
        $mail = (new TemplatedEmail())
            ->to('contact@demo.fr')
            ->from($data->email)
            ->subject('Demande de contact')
            ->context(['data' => $data])
            ->htmlTemplate('emails/contact.html.twig');

        $this->mailer->send($mail);
    }

    public function onRecipeCreatedEvent(RecipeCreatedEvent $event)
    {
        $data = $event->data;
        $mail = (new Email())
            ->to($data->getUser()->getEmail())
            ->from('support@demo.fr')
            ->subject('Création de recette')
            ->text("Vous venez de créer la recette {$data->getTitle()}");

        $this->mailer->send($mail);
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class => 'onContactRequestEvent',
            RecipeCreatedEvent::class =>'onRecipeCreatedEvent',
        ];
    }
}
