<?php

namespace App\Repository;

use App\Entity\Notifications;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Notifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notifications[]    findAll()
 * @method Notifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notifications::class);
    }
    
    
    public function findUnseenByUser (User $user)
    {

        $qb = $this->createQueryBuilder("n");

        return $qb->select("count(n)")
            ->where('n.user = :user')
            ->andWhere('n.seen = 0')
            ->setParameter('user',$user)
            ->orderBy('n.id' , 'DESC')
            ->getQuery()
            ->getSingleScalarResult();
    
    }
    
    
    
//    /**
//     * @return Notifications[] Returns an array of Notifications objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notifications
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
