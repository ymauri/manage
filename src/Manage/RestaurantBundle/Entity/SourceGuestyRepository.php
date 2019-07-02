<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SourceGuestyRepository extends EntityRepository {

    public function getArrayList() {

        $queryresult = $this->getEntityManager()->getRepository('RestaurantBundle:SourceGuesty')->findAll();
        $result = array();
        foreach ($queryresult as $item) {
            $result[$item->getName()] = $item->getName();
        }
        return $result;

    }

}
