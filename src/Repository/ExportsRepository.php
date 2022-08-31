<?php

namespace App\Repository;

use App\Entity\Exports;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Types\Type;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Exports>
 *
 * @method Exports|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exports|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exports[]    findAll()
 * @method Exports[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExportsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exports::class);
    }

    public function add(Exports $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Exports $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findExports(string $local, int $from, int $to): array
    {

        $fromdt = new \DateTimeImmutable();
        $fromdt = $fromdt->setTimestamp($from);

        $todt = new \DateTimeImmutable();
        $todt = $todt->setTimestamp($to);

        $qb = $this->createQueryBuilder('e')
            //->andWhere('e.exportAt BETWEEN :from AND :to')
            ->where('e.exportAt >= :from')
            ->andWhere('e.exportAt <= :to')
            ->setParameter('from',$fromdt->format('Y-m-d'))
            ->setParameter('to',$todt->format('Y-m-d'));

        if (!empty($local) && $local !== '__ALL__') {
            $qb->andWhere('e.localName = :localName')
                ->setParameter('localName',$local);
        }

        $qb->orderBy('e.exportAt', 'ASC');

        $query = $qb->getQuery()->getArrayResult();

        return $query;

    }

    public function getLocals(): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e.localName');

        $locals = [];

        foreach($qb->getQuery()->getArrayResult() as $l) {
            $locals[] = $l['localName'];
        }

        return array_unique($locals);
    }
//    /**
//     * @return Exports[] Returns an array of Exports objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Exports
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
