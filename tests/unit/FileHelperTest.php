<?php

namespace LinuxFileSystemHelper\Tests\unit;

use LinuxFileSystemHelper\FileHelper;
use LinuxFileSystemHelper\FolderHelper;
use PHPUnit\Framework\TestCase;

class FileHelperTest extends TestCase
{
    const TEST_FILE_1 = 'test1.file';
    const TEST_FILE_2 = 'test2.file';
    const TEST_FILE_3 = 'test3.file';

    public static function tearDownAfterClass(): void
    {
        unlink(FolderHelperTest::getTestFolder() . self::TEST_FILE_1);
        unlink(FolderHelperTest::getTestFolder() . self::TEST_FILE_3);
        rmdir(FolderHelperTest::getTestFolder());
    }

    public function testCreateFolderFail()
    {
        FolderHelper::createFolder(FolderHelperTest::getTestFolder());
        $this->assertTrue(FileHelper::createFileIfNotExists(FolderHelperTest::getTestFolder() . self::TEST_FILE_1) , 'test that a file can be created');
    }

    public function testCopyFile()
    {
        $this->assertTrue(FileHelper::copyFile(FolderHelperTest::getTestFolder() . self::TEST_FILE_1, FolderHelperTest::getTestFolder() . self::TEST_FILE_2));
    }

    public function testMoveFile()
    {
        $this->assertTrue(FileHelper::moveFile(FolderHelperTest::getTestFolder() . self::TEST_FILE_2, FolderHelperTest::getTestFolder() . self::TEST_FILE_3));
    }
}
