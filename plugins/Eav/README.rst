EAV Plugin
##########

    Entity–attribute–value model (EAV) is a data model to describe entities where
    the number of attributes (properties, parameters) that can be used to describe
    them is potentially vast, but the number that will actually apply to a given
    entity is relatively modest. In mathematics, this model is known as a sparse
    matrix. EAV is also known as object–attribute–value model, vertical database
    model and open schema.

    -- Wikipedia


Introduction
------------

You will typically use an EAV pattern when you have Entities with a variable number
of attributes, and these attributes can be of different types. This makes it
impossible to define these attributes as column in the entity's table, because there
would be too many, most of them will not have data, and you can't deal with dynamic
attributes at all because columns need to be pre-defined in relational databases.

To solve this situation in a relational fashion you would create a child table, and
relate that to the 'entity' table using a One-to-Many relation, where every
attribute would become a record in the child table. Downside of this approach
however is that to be able to get a specific attribute value, you'll have to loop
over all related records, compare the value of the attribute column with the
attribute you look for, and if a match is found, get the contents of the value
column.

EAV Plugin uses this same implementation, but allows you to merge these virtual
attributes with the entity, so the attributes become properties of the entity
object.


Installation
------------

You can install EAV plugin into your CakePHP project using Composer:

.. code:: bash

    $ composer require quickapps-plugins/eav:"*"

Plugin Loading
^^^^^^^^^^^^^^

Then edit your project ``bootstrap.php`` file and make sure EAV plugin is being
loaded correctly:

.. code:: php

    Plugin::load('Eav');

Check Cake's documentation for further information.

Import DB Schemas
^^^^^^^^^^^^^^^^^

Lastly use the provided SQL script ``/config/eav-mysql.sql`` and import it into your
project's database (MySQL only for the moment), this will create two tables that are
used internally by EAV plugin to store and define virtual properties.


Usage
-----

Once EAV plugin has been loaded into your project and all MySQL tables has been
created your are ready to go. To start using the EAV API you must attach the
``Eav.Eav`` behavior to the table you wish to "extend" (add virtual columns to it),
for example:

.. code:: php

    use Cake\ORM\Table;

    class UsersTable extends Table
    {
        public function initialize(Table $table)
        {
            $this->addBehavior('Eav.Eav');
        }
    }

Defining Attributes
-------------------

Once EAV behavior is attached to your table, you can now start defining virtual
columns. There are two ways of defining virtual columns, CLI based or php-script
based. We'll explain how to define such columns using both methods.


Using EAV CLI (Recommended)
^^^^^^^^^^^^^^^^^^^^^^^^^^^

EAV plugin provides a simple management command-line-interface (CLI) which allows
you to easily add or drop virtual columns.

You need to tell which table is being altered, what action you wish to perform (add
new virtual column, or drop existing one). And if you are adding new column you must
provide column information (column name, data type, etc). Below an example on how to
add new virtual column named `user_age`:

.. code:: bash

    user@name:/path/to/bin/$ cake Eav.table schema --use UsersPlugin.UsersTable --action add --name user_age --type integer --searchable


The ``searchable`` indicates that this virtual column can be in ``WHERE`` clauses.
If you want to drop an existing column:

.. code:: bash

    user@name:/path/to/bin/$ cake Eav.table schema --use UsersPlugin.UsersTable --action drop --name user_age


Check EAV CLI help for more options available.


Using PHP Script
^^^^^^^^^^^^^^^^

.. warning::

    You should do this step just once, otherwise you will end unnecessary updating
    columns every time the script is executed.

You can create new virtual columns definitions using the ``addColumn()`` method of
your table, this method will **update column information if already exists**:

.. code:: php

    use Cake\ORM\Table;

    class UsersTable extends Table
    {
        public function initialize(Table $table)
        {
            $this->addBehavior('Eav.Eav');
            // WARNING: just run once these two lines
            $this->addColumn('user-age', ['type' => 'integer']);
            $this->addColumn('user-address', ['type' => 'string', 'bundle' => 'admin']);
        }
    }

The first argument is the name of the column your are defining, you **must use lower
case letters, numbers or "-" symbol**. For instance, ``user-age`` is a valid column
name but ``user_age`` or ``User-Age`` are not.

And second argument is used to define column's metadata and supports the following
keys:

- type (string): Type of data for that attribute, note that using any other type not
  listed here will throw an exception. Supported values are:

  - **biginteger**
  - **binary**
  - **date**
  - **float**
  - **decimal**
  - **integer**
  - **time**
  - **datetime**
  - **timestamp**
  - **uuid**
  - **string**
  - **text**
  - **boolean**

- bundle (string): Indicates the attribute belongs to a bundle name within the
  table, check the "Bundles" section for further information. Defaults to **null**
  (no bundle).

- searchable (bool): Whether this attribute can be used in SQL's "WHERE" clauses.
  Defaults to **true**


Dropping Virtual Columns
------------------------

You can also drop existing virtual columns previously defined using ``addColumn()``,
to do this you can use the ``dropColumn()`` method:

.. code:: php

    use Cake\ORM\Table;

    class UsersTable extends Table
    {
        public function initialize(Table $table)
        {
            $this->addBehavior('Eav.Eav');
            $this->dropColumn('user-age');
            $this->dropColumn('user-address', 'admin');
        }
    }

Optionally the second argument can be used to indicate the bundle where the column
can be found.

.. warning::

    This method will **remove any stored information** associated to the column
    being dropped, so use with extreme caution.


