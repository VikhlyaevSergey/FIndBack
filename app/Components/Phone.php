<?php
/**
 * Created by PhpStorm.
 * User: Алексей Потеев
 * Mail: poteev-3101-work@yandex.ru
 * Date: 13.11.17
 * Time: 13:54
 */

namespace App\Components;

use InvalidArgumentException;

/**
 * работа с номером телефона
 */
class Phone
{
    /**
     * удалить все символы кроме цифр
     * и обрезать до 10 символов с конца
     *
     * @param string $phone - номер телефона
     *
     * @return string
     */
    public static function create($phone)
    {
        return substr(preg_replace('/[^0-9]/', '', $phone), -10);
    }

    /**
     * удалить все символы кроме цифр
     * и обрезать до 10 символов с конца
     *
     * @param string $phone - номер телефона
     *
     * @return string
     */
    public static function createForSMS($phone)
    {
        $phone = '7' . substr(preg_replace('/[^0-9]/', '', $phone), -10);

        if (!self::validForSMS($phone)) {
            throw new InvalidArgumentException('Неверный формат номера телефона');
        }

        return $phone;
    }

    /**
     * проверить валидность телефона для SMS
     *
     * @param string $phone - номер телефона
     *
     * @return bool
     */
    public static function validForSMS($phone)
    {
        if (strlen($phone) != 11 || substr($phone, 0, 1) != '7') {
            return false;
        }

        return true;
    }

    /**
     * проверить валидность телефона
     *
     * @param string $phone - номер телефона
     *
     * @return bool
     */
    public static function valid($phone)
    {
        if (strlen($phone) !=  10 || substr($phone, 0, 1) === '7') {
            return false;
        }

        return true;
    }
}
