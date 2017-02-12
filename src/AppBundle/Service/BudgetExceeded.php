<?php
/**
 * Created by PhpStorm.
 * User: glympero
 * Date: 09/02/2017
 * Time: 14:16
 */

namespace AppBundle\Service;

use AppBundle\Entity\Budget;

class BudgetExceeded
{

    public function __construct()
    {

    }

    public function budgetExceeded(Budget $budget)
    {
        $total = $this->getTotalValue($budget);

        if($total <= $budget->getValue()){
            return false;
        }
        return true;
    }

    public function getTotalValue(Budget $budget)
    {
        $total = 0;
        $initiatives = $budget->getInitiatives();
        foreach($initiatives as $initiative)
        {
            $total += $initiative->getValue();
        }
        return $total;
    }
}