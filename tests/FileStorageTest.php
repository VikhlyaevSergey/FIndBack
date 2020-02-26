<?php
/**
 * Created by PhpStorm.
 * User: frienze
 * Date: 31.08.18
 * Time: 10:46
 */

namespace Tests;

use Illuminate\Support\Facades\Storage;

trait FileStorageTest
{
    protected $testDirectory = 'test';

    /**
     * create a test directory on a public drive
     */
    protected function createPublicTestDirectory()
    {
        $storage = Storage::disk('local');

        if (collect($storage->directories())->search($this->testDirectory) === false) {
            $storage->createDir($this->testDirectory);
        }
    }

    /**
     * delete the test directory from a public disk
     */
    protected function deletePublicTestDirectory()
    {
        Storage::disk('local')->deleteDirectory($this->testDirectory);
    }
}