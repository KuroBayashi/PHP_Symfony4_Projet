<?php

namespace App\Repository;

use App\Entity\Utilization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Utilization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilization[]    findAll()
 * @method Utilization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilizationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Utilization::class);
    }

	public function findUtil()
    {
        return $this->createQueryBuilder('u')
		    ->select('u.defibrillator_id, count(u.id) AS nb')
            ->groupBy('u.defibrillator_id')
            ->orderBy('u.nb', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Usage[] Returns an array of Usage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Usage
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


}
