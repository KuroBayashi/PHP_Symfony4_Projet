<?php

namespace App\Repository;

use App\Entity\Defibrillator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    // /**
    //  * @return Defibrillator[] Returns an array of Defibrillator objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

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
