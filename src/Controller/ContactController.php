<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();

        // TODO : A SUPPRIMER
        $data->name = 'John Doe';
        $data->email = 'johndoe@gmail.com';
        $data->message = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum nesciunt, inventore esse animi aperiam nulla aliquam quia similique! Autem quod maiores perferendis ratione officiis hic dolorum! Sit et amet architecto?
        Deserunt doloremque nesciunt enim iure minima quos adipisci eaque culpa. Error, aspernatur natus explicabo culpa maxime quo eum earum. Consectetur neque eos aspernatur totam, ut explicabo deserunt omnis adipisci culpa?;";

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mail =( new TemplatedEmail())
                ->to('contact@demo.fr')
                ->from($data->email)
                ->subject('Demande de contact')
                ->context(['data' => $data])
                ->htmlTemplate('emails/contact.html.twig');

            $mailer->send($mail);

            $this->addFlash('success', "Votre email a bien été envoyé !");
            $this->redirectToRoute('contact');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
