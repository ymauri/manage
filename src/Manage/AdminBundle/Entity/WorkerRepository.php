<?php

namespace Manage\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

class WorkerRepository extends EntityRepository {

    public function getChefs() {

        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AdminBundle:Worker AS c WHERE UPPER (c.position) = UPPER (\'Chef\') AND c.isactive LIKE \'1\' ORDER BY c.name ASC')
            ->getResult();
    }

    public function getManagers() {

        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AdminBundle:Worker AS c WHERE UPPER (c.position) = UPPER (\'Manager Restaurant\') AND c.isactive LIKE \'1\' ORDER BY c.name ASC')
            ->getResult();
    }

    public function getRecepties() {

        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AdminBundle:Worker AS c WHERE UPPER (c.position) = UPPER (\'Receptie\') AND c.isactive LIKE \'1\' ORDER BY c.name ASC')
            ->getResult();
    }

    public function getManagersBar() {

        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AdminBundle:Worker AS c WHERE UPPER (c.position) = UPPER (\'Manager Sky Bar\') AND c.isactive LIKE \'1\' ORDER BY c.name ASC')
            ->getResult();
    }

}
