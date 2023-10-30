<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Auther;
use App\Repository\AutherRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AutherType;


class AutherController extends AbstractController
{
    #[Route('/auther', name: 'app_auther')]
    public function index(): Response
    {
        return $this->render('auther/index.html.twig', [
            'controller_name' => 'AutherController',
        ]);
    }
    #[Route('/showauther/{var}',name:'show_auther')]
    public function showAuther($var)
    {
        return $this->render("auther/show.html.twig",array('nameAuther'=>$var));
    }
    #[Route('/listeauther',name:'liste_auther')]
    public function listeAuther()
    {
        $authers = array(

            array('id' => 1, 'username' => ' Victor Hugo','email'=> 'victor.hugo@gmail.com', 'nb_books'=> 100),

    array ('id' => 2, 'username' => 'William Shakespeare','email'=>

'william.shakespeare@gmail.com','nb_books' => 200),

    array('id' => 3, 'username' => ' Taha Hussein','email'=> 'taha.hussein@gmail.com','nb_books' => 300),

);
        return $this->render("auther/liste.html.twig",array('tabAuthers'=>$authers));
    }

    #[Route('/listauther',name:'list_auther')]
    public function list(AutherRepository $repository){
    $authers = $repository->findAll();
    $authers2=$repository->showAllAutherOrderByEmail();
    return $this->render("auther/list.html.twig",array('tabAuthers'=>$authers,'tabAuthers2'=>$authers2));

}
#[Route('/listlivre',name:'list_livre')]
public function listlivre(AutherRepository $repository){
$authers = $repository->findAll();
return $this->render("auther/listlivres.html.twig",array('tablivres'=>$authers));
}
#[Route('/addAuther', name: 'auther_add')]
public function addAuther(ManagerRegistry $managerRegistry)
{
    $auther = new  Auther();
    $auther->setUsername("taha");
    $auther->setEmail("taha@gmail.com");
    #$em = $this->getDoctrine()->getManager();
    $em= $managerRegistry->getManager();
    $em->persist($auther);
    $em->flush();
    return $this->redirectToRoute("authers_list");
}

#[Route('/updateAuther/{id}', name: 'auther_add')]
public function updateAuther(AutherRepository $repository,$id,ManagerRegistry $managerRegistry)
{
    $auther = $repository->find($id);
    $auther->setUsername("mehdi");
    $auther->setEmail("mehdi@gmail.com");
    #$em = $this->getDoctrine()->getManager();
    $em= $managerRegistry->getManager();
    $em->flush();
    return $this->redirectToRoute("authers_list");
}

#[Route('/removeAuther/{id}', name: 'auther_remove')]
public function deleteAuther($id,AutherRepository $repository,ManagerRegistry $managerRegistry)
{
    $auther= $repository->find($id);
    $em= $managerRegistry->getManager();
    if($auther->getNbBooks()==0){
        $em->remove($auther);
        $em->flush();
    }
    else{
        return new Response("Error");
    }

    return $this->redirectToRoute("authers_list");

}
#[Route('/addForm', name: 'add_form')]
    public function addForm(Request $request,ManagerRegistry $managerRegistry)
    {
        $Auther = new Auther();
        $form = $this->createForm(AutherType::class,$Auther);
        $form ->handleRequest($request);
        if($form->isSubmitted())
        {
            $em= $managerRegistry->getManager();
            $em->persist($Auther);
            $em->flush();

            return $this->redirectToRoute('Authers_list');
        }
        return $this->renderForm("Auther/FormAddAuther.html.twig",array("formulaireAuther"=>$form));
    }


}