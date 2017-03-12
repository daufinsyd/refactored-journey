<?php
/*
 * Created by sydney_manjaro the 05/01/17
 */

// src/RASP/RaspBundle/Repository/UserRepository.php
namespace RASP\RaspBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Return list of users having $role
     *
     * @param $role
     * @return array of users
     */
    public function findByRoles($role)
    {

        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where($qb->expr()->like('u.roles', ':roles'))
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }


}