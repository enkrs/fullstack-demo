<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CurrencyRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method CurrencyRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyRate[]    findAll()
 * @method CurrencyRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRateRepository extends ServiceEntityRepository
{
    private int $perPage = 20;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyRate::class);
    }

    public function getLatestDate()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT MAX(r.date) FROM App\Entity\CurrencyRate r')
            ->getSingleScalarResult();
    }

    public function getByDateQuery(string $date, int $limit = null, int $offset = null)
    {
        $query = $this->createQueryBuilder('r')
            ->where('r.date = :date')
            ->orderBy('r.currency')
            ->setParameter('date', $date)
            ->getQuery();

        if ($limit) {
            $query->setMaxResults($limit);
        }
        if ($offset) {
            $query->setFirstResult($offset);
        }

        return $query;
    }
}
