<?php

namespace Manage\RestaurantBundle\Repository;

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
    
    //Pendientes por pagar
    public function pendingPayment($date){
        //Buscar las reservas que deben pagar según el día
        $day = $date->format('d');
        $sql =  $this->getEntityManager()
            ->createQuery('SELECT c FROM RestaurantBundle:CleaningExtra AS c WHERE c.paymentDay >= :day AND c.begin <= :dated AND :dated <= c.end')
            ->setParameter('day', $day)
            ->setParameter('dated', $date->format('Y-m-d'))
            ->getResult();
        $result = array();
        //Por cada reserva pendiente a pagar, revisar si ha sido abonada en algún formulario del mes en curso
        foreach ($sql as $current){
            $rCleaningHotel = $this->getEntityManager()
                ->createQuery('SELECT c FROM RestaurantBundle:RCleaningExtraHotel AS c WHERE c.cleaningextra = :cleaning AND c.payed = 1 ORDER BY c.payedAt DESC')
                ->setParameter('cleaning', $current->getId())
                ->setMaxResults(1)
                ->getResult();
            if (!isset($rCleaningHotel[0]) || $rCleaningHotel[0]->getPayedat()->format('m') < $date->format('m') ||  is_null($rCleaningHotel[0]->getPayedat())) {
                $result[] = $current;
            }
        }
        return $result;
    }

}
