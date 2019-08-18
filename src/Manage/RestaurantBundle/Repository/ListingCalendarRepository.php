<?php

namespace Manage\RestaurantBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Manage\RestaurantBundle\Entity\Rule;
class ListingCalendarRepository extends EntityRepository {

    /*
     * Obtener los departamentos implicados en una regla que se haya ejecutado en un hook
     * */
    public function getListingsByDate($rule, $date){
        $consulta = $this->getEntityManager()->createQuery('SELECT c FROM RestaurantBundle:ListingCalendar AS c WHERE c.checkin = :checkin AND c.rule = :rule ');
        $consulta->setParameter('checkin', $date);
        $consulta->setParameter('rule', $rule);
        return $consulta->getResult();
    }

    /*
     * Obtener los departamentos implicados en una regla que se haya ejecutado (por tipo)
     * */
    public function getListingsByTypeDate($rule, $date, $types){
        $consulta = $this->getEntityManager()->createQuery('SELECT c, l.idguesty, l.type FROM RestaurantBundle:ListingCalendar c INNER JOIN RestaurantBundle:Listing l WITH c.listing LIKE l.idguesty WHERE c.checkin = :checkin AND c.rule = :rule ');
        $consulta->setParameter('checkin', $date);
        $consulta->setParameter('rule', $rule);
        $listings = $consulta->getResult();
        $final = array();
        foreach ($listings as $listing) {
            if (count($types) == 0 || $types[0]==""){
                $final[] = $listing[0];
            }else{
                //Verificar el tipo de listing
                foreach ($types as $type) {
                    if (is_array($listing['type']) && in_array($type, $listing['type'])) {
                        $final[] = $listing[0];
                        break;
                    }
                }
            }
        }
        return $final;
    }

    /*
     * Obtener los departamentos implicados en una regla que se haya ejecutado (por idguesty)
     * */
    public function getListingsByGuestyDate($rule, $date, $ids){
        $consulta = $this->getEntityManager()->createQuery('SELECT c, l.idguesty, l.type FROM RestaurantBundle:ListingCalendar c INNER JOIN RestaurantBundle:Listing l WITH c.listing LIKE l.idguesty WHERE c.checkin = :checkin AND c.rule = :rule ');
        $consulta->setParameter('checkin', $date);
        $consulta->setParameter('rule', $rule);
        $listings = $consulta->getResult();
        $final = array();
        foreach ($listings as $listing) {
            if (empty($ids)){
                $final[] = $listing[0];
            }else {
                //Verificar el tipo de listing
                if (in_array($listing['idguesty'], $ids)) {
                    $final[] = $listing[0];
                }
            }
        }
        return  $final ;
    }

    /*
     * Obtener las fechas del calendario que se afectan en la ejecucion de ua regla.
     * */
    public function getDates($rule){
        $consulta = $this->getEntityManager()->createQuery('SELECT DISTINCT (c.checkin) checkin FROM RestaurantBundle:ListingCalendar c WHERE c.rule = :rule AND c.applied = :applied');
        $consulta->setParameter('rule', $rule);
        $consulta->setParameter('applied', false);
        $data = $consulta->getResult();
        $final = array();
        foreach ($data as $d){
            $final[] = $d['checkin'];
        }
        return $final;
    }
}
