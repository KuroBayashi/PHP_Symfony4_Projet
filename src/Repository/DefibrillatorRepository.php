<?php

namespace App\Repository;

use App\Entity\Defibrillator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Defibrillator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Defibrillator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Defibrillator[]    findAll()
 * @method Defibrillator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DefibrillatorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Defibrillator::class);
    }

    /**
     * @return Defibrillator[] Returns an array of Defibrillator objects
     */
    public function findVisible($minlongitude, $maxlongitude, $minlatitude, $maxlatitude)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.longitude > :minlongitude')
            ->andWhere('d.longitude < :maxlongitude')
            ->andWhere('d.latitude > :minlatitude')
            ->andWhere('d.latitude < :maxlatitude')
            ->setParameter('minlongitude', $minlongitude)
            ->setParameter('maxlongitude', $maxlongitude)
            ->setParameter('minlatitude', $minlatitude)
            ->setParameter('maxlatitude', $maxlatitude)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllWithUtilizationCount() {
        return $this->createQueryBuilder('d')
            ->select('d, count(u) AS utilization_count')
            ->leftJoin('d.utilizations', 'u', Join::WITH, 'd = u.defibrillator')
            ->groupBy('d.id')
            ->orderBy('utilization_count','DESC')
            ->setMaxResults(35)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Defibrillator
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
