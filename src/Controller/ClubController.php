<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TerrainRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use TCPDF;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swift_Message;
use Swift_Mailer;
use Swift_SmtpTransport;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;

#[Route('/club')]
class ClubController extends AbstractController
{
   
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    #[Route('/', name: 'app_club_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository): Response
    {
        return $this->render('club/afficher.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_club_indexx', methods: ['GET'])]
    public function indexUser(ClubRepository $clubRepository,TerrainRepository $terrainRepository ,Request $request): Response
    {    

        $clubs=$clubRepository->findAll();
        $terrains=$terrainRepository->findAll();
        
        
        return $this->render('front/index.html.twig', [
            'clubs' => $clubs,
            'terrains' => $terrains,
        ]);
        
    }

    #[Route('/searchee', name: 'searchee', methods: ['GET'])]
    public function recherche(ClubRepository $clubRepository,TerrainRepository $terrainRepository,Request $request): Response
    {  $terrains=$terrainRepository->findAll();
        $club = $request->get('q');
        $clubs=$clubRepository->recherche($club);
       
        
    
        return $this->render('front/index.html.twig', [
            'clubs' => $clubs,
            'terrains' => $terrains,
        ]);
        
    }
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request , ClubRepository $cr ,  TerrainRepository $terrainRepository,SerializerInterface $serializer): JsonResponse
    {
        $query = $request->get('q');
        $sort = $request->get('sort', 'name'); // Default to sorting by id if no sort parameter is provided
    

        // Effectuer la recherche dans votre base de données ou ailleurs
        $resultss = $cr->recherche($query);
        // Retourner les résultats au format JSON
        
        $json=$serializer->serialize($resultss,'json',['groups'=>'club']);
       
        return $this->json([
            'resultss' => $this->renderView('front/search.html.twig', [
                'clubs' => $resultss,
               
                
            ]),
        
           
        ]);
    }
    #[Route('/sort', name: 'sort', methods: ['GET'])]
    public function sort(Request $request , ClubRepository $cr , TerrainRepository $terrainRepository,SerializerInterface $serializer): JsonResponse
    {
        $query = $request->get('q');
        $sort = $request->get('sort', 'name'); // Default to sorting by id if no sort parameter is provided
    

        // Effectuer la recherche dans votre base de données ou ailleurs
        $resultss = $cr->sortt($sort);
        // Retourner les résultats au format JSON
        
        $json=$serializer->serialize($resultss,'json',['groups'=>'club']);
       
        return $this->json([
            'resultss' => $this->renderView('front/search.html.twig', [
                'clubs' => $resultss,
               
                
            ]),
        
           
        ]);
    }

 
    #[Route('/new', name: 'app_club_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClubRepository $clubRepository,UserRepository $userR): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clubRepository->save($club, true);
            $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername('recycle.tunisia')
            ->setPassword('ztntffukvpwraygm');
        
             $mailer = new Swift_Mailer($transport);
             
             $user = $this->security->getUser(); 
             $userr = $userR->find($user);
             $email = $userr->getEmail();
              $message = (new Swift_Message('Ajout dun club avec succes '))
                ->setFrom(['recycle.tunisia@gmail.com' => 'Recycle tunisia'])
                ->setTo([$email])
                ->setBody('votre nouveau club a ete ajoutee avec succes ');
            $mailer->send($message); 
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
             
        }

        return $this->renderForm('club/ajouter.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_club_show', methods: ['GET'])]
    public function show(Club $club): Response
    {
        return $this->render('club/detaille.html.twig', [
            'club' => $club,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clubRepository->save($club, true);

            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('club/modifier.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_club_delete', methods: ['POST'])]
    public function delete(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $clubRepository->remove($club, true);
        }

        return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
    }
    
    public function generatePdfAction(ClubRepository $clubRepository)
    {
        $clubs = $clubRepository->findAll();
    // créer une instance de TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // définir les informations du document
    $pdf->SetCreator('Mon application Symfony');
    $pdf->SetAuthor('Moi');
    $pdf->SetTitle('Liste des clubs');
    $pdf->SetSubject('Liste des clubs');

    // ajouter une page
    $pdf->AddPage();

    // créer le contenu du PDF
    $html = $this->renderView('front/list.html.twig', [
        'clubs' => $clubs,
    ]);

    // écrire le contenu dans le PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // sauvegarder le PDF sur le bureau
         $projectDir = $this->getParameter('kernel.project_dir');

     // Use the project directory path to define the path to the PDF file
     $pdfPath = $projectDir . '/public/pdfs/liste_clubs.pdf';
    $pdf->Output($pdfPath, 'F');

    // renvoyer une réponse HTTP
    $response = new Response();
    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        'liste_clubs.pdf'
    );
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', $disposition);
    $response->setContent(file_get_contents($pdfPath));

    return $response;
}

}
