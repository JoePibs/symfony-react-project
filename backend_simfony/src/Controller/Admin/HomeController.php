<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController {

    #[Route("/admin", name: "admin.home")]
    function index (): Response
    {
        return $this->render('admin/index.html.twig', [
            'title' => 'Home administration !'
        ]);
    }
}