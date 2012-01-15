# Molino

[![Build Status](https://secure.travis-ci.org/pablodip/molino.png)](http://travis-ci.org/pablodip/molino)

Molino is a small and fast library to make reusable tools persistence backend agnostic.

Molino doesn't provide anything for defining models. This means that you have to define the models for the backend you want to work with in a normal way.

## Usage

You work with a molino, which is just an implementation of the `MolinoInterface`:

    $molino = new Molino();

### Name

Each molino must have a name:

    $name = $molino->getName();

### Creating models

You can create models by using the `createModel` method with the model class as the first argument:

    $article = $molino->createModel('Model\Article');

### Setting and getting data

As using setters and getters is a convention nowadays, Molino doesn't include any method to abstract this. So that you can use setters and getters in a normal way with the models:

    $article->setTitle('foo');
    $title = $article->getTitle();

### Saving models

You can save models by using the `saveModel` method:

    $molino->saveModel($article);

### Refreshing models

You can refresh models by using the `refreshModel` method:

    $molino->refreshModel($article);

### Deleting models

You can delete models by using the `deleteModel` method:

    $molino->deleteModel($article);

## Querying

Molino has three types of queries: select, update and delete, which you can create from the molino:

    $selectQuery = $molino->createSelectQuery('Model\Article');
    $updateQuery = $molino->createUpdateQuery('Model\Article');
    $deleteQuery = $molino->createDeleteQuery('Model\Article');

### Filtering

The three types of queries can be filtered:

    $query->filterEqual('name', 'Pablo');
    $query->filterNotEqual('name', 'Pablo');

    $query->filterIn('name', array('Pablo', 'Pepe'));
    $query->filterNotIn('name', array('Pablo', 'Pepe'));

    $query->filterGreater('age', 20);
    $query->filterLess('age', 20);
    $query->filterGreaterEqual('age', 20);
    $query->filterLessEqual('age', 20);

### Selecting

You can specify the fields you want to select:

    $selectQuery->select(array('title', 'content'));

You can sort, limit, skip:

    $selectQuery->sort('name', 'asc'); // asc o desc
    $selectQuery->limit(10);
    $selectQuery->skip(10);

You can retrieve all, one and the number of results:

    $articles = $selectQuery->all();
    $article = $selectQuery->one();
    $nbArticles = $selectQuery->count();

Select queries implement both the `Countable` and `IteratorAggregate` interfaces:

    $nb = count($selectQuery);
    foreach ($selectQuery as $model) {
        // ...
    }

### Updating

You can set and increment fields:

    $updateQuery->set('name', 'Pablo');
    $updateQuery->inc('age', 1);


Then you execute the update queries:

    $updateQuery->execute();

### Removing

Remove queries can only be filtered and executed:

    $removeQuery->execute();

### Fluent interface

All queries implement a fluent interface:

    $articles = $molino->createSelectQuery('Model\Article')
        ->filterEqual('isActive', true)
        ->sort('createdAt', 'desc')
        ->limit(10)
        ->all()
    ;

## Molinos

Molino comes with two molinos:

### Mandango

To work with [Mandango](http://mandango.org/):

    use Molino\Mandango\Molino;

    $molino = new Molino($mandango);
    $molino->getName() // mandango

### Doctrine ORM

To work with [Doctrine ORM](http://www.doctrine-project.org/projects/orm):

    use Molino\Doctrine\ORM\Molino;

    $molino = new Molino($entityManager);
    $molino->getName() // doctrine_orm

## Limitations

You can do many things with Molino, but as you can probably guess you can't do everything as its API is small (though it's like this to be compatible with different backends).

Anyway, this doesn't mean you can't do complex things compatible with different backends. You just need to implement the parts you can't do with Molino directly by discriminating by backend:

    if ('mandango' === $molino->getName()) {
        // Mandango implementation
    } elseif ('doctrine_orm' === $molino->getName()) {
        // Doctrine ORM implementation
    } else {
        throw new \RuntimeException('This application only works with the following molinos: "mandango", "doctrine_orm".');
    }

## Author

Pablo Díez - <pablodip@gmail.com>

## License

Molino is licensed under the MIT License. See the LICENSE file for full details.
