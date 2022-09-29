<?php

namespace LinuxFileSystemHelper;

use LinuxFileSystemHelper\Exceptions\LinuxFileSystemHelperException;

class FileHelper
{
    public static function createFileIfNotExists(string $filename_and_path): bool
    {
        if (file_exists($filename_and_path) === false) {
            return touch($filename_and_path);
        } else {
            return true;
        }
    }

    public static function diffFiles(string $fileNameAndPath1, string $fileNameAndPath2): array
    {
        exec('diff ' . '"' . $fileNameAndPath1 . '" "' . $fileNameAndPath2 . '" -w', $linesThatDiffers);
        return $linesThatDiffers;
    }

    public static function copyFile(string $oldNameAndPath, string $newNameAndPath): bool
    {
        return copy($oldNameAndPath, $newNameAndPath);
    }

    public static function moveFile(string $source, string $destination): bool
    {
        if (!file_exists($source)) {
            throw new LinuxFileSystemHelperException('Cannot move file. The following source do not exist : ' . $source);
        }

        if (file_exists($destination)) {
            $destination .= '-' . time();
        }

        if (@rename($source, $destination) == false) {
            throw new LinuxFileSystemHelperException("Could move from file from {$source} => {$destination}");
        } else {
            return true;
        }
    }
}