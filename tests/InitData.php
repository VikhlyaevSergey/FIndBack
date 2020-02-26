<?php
/**
 * Created by PhpStorm.
 * User: frienze
 * Date: 31.08.18
 * Time: 10:46
 */

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

trait InitData
{
    /**
     * run the init data file
     */
    protected function setInitData()
    {
        Artisan::call('db:seed', ['--class' => 'InitSeeder']);
    }

    /**
     * get admin
     *
     * @return User
     */
    protected function getAdmin()
    {
        return User::firstOrFail();
    }
}