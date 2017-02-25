<?php namespace Msieprawski\EloquentPhotos;

use \Exception;
use File;
use Msieprawski\EloquentPhotos\Models\Photo;
use Illuminate\Http\UploadedFile;

trait HasPhotos
{

    /**
     * @var string
     */
    protected $targetPhotosDirectory = 'users';

    /**
     * @var mixed
     */
    protected $loadedPhotosCollection;

    /**
     * @param string|UploadedFile $photo
     * @throws Exception
     */
    public function addPhoto($photo)
    {
        if (!$this->targetPhotosDirectory) {
            throw new Exception('Please set target photos directory for model ' . self::class);
        }
        if (!is_string($photo) && !$photo instanceof UploadedFile) {
            throw new Exception('Photo must be a string or UploadedFile object.');
        }

        $targetDir = $this->prepareTargetPhotosDirectory();
        $targetPath = base_path($targetDir);

        if ($photo instanceof UploadedFile) {
            $randomizedName = str_random(32);
            $photo->move($targetPath, $randomizedName);

            $originalName = $photo->getClientOriginalName();
            $name = $this->generateUniqueFileName($targetPath . DIRECTORY_SEPARATOR . $randomizedName, $photo->getClientOriginalExtension());
            File::move($targetPath . DIRECTORY_SEPARATOR . $randomizedName, $targetPath . DIRECTORY_SEPARATOR . $name);
        } else {
            $originalName = File::name($photo) . '.' . File::extension($photo);
            $name = $this->generateUniqueFileName($photo, File::extension($photo));
        }

        $newPath = $targetDir . DIRECTORY_SEPARATOR . $name;
        $size = round(File::size(base_path($newPath)) / 1024, 2);

        $photoModel = new Photo();
        $photoModel->eloquent_id = $this->id;
        $photoModel->eloquent_type = self::class;
        $photoModel->photo_original_name = $originalName;
        $photoModel->photo_path = $newPath;
        $photoModel->photo_size = $size;
        $photoModel->save();
    }

    /**
     * @param array $photos
     */
    public function addPhotos(array $photos)
    {
        foreach ($photos as $photo) {
            $this->addPhoto($photo);
        }
    }

    /**
     * @return mixed
     */
    public function photos()
    {
        if (is_object($this->loadedPhotosCollection)) {
            return $this->loadedPhotosCollection;
        }

        $this->loadedPhotosCollection = $this->hasMany(Photo::class, 'eloquent_id')
            ->where('eloquent_type', self::class);
        return $this->loadedPhotosCollection;
    }

    /**
     * @return bool
     */
    public function destroyPhotos()
    {
        $photos = $this->photos;
        if ($photos->isEmpty()) {
            return true;
        }

        foreach ($photos as $photo) {
            /** @var Photo $photo */
            $photo->delete();
        }

        return true;
    }

    /**
     * @return string
     */
    public function getTargetPhotosDirectory()
    {
        return Photo::ELOQUENT_PHOTOS_PATH . DIRECTORY_SEPARATOR . $this->targetPhotosDirectory . DIRECTORY_SEPARATOR . $this->id;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function prepareTargetPhotosDirectory()
    {
        $targetDir = $this->getTargetPhotosDirectory();
        if (!File::exists($targetDir)) {
            @File::makeDirectory($targetDir, 0777, true, true);
        }
        if (!File::exists($targetDir)) {
            throw new Exception('Could not create or find target photos directory: ' . $targetDir);
        }

        return $targetDir;
    }

    /**
     * @param string $path
     * @param string $extension
     * @return string
     */
    private function generateUniqueFileName($path, $extension)
    {
        $dirname = File::dirname($path);

        $i = 1;
        do {
            $newName = $i . '.' . $extension;

            // If file does not exist (with generated name) then we can consider this name as unique
            $unique = !File::exists($dirname . DIRECTORY_SEPARATOR . $newName);
            $i++;
        } while ($unique == false);

        return $newName;
    }

}