<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function index(UserRepository $UserRepo): Response
    {
        return $this->render('admin_dashbord/index.html.twig', [
            'controller_name' => 'AdminController',

            'user_tab' => $UserRepo->findAll(),
            
        ]);
    }
    #[Route('/searche', name: 'searchu', methods: ['GET'])]
    public function searche(Request $request , UserRepository $cr ,SerializerInterface $serializer): JsonResponse
    {
        $query = $request->get('q');
       $resultss = $cr->recherchee($query);
        $json=$serializer->serialize($resultss,'json',['groups'=>'club']);

        // Effectuer la recherche dans votre base de données ou ailleurs
       
        // Retourner les résultats au format JSON
        
        
       
        return $this->json([
            'resultss' => $this->renderView('admin_dashbord/search.html.twig', [
                'clubs' => $resultss,
               
                
            ]),
        
           
        ]);
    }
    #[Route('/admin/deleteuser/{id}', name: 'app_admin_delete')]
    public function DeleteUser(UserRepository $UserRepo,$id,EntityManagerInterface $entityManager): Response
    {


        $user = $entityManager
            ->getRepository(User::class)
            ->find($id);

         // Get the Doctrine entity manager
         $entityManager = $this->getDoctrine()->getManager();


         
         // Remove the user from the database
         $entityManager->remove($user);
         $entityManager->flush();


         // Redirect to the user list page
         return $this->redirectToRoute('app_admin');
    }
}
