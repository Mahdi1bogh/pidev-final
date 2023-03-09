<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Exploit;
use App\Entity\Tournois;
use App\Entity\User;
use App\Form\SignupformType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\TournoisRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Swift_Message;
use Swift_Mailer;
use Swift_SmtpTransport;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function index1(): Response
    {
        return $this->render('home/about.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/club', name: 'app_club')]
    public function index2(): Response
    {
        return $this->render('home/club.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/home/magasin', name: 'app_home_magasin')]
    public function magasin(ProductRepository $productRepository, CategoryRepository $categoryRepository)
{
    $products = $productRepository->findAll();
    $categories = $categoryRepository->findAll();

    return $this->render('home/magasin.html.twig', [
        'produits' => $products,
        'categories' => $categories,
    ]);
}
   
    #[Route('/home/filter', name: 'app_product_filter', methods: ['POST'])]
    public function filter(Request $request, ProductRepository $productRepository)
    {
        
        $categoryId = $request->query->get('category');
        $categoryId = $categoryId ? $categoryId : null;
        $minPrice = $request->query->get('minPrice') ;
        $minPrice = $minPrice ? floatval($minPrice) : 0.00;
        $maxPrice = $request->query->get('maxPrice');
        $maxPrice = $maxPrice ? floatval($maxPrice) : 99999.00;

        $products = $productRepository->findByCategoryAndPriceRange($categoryId, $minPrice, $maxPrice);

        $response = [];
        foreach ($products as $product) {
            $response[] = [
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'price' => $product->getPrice(),
                'img' => $product->getImg(),
                'rating' => $product->getRating(),
                'category' => [
                    'id' => $product->getCategory()->getId(),
                    'name' => $product->getCategory()->getName(),
                ],
            ];
            
        }

        return new JsonResponse($response);
    }

    #[Route('/reclamation', name: 'app_reclamation')]
    public function index4(): Response
    {
        return $this->render('home/reclamation.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/tournoisfront', name: 'app_tournois', methods: ['GET'])]
    public function index5(TournoisRepository $tournoisRepository): Response
    {
        return $this->render('home/tournois.html.twig', [
            'tournois' => $tournoisRepository->findAll(),
        ]);
    }
    #[Route('/tournoisdetails/{id}', name: 'app_details', methods: ['GET'])]
    public function show(Tournois $tournoi): Response
    {
        return $this->render('home/tournoisdetails.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    #[Route('/signup', name: 'app_signup')]
    public function index6(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,UserRepository $userR): Response
    {

        
        $user = new User();
        
    

        $form = $this->createForm(SignupformType::class,$user);

        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid())
        {
            // encode the plain password
            $userData = $form->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $userData->getPassword()
                )
               
            );
            /** @var UploadedFile $uploadedFile */
            $uploadedFile=$form['image']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFile = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFile
               );

               $user->setImage($newFile);

               $user->setRoles(['ROLE_USER']);

               $entityManager->persist($user);
               $entityManager->flush();
               $mail = $user->getEmail();
               $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername('recycle.tunisia')
            ->setPassword('ztntffukvpwraygm');
        
             $mailer = new Swift_Mailer($transport);
             
             $user = $this->security->getUser(); 
             $userr = $userR->find($user);
             $email = $userr->getEmail();
              $message = (new Swift_Message('Ajout dun utilisateur avec succes '))
                ->setFrom(['recycle.tunisia@gmail.com' => 'Recycle tunisia'])
                ->setTo([$mail])
                ->setBody('un utilisateur  a ete ajoutee avec succes');
            $mailer->send($message); 

        }


        
        return $this->render('home/register.html.twig', [
            'controller_name' => 'HomeController',
            'formUser' => $form->createView(),
            "user" => $user ,"i"=>$user
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function index7(): Response
    {
        return $this->render('home/register.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/panier', name: 'app_panier')]
    public function index8(): Response
    {
        return $this->render('home/panier.html.twig', [
            'controller_name' => 'HomeController',
            
        ]);
    }
    
    
    #[Route('/login', name: 'app_login')]
    public function index9(authenticationUtils $authenticationUtils,Request $request ,EntityManagerInterface $entityManager): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $email = $authenticationUtils->getLastUsername();
       
       
        
        return $this->render('security/login.html.twig', [
            'email' => $email , 
            'error' => $error ,
            
        ]);
    }


    #[Route('/calender', name: 'app_calender')]
    public function calender(TournoisRepository $calendar)
    {
        $events = $calendar->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDateTour()->format('Y-m-d H:i:s'),
                'end' => $event->getDateFin()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('home/calendar.html.twig', compact('data'));
    }
}
