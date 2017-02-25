<?php namespace Msieprawski\EloquentPhotos\Observers;

use File;
use Msieprawski\EloquentPhotos\Models\Photo;

class PhotoObserver
{

    /**
     * Sets proper file permissions when photo model has been created.
     *
     * @param Photo $photo
     */
    public function created(Photo $photo)
    {
        $photoPath = $photo->getPath();
        if (!$this->photoExists($photoPath)) {
            return;
        }

        @File::chmod($photoPath, 0644);
    }

    /**
     * Removes photo file from server when photo model has been deleted.
     *
     * @param Photo $photo
     */
    public function deleted(Photo $photo)
    {
        $photoPath = $photo->getPath();
        if (!$this->photoExists($photoPath)) {
            return;
        }

        if (!File::isWritable($photoPath)) {
            @File::chmod($photoPath, 0777);
        }
        if (!File::isWritable($photoPath)) {
            return;
        }

        File::delete($photoPath);
    }

    /**
     * @param $photoPath
     * @return bool
     */
    private function photoExists($photoPath)
    {
        return $photoPath && File::exists($photoPath);
    }

}