<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    function allPosts(): array
    {
        return $this->createQueryBuilder('p')
           ->where('p.published = true')
           ->orderBy('p.createdAt', 'DESC')
           ->getQuery()
           ->getResult()
        ;
    }

    function lastPosts(int $id): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where($qb->expr()->notIn('p.id', ':id'))
           ->andWhere('p.published = true')
           ->setParameter('id', $id)
           ->orderBy('p.createdAt', 'DESC')
           ->setMaxResults(3)
        ;
        return $qb->getQuery()->getResult();
    }

    function postsInCategory(int $id): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.categories', 'c')
           ->where($qb->expr()->in('c.id', ':id'))
           ->andWhere('p.published = true')
           ->setParameter('id', $id)
           ->orderBy('p.createdAt', 'DESC')
        ;
        return $qb->getQuery()->getResult();
    }

    function postsInUser(int $id): array
    {
        return $this->createQueryBuilder('p')
           ->join('p.author', 'author')
           ->where('author.id = :id')
           ->setParameter('id', $id)
           ->orderBy('p.createdAt', 'DESC')
           ->getQuery()
           ->getResult()
        ;
    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
