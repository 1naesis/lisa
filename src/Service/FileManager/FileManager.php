<?php


namespace App\Service\FileManager;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager implements FIleManagerInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function saveFile(UploadedFile $file, string $directory = 'upload_directory'): string
    {
        $path = $this->container->getParameter($directory);
        $tmpFileName = time();
        while (file_exists($path.uniqid($tmpFileName.'_').'.'.$file->guessExtension())){
            $tmpFileName++;
        }
        $fileName = uniqid($tmpFileName.'_').'.'.$file->guessExtension();
        $file->move($path, $fileName);
        if(file_exists($path.$fileName)){
            return $fileName;
        }
        return '';
    }

    public function removeFile(string $fileName, string $directory = ''): bool
    {
        $path = $this->container->getParameter($directory)??'';
        if(file_exists($path.$fileName)){
            return unlink($path.$fileName);
        }
        return false;
    }
}