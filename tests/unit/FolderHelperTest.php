<?php

namespace LinuxFileSystemHelper\Tests\unit;

use LinuxFileSystemHelper\Exceptions\LinuxFileSystemHelperException;
use LinuxFileSystemHelper\FolderHelper;
use PHPUnit\Framework\TestCase;

class FolderHelperTest extends TestCase
{
    public function testCreateFolderFail()
    {
        $this->expectException(LinuxFileSystemHelperException::class);
        FolderHelper::createFolder('/invalid-path/');

        $this->expectException(LinuxFileSystemHelperException::class);
        FolderHelper::createFolder('invalid-path');
    }
    public function testCreateFolderSuccess()
    {
        $folder_to_create = dirname(__DIR__) . "/data/test-folder/";

        FolderHelper::createFolder($folder_to_create);

        $this->assertTrue(is_dir($folder_to_create));
    }
}