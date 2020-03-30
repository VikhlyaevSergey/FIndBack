<?php

namespace App\Observers;

use App\Components\Image\ImageHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Exception\NotReadableException;

class ImageObserver
{
    public function updating(Model $model)
    {
        if ($model->image && $model->getOriginal('image') !== $model->image) {

            try {
                $image = ImageHelper::make($model->image, $this->getStorage($model));
                [$file] = $image->saveAllSizes();

                $model->image = $file;
            } catch (NotReadableException $e) {
                $error = ValidationException::withMessages(['image' => 'Неверный тип картинки']);
                throw $error;
            }
        }
    }

    protected function getStorage(Model $model)
    {
        if ($model instanceof User) {
            return 'avatars';
        }

        return 'public';
    }
}
