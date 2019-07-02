<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Manage\RestaurantBundle\Entity\RCheckinHotel;
use Manage\RestaurantBundle\Entity\RCheckoutHotel;


class RCheckoutHotelRepository extends EntityRepository {

    //Obtener el listado de chekout ordenado por el número de las habitaciones (listing).
    public function getOrderedCheckout($hotelid) {
        $consulta = $this->getEntityManager()->createQuery(' SELECT r, l FROM RestaurantBundle:RCheckoutHotel r JOIN r.listing l WHERE r.hotel = :id ORDER BY l.number ASC ');
        $consulta->setParameter('id', $hotelid);

        return $consulta->getResult();
    }
    
    public function getLastCheckinBorg($apto, $hotel){
        $consulta = $this->getEntityManager()->createQuery('SELECT r FROM RestaurantBundle:RCheckinHotel r JOIN r.hotel h WHERE r.listing = '.$apto.' ORDER BY h.dated DESC');
        //$consulta->setMaxResults(1);
        $checkin = $consulta->getResult();
        foreach ($checkin as $c){
            var_dump($c);die;
            //if ($checkin[0] != null){
                //Añadir las noches a la fecha del checkin
                $fecha = $c->getHotel()->getDated();
                $fecha->add( new \DateInterval(
                    'P0Y0M' . $c->getNights() . 'DT0H0M0S'
                ));


                /*var_dump($hotel->getDated()->format('d-m-Y'));die;*/
                //Si la fecha del checkin+noches es igual a la fecha del formulario actual entonces tomar el valor de Borg
                if ($fecha->diff($hotel->getDated())->days == 0){
                    return $c->getBorg();
                }
            //}
        }

        return null;
    }
}
