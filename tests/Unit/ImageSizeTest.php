<?php

namespace Tests\Unit;

use App\Components\Image\ImageSize;
use InvalidArgumentException;
use Tests\FileStorageTest;
use Tests\TestCase;

class ImageSizeTest extends TestCase
{
    use FileStorageTest;

    /**
     * создать максимальный размер
     * ширина соответствует макимальной, высоты нет
     */
    public function testCreateMaxSize()
    {
        $size = ImageSize::createMaxSize();

        $this->assertEquals(ImageSize::MAX, $size->getWidth());
        $this->assertNull($size->getHeight());
    }

    /**
     * создать средний размер
     * ширина соответствует средней, высоты нет
     */
    public function testCreateMediumSize()
    {
        $size = ImageSize::createMediumSize();

        $this->assertEquals(ImageSize::MEDIUM, $size->getWidth());
        $this->assertNull($size->getHeight());
    }

    /**
     * создать минимальный размер
     * ширина соответствует минимальной, высоты нет
     */
    public function testCreateMiniSize()
    {
        $size = ImageSize::createMiniSize();

        $this->assertEquals(ImageSize::MINI, $size->getWidth());
        $this->assertNull($size->getHeight());
    }

    /**
     * создать кастомный размер
     * с шириной и высотой
     * ширина и высота соответствуют заданным
     */
    public function testCreateCustomSizeWithWightAndHeight()
    {
        $wight  = 300;
        $height = 200;
        $size   = ImageSize::createCustomSize($wight, $height);

        $this->assertEquals($wight, $size->getWidth());
        $this->assertEquals($height, $size->getHeight());
    }

    /**
     * создать кастомный размер
     * только с шириной
     * ширина соответствует заданной, высоты нет
     */
    public function testCreateCustomSizeWithOnlyWight()
    {
        $wight = 300;
        $size  = ImageSize::createCustomSize($wight);

        $this->assertEquals($wight, $size->getWidth());
        $this->assertNull($size->getHeight());
    }

    /**
     * создать кастомный размер
     * только с высотой
     * высота соответствует заданной, ширины нет
     */
    public function testCreateCustomSizeWithOnlyHeight()
    {
        $height = 300;
        $size   = ImageSize::createCustomSize(NULL, $height);

        $this->assertEquals($height, $size->getHeight());
        $this->assertNull($size->getWidth());
    }

    /**
     * создать кастомный размер
     * без шириный и высоты
     * исключение
     */
    public function testCreateCustomSizeWithoutWightAndHeight()
    {
        $this->expectException(InvalidArgumentException::class);

        ImageSize::createCustomSize();
    }
}
