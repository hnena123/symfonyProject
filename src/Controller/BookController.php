<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;


class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/listBook',name:'list_Book')]
    public function list(BookRepository $repository){
    $Books = $repository->findAll();
    return $this->render("Book/list.html.twig",array('tabBooks'=>$Books));
    }
    #[Route('/addFormBook', name: 'add_form Book')]
    public function addForm(Request $request,ManagerRegistry $managerRegistry)
    {
        $Book = new Book();
        $form = $this->createForm(BookType::class,$Book);
        $form ->handleRequest($request);
        if($form->isSubmitted())
        {
            $em= $managerRegistry->getManager();
            $em->persist($Book);
            $em->flush();

            return $this->redirectToRoute('list_Book');
        }
        return $this->renderForm("Book/FormAddBook.html.twig",array("formulaireBook"=>$form));
    }
    #[Route('/updateBook/{ref}', name: 'update_book')]
    public function updateBook($ref,BookRepository $repository,ManagerRegistry $manager, Request $request): Response
    {
        $book = $repository->find($ref);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $manager->getManager();
            $em->flush();
            return $this->redirectToRoute('list_book');
        }
        return $this->renderForm('book/updateBook.html.twig', ['form' => $form]);
    }

    #[Route('/showBook/{ref}', name: 'show_book')]
    public function showAuther($ref,BookRepository $repository)
    {
        return $this->render('book/show.html.twig',
            array('book'=>$repository->find($ref)));
    }
    #[Route('/deleteBook/{ref}', name:'delete_book')]
    public function delete($ref,BookRepository $repository,ManagerRegistry $managerRegistry)
    {
        $book= $repository->find($ref);
        $em= $managerRegistry->getManager();

            $em->remove($book);
            $em->flush();

        return $this->redirectToRoute("list_book");
    }
    #[Route('/listbook/{id}', name: 'listbookbyAuther')]
    public function findBooksByAuther($id,BookRepository $repository)
    {
        $Books=$repository->findBooksByAuther($id);
        return $this->render("book/listbookbyAuther.html.twig",array("tabBooks"=>$Books));
    }


}
