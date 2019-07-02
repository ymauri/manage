<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ReceptionVoucherRepository extends EntityRepository {

    public function resetReceptionVoucher($id) {

        return $this->getEntityManager()
                        ->createQuery('Update RestaurantBundle:ReceptionVoucher r set r.isactive = 0 where r.id <> :ids')
                        ->setParameter('ids', $id)
                        ->getResult();
    }

}
