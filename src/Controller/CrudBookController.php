<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/crud/book')]
class CrudBookController extends AbstractController
{   //add a new book
    #[Route('/new', name: 'app_new_book')]
    public function newBook(BookRepository$repository,
                            ManagerRegistry $doctrine,
                            Request $request): Response
    {
        $book = new Book();
        $message="";
        $form= $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //check if the book exists
            $books=$repository->findByTitle($book->getTitle());
            if(count($books)==0){
                $em=$doctrine->getManager();
                $em->persist($book);
                $em->flush();
            }else{
                $message="Book already exists";
            }
        }
        return $this->render('crud_book/form.html.twig',
            ['form'=>$form->createView(), 'message'=>$message]);
    }

}
