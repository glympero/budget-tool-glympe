<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Budget;
use AppBundle\Entity\Initiative;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Console\Helper\Table;

class DefaultController extends Controller
{   
    
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $budget = new Budget();
        $budget->setStartDate(new \DateTime('today'));
        $budget->setEndDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($budget)
            ->add('title', TextType::class)
            ->add('value', NumberType::class)
            ->add('startDate', DateType::class)
            ->add('endDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Budget'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $budget = $form->getData();

            $em = $this->getDoctrine()->getManager();
            // Prepare to save the object
            $em->persist($budget);
            //Executr query
            $em->flush();
            $this->addFlash(
                'success',
                'Your budget has been saved!'
            );
            $budget_id = $budget->getId();
            return $this->redirectToRoute('initiative', array('budget_id' => $budget_id));
        }

        return $this->render('default/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Matches /initiative/*
     * 
     * @Route("/initiative/{budget_id}", name="initiative")
     */
    public function initiativeAction(Request $request, $budget_id)
    {   
        $budget = $this->getDoctrine()
        ->getRepository('AppBundle:Budget')
        ->find($budget_id);
        $allInitiatives = $budget->getInitiatives();
        // create a task and give it some dummy data for this example
        $initiative = new Initiative();
        $initiative->setBudget($budget);
        $form = $this->createFormBuilder($initiative)
            ->add('title', TextType::class)
            ->add('value', NumberType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Initiative'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $initiative = $form->getData();
            // Prepare to save the object
            $em = $this->getDoctrine()->getManager();
            $em->persist($initiative);
            //Executr query
            $em->flush();
            if($budget->budgetExceeded())
            {
                $this->addFlash(
                    'danger',
                    'Your initiative has been saved! - budget exceeded!'
                );
            }else {
                $this->addFlash(
                    'info',
                    'Your initiative has been saved! - budget not exceeded'
                );
            }
            $allInitiatives = $budget->getInitiatives();
            return $this->render('default/initiative.html.twig', array('form' => $form->createView(),'allInitiatives' => $allInitiatives));
        }

        return $this->render('default/initiative.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
