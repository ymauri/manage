<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RuleLogRepository extends EntityRepository {

    public function getRuleApplied($date) {
        $query =  $this->getEntityManager()
                        ->createQuery('Select r from RestaurantBundle:RuleLog r Where r.checkin = :valor ')
                        ->setParameter('valor', $date->format('Y-m-d'))
                        ->getResult();
        $result = array();
        foreach ($query as $q){
            if($q->getRule()->getActive()){
                $result[$q->getRule()->getId()][] = $q;
            }
        }
        return $result;
    }

}
