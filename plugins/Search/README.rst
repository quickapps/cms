Search Plugin
#############

The Search Plugin allows entities to be search-able through an auto-generated index
of words. You can make any table "index-able" by attaching the
``SearchableBehavior`` to it.

Searchable Behavior
===================

This `behavior <http://book.cakephp.org/3.0/en/orm/behaviors.html>`__ is provided by
the Search plugin and it's responsible of index each entity under your tables, it
also allows you to search any of those entities by using human-friendly search
criteria.

Using this Behavior
-------------------

You must indicate which fields can be indexed when attaching this behavior to your
tables. For example, when attaching this behavior to ``Users`` table:

.. code:: php

    $this->addBehavior('Search.Searchable', [
        'fields' => ['username', 'email']
    ]);

In the example above, this behavior will look for words to index in user’s
"username" and user’s "email" properties.

If you need a really special selection of words for each entity is being indexed,
then you can set the ``fields`` option as a callable which should return a list of
words for the given entity. For example:

.. code:: php

    $this->addBehavior('Search.Searchable', [
        'fields' => function ($user) {
            return "{$user->name} {$user->email}";
        }
    ]);

You can return either, a plain text of space-separated words, or an array list of
words:

.. code:: php

    $this->addBehavior('Search.Searchable', [
        'fields' => function ($user) {
            return [
                'word 1',
                'word 2',
                'word 3',
            ];
        }
    ]);

This behaviors will apply a series of filters (converts to lowercase, remove line
breaks, etc) to the resulting word list, so you should simply return a RAW string of
words and let this behavior do the rest of the job.

Banned Words
~~~~~~~~~~~~

You can use the ``bannedWords`` option to tell which words should not be indexed by
this behavior. For example:

.. code:: php

    $this->addBehavior('Search.Searchable', [
        'bannedWords' => ['of', 'the', 'and']
    ]);

If you need to ban a really specific list of words you can set ``bannedWords``
option as a callable method that should return true or false to tell if a words is
banned or not. For example:

.. code:: php

    $this->addBehavior('Search.Searchable', [
        'bannedWords' => function ($word) {
            return strlen($word) <= 3;
        }
    ]);

-  Returning TRUE indicates that the word is banned (will not be index).
-  Returning FALSE indicates that the word is NOT banned (will be index).

In the example, above any word of 4 or more characters will be indexed (e.g. "home",
"name", "quickapps", etc). Any word of 3 or less characters will be banned (e.g.
"and", "or", "the").

Searching Entities
------------------

When attaching this behavior, every entity under your table gets a list of indexed
words. The idea is you can use this list of words to locate any entity based on a
customized search-criteria. A search-criteria looks as follow:

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

You must use the ``search()`` method to scope any query using a search-criteria. For
example, in one controller using ``Users`` model:

.. code:: php

    $criteria = '"this phrase" OR -"not this one" AND this';
    $query = $this->Users->find();
    $query = $this->Users->search($criteria, $query);

The above will alter the given $query object according to the given criteria. The
second argument (query object) is optional, if not provided this Behavior
automatically generates a find-query for you. Previous example and the one below are
equivalent:

.. code:: php

    $criteria = '"this phrase" OR -"not this one" AND this';
    $query = $this->Users->search($criteria);

Search Operators
----------------

An ``Operator`` is a search-criteria command which allows you to perform very
specific SQL filter conditions. An operator is composed of **two parts**, a ``name``
and its ``arguments``, both parts must be separated using the ``:`` symbol e.g.:

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
``SearchableBehavior.operator<OperatorName>`` event will be triggered, so other
plugins may respond and handle any undefined operator. For example, given the search
criteria below, lets suppose ``date`` operator **was not defined** early:

::

    "this phrase" author:"John Locke" date:2013-06-06..2014-06-06

The ``SearchableBehavior.operatorDate`` event will be fired. A plugin may respond to
this call by implementing this event:

.. code:: php

    // ...

    public function implementedEvents() {
        return [
            'SearchableBehavior.operatorDate' => 'operatorDate',
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