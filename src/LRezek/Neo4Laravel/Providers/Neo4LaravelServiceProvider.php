<?php namespace LRezek\Neo4Laravel\Providers;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use HireVoice\Neo4j\Configuration;
use HireVoice\Neo4j\EntityManager;
use Illuminate\Support\ServiceProvider;

class Neo4LaravelServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    protected $cacheMap = array(
        'array' => '\Doctrine\Common\Cache\ArrayCache',
        'apc' => '\Doctrine\Common\Cache\ApcCache',
        'filesystem' => '\Doctrine\Common\Cache\FilesystemCache',
        'phpFile' => '\Doctrine\Common\Cache\PhpFileCache',
        'winCache' => '\Doctrine\Common\Cache\WinCacheCache',
        'xcache' => '\Doctrine\Common\Cache\XcacheCache',
        'zendData' => '\Doctrine\Common\Cache\ZendDataCache'
    );

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $laravel = app();
        if (substr($laravel::VERSION, 0, 1) != '5')
        {
            $this->package('lrezek/neo4laravel');
        }
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

        $app = $this->app;

        //Make and share the singleton with the application
        $app['lrezek.neo4laravel.entitymanager'] = $app->share(function ($app) {

            //Register annotations with doctrine
            \Doctrine\Common\Annotations\AnnotationRegistry::registerFile(app_path() . '/../vendor/hirevoice/neo4jphp-ogm/lib/HireVoice/Neo4j/Annotation/Auto.php');
            \Doctrine\Common\Annotations\AnnotationRegistry::registerFile(app_path() . '/../vendor/hirevoice/neo4jphp-ogm/lib/HireVoice/Neo4j/Annotation/Entity.php');
            \Doctrine\Common\Annotations\AnnotationRegistry::registerFile(app_path() . '/../vendor/hirevoice/neo4jphp-ogm/lib/HireVoice/Neo4j/Annotation/Index.php');
            \Doctrine\Common\Annotations\AnnotationRegistry::registerFile(app_path() . '/../vendor/hirevoice/neo4jphp-ogm/lib/HireVoice/Neo4j/Annotation/ManyToMany.php');
            \Doctrine\Common\Annotations\AnnotationRegistry::registerFile(app_path() . '/../vendor/hirevoice/neo4jphp-ogm/lib/HireVoice/Neo4j/Annotation/ManyToOne.php');
            \Doctrine\Common\Annotations\AnnotationRegistry::registerFile(app_path() . '/../vendor/hirevoice/neo4jphp-ogm/lib/HireVoice/Neo4j/Annotation/Property.php');

            //Get config parameters
            $default = $app['config']->get('database.default');
            $settings = $app['config']->get('database.connections');
            $config = (!empty($default) && $default == 'neo4j') ? $settings[$default] : $settings;

            //If you have a meta cache but not a annotation reader, make a annotation reader out of the meta cache
            if (empty($config['annotation_reader']) && !empty($config['meta_data_cache']))
            {
                //Get the associated doctrine class
                $metaCache = new $this->cacheMap[$config['meta_data_cache']];

                //Set the namespace to the cache_prefix, or make it neo4j if it's not there
                $metaCache->setNamespace((empty($config['cache_prefix'])) ? 'neo4j' : $config['cache_prefix']);

                //Create the reader
                $config['annotation_reader'] = new CachedReader(new AnnotationReader, $metaCache, false);
            }

            //Return the new instance (the share method insures it's a singleton)
            return new EntityManager(new Configuration($config));

        });

	}

    /**
     * List the services that this provider provides.
     *
     * @return array Provided services.
     */
    public function provides()
    {
        return ['lrezek.neo4laravel.entitymanager'];
    }
}
