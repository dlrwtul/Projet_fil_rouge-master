<?php

namespace App\Repository;

use App\Entity\CommandeBoissonTaille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeBoissonTaille>
 *
 * @method CommandeBoissonTaille|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeBoissonTaille|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeBoissonTaille[]    findAll()
 * @method CommandeBoissonTaille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeBoissonTailleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeBoissonTaille::class);
    }

    public function add(CommandeBoissonTaille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommandeBoissonTaille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneCommandeBoissonTailleBy(int $idBoissonTaille){
        return $this->createQueryBuilder('c')
                    ->join('App\Entity\BoissonTaille','bt','WITH')
                    ->andWhere('bt.id = :val')
                    ->setParameter('val',$idBoissonTaille)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

//    /**
//     * @return CommandeBoissonTaille[] Returns an array of CommandeBoissonTaille objects
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

//    public function findOneBySomeField($value): ?CommandeBoissonTaille
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
