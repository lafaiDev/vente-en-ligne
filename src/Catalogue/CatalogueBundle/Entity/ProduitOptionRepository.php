<?php

namespace Catalogue\CatalogueBundle\Entity;

/**
 * ProduitOptionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitOptionRepository extends \Doctrine\ORM\EntityRepository
{
    //cherche un produit dans un tableux pour afecher dans le panier
    public function findArray($array)
    {
        $qb = $this->createQueryBuilder('u')
            ->Select('u')
            ->Where('u.id IN (:array)')
            ->setParameter('array', $array);
        return $qb->getQuery()->getResult();
    }
}
