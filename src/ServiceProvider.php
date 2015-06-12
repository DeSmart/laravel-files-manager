<?php namespace DeSmart\Files;

use DeSmart\Files\Manager;
use DeSmart\Files\FileRepository;
use DeSmart\Files\Mapper\GenericMapper;
use DeSmart\Files\Entity\FileEntityFactory;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    protected $configPath = __DIR__.'/../config/desmart_files.php';

    public function boot()
    {
        $this->publishes([
            $this->configPath => config_path('desmart_files.php'),
            __DIR__.'/../database/migrations/' => database_path('/migrations'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom($this->configPath, 'desmart_files');

        $this->registerManager();
        $this->app->bind(GenericMapper::class, function () {
            return new GenericMapper($this->getStorage());
        });
    }

    protected function registerManager()
    {
        $config = $this->app['config']->get('desmart_files');

        $this->app->bind(Manager::class, function () use ($config) {
            $manager = new Manager(
                $this->app->make(FileRepository::class),
                $this->app->make(FileEntityFactory::class),
                $this->getStorage()
            );

            $mappers = array_map([$this->app, 'make'], $config['mappers']);
            $manager->setMappers(...$mappers);

            return $manager;
        });
    }

    protected function getStorage() 
    {
        $config = $this->app['config']->get('desmart_files');

        return $this->app['filesystem']->disk($config['storage_disk']);
    }
}
