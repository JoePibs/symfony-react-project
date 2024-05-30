<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactFormType;
use App\DTO\ContactDTO;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();
        $form = $this->createForm(ContactFormType::class);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $data['submitted_at'] = (new \DateTime())->format('Y-m-d H:i:s');
                $mail = (new TemplatedEmail())
                    ->from($data['email'])
                    ->to($data['service_email'])
                    ->subject($data['subject'].' submit at: '.$data['submitted_at'])
                    ->text($data['content'])
                    ->htmlTemplate('contact/contact.html.twig')
                    ->context([
                        'data' => $data
                    ]);
                $mailer->send($mail);
                $this->addFlash('success', 'Message sent');
                return $this->redirectToRoute('contact');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Message not sent'.$e->getMessage());
            }
        
            return $this->redirectToRoute('contact');
        }
        Return $this->render('contact/form.html.twig', ['form' => $form]);
    }
}
