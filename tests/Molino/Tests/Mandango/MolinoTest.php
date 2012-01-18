<?php

namespace Molino\Tests\Mandango;

use Molino\Mandango\Molino;

class MolinoTest extends TestCase
{
    private $molino;

    protected function setUp()
    {
        parent::setUp();

        $this->molino = new Molino($this->mandango);
    }

    public function testGetMandango()
    {
        $this->assertSame($this->mandango, $this->molino->getMandango());
    }

    public function testGetName()
    {
        $this->assertSame('mandango', $this->molino->getName());
    }

    public function testCreate()
    {
        $model = $this->molino->create('Model\Mandango\Article');
        $this->assertInstanceOf('Model\Mandango\Article', $model);
        $this->assertTrue($model->isNew());
    }

    public function testSave()
    {
        $model = $this->mandango->create('Model\Mandango\Article');
        $this->molino->save($model);
        $this->assertFalse($model->isNew());
    }

    public function testRefresh()
    {
        $model = $this->mandango->create('Model\Mandango\Article')->setTitle('foo')->save();
        $model->getRepository()->update(array('_id' => $model->getId()), array('$set' => array('title' => 'bar')));
        $this->molino->refresh($model);
        $this->assertSame('bar', $model->getTitle());
    }

    public function testDelete()
    {
        $model = $this->mandango->create('Model\Mandango\Article');
        $model->save();
        $this->molino->delete($model);
        $repository = $this->mandango->getRepository('Model\Mandango\Article');
        $this->assertNull($repository->createQuery(array('_id' => $model->getId()))->one());
    }

    public function testCreateSelectQuery()
    {
        $query = $this->molino->createSelectQuery('Model\Mandango\Article');
        $this->assertInstanceOf('Molino\Mandango\SelectQuery', $query);
        $this->assertSame($this->molino, $query->getMolino());
        $this->assertSame('Model\Mandango\Article', $query->getModelClass());
    }

    public function testCreateUpdateQuery()
    {
        $query = $this->molino->createUpdateQuery('Model\Mandango\Article');
        $this->assertInstanceOf('Molino\Mandango\UpdateQuery', $query);
        $this->assertSame($this->molino, $query->getMolino());
        $this->assertSame('Model\Mandango\Article', $query->getModelClass());
    }

    public function testCreateDeleteQuery()
    {
        $query = $this->molino->createDeleteQuery('Model\Mandango\Article');
        $this->assertInstanceOf('Molino\Mandango\DeleteQuery', $query);
        $this->assertSame($this->molino, $query->getMolino());
        $this->assertSame('Model\Mandango\Article', $query->getModelClass());
    }

    public function testFindOneById()
    {
        $articles = $this->loadArticles(10);

        $this->assertSame($articles[2], $this->molino->findOneById('Model\Mandango\Article', $articles[2]->getId()));
        $this->assertSame($articles[5], $this->molino->findOneById('Model\Mandango\Article', $articles[5]->getId()));
        $this->assertNull($this->molino->findOneById('Model\Mandango\Article', 'no'));
    }
}
