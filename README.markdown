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

    $article = $molino->create('Model\Article');

### Setting and getting data

As using setters and getters is a convention nowadays, Molino doesn't include any method to abstract this. So that you can use setters and getters in a normal way with the models:

    $article->setTitle('foo');
    $title = $article->getTitle();

### Saving models

You can save models by using the `saveModel` method:

    $molino->save($article);

### Refreshing models

You can refresh models by using the `refreshModel` method:

    $molino->refresh($article);

### Deleting models

You can delete models by using the `deleteModel` method:

    $molino->delete($article);

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

You can also use the `filter` method, which links to those methods:

    $query->filter('name', '==', 'Pablo');
    $query->filter('name', '!=', 'Pablo');

    $query->filter('name', 'in', array('Pablo', 'Pepe'));
    $query->filter('name', 'not_in', array('Pablo', 'Pepe'));

    $query->filter('age', '>', 20);
    $query->filter('age', '<', 20);
    $query->filter('age', '>=, 20);
    $query->filter('age', '<=', 2');

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

Select queries allow you to create an adapter for [Pagerfanta](https://github.com/whiteoctober/Pagerfanta):

    use Pagerfanta\Pagerfanta;

    $adapter = $selectQuery->createPagerfantaAdapter();
    $pagerfanta = new Pagerfanta($adapter);

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

## Events

You can optionally use events with molinos. In order to do that you have to use the class `EventMolino`, which receives a molino and an event dispatcher. You can use the event molino in a normal way, as it also implements the `MolinoInterface` it simply wraps a molino for use with events.

`EventMolino` depends on the Symfony2 EventDispatcher component, since it must receive an instance of `Symfony\Component\EventDispatcher\EventDispatcherInterface` as event dispatcher.

    use Molino\Mandango\Molino;
    use Molino\EventMolino;
    use Symfony\Component\EventDispatcher\EventDispatcher;

    $eventDispatcher = new EventDispatcher();
    $mandangoMolino = new Molino($mandango);
    $molino = new EventMolino($mandangoMolino, $eventDispatcher);

    // using the final molino in a normal way
    $model = $molino->createSelectQuery('Model\Article')
        ->filterEqual('is_active', true)
        ->sort('created_at', 'desc')
        ->one()
    ;

### Event classes

There are two event classes used:

  * `Molino\Event\ModelEvent`: for events with a model.
  * `Molino\Event\QueryEvent`: for events with a query.

You can access to the molino in both classes:

    $molino = $modelEvent->getMolino();
    $molino = $queryEvent->getMolino();

And to the model or query depending on the class:

    $model = $modelEvent->getModel();
    $modelEvent->setModel($model);

    $query = $queryEvent->getQuery();
    $queryEvent->setQuery($query);

### Events

The events you can use are saved as constants in the class `Molino\Event\Events`. They are:

  * `CREATE`: when creating a model. It uses a `ModelEvent`.
  * `PRE_SAVE`: before saving a model. It uses a `ModelEvent`.
  * `POST_SAVE`: after saving a model. It uses a `ModelEvent`.
  * `PRE_REFRESH`: before refreshing a model. It uses a `ModelEvent`.
  * `POST_REFRESH`: after refreshing a model. It uses a `ModelEvent`.
  * `PRE_DELETE`: before deleting a model. It uses a `ModelEvent`.
  * `POST_DELETE`: after deleting a model. It uses a `ModelEvent`.
  * `CREATE_QUERY`: when creating any query (select, update, delete). It uses a `QueryEvent`.

### Examples

Assigning an author to the articles automatically when creating articles:

    use Molino\Event\Events;
    use Molino\Event\ModelEvent;
    use Model\Article;

    $author = get_author();

    $eventDispather->addListener(Events::CREATE, function (ModelEvent $event) use ($author) {
        if ($event->getModel() instanceof Article) {
            $event->getModel()->setAuthor($author);
        }
    });

Filtering all articles queries by an author:

    use Molino\Event\Events;
    use Molino\Event\QueryEvent;

    $author = get_author();

    $eventDispather->addListener(Events::CREATE_QUERY, function (QueryEvent $event) use ($author) {
        if ('Model\Article' === $event->getQuery()->getModelClass()) {
            $event->getQuery()->filterEqual('author_id', $author->getId());
        }
    });

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
