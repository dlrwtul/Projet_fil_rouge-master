<?php

namespace App\Repository;

use App\Entity\CommandeMenuBoissonTaille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeMenuBoissonTaille>
 *
 * @method CommandeMenuBoissonTaille|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeMenuBoissonTaille|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeMenuBoissonTaille[]    findAll()
 * @method CommandeMenuBoissonTaille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeMenuBoissonTailleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeMenuBoissonTaille::class);
    }

    public function add(CommandeMenuBoissonTaille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommandeMenuBoissonTaille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CommandeMenuBoissonTaille[] Returns an array of CommandeMenuBoissonTaille objects
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

//    public function findOneBySomeField($value): ?CommandeMenuBoissonTaille
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
