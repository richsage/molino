<?php

namespace Molino\Tests\Doctrine\ORM;

use Molino\Doctrine\ORM\DeleteQuery;
use Molino\Doctrine\ORM\SelectQuery;
use Doctrine\ORM\QueryBuilder;

class DeleteQueryTest extends TestCase
{
    private $queryBuilder;
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->queryBuilder = $this->entityManager->createQueryBuilder()->from('Model\Doctrine\ORM\Article', 'm');
        $this->query = new DeleteQuery($this->queryBuilder);
    }

    public function testConstructorDelete()
    {
        $this->assertSame(QueryBuilder::DELETE, $this->queryBuilder->getType());
    }

    public function testExecute()
    {
        $this->loadArticles(10);
        $this->query->execute();

        $selectQuery = new SelectQuery($this->queryBuilder);
        $this->assertSame(0, $selectQuery->count());
    }
}
