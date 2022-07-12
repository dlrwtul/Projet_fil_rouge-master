<?php 

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService
{
    public function upload(UploadedFile $file)
    {
        $strm = fopen($file->getRealPath(),'rb'); 

        return stream_get_contents($strm);
    }

}