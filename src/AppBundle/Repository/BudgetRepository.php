<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Budget;
use Doctrine\ORM\EntityRepository;

class BudgetRepository extends EntityRepository
{
    /**
     * @return Budget[]
     */
    public function findAllByAlphabeticalOrder()
    {
        return $this->createQueryBuilder('budget')
            ->orderBy('budget.title', 'ASC')
            ->getQuery()
            ->execute();
    }
}
