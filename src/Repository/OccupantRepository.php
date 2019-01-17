<?php

namespace App\Repository;

use App\Entity\Occupant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Occupant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Occupant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Occupant[]    findAll()
 * @method Occupant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OccupantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Occupant::class);
    }

//    /**
//     * @return Occupant[] Returns an array of Occupant objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Occupant
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
