<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactFormType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class HomeController extends AbstractController {

    #[Route("/", name: "home")]
    function index (): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => 'Bonjour les gens !'
        ]);
    }
}