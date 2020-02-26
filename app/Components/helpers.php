<?php

use App\Components\ResponseApi;
use Illuminate\Support\Str;

/**
 * Created by PhpStorm.
 * User: frienze
 * Date: 11.12.18
 * Time: 12:45
 */

/**
 * get auth user
 *
 * @return \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User
 */
function user()
{
    return Auth::user();
}

/**
 * get model class
 *
 * @param string $table
 *
 * @return bool|string
 */
function getModelByTableName(string $table)
{
    $namespace = 'App\Models\\';

    if (class_exists($namespace . $table)) {
        return $namespace . $table;
    }

    $table = ucfirst($table);

    if (class_exists($namespace . $table)) {
        return $namespace . $table;
    }

    $table = Str::singular($table);

    if (class_exists($namespace . $table)) {
        return $namespace . $table;
    }

    return false;
}

/**
 * check APP_ENV is set to the "testing"
 *
 * @return bool
 */
function isTestingEnv()
{
    return env('APP_ENV') === 'testing';
}

/**
 * check APP_ENV is set to the "production"
 *
 * @return bool
 */
function isProductionEnv()
{
    return env('APP_ENV') === 'production';
}

/**
 * check APP_ENV is set to the "development"
 *
 * @return bool
 */
function isDevelopmentEnv()
{
    return env('APP_ENV') === 'development';
}

/**
 * @param bool     $success
 * @param int|NULL $error
 * @param array    $message
 * @param array    $response
 *
 * @return ResponseApi
 */
function responseApi(array $response = [], bool $success = true, int $error = NULL, $message = '')
{
    return new ResponseApi($response, $success, $error, $message);
}