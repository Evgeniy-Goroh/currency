<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    /**
     * Delete all currency from database
     *
     * @return integer
     */
    public function deleteAllCurrency(): int
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->delete(Currency::class);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Query
     */
    public function getCurrencyPage(): Query
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('c')->from($this->getEntityName(), 'c');

        return $qb->getQuery();
    }
}
