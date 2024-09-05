<?php

namespace App\Repository;

use App\Entity\Affectation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Affectation>
 */
class AffectationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectation::class);
    }


    // fonction de recherche des affectations
    // parametre : data = les données du formulaire reçu
    public function searchFiltres($form)
    {
        $qb = $this->createQueryBuilder('a');

        if (!is_null($form->get("poste")->getData())){
            $qb->andWhere('a.poste = :poste')
            ->setParameter('poste', $form->get("poste")->getData());
        }
        if (!is_null($form->get("restaurant")->getData())){
            $qb->andWhere('a.restaurant = :restaurant')
            ->setParameter('restaurant', $form->get("restaurant")->getData());
        }
        if (!is_null($form->get("collaborateur")->getData())){
            $qb->andWhere('a.collaborateur = :collaborateur')
            ->setParameter('collaborateur', $form->get("collaborateur")->getData());
        }/*
        if (!empty($form->get("date_debut")->getData())){
            $qb->andWhere('a.date_debut = :date_debut')
            ->setParameter('date_debut', $form->get("date_debut")->getData()->format('Y-m-d'));
        }
        if (!empty($form->get("date_fin")->getData())){
            $qb->andWhere('a.date_fin = :date_fin')
            ->setParameter('date_fin', $form->get("date_fin")->getData()->format('Y-m-d'));
        }*/

        return $qb->getQuery()
            ->getResult();
    }

    public function getCollaborateursRestaurant($restaurantId)
    {
        // fonction qui récupère la liste des collaborateurs en poste dans le restaurant
        return $this->createQueryBuilder('a')
            ->leftJoin('a.restaurant', 'r')
            ->leftJoin('a.collaborateur', 'c')
            ->leftJoin('a.poste', 'p')
            ->select('a, c, p')
            ->where('r.id = :restaurantId')
            ->andWhere('a.date_fin IS NULL')
            ->setParameter('restaurantId', $restaurantId)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Affectation[] Returns an array of Affectation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Affectation
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
