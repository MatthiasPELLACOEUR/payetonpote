<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use App\Form\CampaignType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/campaign")
 */
class CampaignController extends AbstractController
{
    /**
     * @Route("/", name="campaign_index", methods={"GET"})
     */
    public function index(): Response
    {
        $campaigns = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findAll();

        return $this->render('campaign/index.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @Route("/new/", name="campaign_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $campaign = new Campaign();
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $campaign->setId(); //il faut définir l'id de la campagne !
            $entityManager->persist($campaign);
            $entityManager->flush();

            return $this->redirectToRoute('campaign_show', ['id' => $campaign->getId()]);
        }

        return $this->render('campaign/new.html.twig', [
            'campaign' => $campaign,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campaign_show", methods={"GET"})
     */
    public function show(Campaign $campaign): Response
    {
        $participants = $this->getDoctrine()
        ->getRepository(Participant::class)
        ->findBy(array('campaign' => $campaign));

        $countParticipant= count($participants);
        $payments = $this->getDoctrine()
        ->getRepository(Payment::class)
        ->findBy(array('participant' => $participants));
        $totalPayment = 0;
        $pourcentage = 0;

        foreach($payments as $payment){
            $totalPayment += $payment->getAmount();
            
        }

        $pourcentage = round(($totalPayment/$campaign->getGoal())*100);

        return $this->render('campaign/show.html.twig', [
            'campaign' => $campaign,
            'totalPayment' => $totalPayment,
            'pourcentage' => $pourcentage,
            'countParticipant' => $countParticipant,
            'payments' => $payments,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="campaign_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Campaign $campaign): Response
    {
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('campaign_index');
        }

        return $this->render('campaign/edit.html.twig', [
            'campaign' => $campaign,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campaign_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Campaign $campaign): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campaign->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($campaign);
            $entityManager->flush();
        }

        return $this->redirectToRoute('campaign_index');
    }
}
