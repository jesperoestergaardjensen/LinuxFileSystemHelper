<?php

namespace LinuxFileSystemHelper\Tests\unit;

use LinuxFileSystemHelper\Exceptions\LinuxFileSystemHelperException;
use LinuxFileSystemHelper\FileHelper;
use LinuxFileSystemHelper\FolderHelper;
use PHPUnit\Framework\TestCase;

class FileHelperTest extends TestCase
{
    const TEST_FILE_1 = 'test1.file';
    const TEST_FILE_2 = 'test2.file';
    const TEST_FILE_3 = 'test3.file';
    const TEST_FILE_4 = 'test4.file';
    const TEST_FILE_5 = 'test5.file';
/*
    private array $files_to_unlink_in_tear_down = [];

    public function setUp(): void
    {
        $this->files_to_unlink_in_tear_down = 
            [
                FolderHelperTest::getTestFolder() . self::TEST_FILE_1,
                FolderHelperTest::getTestFolder() . self::TEST_FILE_3,
                FolderHelperTest::getTestFolder() . self::TEST_FILE_4,
                FolderHelperTest::getTestFolder() . self::TEST_FILE_5
            ];
    }
  */
    public static function tearDownAfterClass(): void
    {
        unlink(FolderHelperTest::getTestFolder() . self::TEST_FILE_1);
        unlink(FolderHelperTest::getTestFolder() . self::TEST_FILE_3);
        unlink(FolderHelperTest::getTestFolder() . self::TEST_FILE_4);
        unlink(FolderHelperTest::getTestFolder() . self::TEST_FILE_5);
        rmdir(FolderHelperTest::getTestFolder());
    }

    public function testCreateFolderFail()
    {
        FolderHelper::createFolder(FolderHelperTest::getTestFolder());
        $this->assertTrue(FileHelper::createFileIfNotExists(FolderHelperTest::getTestFolder() . self::TEST_FILE_1),
            'test that a file can be created');

        $this->assertTrue(FileHelper::createFileIfNotExists(FolderHelperTest::getTestFolder() . self::TEST_FILE_1),
            'test that a file can be created');
    }

    public function testCopyFile()
    {
        $this->assertTrue(FileHelper::copyFile(FolderHelperTest::getTestFolder() . self::TEST_FILE_1,
            FolderHelperTest::getTestFolder() . self::TEST_FILE_2));
    }

    public function testMoveFile()
    {
        $this->assertTrue(FileHelper::moveFile(FolderHelperTest::getTestFolder() . self::TEST_FILE_2,
            FolderHelperTest::getTestFolder() . self::TEST_FILE_3));
    }

    public function testMoveFileSourceException()
    {
        $this->expectException(LinuxFileSystemHelperException::class);
        $this->expectExceptionMessage('Cannot move file. The following source do not exist : ' . 'non-existing-folder');
        FileHelper::moveFile('non-existing-folder', FolderHelperTest::getTestFolder() . self::TEST_FILE_3);
    }

    public function testMoveFileDestinationException()
    {
        touch(FolderHelperTest::getTestFolder() . 'temp_file_source_1.txt');

        $this->expectException(LinuxFileSystemHelperException::class);
        $this->expectExceptionMessage("Could move from file from ". FolderHelperTest::getTestFolder() . 'temp_file_source_1.txt' . " => ");

        try {
            FileHelper::moveFile(FolderHelperTest::getTestFolder() . 'temp_file_source_1.txt', '');
        } finally {
            unlink(FolderHelperTest::getTestFolder() . 'temp_file_source_1.txt');
        }
    }

    public function testMoveFileToExistingDestination()
    {
        touch(FolderHelperTest::getTestFolder() . 'temp_file_source_1.txt');
        touch(FolderHelperTest::getTestFolder() . 'temp_file_source_2.txt');

        FileHelper::moveFile(FolderHelperTest::getTestFolder() . 'temp_file_source_1.txt', FolderHelperTest::getTestFolder() . 'temp_file_destination.txt');
        FileHelper::moveFile(FolderHelperTest::getTestFolder() . 'temp_file_source_2.txt', FolderHelperTest::getTestFolder() . 'temp_file_destination.txt');

        $this->assertTrue(file_exists(FolderHelperTest::getTestFolder() . 'temp_file_destination.txt'));
        $this->assertTrue(file_exists(FolderHelperTest::getTestFolder() . 'temp_file_destination.txt-' . time()));

        unlink(FolderHelperTest::getTestFolder() . 'temp_file_destination.txt');
        unlink(FolderHelperTest::getTestFolder() . 'temp_file_destination.txt-' . time());
    }

    public function testDiffFiles()
    {
        $data1 = "a\nb\nc\n";
        $data2 = "a\nb\nc\nd\n";

        file_put_contents(FolderHelperTest::getTestFolder() . self::TEST_FILE_4, $data1);
        file_put_contents(FolderHelperTest::getTestFolder() . self::TEST_FILE_5, $data2);

        $diff = FileHelper::diffFiles(FolderHelperTest::getTestFolder() . self::TEST_FILE_4,
            FolderHelperTest::getTestFolder() . self::TEST_FILE_5);

        $this->assertSame($diff[0], '3a4', 'Line 4 from file 2 was added to line 3 on file 1');
        $this->assertSame($diff[1], '> d', 'd was added from file 1 to file 2');
    }
}
