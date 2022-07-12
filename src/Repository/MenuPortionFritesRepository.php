<?php

namespace App\Repository;

use App\Entity\MenuPortionFrites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MenuPortionFrites>
 *
 * @method MenuPortionFrites|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuPortionFrites|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuPortionFrites[]    findAll()
 * @method MenuPortionFrites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuPortionFritesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuPortionFrites::class);
    }

    public function add(MenuPortionFrites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MenuPortionFrites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MenuPortionFrites[] Returns an array of MenuPortionFrites objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MenuPortionFrites
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
