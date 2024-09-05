<?php

namespace App\Repository;

use App\Entity\Collaborateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Collaborateur>
 */
class CollaborateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collaborateur::class);
    }

    public function search($data)
    {
        // fonction de recherche a partir d'un mot
        return $this->createQueryBuilder('c')
            ->andWhere('c.nom LIKE :search')
            ->orWhere('c.prenom LIKE :search')
            ->orWhere('c.email LIKE :search')
            ->setParameter('search', '%' . $data . '%')
            ->getQuery()
            ->getResult();
    }

    public function searchNoAffect()
    {
        // fonction de recherche des collaborateur non affecté
        // on selectionne tout les collaborateur dont l'id n'est pas dans une affectation en cours
        return $this->createQueryBuilder('c')
            ->andWhere('c.id NOT IN (
                SELECT IDENTITY(a.collaborateur)
                FROM App\Entity\Affectation a
                WHERE a.date_fin IS NULL)'
            )
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Collaborateur[] Returns an array of Collaborateur objects
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

    //    public function findOneBySomeField($value): ?Collaborateur
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
