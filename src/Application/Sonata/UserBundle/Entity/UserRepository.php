<?php

namespace Application\Sonata\UserBundle\Entity;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function lastUsersMonth()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('u')
            ->from($this->_entityName, 'u')// Dans un repository, $this->_entityName est le namespace de l'entité gérée
            ->Where('u.createdAt <= :now')
            ->andWhere('u.createdAt >= :delay')
            ->setParameter('now', new \DateTime('now'))
            ->setParameter('delay', new \DateTime('last month'))
            ->andWhere('u.enabled = true')
            ->orderBy('u.createdAt', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function desactivateOutUser()
    {
        $query = $this->_em->createQueryBuilder()
            ->update('Application\Sonata\UserBundle\Entity\User', 'u')
            ->set('u.enabled', ':boolean')
            ->where('u.date_sortie < :now')
            ->setParameter('boolean', false)
            ->setParameter('now', new \DateTime('now'))
            ->getQuery();

        return $query->execute();
    }

    /**
     * @param string $role
     *
     * @return array
     */
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }
}
