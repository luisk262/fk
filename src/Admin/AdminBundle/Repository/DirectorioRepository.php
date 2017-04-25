<?php

namespace Admin\AdminBundle\Repository;

/**
 * DirectorioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DirectorioRepository extends \Doctrine\ORM\EntityRepository
{
    public function countAll(){
       $query= $this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery();
       if($query->getOneOrNullResult()){
           return intval($query->getOneOrNullResult()[1]);
       }
       else{
           return null;
       }
    }
    public  function count($searchParam){
        if(!empty($searchParam)){
            $query=$this->createQueryBuilder('d')
                ->select('COUNT(d.id)');
            if($searchParam['ciudad']){
               $query->where('d.direccion Like :ciudad')
                   ->orWhere('d.ciudad Like :ciudad')
                   ->setParameter('ciudad','%'. $searchParam['ciudad'] .'%');
            }
            if($searchParam['nombre']) {
                $query->where('d.nombre Like :nombre')
                    ->setParameter('nombre','%'. $searchParam['nombre'] .'%');

            }
            if($searchParam['nombre'] and $searchParam['ciudad']) {
                $query->where('d.nombre Like :nombre')
                    ->andWhere('d.ciudad Like :ciudad')
                    ->setParameter('ciudad','%'. $searchParam['ciudad'] .'%')
                    ->setParameter('nombre','%'. $searchParam['nombre'] .'%');
            }
            $result= $query->getQuery()->getOneOrNullResult();
            if($result){
               return intval($result[1]);
            }else{
                return $result;
            }
        }
        else{return null;}
    }
    public function findpaginator($searchParam){
        if(!empty($searchParam)){
            $query=$this->createQueryBuilder('d')
                ->select('d');
            if($searchParam['ciudad']){
                $query->where('d.direccion Like :ciudad')
                    ->orWhere('d.ciudad Like :ciudad')
                    ->setParameter('ciudad','%'. $searchParam['ciudad'] .'%');
            }
            if($searchParam['nombre']) {
                $query->where('d.nombre Like :nombre')
                    ->setParameter('nombre','%'. $searchParam['nombre'] .'%');

            }
            if($searchParam['nombre'] and $searchParam['ciudad']) {
                $query->where('d.nombre Like :nombre')
                    ->andWhere('d.ciudad Like :ciudad')
                    ->setParameter('ciudad','%'. $searchParam['ciudad'] .'%')
                    ->setParameter('nombre','%'. $searchParam['nombre'] .'%');
            }

            $result= $query->getQuery()->getArrayResult();
            return $result;

        }
        else{
            return null;}
    }
}