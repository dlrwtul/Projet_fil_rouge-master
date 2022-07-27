<?php

namespace App\Repository;

use App\Entity\DetailsProduitComplement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailsProduitComplement>
 *
 * @method DetailsProduitComplement|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailsProduitComplement|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailsProduitComplement[]    findAll()
 * @method DetailsProduitComplement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailsProduitComplementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailsProduitComplement::class);
    }

    public function add(DetailsProduitComplement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DetailsProduitComplement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DetailsProduitComplement[] Returns an array of DetailsProduitComplement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DetailsProduitComplement
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
