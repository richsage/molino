<?php

namespace Molino\Tests\Mandango;

use Mandango\Mandango;
use Mandango\Cache\ArrayCache;
use Mandango\Connection;
use Model\Mandango\Mapping\Metadata;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $mandango;

    protected function setUp()
    {
        if (!class_exists('Mongo')) {
            $this->markTestSkipped('Mongo is not available.');
        }

        $this->mandango = new Mandango(new Metadata(), new ArrayCache());
        $this->mandango->setConnection('global', new Connection('mongodb://localhost:27017', 'molino_mandango'));
        $this->mandango->setDefaultConnectionName('global');

        foreach ($this->mandango->getAllRepositories() as $repository) {
            $repository->getCollection()->drop();
        }
    }

    protected function loadArticles($nb)
    {
        $articles = array();
        for ($i = 1; $i <= $nb; $i++) {
            $articles[$i] = $this->mandango->create('Model\Mandango\Article')->setTitle($i)->save();
        }

        return $articles;
    }
}
