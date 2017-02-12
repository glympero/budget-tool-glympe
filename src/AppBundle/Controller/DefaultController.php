<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Budget;
use AppBundle\Entity\Initiative;
use AppBundle\Form\BudgetFormType;
use AppBundle\Form\InitiativeFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
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
        if ($form->isSubmitted() && $form->isValid())
        {
            return $this->budgetSuccessfullySubmitted($form);
        }elseif($form->isSubmitted() && !$form->isValid())
        {
            return $this->getBudgetFormErrors($form);
        }

        return $this->render('default/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/budgets", name="list_budgets")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $budgets = $em->getRepository('AppBundle:Budget')
            ->findAllByAlphabeticalOrder();

        return $this->render('default/list.html.twig', [
            'budgets' => $budgets
        ]);
    }

    /**
     * Matches /initiative/*
     * 
     * @Route("/initiative/{budget_id}", name="initiative")
     */
    public function initiativeAction(Request $request, $budget_id)
    {
        if (!$budget_id) {
            throw $this->createNotFoundException('Budget Not Found!');
        }
        $budget = $this->getBudget($budget_id);
        $form = $this->generateInitiativeForm($budget_id);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->initiativeSuccessfullySubmitted($form, $budget);
        }elseif ($form->isSubmitted() && !$form->isValid())
        {
            return $this->getInitiativesFormErrors($form, $budget);
        }

        //$em = $this->getDoctrine()->getManager();
//        $allInitiatives = $em->getRepository('AppBundle:Initiative')
//            ->findBy(
//                array('budget' => $budget_id)
//            );
        $budgetExceeded = $this->get('app.budget_exceeded');
        $totalValue = $budgetExceeded->getTotalValue($budget);
        $allInitiatives = $budget->getInitiatives();
        $budgetValue = $budget->getValue();

        return $this->render('default/initiative.html.twig', array(
            'form' => $form->createView(),
            'allInitiatives' => $allInitiatives,
            'totalValue' => $totalValue,
            'budgetValue' => $budgetValue
        ));
    }

    private function generateBudgetForm()
    {
        $budget = new Budget();
        $budget->setStartDate(new \DateTime('today'));
        $budget->setEndDate(new \DateTime('tomorrow'));
        $form = $this->createForm(BudgetFormType::class, $budget);
        return $form;
    }

    private function generateInitiativeForm($budget_id)
    {   $budget = $this->getBudget($budget_id);
        $initiative = new Initiative();
        $initiative->setBudget($budget);
        $form = $this->createForm(InitiativeFormType::class, $initiative);
        return $form;
    }

    private function getBudget($budget_id)
    {
        $budget = $this->getDoctrine()
            ->getRepository('AppBundle:Budget')
            ->find($budget_id);
        return $budget;
    }

    private function getBudgetFormErrors(Form $form)
    {
        $validator = $this->get('validator');
        $errors = $validator->validate($form);
        if (count($errors) > 0) {
            return $this->render('default/index.html.twig', array(
                'form' => $form->createView(),
                'errors' => $errors
            ));
        }
    }

    private function getInitiativesFormErrors(Form $form, Budget $budget)
    {
        $validator = $this->get('validator');
        $errors = $validator->validate($form);
        $allInitiatives = $budget->getInitiatives();
        $budgetValue = $budget->getValue();
        $totalValue = $this->get('app.budget_exceeded')->getTotalValue($budget);
        if (count($errors) > 0) {
            return $this->render('default/initiative.html.twig', array(
                'form' => $form->createView(),
                'errors' => $errors,
                'allInitiatives' => $allInitiatives,
                'budgetValue' => $budgetValue,
                'totalValue' => $totalValue
            ));
        }
    }

    private function budgetSuccessfullySubmitted(Form $form)
    {
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

    private function initiativeSuccessfullySubmitted(Form $form, Budget $budget)
    {
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


}
