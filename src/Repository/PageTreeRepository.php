<?php

namespace App\Repository;

use App\Entity\PageTree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PageTree>
 *
 * @method PageTree|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageTree|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageTree[]    findAll()
 * @method PageTree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageTree::class);
    }

    public function save(PageTree $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PageTree $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
