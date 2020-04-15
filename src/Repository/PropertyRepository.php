<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertySearch;
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
    public function findNotSoldQuery(PropertySearch $search):Query
    {
        $query = $this->findNotSoldQueryBuilder();

        if ($search->getMaxPrice()) {
            $query = $query
                ->andWhere('property.price <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }
        
        if ($search->getMinSurface()) {
            $query = $query
                ->andWhere('property.surface >= :minsurface')
                ->setParameter('minsurface', $search->getMinSurface());
        }

        if ($search->getOptions()->count() > 0) {
            $k = 0;
            foreach($search->getOptions() as $option) {
                $k++;
                $query = $query
                    ->andWhere(":option$k MEMBER OF property.options")
                    ->setParameter("option$k", $option);
            }
        }
        
        return $query->getQuery();
    }

    public function findNotSoldQueryNoSearch():Query
    {
        $query = $this->findNotSoldQueryBuilder();
        return $query->getQuery();
    }

    /**
     * @return Property[]
     */
    public function findNotSold():array
    {
        return $this->findNotSoldQueryNoSearch()
            ->getResult();
    }

    /**
     * @return Property[]
     */
    public function findLatest():array
    {
        return $this->findNotSoldQueryNoSearch()
            ->setMaxResults(4)
            ->getResult();
    }

    private function findNotSoldQueryBuilder():QueryBuilder
    {
        return $this->createQueryBuilder('property')
            ->where('property.sold = false');
    }
}
