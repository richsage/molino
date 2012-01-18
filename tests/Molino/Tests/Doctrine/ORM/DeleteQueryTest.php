<?php

namespace Molino\Tests\Doctrine\ORM;

use Molino\Doctrine\ORM\Molino;
use Molino\Doctrine\ORM\DeleteQuery;
use Molino\Doctrine\ORM\SelectQuery;
use Doctrine\ORM\QueryBuilder;

class DeleteQueryTest extends TestCase
{
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->molino = new Molino($this->entityManager);
        $this->modelClass = 'Model\Doctrine\ORM\Article';
        $this->query = new DeleteQuery($this->molino, $this->modelClass);
    }

    public function testConfigureQueryBuilder()
    {
        $this->assertSame(QueryBuilder::DELETE, $this->query->getQueryBuilder()->getType());
    }

    public function testExecute()
    {
        $this->loadArticles(10);
        $this->query->execute();

        $selectQuery = new SelectQuery($this->molino, $this->modelClass);
        $this->assertSame(0, $selectQuery->count());
    }
}
