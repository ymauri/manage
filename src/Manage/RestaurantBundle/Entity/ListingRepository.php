<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ListingRepository extends EntityRepository {

    public function getAllActiveListings(){
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM RestaurantBundle:Listing AS l WHERE l.activeforrent = \'1\' ORDER BY c.value ASC')
            ->getResult();
    }

}
