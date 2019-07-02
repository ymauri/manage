<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Manage\RestaurantBundle\Entity\Checkin;


class RCheckinHotelRepository extends EntityRepository {

   //Obtener el último checkin registrado para un hotel específico.
    public function lastCheckin($hotel){
        $sql =  $this->getEntityManager()
            ->createQuery('SELECT r FROM RestaurantBundle:RCheckinHotel AS r JOIN r.checkin c WHERE r.hotel = '.$hotel.' ORDER BY c.updatedat DESC ')
            ->setMaxResults(1)
            ->getResult();
        return $sql[0];
    }

    public function getNewerThan($newerThan, $currentdate) {
        $qb = $this->getEntityManager()->createQuery('SELECT r FROM RestaurantBundle:Checkin r WHERE r.updatedat > \''.$newerThan.'\' AND r.date = \''.$currentdate.'\'');


        return $qb->getResult();
    }

    //Obtener el listado de chekout ordenado por el número de las habitaciones (listing).
    public function getOrderedCheckin($hotelid) {
        $consulta = $this->getEntityManager()->createQuery(' SELECT r, l FROM RestaurantBundle:RCheckinHotel r JOIN r.listing l WHERE r.hotel = :id ORDER BY l.number ASC ');
        $consulta->setParameter('id', $hotelid);

        $sqlresult =  $consulta->getResult();
        $result = array();
        foreach ($sqlresult as $r){
            if (is_null($r->getCheckin()) || $r->getCheckin()->getStatus() == 'confirmed')
                $result [] = $r;
        }
        return $result;
    }
}
