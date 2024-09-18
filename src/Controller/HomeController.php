<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {   $name="Aziz";
        $email= "aziz@gmail.com";
        return $this->render('home/contact.html.twig',
        array(
            'name' => $name,
            'email' => $email
        )
        );
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/home.html.twig');
    }


}
