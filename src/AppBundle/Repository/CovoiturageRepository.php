<?php

namespace AppBundle\Repository;

/**
 * CovoiturageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CovoiturageRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(),array('datePublication' => 'DESC'));
    }

    public function findByCity($city)
    {
        $em = $this->getEntityManager();
        $result = $em->createQuery("select c from AppBundle:Covoiturage c where c.depart like :s or c.destination like :s order by c.datePublication DESC ")
            ->setParameter(':s','%'.$city.'%');
        return $result->getResult();
    }
}
