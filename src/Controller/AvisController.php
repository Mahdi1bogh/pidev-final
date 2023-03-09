<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;

#[Route('/avis')]
class AvisController extends AbstractController
{
    #[Route('/{id}', name: 'app_avis_index', methods: ['GET'])]
    public function index(AvisRepository $avisRepository, Club $id,ClubRepository $cr): Response
    {
        $club = $cr->find($id);
        $avis = $this->getDoctrine()->getRepository(Avis::class)->findBy([
            'club' => $id,
        ]);
        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
            'club' => $club,
        ]);
    }
    #[Route('/f/{id}', name: 'app_avis_iindex', methods: ['GET'])]
    public function iindex(AvisRepository $avisRepository, Club $id,ClubRepository $cr): Response
    {
        $club = $cr->find($id);
        $avis = $this->getDoctrine()->getRepository(Avis::class)->findBy([
            'club' => $id,
        ]);
        return $this->render('avis/iindex.html.twig', [
            'avis' => $avis,
            'club' => $club,
        ]);
    }

    #[Route('/new/{id}', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AvisRepository $avisRepository ,ClubRepository $clubRepository,Club $id): Response
    {
        $avi = new Avis();
        $a = $clubRepository->find($id);
        $avi-> setClub($id);

        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avisRepository->save($avi, true);

            return $this->redirectToRoute('app_avis_index', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/new.html.twig', [
            'avi' => $avi,
            'form' => $form,
            'club' =>$a,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avi, AvisRepository $avisRepository): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avisRepository->save($avi, true);

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, AvisRepository $avisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $avisRepository->remove($avi, true);
        }

        return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    }
}
