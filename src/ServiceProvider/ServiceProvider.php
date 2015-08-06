<?php namespace DeSmart\Files\ServiceProvider;

use DeSmart\Files\Manager;
use DeSmart\Files\Model\File;
use DeSmart\Files\FileRepository;
use DeSmart\Files\Mapper\GenericMapper;
use DeSmart\Files\Entity\FileEntityFactory;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    protected $configPath = __DIR__.'/../../config/desmart_files.php';

    public function boot()
    {
        $this->publishes([
            $this->configPath => config_path('desmart_files.php'),
            __DIR__.'/../../database/migrations/' => database_path('/migrations'),
        ]);
    }

    public function register()
    {
        $this->configure();

        $this->registerManager();
        $this->registerEntityFactory();
        $this->registerFileRepository();
        $this->registerStorage();
        $this->app->bind(GenericMapper::class, function () {
            return new GenericMapper($this->app->make('desmart_files.storage'));
        });
    }

    protected function configure()
    {
        $this->mergeConfigFrom($this->configPath, 'desmart_files');
    }

    protected function registerManager()
    {
        $config = $this->app['config']->get('desmart_files');

        $this->app->bind(Manager::class, function () use ($config) {
            $manager = new Manager(
                $this->app->make(FileRepository::class),
                $this->app->make(FileEntityFactory::class),
                $this->app->make('desmart_files.storage')
            );

            $mappers = array_map([$this->app, 'make'], $config['mappers']);
            $manager->setMappers(...$mappers);

            return $manager;
        });
    }

    protected function registerEntityFactory()
    {
        $this->app->bind(FileEntityFactory::class, function () {
            $fileEntityClass = $this->app['config']->get('desmart_files.file_entity_class');

            return new FileEntityFactory($fileEntityClass);
        });
    }

    protected function registerFileRepository()
    {
        $this->app->bind(FileRepository::class, function () {
            return new FileRepository(
                $this->app->make(File::class),
                $this->app->make('db')
            );
        });
    }

    protected function registerStorage()
    {
        $config = $this->app['config']->get('desmart_files');

        $this->app->bind('desmart_files.storage', function () use ($config) {
            return $this->app['filesystem']->disk($config['storage_disk']);
        });
    }
}
