<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Tournois;
use App\Entity\User;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Repository\TournoisRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Dompdf\Dompdf;
use Dompdf\Options;



#[Route('/participant')]
class ParticipantController extends AbstractController
{
    #[Route('/', name: 'app_participant_index', methods: ['GET'])]
    public function index(ParticipantRepository $participantRepository): Response
    {
        return $this->render('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParticipantRepository $participantRepository): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participantRepository->save($participant, true);

            return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/new.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participant_show', methods: ['GET'])]
    public function show(Participant $participant): Response
    {
        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participant $participant, ParticipantRepository $participantRepository): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participantRepository->save($participant, true);

            return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participant_delete', methods: ['POST'])]
    public function delete(Request $request, Participant $participant, ParticipantRepository $participantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participant->getId(), $request->request->get('_token'))) {
            $participantRepository->remove($participant, true);
        }

        return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/participer/{id}', name: 'app_participer' , methods: ['GET', 'POST'])]
    public function participer($id,ManagerRegistry $doctrine ,TournoisRepository $rep2,UserRepository $rep,Request $request): Response
    {

        $user =new User();
        $tournoi= new Tournois();
        $tournoi= $rep2->find($id);
        $user = $rep->find(2);
        $participation= new Participant();
            $participation->setUsers($user);
            $participation->setTournois($tournoi);
            $participation->setDateP(new \DateTime('now'));
            $em =$doctrine->getManager();
            $em->persist($participation);
            $em->flush();
            if ($request->isMethod('POST')) {
                // Get the Twilio client instance
                $client = new Client($this->getParameter('twilio.account_sid'), $this->getParameter('twilio.auth_token'));
        
                // Send an SMS message
                $client->messages->create(
                    // The phone number to send the SMS to
                    '+21693337077',
                    array(
                        // The phone number the SMS is from
                        'from' => $this->getParameter('twilio.from_number'),
                        // The body of the SMS message
                        'body' => 'Votre participation à été crée avec succés '
                    )
                );
            }        


        return $this->redirectToRoute('app_tournois');
    }


    


    

    
 
}






