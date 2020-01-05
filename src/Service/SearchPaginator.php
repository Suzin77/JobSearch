<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;


class SearchPaginator
{
    public $itemsPerPage = 10;
    public $numberOfEntries;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public $numberOfPages;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function init(QueryBuilder $queryBuilder)
    {
        $this->setQueryBuilder($queryBuilder);
        $this->setNumberOfEntries();
        $this->setNumberOfPages();
    }


    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function setNumberOfPages()
    {
        $this->numberOfPages = $this->calculateNumberOfPages();
    }

    public function getNumberOfPages()
    {
        return $this->numberOfPages;
    }

    private function setNumberOfEntries()
    {
        $this->numberOfEntries = count($this->queryBuilder->getQuery()->execute());
    }

    public function getNumberOfEntries()
    {
        return $this->numberOfEntries;
    }

    private function calculateNumberOfPages()
    {
        return (int) ceil($this->numberOfEntries/$this->itemsPerPage);
    }

    public function getData(int $currentPage)
    {
        $firstResult = $currentPage* $this->itemsPerPage - $this->itemsPerPage;
        $maxResult = $this->itemsPerPage;

        $this->queryBuilder->setFirstResult($firstResult)
            ->setMaxResults($maxResult);

        return $this->queryBuilder->getQuery()->execute();
    }

}