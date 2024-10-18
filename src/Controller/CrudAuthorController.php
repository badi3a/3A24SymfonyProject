<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    //search authors by Name
    #[Route('/search', name:'app_search_author')]
    public function searchByName(Request $request, AuthorRepository $repository):Response
    {
        $name= $request->query->get('name');
        //fetch the authors from the daba base by name
        $authors=$repository->findByName($name);
       //test de code: var_dump($authors); die();
        return $this->render('crud_author/listAuthor.html.twig',
            ['authors'=>$authors]);
    }

    //method to insert author into the database
    #[Route("/insert",name:"app_insert_author")]
    public function insertAuthor(ManagerRegistry $doctrine):Response
    {
        //1.crate a static instance
        $author= new Author();
        $author->setName('ahmed');
        $author->setEmail('ahmed@gmail');
        $author->setNbrBooks(5);
        //2.crate a copy of the doctrine with entityManager: $em
        //2.a: use Doctrine\Persistence\ManagerRegistry;
           $em=$doctrine->getManager();
        //2.persist object in the doctrine layer
            $em->persist($author);
        //3.save data: object in the database
           $em->flush();
           return $this->redirectToRoute("app_crud_author");
    }
   //delete object from the data base using the primary key: ID
    #[Route("/delete/{id}",name:"app_delete_author")]
    public function deleteAuthor($id, AuthorRepository $repository, ManagerRegistry $doctrine):Response
    {
        //get the object from the database
        $author=$repository->find($id);
        //2.crate a copy of the doctrine with entityManager: $em
         //2.a: use Doctrine\Persistence\ManagerRegistry;
        $em=$doctrine->getManager();
        //3. remove the object from the doctrine layer
        $em->remove($author);
        //4. save the updates in the database
        $em->flush();
        return $this->redirectToRoute("app_crud_author");
    }
    //update an author with a specific ID
    #[Route("/update/{id}",name:"app_update_author")]
    public function updateAuthor(Author $author,ManagerRegistry $doctrine):Response
    {
        //step1.get the object from the database
        //$author=$repository->find($id);
        $em=$doctrine->getManager();
        $author->setEmail('updated email');
        $em->flush();
        return $this->redirectToRoute("app_crud_author");
    }
     //method to add a new author with a form submitted by the user
    #[Route("/insertForm",name:"app_insertForm_author")]
    public function insertFormAuthor(Request $request, ManagerRegistry $doctrine):Response
    {   $author= new Author();
        $form= $this->createForm(AuthorType::class,$author);
         $form=$form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
             $em=$doctrine->getManager();
             $em->persist($author);
             $em->flush();
             return $this->redirectToRoute("app_crud_author");
         }
        return $this->render('crud_author/formAuthor.html.twig',
        ['form'=>$form->createView()]);
    }
}
