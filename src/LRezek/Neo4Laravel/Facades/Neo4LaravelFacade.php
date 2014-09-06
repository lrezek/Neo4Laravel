<?php namespace LRezek\Neo4Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Neo4LaravelFacade extends Facade
{

    /**
     * Get the registered name of the component
     *
     * @return string
     * @codeCoverageIgnore
     */
    protected static function getFacadeAccessor()
    {
        return 'lrezek.neo4laravel.entitymanager';
    }
}
