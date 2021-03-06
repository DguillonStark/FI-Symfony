<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\ArticleSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /*
     * @return Query
     */
    public function findAllQuery(ArticleSearch $search): Query
    {
        $query = $this->createQueryBuilder('a');

        if ($search->getTitre()) {
            $query = $query
                ->andWhere('a.title like :title');
            $query->setParameter('title', '%'.$search->getTitre().'%');
        }

        if ($search->getTags()->count() > 0) {
            // Avoid sql Injection
            $k = 0;
            foreach ($search->getTags() as $tag) {
                $k++;
                $query = $query
                    ->andWhere(":tag$k MEMBER OF a.tags")
                    ->setParameter("tag$k", $tag);
            }
        }


        $query = $query->orderBy('a.createdAt', 'DESC');

        return $query->getQuery();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
