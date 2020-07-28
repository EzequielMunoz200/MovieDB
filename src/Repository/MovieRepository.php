<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findByPartialTitle($partialTitle){
        $builder = $this->createQueryBuilder("movie");
        $builder->where("movie.title LIKE :partialTitle");
        $builder->setParameter('partialTitle', '%'.$partialTitle.'%');
        $builder->orderBy('movie.title', 'ASC');
        $query = $builder->getQuery();
       
        return $query->execute();
    }

    public function findWithFullData($id){
        $builder = $this->createQueryBuilder("movie");
        $builder->where("movie.id = :id");
        $builder->setParameter('id', $id);

        //je crée une jointure avec la table movie-actor
        $builder->leftjoin('movie.movieActors', 'actor');
        //J'ajoute l'acteur au select pour que doctrine alimente les objets associés
        $builder->addSelect('actor');

        //je crée la lointure avec les personnes
        $builder->leftjoin('actor.person', 'person');
        //J'ajoute la personne au select pour que doctrine alimente les objets associés
        $builder->addSelect('person');

        //pareil pour les articles
        //je crée une jointure avec la table post
        $builder->leftjoin('movie.posts', 'post');
        $builder->addSelect('post');

        $builder->leftjoin('movie.director', 'director');
        $builder->addSelect('director');

        $builder->leftjoin('movie.writers', 'writer');
        $builder->addSelect('writer');

        $builder->leftjoin('movie.categories', 'category');
        $builder->addSelect('category');


        $query = $builder->getQuery();
        $result = $query->getOneOrNullResult();

        return $result;
    }


    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
