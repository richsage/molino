<?php

namespace Molino\Tests\Doctrine\ORM;

use Molino\Doctrine\ORM\Molino;
use Molino\Doctrine\ORM\BaseQuery as OriginalBaseQuery;

class BaseQuery extends OriginalBaseQuery
{
}

class BaseQueryTest extends TestCase
{
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->molino = new Molino($this->entityManager);
        $this->modelClass = 'Model\Doctrine\ORM\Article';
        $this->query = new BaseQuery($this->molino, $this->modelClass);
    }

    public function testGetQueryBuilder()
    {
        $queryBuilder = $this->query->getQueryBuilder();
        $this->assertInstanceOf('Doctrine\ORM\QueryBuilder', $queryBuilder);
        $this->assertSame($this->entityManager, $queryBuilder->getEntityManager());
        $this->assertSame(array($this->modelClass), $queryBuilder->getRootEntities());
        $this->assertSame($queryBuilder, $this->query->getQueryBuilder());
    }

    public function testFilterEqual()
    {
        $this->assertSame($this->query, $this->query->filterEqual('title', 'foo'));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title = ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => 'foo'), $this->query->getQueryBuilder()->getParameters());
    }

    public function testFilterNotEqual()
    {
        $this->assertSame($this->query, $this->query->filterNotEqual('title', 'foo'));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title <> ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => 'foo'), $this->query->getQueryBuilder()->getParameters());
    }

    /**
     * @dataProvider filterLikeProvider
     */
    public function testFilterLike($value, $like)
    {
        $this->assertSame($this->query, $this->query->filterLike('title', $value));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title LIKE ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => $like), $this->query->getQueryBuilder()->getParameters());
    }

    /**
     * @dataProvider filterLikeProvider
     */
    public function testFilterNotLike($value, $like)
    {
        $this->assertSame($this->query, $this->query->filterNotLike('title', $value));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title NOT LIKE ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => $like), $this->query->getQueryBuilder()->getParameters());
    }

    public function filterLikeProvider()
    {
        return array(
            array('foo', 'foo'),
            array('*foo', '%foo'),
            array('foo*', 'foo%'),
            array('*foo*', '%foo%'),
            array('f*oo', 'f%oo'),
        );
    }

    public function testFilterIn()
    {
        $this->assertSame($this->query, $this->query->filterIn('title', array('foo', 'bar')));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title IN ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => array('foo', 'bar')), $this->query->getQueryBuilder()->getParameters());
    }

    public function testFilterNotIn()
    {
        $this->assertSame($this->query, $this->query->filterNotIn('title', array('foo', 'bar')));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title NOT IN ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => array('foo', 'bar')), $this->query->getQueryBuilder()->getParameters());
    }

    public function testFilterGreater()
    {
        $this->assertSame($this->query, $this->query->filterGreater('age', 20));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age > ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => 20), $this->query->getQueryBuilder()->getParameters());
    }

    public function testFilterLess()
    {
        $this->assertSame($this->query, $this->query->filterLess('age', 20));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age < ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => 20), $this->query->getQueryBuilder()->getParameters());
    }

    public function testFilterGreaterEqual()
    {
        $this->assertSame($this->query, $this->query->filterGreaterEqual('age', 20));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age >= ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => 20), $this->query->getQueryBuilder()->getParameters());
    }

    public function testFilterLessEqual()
    {
        $this->assertSame($this->query, $this->query->filterLessEqual('age', 20));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age <= ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => 20), $this->query->getQueryBuilder()->getParameters());
    }

    public function testSeveral()
    {
        $this->query
            ->filterEqual('title', 'foo')
            ->filterNotEqual('content', 'bar')
            ->filterIn('author', array(1, 2))
        ;
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title = ?1 AND m.content <> ?2 AND m.author IN ?3', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => 'foo', 2 => 'bar', 3 => array(1, 2)), $this->query->getQueryBuilder()->getParameters());
    }
}
