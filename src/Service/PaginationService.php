<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PaginationService
 * Paging class that extracts any notion of calculation and data recovery from our controllers
 * It requires after instantiation that we pass the entity on which we want to work
 *
 * @package App\Service
 */
class PaginationService {
    /**
     * name of the entity on which you want to paginate
     * @var string
     */
    private $entityClass;

    /**
     * The number of records to recover
     * @var int
     */
    private $limit = 10;

    /**
     * @var int
     */
    private $currentPage = 1;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * PaginationService constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Retrieves paged data for a specific entity
     *
     * @return object[]
     * @throws \Exception if $entityClass property is not defined
     */
    public function getData()
    {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utiliser la méthode setEntityClass du service PaginationService");
        }
        // Calcul offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // ask Repo : findBy
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        // return
        return $data;
    }

    /**
     * Retrieves the number of pages that exist on a particular entity
     *
     * @return int
     * @throws \Exception if $entityClass is not defined
     */
    public function getPages()
    {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utiliser la méthode setEntityClass du service PaginationService");
        }
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return $this
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param $entityClass
     * @return $this
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

}