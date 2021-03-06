<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use App\Entity\Campaign;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{

       /**
     * @Route("/new/{id}", name="payment_new", methods={"GET","POST"})
     */
    public function new(Request $request, Campaign $campaign): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);
        $participant = $payment->getParticipant();
        
        
        if ($form->isSubmitted() && $form->isValid() && $payment->getAmount() > 0) {
            $entityManager = $this->getDoctrine()->getManager();
            $participant->setCampaign($campaign);
            $participant->getName();
            $participant->getEmail();
            // dd($request);
            
            $payment->getParticipant()->setId();

            $entityManager->persist($participant);
            $entityManager->persist($payment);
            $entityManager->flush();
            return $this->redirectToRoute('campaign_show', ['id' => $campaign->getId()]);
        }

    
        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
            'campaign' => $campaign,
        ]);
        
    }

}
