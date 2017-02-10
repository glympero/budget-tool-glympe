<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Budget;
use AppBundle\Entity\Initiative;
use AppBundle\Form\BudgetFormType;
use AppBundle\Form\InitiativeFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->generateBudgetForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $budget = $form->getData();
            $em = $this->getDoctrine()->getManager();
            // Prepare to save the object
            $em->persist($budget);
            //Execute query
            $em->flush();
            $this->get('app.show_flash_messages')->getSuccessMsg();
            $budget_id = $budget->getId();
            return $this->redirectToRoute('initiative', array('budget_id' => $budget_id));
        }

        return $this->render('default/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Matches /initiative/*
     * 
     * @Route("/initiative/{budget_id}", name="initiative")
     */
    public function initiativeAction(Request $request, $budget_id)
    {
        if (!$budget_id) {
            throw $this->createNotFoundException('Budget1 Not Found!');
        }
        $budget = $this->getBudget($budget_id);
        $form = $this->generateInitiativeForm($budget_id);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $initiative = $form->getData();
            // Prepare to save the object
            $em = $this->getDoctrine()->getManager();
            $em->persist($initiative);
            //Execute query
            $em->flush();

            $budgetExceeded = $this->get('app.budget_exceeded');
            $flashMessage = $this->get('app.show_flash_messages');
            if($budgetExceeded->budgetExceeded($budget))
            {
                $flashMessage->getDangerMsg();
            }else {
                $flashMessage->getInfoMsg();
            }
            
            
        }
        //$em = $this->getDoctrine()->getManager();
//        $allInitiatives = $em->getRepository('AppBundle:Initiative')
//            ->findBy(
//                array('budget' => $budget_id)
//            );
        
        $allInitiatives = $budget->getInitiatives();

        return $this->render('default/initiative.html.twig', array(
            'form' => $form->createView(),'allInitiatives' => $allInitiatives
        ));
    }

    private function generateBudgetForm()
    {
        $budget = new Budget();
        $budget->setStartDate(new \DateTime('today'));
        $budget->setEndDate(new \DateTime('tomorrow'));
        $form = $this->createForm(BudgetFormType::class, $budget);
//        $form = $this->createFormBuilder($budget)
//            ->add('title', TextType::class)
//            ->add('value', NumberType::class)
//            ->add('startDate', DateType::class)
//            ->add('endDate', DateType::class)
//            ->add('save', SubmitType::class, array('label' => 'Create Budget'))
//            ->getForm();
        return $form;
    }

    private function generateInitiativeForm($budget_id)
    {   $budget = $this->getBudget($budget_id);
        // create a task and give it some dummy data for this example
        $initiative = new Initiative();
        $initiative->setBudget($budget);
        $form = $this->createForm(InitiativeFormType::class, $initiative);
//        $form = $this->createFormBuilder($initiative)
//            ->add('title', TextType::class)
//            ->add('value', NumberType::class)
//            ->add('save', SubmitType::class, array('label' => 'Create Initiative'))
//            ->getForm();
        return $form;
    }

    private function getBudget($budget_id)
    {
        $budget = $this->getDoctrine()
            ->getRepository('AppBundle:Budget')
            ->find($budget_id);
        return $budget;
    }


}
