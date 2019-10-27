<?php

namespace Manage\RestaurantBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class FolderRepository
 * @package Manage\RestaurantBundle\Repository
 */
class FolderRepository extends EntityRepository {

    public function getRootNodes(){
        $childrens = $this->getEntityManager()->createQuery('SELECT r FROM RestaurantBundle:Folder AS r WHERE r.isroot = TRUE ORDER BY r.details ASC')->getResult();
        return($childrens);
    }

    public function getChildrensNodes($parent){
        $childrens = $this->getEntityManager()->createQuery('SELECT r FROM RestaurantBundle:RFolderFolder r JOIN r.child c WHERE r.father = :parent ORDER BY c.details ASC')->setParameter("parent",$parent)->getResult();
        $childrensarray = array();
        foreach ($childrens as $child){
            $childrensarray[] = $child->getChild();
        }
        return $childrensarray;
    }

    public function getChildrensFurnitures($parent){
        $folder = $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Folder f WHERE f.id = :id')->setParameter("id",$parent)->getResult();
        if ($folder[0]->getIsroot()){
            return $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Furniture f WHERE f.pathfolder like :parent')->setParameter("parent",'%/'.$parent.'/%')->getResult();
        }
        else{
            return $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Furniture f WHERE f.folder = :parent')->setParameter("parent", $parent)->getResult();
        }
    }

    public function getChildrensFurnituresFull($parent){
            return $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Furniture f WHERE f.pathfolder like :parent')->setParameter("parent",'%/'.$parent.'/%')->getResult();
    }

    public function getChildrensFurnituresIssues($parent){
        return $this->getEntityManager()
                    ->createQuery('SELECT f FROM RestaurantBundle:Furniture f JOIN f.tags t WHERE f.pathfolder like :parent AND t.name = :tagname')
                    ->setParameter("parent",'%/'.$parent.'/%')
                    ->setParameter("tagname", 'Onderhoud')
                    ->getResult();
    }

    public function getStatisticsChildrens($parent){
        $folder = $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Folder f WHERE f.id = :id')->setParameter("id",$parent)->getResult();
        $quantity = $total = 0;
        $result = array();
        //if ($folder[0]->getIsroot()){
            $result = $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Furniture f WHERE f.pathfolder like :parent')->setParameter("parent",'%/'.$parent.'/%')->getResult();
        //}
        //else{
          //  $result = $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Furniture f WHERE f.folder = :parent')->setParameter("parent", $parent)->getResult();
        //}
        foreach ($result as $item){
            $quantity += $item->getQuantity();
            $total += $item->getTotalvalue();
        }
        return array('quantity'=>$quantity, 'total'=>$total);
    }


    public function getChildrensReportFurnitures($parent){
        $folder = $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Folder f WHERE f.id = :id')->setParameter("id",$parent)->getResult();
        if (count($folder) > 0 && $folder[0]->getIsroot()){
            return $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Furniture f WHERE f.pathfolder like :parent')->setParameter("parent",'%/'.$parent.'/%')->getResult();
        }
        else{
            return $this->getEntityManager()->createQuery('SELECT f FROM RestaurantBundle:Furniture f WHERE f.folder = :parent ')->setParameter("parent", $parent)->getResult();
        }
    }

}
