<?php

namespace LinuxFileSystemHelper\Tests\unit;

use LinuxFileSystemHelper\Exceptions\LinuxFileSystemHelperException;
use LinuxFileSystemHelper\FolderHelper;
use PHPUnit\Framework\TestCase;

class FolderHelperTest extends TestCase
{
    public static function getTestFolder(): string {
        return dirname(__DIR__) . "/data/test-folder/";
    }

    public static function getPhotosTestFolder(): string {
        return dirname(__DIR__) . "/data/photos/";
    }

    public static function tearDownAfterClass(): void
    {
        if (file_exists(self::getTestFolder())) {
            rmdir(self::getTestFolder());
        }
    }

    public function testCreateFolderFail()
    {
        $this->expectException(LinuxFileSystemHelperException::class);
        FolderHelper::createFolder('/invalid-path/');

        $this->expectException(LinuxFileSystemHelperException::class);
        FolderHelper::createFolder('invalid-path');
    }

    public function testCreateFolderSuccess()
    {
        $folder_to_create = self::getTestFolder();
        FolderHelper::createFolder($folder_to_create);
        $this->assertTrue(is_dir($folder_to_create));
    }

    public function testListFilesRecursiveFromFolderCaseIgnoreFilter()
    {
        // Two file are expected with no filter
        $file_list = FolderHelper::listFilesRecursiveFromFolder(self::getPhotosTestFolder(),'.jpg');
        $this->assertCount(2, $file_list, 'Two file are expected with no filter');

        // One file is expected with filter
        $file_list = FolderHelper::listFilesRecursiveFromFolder(self::getPhotosTestFolder(),'.jpg', ['.trash']);
        $this->assertCount(1, $file_list, 'One file is expected with filter');
    }
    public function testListFilesRecursiveFromFolderCaseB()
    {
        $file_list = FolderHelper::listFilesRecursiveFromFolder(self::getPhotosTestFolder(),'.jpg', ['.trash']);

        $file_info_array = explode(';', $file_list[0]);

        $this->assertTrue(is_numeric($file_info_array[0]));
        $this->assertEquals('2021-11-07', $file_info_array[1]);
        $this->assertEquals(self::getPhotosTestFolder() . 'SampleJPGImage_100kbmb.jpg', $file_info_array[2]);
    }

    public function testListFilesRecursiveFromFolderCaseC()
    {
        $file_list = FolderHelper::listFilesRecursiveFromFolder(self::getPhotosTestFolder(),'.pdf');
        $this->assertEmpty($file_list);
    }
}
