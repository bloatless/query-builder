<p align="center">
    <img src="https://bloatless.org/img/logo.svg" width="60px" height="80px">
</p>

<h1 align="center">Bloatless Query Builder</h1>

<p align="center">
    A query builder for PDO MySQL.
</p>

## Installation

You can install the library using composer:

```
php composer.phar require bloatless/query-builder
``` 

## Usage

- [Query Builder](#query-builder)
  * [Connections](#connections)
  * [Factory](#factory)
  * [SELECT](#select)
    + [A simple select](#a-simple-select)
    + [Table and column alias](#table-and-column-alias)
    + [Get specific columns](#get-specific-columns)
    + [First row only](#first-row-only)
    + [Single column as array](#single-column-as-array)
    + [Counting rows](#counting-rows)
    + [Joins](#joins)
    + [Group by](#group-by)
    + [Order by](#order-by)
    + [Having](#having)
    + [Limit and Offset](#limit-and-offset)
    + [Distinct](#distinct)
  * [UPDATE](#update)
  * [DELETE](#delete)
  * [WHERE](#where)
    + [Simple where](#simple-where)
    + [Or where](#or-where)
    + [Where in](#where-in)
    + [Where not in](#where-not-in)
    + [Or where in](#or-where-in)
    + [Or where not in](#or-where-not-in)
    + [Where between](#where-between)
    + [Or where between](#or-where-between)
    + [Where null](#where-null)
    + [Where not null](#where-not-null)
    + [Or where null](#or-where-null)
    + [Or where not null](#or-where-not-null)
  * [INSERT](#insert)
    + [Single row](#single-row)
    + [Multiple rows](#multiple-rows)
    + [Last insert id](#last-insert-id)
  * [RAW Queries](#raw-queries)
    + [Raw select queries](#raw-select-queries)
    + [Other raw queries](#other-raw-queries)
  * [Reset](#reset)
  * [Security](#security)

### Query Builder

This documentation explains the complete usage API of the Bloatless Query Builder.

#### Connections

You can define multiple database connections in your projects `config.php` file.

```php
$config = [
    'db' => [
        'connections' => [
            'db1' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'database' => 'db1',
                'username' => 'root',
                'password' => 'your-password',
                'charset' => 'utf8', // Optional
                'timezone' => 'Europe/Berlin', // Optional
            ],
            
            // add additional connections here...
        ],
    
        'default_connection' => 'db1',
    ]
];
```

#### Factory

The QueryBuilder factory needs to be initialized using a config array providing the connection credentials:

```php
$db = new \Bloatless\Endocore\Components\QueryBuilder\Factory($config['db']);
```

Once initialized the factory can be used to provide query-builder objects for various database operations:

```php
$selectQueryBuilder = $db->makeSelect();
$updateQueryBuilder = $db->makeUpdate();
$deleteQueryBuilder = $db->makeDelete();
$insertQueryBuilder = $db->makeInsert();
$rawQueryBuilder = $db->makeRaw();
```

With no arguments provided the default database connection is used. If you want to use a different connection you can
pass the connection name as an argument.

```php
$updateQueryBuilder = $db->makeUpdate('db2');
```

#### SELECT

##### A simple select

```php
$rows = $db->makeSelect()->from('customers')->get();
```

##### Table and column alias

Aliases can be used on table names as well as on column names.

```php
$rows = $db->makeSelect()
    ->cols(['customer_id AS id', 'firstname', 'lastname'])
    ->from('customers AS c')
    ->get();
```

##### Get specific columns

```php
$rows = $db->makeSelect()
    ->cols(['customer_id', 'firstname', 'lastname'])
    ->from('customers')
    ->get();
```

##### First row only

```php
$row = $db->makeSelect()
    ->from('customers')
    ->whereEquals('customer_id', 42)
    ->first();
```

##### Single column as array

```php
$names = $db->makeSelect()
    ->from('customers')
    ->pluck('firstname');
```

Will fetch an array containing all first names of the `customers` table.

You can specify a second column which will be used for the keys of the array:

```php
$names = $db->makeSelect()
    ->from('customers')
    ->pluck('firstname', 'customer_id');
```

Will fetch an array of all first names using the `customer_id` as array key.

##### Counting rows

```php
$rowCount = $db->makeSelect()
    ->from('customers')
    ->count();
```

##### Joins

You can join tables using the `join`, `leftJoin` or `rightJoin` methods. You can of course join multiple tables.

```php
$rows = $db->makeSelect()
    ->from('customers')
    ->join('orders', 'customers.customer_id', '=', 'orders.customer_id')
    ->get();
```

##### Group by

```php
$rows = $db->makeSelect()
    ->from('orders')
    ->groupBy('customer_id')
    ->get();
```

##### Order by

```php
$rows = $db->makeSelect()
    ->from('customers')
    ->orderBy('firstname', 'desc')
    ->get();
```

##### Having

```php
$rows = $db->makeSelect()
    ->from('orders')
    ->having('amount', '>', 10)
    ->orHaving('cart_items', '>' 5)
    ->get();
```

##### Limit and Offset

```php
$rows = $db->makeSelect()
    ->from('orders')
    ->limit(10)
    ->offset(20)
    ->get();
```

##### Distinct

```php
$rows = $db->makeSelect()
    ->distinct()
    ->from('orders')
    ->get();
```

#### UPDATE

```php
$rows = $db->makeUpdate()
    ->table('customers')
    ->whereEquals('customer_id', 42)
    ->update([
        'firstname' => 'Homer'
    ]);
```

#### DELETE

```php
$rows = $db->makeDelete()
    ->from('customers')
    ->whereEquals('customer_id', 42)
    ->delete();
```

#### WHERE

You can use various where clauses on all `select`, `update` and `delete` queries:

##### Simple where

```php
$rows = $db->makeSelect()
    ->from('customers')
    ->where('customer_id', '=', 42)
    ->where('customer_id', '>', 10)
    ->whereEquals('customer_id', 42)
    ->get();
```

##### Or where

```php
->orWhere('customer_id', '>', 5)
```

##### Where in

```php
->whereIn('customer_id', [1,2,3])
```

##### Where not in

```php
->whereNotIn('customer_id', [1,2,3])
```

##### Or where in

```php
->orWhereIn('customer_id', [1,2,3])
```

##### Or where not in

```php
->orWhereNotIn('customer_id', [1,2,3])
```

##### Where between

```php
->whereBetween('customer_id', 5, 10)
```

##### Or where between

```php
->orWhereBetween('customer_id', 5, 10)
```

##### Where null

```php
->whereNull('customer_id')
```

##### Where not null

```php
->whereNotNull('customer_id')
```

##### Or where null

```php
->orWhereNull('customer_id')
```

##### Or where not null

```php
->orWhereNotNull('customer_id')
```

#### INSERT

##### Single row

```php
$customerId = $db->makeInsert()
    ->into('customers')
    ->row([
        'firstname' => 'Homer',
        'lastname' => 'Simpson',
    ]);
```

When inserting a single row, the auto-increment value of the newly added row will be returned.

##### Multiple rows

You can insert multiple rows at once using the `rows` method:

```php
$db->makeInsert()
    ->into('customers')
    ->rows([
        [
            'firstname' => 'Homer',
            'lastname' => 'Simpson',
        ],
        [
            'firstname' => 'Marge',
            'lastname' => 'Simpson',
        ],
    ]);
```

##### Last insert id

In case you need to fetch the id if the last insert manually you can use the `getLastInsertId` method:

```php
$id = $insertQueryBuilder->getLastInsertId();
```

#### RAW Queries

There will always be some kind of queries you can not build using the methods of a query builder. In those cases you
can utilize the `RawQueryBuilder` which allows you to execute raw queries to the database.

##### Raw select queries

```php
$rows = $db->makeRaw()
    ->prepare("SELECT * FROM `orders` WHERE `customer_id` = :id", [
        'id' => 42,
    ])
    ->get();
```

##### Other raw queries

```php
$db->makeRaw()
    ->prepare("UPDATE `customers` SET `firstname` = :name WHERE `customer_id` = :id", [
        'name' => 'Homer',
        'id' => 42,
    ])
    ->run();
```

#### Reset

All query builders have a `reset` method. This method can be used to clear all previously set values without the need
of creating a new QueryBuilder object.

```php
$builder = $db->makeSelect()
    ->from('customers')
    ->whereEquals('customer_id', 42);

$builder->reset();

...
```

#### Security

All query builders internally user PDO parameter binding to reduce the risk of injection attacks as much as possible.
Additionally table names as well as field names are quoted - so you don't have to worry about that. This works on simple
table names or when using aliases. Nevertheless you should always try to avoid using user input within sql statements! 

## License

MIT