<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/crud/author')]
class CrudAuthorController extends AbstractController
{
    #[Route('/list', name: 'app_crud_author')]
    public function listAuthor(AuthorRepository $repository ): Response
    {   //$repository= new AuthorRepository();
        $authors=$repository->findAll();
        return $this->render('crud_author/listAuthor.html.twig',
        ['authors'=>$authors]);
    }
}
