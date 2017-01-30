<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Budget;
use AppBundle\Entity\Initiative;

class BudgetTest extends \PHPUnit_Framework_TestCase
{
    public function testBudget()
    {
        $budget = new Budget();
        $budget->setId();
        $budget->setTitle("Test Budget");
        $budget->setValue(1000);
        
        $initiative = new Initiative();
        $initiative->setTitle("First Initiative");
        $initiative->setValue(200);
        $initiative->setBudget($budget);
        $budget->addInitiative($initiative);

        $this->assertEquals(1000, $budget->getValue());

        $this->assertEquals(false, $budget->budgetExceeded());
        
        $initiative2 = new Initiative();
        $initiative2->setTitle("Second Initiative");
        $initiative2->setBudget($budget);
        $initiative2->setValue(800);
        $budget->addInitiative($initiative2);
        
        $this->assertEquals(false, $budget->budgetExceeded());

        $initiative3 = new Initiative();
        $initiative3->setTitle("Third Initiative");
        $initiative3->setBudget($budget);
        $initiative3->setValue(200);
        
        $budget->addInitiative($initiative3);

        $this->assertEquals(true, $budget->budgetExceeded());
    }
}