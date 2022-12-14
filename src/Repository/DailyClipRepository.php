<?php

namespace App\Repository;

use App\Entity\DailyClip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DailyClip>
 *
 * @method DailyClip|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyClip|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyClip[]    findAll()
 * @method DailyClip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyClipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyClip::class);
    }

    public function save(DailyClip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function clear(bool $flush = false): void
    {
        $a = $this->findAll();
        foreach ($a as $entity) {
            $this->remove($entity);
        }
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DailyClip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DailyClip[] Returns an array of DailyClip objects
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

//    public function findOneBySomeField($value): ?DailyClip
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
