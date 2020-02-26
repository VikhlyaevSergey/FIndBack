<?php
/**
 * Created by PhpStorm.
 * User: frienze
 * Date: 28.11.18
 * Time: 12:21
 */

namespace App\Components\Image;

use InvalidArgumentException;

class ImageSize
{
    const MAX_NAME      = 'MAX';
    const MEDIUM_NAME   = 'MEDIUM';
    const MINI_NAME     = 'MINI';
    const ORIGINAL_NAME = 'ORIGINAL';

    const MAX    = 600;
    const MEDIUM = 450;
    const MINI   = 150;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    private function __construct()
    {
    }

    /**
     * @return static
     */
    public static function createMaxSize()
    {
        $static        = new static;
        $static->width = self::MAX;

        return $static;
    }

    /**
     * @return static
     */
    public static function createMediumSize()
    {
        $static        = new static;
        $static->width = self::MEDIUM;

        return $static;
    }

    /**
     * @return static
     */
    public static function createMiniSize()
    {
        $static        = new static;
        $static->width = self::MINI;

        return $static;
    }

    /**
     * @param int|NULL $width
     * @param int|NULL $height
     *
     * @return static
     */
    public static function createCustomSize(int $width = NULL, int $height = NULL)
    {
        if (!$width && !$height) {
            throw new InvalidArgumentException('Не хватает аргументов');
        }

        $static         = new static;
        $static->width  = $width;
        $static->height = $height;

        return $static;
    }

    /**
     * получить ширину
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * получить высоту
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getSuffixName()
    {
        if ($this->width && $this->height) {
            return "_{$this->width}x{$this->height}";
        }

        switch ($this->width) {
            case self::MAX:
                return '_' . self::MAX_NAME;

            case self::MEDIUM:
                return '_' . self::MEDIUM_NAME;

            case self::MINI:
                return '_' . self::MINI_NAME;

            default:
                return "_{$this->width}x{$this->height}";
        }
    }
}