<?php

namespace App\Repository;

use App\Entity\CommandePortionFrites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandePortionFrites>
 *
 * @method CommandePortionFrites|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandePortionFrites|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandePortionFrites[]    findAll()
 * @method CommandePortionFrites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandePortionFritesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandePortionFrites::class);
    }

    public function add(CommandePortionFrites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommandePortionFrites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CommandePortionFrites[] Returns an array of CommandePortionFrites objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CommandePortionFrites
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
