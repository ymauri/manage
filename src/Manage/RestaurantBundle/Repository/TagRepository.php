<?php

namespace Manage\RestaurantBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository {
    public function getArrayTags()
    {
        $query = $this->createQueryBuilder('r')
            ->select('r')
            ->addOrderBy('r.name');
        $result = array();
        foreach ($query as $key=>$element){
            $result[$element->getId()] = $element->getName();
        }
        return $result;
    }
}
