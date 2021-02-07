<?php

namespace App\Repository;

use App\Entity\LineasDeIncidencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LineasDeIncidencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineasDeIncidencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineasDeIncidencia[]    findAll()
 * @method LineasDeIncidencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineasDeIncidenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineasDeIncidencia::class);
    }

    // /**
    //  * @return LineasDeIncidencia[] Returns an array of LineasDeIncidencia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LineasDeIncidencia
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
