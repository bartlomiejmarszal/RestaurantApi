<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function applyRequestParametersFilter(Request $request): array
    {
        $qb = $this->createQueryBuilder('restaurant');
        foreach ($request->query as $key => $value) {
            $this->setFilter($key, $value, $qb);
        }
        return  $qb->getQuery()->getResult();

    }

    /**
     * @param $key
     * @param $value
     * @param QueryBuilder $qb
     */
    private function setFilter($key, $value, QueryBuilder $qb)
    {
        switch ($key) {
            case $key === 'name':
                $qb->andWhere($qb->expr()->like("restaurant.name", ':name'));
                $qb->setParameter('name', $value);
                break;
            case $key === 'cousine':
                $qb->andWhere($qb->expr()->eq("restaurant.cuisine", ':cuisine'));
                $qb->setParameter('cuisine', $value);
                break;
            case $key === 'city':
                $qb->andWhere($qb->expr()->eq("restaurant.city", ':city'));
                $qb->setParameter('city', $value);
                break;
            case $key === 'search':
                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like('restaurant.name', ':name'),
                        $qb->expr()->like('restaurant.city', ':city'),
                        $qb->expr()->like('restaurant.cuisine', ':cuisine')
                    )
                );
                $qb->setParameter('city', $value);
                $qb->setParameter('cuisine', $value);
                $qb->setParameter('name', $value);
                break;
        }
    }
}
