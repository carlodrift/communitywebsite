<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

    public function __construct(
        private readonly SluggerInterface $slugger,
    )
    {
    }


    /**
     * @param $file
     * @param $directory
     * @return string
     */
    public function upload($file, $directory): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $directory,
                $newFilename
            );
        } catch (FileException $ignored) {

        }


        return $newFilename;
    }
}
