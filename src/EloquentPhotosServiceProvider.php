<?php namespace Msieprawski\EloquentPhotos;

use Illuminate\Support\ServiceProvider;
use Msieprawski\EloquentPhotos\Models\Photo;
use Msieprawski\EloquentPhotos\Observers\PhotoObserver;

class EloquentPhotosServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //dd(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'migrations');
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'migrations' => database_path('migrations'),
        ], 'migrations');

        Photo::observe(PhotoObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
