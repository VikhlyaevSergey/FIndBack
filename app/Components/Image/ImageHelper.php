<?php
/**
 * Created by PhpStorm.
 * User: frienze
 * Date: 28.11.18
 * Time: 12:37
 */

namespace App\Components\Image;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as FacadeImage;
use Intervention\Image\Image;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ImageHelper
{
    /**
     * @var Image
     */
    public $image;

    /**
     * @var Storage
     */
    public $storage;

    /**
     * @var string
     */
    protected $savedOriginalName;

    /**
     * @var string
     */
    protected $savedOriginalPath;

    /**
     * @var ImageSize
     */
    protected $size;

    /**
     * @var string
     */
    protected $folder;

    /**
     * @param mixed  $image
     * @param string $storage
     *
     * @return static
     */
    public static function make($image, $storage = 'public')
    {
        $storage         = isTestingEnv() ? 'test' : $storage;
        $static          = new static;
        $static->image   = FacadeImage::make($image);
        $static->storage = Storage::disk($storage);

        return $static;
    }

    /**
     * @param           $storage
     * @param           $folder
     * @param ImageSize $size
     *
     * @return static
     */
    public static function makeFromSize($folder, ImageSize $size, $storage = 'public')
    {
        $storage         = isTestingEnv() ? 'test' : $storage;
        $static          = new static;
        $static->storage = Storage::disk($storage);
        $static->folder  = $folder;

        $suffix = $size->getSuffixName();

        foreach ($static->storage->files($folder) as $file) {
            if (strpos($file, $suffix) !== false) {
                $static->image = FacadeImage::make($static->storage->path($file));
                break;
            }
        }

        if (!$static->image) {
            throw new FileNotFoundException();
        }

        return $static;
    }

    /**
     * @param           $storage
     * @param           $folder
     *
     * @return static
     */
    public static function makeOriginal($folder, $storage = 'public')
    {
        $storage         = isTestingEnv() ? 'test' : $storage;
        $static          = new static;
        $static->storage = Storage::disk($storage);
        $static->folder  = $folder;

        foreach ($static->storage->files($folder) as $file) {
            if (strpos($file, ImageSize::ORIGINAL_NAME) !== false) {
                $static->image = FacadeImage::make($static->storage->path($file));
                break;
            }
        }

        if (!$static->image) {
            throw new FileNotFoundException();
        }

        return $static;
    }

    /**
     * @param ImageSize $size
     *
     * @return $this
     */
    public function setSize(ImageSize $size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * сохранить изображения
     *
     * @return array
     */
    public function save()
    {
        $suffix       = '_' . ImageSize::ORIGINAL_NAME;
        $name         = $this->folder ?? md5(microtime(true) . Str::random());
        $mime         = $this->getMime();
        $this->folder = $name;

        $this->createDirIfNotExists($name);

        $this->savedOriginalName = $fullName = "{$name}{$suffix}.{$mime}";
        $this->savedOriginalPath = "{$this->storage->path($this->folder)}/{$this->savedOriginalName}";

        $this->saveImageIfNotExists($this->image, $this->savedOriginalPath);

        if ($this->size) {
            $imageForResize = FacadeImage::make($this->savedOriginalPath);

            $height = $this->size->getHeight();
            $width  = $this->size->getWidth();

            $callback = $height && $width ? NULL : function ($constraint) {
                $constraint->aspectRatio();
            };

            $imageForResize = $imageForResize->resize($width, $height, $callback);

            $fullName = "{$name}{$this->size->getSuffixName()}.{$mime}";
            $path     = "{$this->storage->path($this->folder)}/{$fullName}";

            $this->saveImageIfNotExists($imageForResize, $path);
        }

        return [$name, $fullName];
    }

    /**
     * созхранить картинку во всех стандартных размерах
     *
     * @return array
     */
    public function saveAllSizes()
    {
        $this->setSize(ImageSize::createMiniSize());
        $this->save();
        $this->setSize(ImageSize::createMediumSize());
        $this->save();
        $this->setSize(ImageSize::createMaxSize());

        return $this->save();
    }

    /**
     * удалить картинку
     */
    public function delete()
    {
        $path = "{$this->folder}/{$this->image->basename}";

        if ($this->storage->exists($path)) {
            $this->storage->delete($path);
        }
    }

    /**
     * удалить картинки всех размеров
     */
    public function deleteAllSizes()
    {
        if (collect($this->storage->directories())->search($this->folder) !== false) {
            $this->storage->deleteDirectory($this->folder);
        }
    }

    public function getUrl()
    {
        $suffix = '_' . ImageSize::ORIGINAL_NAME;
        $mime   = $this->getMime();

        if ($this->size) {
            $suffix = $this->size->getSuffixName();
        }

        return $this->storage->url("{$this->folder}/{$this->folder}{$suffix}.{$mime}");
    }

    public function getAbsolutePath()
    {
        $suffix = '_' . ImageSize::ORIGINAL_NAME;
        $mime   = $this->getMime();

        if ($this->size) {
            $suffix = $this->size->getSuffixName();
        }

        return $this->storage->path("{$this->folder}/{$this->folder}{$suffix}.{$mime}");
    }

    public function getPath() {
        $suffix = '_' . ImageSize::ORIGINAL_NAME;
        $mime   = $this->getMime();

        if ($this->size) {
            $suffix = $this->size->getSuffixName();
        }

        return "{$this->folder}/{$this->folder}{$suffix}.{$mime}";
    }

    /**
     * @param $name
     */
    protected function createDirIfNotExists($name)
    {
        $path = $this->storage->path($name);

        if (!is_dir($path)) {
            $this->storage->createDir($name);
        }
    }

    /**
     * @param Image  $image
     * @param string $path
     */
    protected function saveImageIfNotExists(Image $image, string $path)
    {
        if (!$this->storage->exists($path)) {
            $image->save($path);
        }
    }

    /**
     * получить тип изображения
     *
     * @return string
     */
    public function getMime()
    {
        $mime = $this->image->mime();

        return $this->image->extension ?: substr($mime, strpos($mime, '/') + 1);
    }
}