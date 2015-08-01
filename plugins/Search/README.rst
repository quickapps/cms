Search Plugin
#############

The Search Plugin allows entities to be search-able through an auto-generated index
of words. You can make any table "index-able" by attaching the
``SearchableBehavior`` to it.

Searchable Behavior
===================

This `behavior <http://book.cakephp.org/3.0/en/orm/behaviors.html>`__ is provided by
the Search plugin and allows entities to be "searchable" by using interchangeable
search engines, such engines are responsible of index each entity under your tables,
they also allows you to locate any of those entities using engine-specific query
language.

Using this Behavior
-------------------

You must indicate attach the Searchable behavior and tell which search engine should
be used, by default ``Generic Engine`` will be used which should cover most cases,
however new Engine adapters can be created to cover your needs:

.. code:: php

    $this->addBehavior('Search.Searchable', [
        'engine' => [
            'className' => 'Search\Engine\Generic\GenericEngine',
            'config' => [
                'bannedWords' => []
            ]
        ]
    ]);

This particular engine (GenericEngine) will apply a series of filters (converts to
lowercase, remove line breaks, etc) to words list extracted from each entity being
indexed. For more details check "Generic Engine" documentation.

Searching Entities
------------------

When attaching this behavior, every entity under your table gets indexed ``depending
on the Search Engine being used``. The idea is you can use this index to locate any
entity indexed in that way. To search entities you should use the `search()` method:

.. code:: php

    $query = $this->Articles->search($criteria);

This method interacts with the engine being used. The ``$criteria`` must be a valid
search-query compatible with the engine being used.

Indexing Events
---------------

Whatever search engine is being used, some events are automatically triggered by
Searchable Behavior when an entity is being index or when its index is being
removed, you can catch these events in your table and alter the index information as
you need:

- ``Model.beforeIndex``: Before entity gets indexed by the configured search engine
  adapter. First argument is the entity instance being indexed.

- ``Model.afterIndex``: After entity was indexed by the configured search engine
  adapter. First argument is the entity instance that was indexed, and second
  indicates whether the indexing process completed correctly or not.

- ``Model.beforeRemoveIndex``: Before entity's index is removed. First argument is
  the affected entity instance.

- ``Model.afterRemoveIndex``: After entity's index is removed. First argument is the
  affected entity instance, and second indicates whether the index-removing process
  completed correctly or not.

Search Criteria
---------------

In most cases ``$criteria`` will be a string representing a search query. For
instance: ``chris AND pratt AND -rat``. Criteria's syntax depends exclusively on the
search engine being used. Search plugin provides a generic criteria parsing API for
defining new criteria syntax.

By default Search plugin comes with one built-in language parser: "Mini-Language
Parser" which is used by the built-in "Generic Engine" search engine.

A criteria parser must satisfy the ``Search\Parser\ParserInterface`` interface;
basically it must provide the ``parser()`` method which must return an array list of
"tokens" objects (``Search\Parser\TokenInterface``).

Search Operators
----------------

An ``Operator`` is a search-criteria command which allows you to perform very
specific SQL filter conditions. An operator is composed of **two parts**, a ``name``
and its ``arguments``, both parts separated using the ``:`` symbol e.g.:

::

    // operator name is: "created"
    // operator arguments are: "2013..2016"
    created:2013..2016

.. note::

    Operators names are treated as **lowercase_and_underscored**, so ``AuthorName``,
    ``AUTHOR_NAME`` or ``AuThoR_naMe`` are all treated as: ``author_name``.

You can define custom operators for your table by using the ``addSearchOperator()``
method. For example, you might need create a custom operator ``author`` which would
allow you to search an ``Article`` entity by its author name. A search-criteria
using this operator may looks as follow:

::

    // get all articles containing `this phrase` and created by `John Locke`
    "this phrase" author:"John Locke"

You can define in your table an operator method and register it into this behavior
under the `author` name, a full working example may look as follow:

.. code:: php

    class MyTable extends Table {
        public function initialize(array $config)
        {
            // attach the behavior
            $this->addBehavior('Search.Searchable');

            // register a new operator for handling `author:<author_name>` expressions
            $this->addSearchOperator('author', 'operatorAuthor');
        }

        public function operatorAuthor(Query $query, Token $token)
        {
            // $query: The query object to alter
            // $token: Token representing the operator to apply.
            // Scope query using $token information and return.
            return $query;
        }
    }

You can also define operator as a callable function:

.. code:: php

    class MyTable extends Table
    {
        public function initialize(array $config)
        {
            $this->addBehavior('Search.Searchable');

            $this->addSearchOperator('author', function(Query $query, Token $token) {
                // Scope query and return.
                return $query;
            });
        }
    }

Built-in Operator
~~~~~~~~~~~~~~~~~

Search Plugin comes with a few of these operator that should cover most common use
cases:

Date Operator
^^^^^^^^^^^^^

Allows to filter by date-based column types, for example, ``created``, ``modified``,
etc. Date ranges are fully supported as follow: ``created:2014..2015``.

