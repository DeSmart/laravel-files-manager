<?php namespace DeSmart\Files\ServiceProvider;

class LumenServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Eh Lumen ..
    }

    protected function configure()
    {
        $this->app->configure('desmart_files');

        parent::configure();
    }
}