Fetching Entities
-----------------

After behavior is attached to your table and some virtual columns are defined, you
can start fetching entities from your table as usual, using "Table::find()" or
similar; every Entity fetched in this way will have additional attributes as they
were conventional table columns. For example in any controller:

.. code:: php

    $user = $this->Users->get(1);
    debug($user)

    [
        // ...
        'properties' => [
            'id' => 1, // real table column
            'name' => 'John', // real table column
            'user-age' => 15 // EAV attribute
            'user-phone' => '+34 256 896 200' // EAV attribute
        ]
    ]

You can use your EAV attributes as usual; you can apply validation rules, use them
in your **WHERE** clauses, create form inputs, save entities, etc:

.. code:: php

    $adults = $this->Users
        ->find()
        ->where(['Users.user-age >' => 18])
        ->all();

.. note::

    EAV API has some limitation, for instance you cannot use virtual attributes in
    ORDER BY clauses, GROUP BY, HAVING or any aggregation function.


Bundles
-------

Bundles are sub-sets of attributes within the same table. For example, we could have
"articles pages", "plain pages", etc; all of them are Page entities but they might
have different attributes depending to which bundle they belongs to:

.. code:: php

    $this->addColumn('article-body', ['type' => 'text', 'bundle' => 'article']);
    $this->addColumn('page-body', ['type' => 'text', 'bundle' => 'page']);

We have defined two different columns for two different bundles, ``article`` and
``page``, now we can find Page Entities and fetch attributes only of certain
``bundle``:

.. code:: php

    $firstArticle = $this->Pages
        ->find('all', ['bundle' => 'article'])
        ->limit(1)
        ->first();

    $firstPage = $this->Pages
        ->find('all', ['bundle' => 'page'])
        ->limit(1)
        ->first();

    debug($firstArticle);
    // Produces:
    [
        // ...
        'properties' => [
            'id' => 1,
            'article-body' => 'Lorem ipsum dolor sit amet ...',
        ]
    ]

    debug($firstPage);
    // Produces:
    [
        // ...
        'properties' => [
            'id' => 5,
            'page-body' => 'Nulla consequat massa quis enim. Donec pede.',
        ]
    ]

If no ``bundle`` option is given when retrieving entities EAV behavior will fetch
all attributes regardless of the bundle they belong to:

.. code:: php

    $firstPage = $this->Pages
        ->find()
        ->limit(1)
        ->first();

    debug($firstPage);
    // Produces:
    [
        // ...
        'properties' => [
            'id' => 5,
            'article-body' => 'Lorem ipsum dolor sit amet ...',
            'page-body' => null
        ]
    ]


.. warning::

    Please be aware that using the ``bundle`` option you are telling EAV behavior to
    fetch only attributes within that bundle, this may produce ``column not found``
    SQL errors when using incorrectly::

        $this->Pages
            ->find('all', ['bundle' => 'page'])
            ->where(['article-body LIKE' => '%massa quis enim%'])
            ->limit(1)
            ->first();

    As ``article-body`` attribute exists only on ``article`` bundle you will get an
    SQL error as described before.


EAV Cache
---------

In some cases when fetching to many entities per query EAV may become slow, as for
every entity being fetched EAV plugin needs to retrieve all virtual columns related
to that entity, that is, for every entity an additional ``SELECT`` query is
performed. In order to improve this, EAV allows to cache virtual values of every
entity as a serialized structure under a real column of your entities. To do so, you
must indicate the name of the column where EAV values will be cached using the
``cache`` option, for example:

Cache all virtual values under the ``eav_cache`` column:

.. code:: php

    $this->addBehavior('Eav.Eav', ['cache' => 'eav_cache']);

Cache custom sets of virtual values under different columns:

.. code:: php

    $this->addBehavior('Eav.Eav', [
        'cache' => [
            'contact_info' => ['user-name', 'user-address'],
            'eav_all' => '*',
        ],
    ]);


Accesing cached values
^^^^^^^^^^^^^^^^^^^^^^

After cache has been enabled, you can access cached EAV values as follow:

.. code:: php

    // controller
    use App\AppController;

    class UsersController extends AppController
    {
        public function index()
        {
            // load the model and fetch ALL USERS AT ONCE.
            $this->loadModel('Users');
            $users = $this->Users->find('all', ['eav' => true])
            $this->set('users', $users);
        }
    }

    // view
    foreach ($users as $user) {
        // physical column `name`
        $name = $user->get('name');

        // virtual columns read from cache, read as follow:
        // $user->get(<cache_column_name>)->get(<virtual_column_name>);
        $age = $user->get('eav_cache')->get('user-age');

        echo sprintf('%s is %s years old', $name, $age);
    }

Limitations
^^^^^^^^^^^

Caches are automatically updated after every entity update. However, cache may
become out of sync under certain circumstances. In some cases, you will be able to
see cached values for virtual columns that was previously removed/modified if the
entity has not been updated/synced yet.

Updating EAV-cache of every entity after virtual columns are changed is a really
expensive task, that is why EAV plugin **will not** perform this task automatically.

To summarize, you must be aware of the following cases:

- After dropping a virtual column.
- After adding new virtual columns.
- After virtual column's definition is changed (type of value, etc).

.. note::

    You can use the ``updateEavCache()`` method of your table to update EAV cache
    for a single entity:

    .. code:: php

        $this->loadModel('Users');
        $user = $this->Users->get($id),
        $this->Users->updateEavCache($entity);
