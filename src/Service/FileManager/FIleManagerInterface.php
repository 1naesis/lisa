<?php


namespace App\Service\FileManager;


use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FIleManagerInterface
{
    /**
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    public function saveFile(UploadedFile $file, string $directory):string;

    /**
     * @param string $fileName
     * @param string $directory
     * @return bool
     */
    public function removeFile(string $fileName, string $directory):bool;
}