<?php

namespace App\Observers;

use App\Components\Image\ImageHelper;
use App\Components\Image\ImageSize;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Exception\NotReadableException;

class ImageObserver
{
    /**
     * после получения модели добавить поля
     *
     * @param Model $model
     */
    public function retrieved(Model $model)
    {
        $this->addField($model);
    }

    /**
     * после сохранения модели добавить поля
     *
     * @param Model $model
     */
    public function saved(Model $model)
    {
        $this->addField($model);
    }

    /**
     * @param Model $model
     */
    public function saving(Model $model)
    {
        unset($model->image_url_max);
        unset($model->image_url_medium);
        unset($model->image_url_mini);
        unset($model->image_url);

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

    /**
     * добавить поля картинок
     *
     * @param Model $model
     */
    protected function addField(Model $model)
    {
        $model->image_url_max    = NULL;
        $model->image_url_medium = NULL;
        $model->image_url_mini   = NULL;
        $model->image_url        = NULL;

        if (!$model->image) {
            return;
        }

        $image = ImageHelper::makeOriginal($model->image, $this->getStorage($model));

        $model->image_url        = $image->getUrl();
        $model->image_url_medium = $image->setSize(ImageSize::createMediumSize())->getUrl();
        $model->image_url_mini   = $image->setSize(ImageSize::createMiniSize())->getUrl();
        $model->image_url_max    = $image->setSize(ImageSize::createMaxSize())->getUrl();
    }
}