To use this operator you should indicate the column you wish to scope as follow:

.. code:: php

    $this->addSearchOperator('created', 'Search.Date', ['field' => 'created_on']);

Once operator is attached you should be able to filter using the ``created``
operator in you search criteria:, for example:

.. code:: php

    $criteria = "created:2015..2016";
    $this->Articles->search($criteria);

Generic Operator
^^^^^^^^^^^^^^^^

Provides generic scoping for any column type. Usage:

.. code:: php

    $this->addSearchOperator('name', 'Search.Date', ['field' => 'name']);

Supported options:

-   conjunction: Indicates which conjunction type should be used when scoping the
    column. Defaults to `auto`, accepted values are:

    - LIKE: Useful when matching string values, accepts wildcard ``*`` for matching
      "any" sequence of chars and ``!`` for matching any single char. e.g.
      ``author:c*`` or ``author:ca!``, mixing: ``author:c!r*``.

    - IN: Useful when operators accepts a list of possible values. e.g.
      ``author:chris,carter,lisa``.

    - =: Used for strict matching.

    - <>: Used for strict matching.

    - auto: Auto detects, it will use ``IN`` if comma symbol is found in the given
      value, ``LIKE`` will be used otherwise. e.g. For ``author:chris,peter`` the
      "IN" conjunction will be used, and for ``author:chris`` the "LIKE" conjunction
      will be used instead.

Limit Operator
^^^^^^^^^^^^^^

Allows to limit the number of results. Usage:

.. code:: php

    $this->addSearchOperator('num_articles', 'Search.Limit');

Once operator is attached you should be able to filter using the ``num_articles``
operator in you search criteria:, for example:

.. code:: php

    $criteria = "num_articles:6";
    $this->Articles->search($criteria);


Order Operator
^^^^^^^^^^^^^^

Allows to order results by given columns. When attaching this operator you must
indicate which columns are allowed to be ordered by, for example:

.. code:: php

    $this->addSearchOperator('order_articles_by', 'Search.Order', [
        'fields' => ['title', 'created_on']
    ]);

In this example, results can be sorted only by "title" and "created_on" columns.
Once operator is attached you should be able to filter using the
``order_articles_by`` operator in you search criteria and indicating the column and
the ordering direction ("asc" or "desc"), if no direction is given "asc" will be
used by default, for example:

.. code:: php

    $criteria = "order_articles_by:title,asc";
    $this->Articles->search($criteria);

Ordering by multiple columns is supported, in these cases each order command must be
separated using the ``;`` symbol:

.. code:: php

    $criteria = "order_articles_by:title;created_on,desc";
    $this->Articles->search($criteria);

Range Operator
^^^^^^^^^^^^^^

Allows to scope results matching a given range constraint, in order words, SQL's
``BETWEEN`` equivalent. Lower and upper values must be separated using "..".
Example:

.. code:: php

    $this->addSearchOperator('comments_count', 'Search.Range', [
        'field' => 'num_comments'
    ]);

Once operator is attached you should be able to filter using the ``comments_count``
operator in you search criteria:, for example:

.. code:: php

    $criteria = "comments_count:6..10";
    $this->Articles->search($criteria);

This example should return only articles with 6 to 10 comments.


Creating Reusable Operators
~~~~~~~~~~~~~~~~~~~~~~~~~~~

If your application has operators that are commonly reused, it is helpful to package
those operators into re-usable classes:

.. code:: php

    // in MyPlugin/Model/Search/CustomOperator.php
    namespace MyPlugin\Model\Search;

    use Search\Operator;

    class CustomOperator extends Operator
    {
        public function scope($query, $token)
        {
            // Scope $query
            return $query;
        }
    }

    // In any table class:

    // Add the custom operator,
    $this->addSearchOperator('operator_name', 'MyPlugin.Custom', ['opt1' => 'val1', ...]);

    // OR passing a constructed operator
    use MyPlugin\Model\Search\CustomOperator;
    $this->addSearchOperator('operator_name', new CustomOperator($this, ['opt1' => 'val1', ...]));


Fallback Operators
~~~~~~~~~~~~~~~~~~

When an operator is detected in the given search criteria but no operator callable
was defined using ``addSearchOperator()``, then
``Search.operator<OperatorName>`` event will be triggered, so other
plugins may respond and handle any undefined operator. For example, given the search
criteria below, lets suppose ``date`` operator **was not defined** early:

::

    "this phrase" author:"John Locke" date:2013-06-06..2014-06-06

The ``Search.operatorDate`` event will be fired. A plugin may respond to
this call by implementing this event:

.. code:: php

    // ...

    public function implementedEvents() {
        return [
            'Search.operatorDate' => 'operatorDate',
        ];
    }

    // ...

    public function operatorDate($event, $query, $token)
    {
        // alter $query object and return it
        return $query;
    }

    // ...

.. note::

    -  Event handler method should always return the modified $query object.
    -  The event’s context, that is ``$event->subject``, is the table instance that
       triggered the event.


Interacting With The Engine
---------------------------

