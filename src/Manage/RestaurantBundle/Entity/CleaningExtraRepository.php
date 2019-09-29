<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Manage\RestaurantBundle\Entity\CleaningExtra;


class CleaningExtraRepository extends EntityRepository {

   //Obtener los deptos de limpieza programada de acuerdo con el rango de fecha.
    public function cleaningByDateRange($day, $weekday){
        $sql =  $this->getEntityManager()
            ->createQuery('SELECT c FROM RestaurantBundle:CleaningExtra AS c WHERE c.begin <= :day  AND c.end > :day')
            ->setParameter('day', $day)
            ->getResult();
        $result = array();

        foreach ($sql as $current){
            if (in_array($weekday, $current->getDayweek())){
                $result[] = $current->getListing()->getId();
            }
        }
        return $result;
    }
}
