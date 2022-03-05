<?php

namespace LinuxFileSystemHelper;

use LinuxFileSystemHelper\Exceptions\LinuxFileSystemHelperException;

class FolderHelper
{
    public const EMPTY_LINUX_FOLDER = ['.', '..'];

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

    /**
     * Return a list of files. A line in the list could e.g. look like this:
     * 7475750;2021-11-07;/home/[some folder name]/LinuxFileSystemHelper/tests/data/photos/SampleJPGImage_100kbmb.jpg
     *
     * First column being inode
     * Secound column date of creation
     * Third column full path and file name.
     *
     * @param string     $path_to_search
     * @param string     $file_type             File type to list e.g. '.jpg'
     * @param array|null $folder_ignore_list    List of folders to ignore e.g. ['.trash']
     *
     * @return array
     */
    public static function listFilesRecursiveFromFolder(string $path_to_search, string $file_type, array $folder_ignore_list = []): array
    {
        $folder_ignore_pattern = '';

        // Prepare ignore string
        foreach ($folder_ignore_list as $folder_to_ignore) {
            $folder_ignore_pattern .= ' -name ' . $folder_to_ignore. ' -prune -o ';
        }

        $command_to_execute = 'find \'' . $path_to_search . '\' ' . $folder_ignore_pattern. ' -printf "%i;%TY-%Tm-%Td;%p\n" | grep "\.'.ltrim($file_type,'.').'$" -i ';
        exec($command_to_execute, $files);
        return $files;
    }

    /**
     * @param string $path
     *
     * @return bool
     * @throws LinuxFileSystemHelperException
     */
    public static function isFolderEmpty(string $path): bool
    {
        $scan_result = scandir($path);

        if ($scan_result === false) {
            throw new LinuxFileSystemHelperException("The supplied path ($path) do not seem to be a folder!");
        }

        if ($scan_result == self::EMPTY_LINUX_FOLDER) {
            return true;
        }

        return false;
    }
}
