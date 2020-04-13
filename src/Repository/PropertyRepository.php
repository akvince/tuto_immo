<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }
    
    /**
     * @return Query
     */
    public function findNotSoldQuery():Query
    {
        return $this->findNotSoldQueryBuilder()
            ->getQuery();
    }

    /**
     * @return Property[]
     */
    public function findNotSold():array
    {
        return $this->findNotSoldQuery()
            ->getResult();
    }

    /**
     * @return Property[]
     */
    public function findLatest():array
    {
        return $this->findNotSoldQuery()
            ->setMaxResults(4)
            ->getResult();
    }

    private function findNotSoldQueryBuilder():QueryBuilder
    {
        return $this->createQueryBuilder('property')
            ->where('property.sold = false');
    }
}
