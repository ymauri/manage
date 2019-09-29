<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\EntityRepository;


class ParametersRepository extends EntityRepository {

   //Datos de IVA
    public function getFieldsIva(){
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM RestaurantBundle:Parameters AS c WHERE  (c.variable) = UPPER (\'iva_higher_service\') OR UPPER (c.variable) = UPPER (\'iva_low_service\') OR UPPER (c.variable) = UPPER (\'iva_none_service\') ')
            ->getResult();
    }

    //Datos de Hotel
    public function getFieldsHotel(){
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM RestaurantBundle:Parameters AS c WHERE  (c.variable) = UPPER (\'turism_taxes\') OR UPPER (c.variable) = UPPER (\'parking_hotel\') OR UPPER (c.variable) = UPPER (\'nights_limit_pay\') OR UPPER (c.variable) = UPPER (\'parking_quantity\')')
            ->getResult();
    }

    //Datos de General
    public function getFieldsGeneral(){
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM RestaurantBundle:Parameters AS c WHERE  (c.variable) = UPPER (\'phrase\')')
            ->getResult();
    }

    //Datos de General
    public function getFieldsRules(){
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM RestaurantBundle:Parameters AS c WHERE  (c.variable) >  :tag ORDER BY c.label')
            ->setParameter('tag','tag')
            ->getResult();
    }

    //Obtener el Iva activo para service
    public function getServiceIvaActive(){
        $result = $this->getEntityManager()
            ->createQuery('SELECT c FROM RestaurantBundle:Parameters AS c WHERE  ((c.variable) = UPPER (\'iva_higher_service\') OR UPPER (c.variable) = UPPER (\'iva_low_service\') OR UPPER (c.variable) = UPPER (\'iva_none_service\')) AND c.isactive =  1 ')
            ->getResult();
        return $result[0];
    }

}