You can get an instance of the Search Engine being used by invoking the
``searchEngine()`` method, this allows you, for instance, manually index an entity,
get index information for an specific entity, etc.

.. code:: php

    $engine = $this->Articles->searchEngine();
    $engine->search( ... );
    $engine->get( ... );
    $engine->index( ... );
    $engine->delete( ... );


You can also use the ``searchEngine()`` method to change the engine on the fly:

.. code:: php

    $config = [ ... ];
    $engine = $this->Articles->searchEngine(new CustomSearchEngine($this->Articles, $config));


Engines Adapters
################

New search engine adapters can be created, such adapters must simply extend the
class ``Search\Engine\BaseEngine``. These adapters must provide methods for
indexing, retrieving and removing indexes. This allows for instance use different
search engines for indexing different tables.

This plugin comes with one built-in Search Engine adapter: ``Generic Engine`` which
should be enough in most cases. However, when working with big-sized tables a more
efficiency approach is recommended, such as ``Elasticsearch``, ``Apache SOLR``,
``Sphinx``, etc.


---


Generic Engine
##############

Search plugins comes with one built-in Engine which should cover most use cases.
This Search Engine allows entities to be searchable through an auto-generated list
of words using ``LIKE`` SQL expressions, and optionally ``fulltext`` based searchs.
If you need to hold a very large amount of index information you should create your
own Engine adapter to work with third-party solutions such as "Elasticsearch",
"Sphinx", etc. Or enable ``fulltext`` index to speed up Generic Engine.


Using Generic Engine
--------------------

You must indicate Searchable behavior to use this engine when attaching Search
Behavior to your table. For example when attaching Searchable behavior to `Articles`
table:

.. code:: php

    $this->addBehavior('Search.Searchable', [
        'engine' => [
            'className' => 'Search\Engine\Generic\GenericEngine',
            'config' => [
                'bannedWords' => []
            ]
        ]
    ]);

This engine will apply a series of filters (converts to lowercase, remove line
breaks, etc) to words list extracted from each entity being indexed.


Banned Words
------------

You can use the `bannedWords` option to tell which words should not be indexed by
this engine. For example:


.. code:: php

    $this->addBehavior('Search.Searchable', [
        'engine' => [
            'className' => 'Search\Engine\Generic\GenericEngine',
            'config' => [
                'bannedWords' => ['of', 'the', 'and']
            ]
        ]
    ]);

If you need to ban a really specific list of words you can set `bannedWords` option
as a callable method that should return true or false to tell if a words should be
indexed or not. For example:

.. code:: php

    $this->addBehavior('Search.Searchable', [
        'engine' => [
            'className' => 'Search\Engine\Generic\GenericEngine',
            'config' => [
                'bannedWords' => function ($word) {
                    return strlen($word) > 3;
                }
            ]
        ]
    ]);

- Returning TRUE indicates that the word is safe for indexing (not banned).
- Returning FALSE indicates that the word should NOT be indexed (banned).

In the example, above any word of 4 or more characters will be indexed (e.g. "home",
"name", "quickapps", etc). Any word of 3 or less characters will be banned (e.g.
"and", "or", "the").


Searching Entities
------------------

When using this engine, every entity under your table gets a list of indexed words.
The idea behind this is that you can use this list of words to locate any entity
based on a customized search-criteria. A search-criteria looks as follow:

::

    "this phrase" OR -"not this one" AND this

Use wildcard searches to broaden results; asterisk (``*``) matches any one or more
characters, exclamation mark (``!``) matches any single character:

::

    "thisrase" OR wor* AND thi!

Anything containing space (" ") characters must be wrapper between quotation marks:

::

    "this phrase" my_operator:100..500 -word -"more words" -word_1 word_2

The search criteria above will be treated as it were composed by the following
parts:

::

    [
        this phrase,
        my_operator:100..500,
        -word,
        -more words,
        -word_1,
        word_2,
    ]

Search criteria allows you to perform complex search conditions in a human-readable
way. Allows you, for example, create user-friendly search-forms, or create some RSS
feed just by creating a friendly URL using a search-criteria. e.g.:
``http://example.com/rss/category:music created:2014``

You must use the Searchable Behavior's ``search()`` method to scope any query using
a search-criteria. For example, in some controller using ``Articles`` model:

.. code:: php

    $criteria = '"this phrase" OR -"not this one" AND this';
    $query = $this->Articles->find();
    $query = $this->Articles->search($criteria, $query);

The above will alter the given ``$query`` object according to the given criteria.
The second argument (query object) is optional, if not provided this Searchable
Behavior automatically generates a find-query for you. Previous example and the one
below are equivalent:

.. code:: php

    $criteria = '"this phrase" OR -"not this one" AND this';
    $query = $this->Articles->search($criteria);


Fulltext Search
---------------

Generic engine uses by default ``LIKE`` SQL-statements when searching trough index,
this should be enough for small sized web sites. However, for large websites
fulltext index is recommended in order to improve search speed, you can enable
fulltext search by simply creating a ``fulltext index`` for the ``words`` column of
the ``search_datasets``.
