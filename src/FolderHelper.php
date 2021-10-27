<?php

namespace LinuxFileSystemHelper;

use LinuxFileSystemHelper\Exceptions\LinuxFileSystemHelperException;

class FolderHelper
{
    /**
     * @param string $folder
     * @param bool   $recursive
     *
     * @return bool
     * @throws LinuxFileSystemHelperException
     */
    public static function createFolder(string $folder, bool $recursive = true): bool
    {
        // Folder string is expected to contain '/'
        if (strpos($folder, '/') === false) {
            throw new LinuxFileSystemHelperException("Cannot create folder : $folder - did you supply an invalid folder?");
        }

        if (file_exists($folder)) {
            return true;
        }

        if (@mkdir($folder,0751, $recursive) == false) {
            throw new LinuxFileSystemHelperException('Could not create the following path : ' . $folder);
        } else {
            return true;
        }
    }
}
