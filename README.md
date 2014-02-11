Allows you to use [Neo4j](http://www.neo4j.org) in [Laravel 4.1](http://www.laravel.com).

Installation
============

Add `lrezek/neo4jogm` as a requirement to composer.json:

```
{
    "require": {
       "lrezek/neo4jogm": "dev-master"
    }
}
```

You may need to add the package dependencies as well:

```
{
    "require": {
       "everyman/neo4jphp":"dev-master",
       "hirevoice/neo4jphp-ogm":"dev-master"
    }
}
```

Update your packages with `composer update` or install with `composer install`.

Once Composer has updated your packages, you'll need to tell Lavarel about the service provider. Add the following to the `providers` in app/config/database.php: 

```
'Lrezek\Neo4jogm\Neo4jogmServiceProvider',
```

Database Configuration
=============

The Neo4J database configuration is autoloaded from app/config/database.php. To add a Neo4J connection, simply add the following to the `connections` parameter:

```
'neo4j' => array(
            'transport' => 'curl',
            'host' => 'localhost',
            'port' => '7474',
            'debug' => true,
            'proxy_dir' => '/tmp',
            'cache_prefix' => 'neo4j',
            'meta_data_cache' => 'array',
            'annotation_reader' => null,
            'username' => null,
            'password' => null,
            'pathfinder_algorithm' => null,
            'pathfinder_maxdepth' => null
        )
```

And set the default connection as follows:

```
'default' => 'neo4j',
```

Usage
=====

Once this set-up is complete, you can use entities and do queries as shown in [lphuberdeau/Neo4j-PHP-OGM](https://github.com/lphuberdeau/Neo4j-PHP-OGM). The only difference is in obtaining a singleton Entity manager. Instead of:

```
$em = $this->get('hirevoice.neo4j.entity_manager');
```

use:

```
$em = App::make('entitymanager');
```