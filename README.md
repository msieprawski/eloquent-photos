# Eloquent Photos
## About
This package has been created for everyone which has photos related to any eloquent models in projects. 
 
## Compatibility
Currently this package has been tested and developed for Laravel 5.3 or greater.

## Installation
1. `composer require msieprawski/eloquent-photos`
2. Add package service provider in your `config/app.php` file:
    `Msieprawski\EloquentPhotos\EloquentPhotosServiceProvider::class,`
3. Add HasPhotos trait to your model:
    `use Msieprawski\EloquentPhotos\HasPhotos`
4. Add protected property to your model with directory name where photos should be stored:
    `protected $targetPhotosDirectory = 'users';`
    
## Usage
1. Add photos to your model
```php
<?php namespace App;
$user = User::find(1);
$user->addPhoto('/path/to/your/photo.jpg');
$user->addPhotos([
    '/path/to/your/photo1.jpg',
    '/path/to/your/photo2.jpg',
]);
```
2. Add uploaded photos to your model
```php
<?php namespace App;

$photos = request()->file('photos');
$user = User::find(1);
$user->addPhoto($photos);
```
It will automatically upload the photos and store it against user entity.
3. Get photos
```php
<?php namespace App;
$user = User::find(1);
$photos = $user->photos;
foreach ($photos as $photo) {
    /** @var Msieprawski\EloquentPhotos\Photo $photo */
    echo $photo->photo_path;
}
```
4. Delete photos
```php
<?php namespace App;
$user = User::find(1);
$user->destroyPhotos();
```

## License
Licensed under the MIT License