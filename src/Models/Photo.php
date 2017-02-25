<?php namespace Msieprawski\EloquentPhotos\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo
 * @package Msieprawski\EloquentPhotos\Models
 */
class Photo extends Model
{

    // Starting from project's root directory
    const ELOQUENT_PHOTOS_PATH = 'storage';

    /**
     * @var string
     */
    protected $table = 'eloquent_photos';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return string
     */
    public function getPath()
    {
        return base_path($this->photo_path);
    }

}