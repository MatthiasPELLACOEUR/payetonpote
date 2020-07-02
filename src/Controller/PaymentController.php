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
     * @Route("/new", name="payment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $payment->setId();
            $payment->getParticipant()->setId();
            $entityManager->persist($payment);
            dd($payment, $request);
            $entityManager->flush();

            return $this->redirectToRoute('payment_show', ['id' => $payment->getId()]);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }

}
