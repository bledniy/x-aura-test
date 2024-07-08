<?php

namespace App\Service;

use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class ResumeFileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {
        if (!file_exists($this->targetDirectory)) {
            if (!mkdir($this->targetDirectory, 0775, true) && !is_dir($this->targetDirectory)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $this->targetDirectory));
            }
        }
    }

    /**
     * @throws Exception
     */
    public function uploadFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . uniqid('-') . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $newFilename);
            error_log("File successfully moved to " . $this->getTargetDirectory() . "/" . $newFilename);
        } catch (FileException $e) {
            error_log("Error moving file: " . $e->getMessage());
            throw new Exception('Could not move the file to the target directory.');
        }

        return $newFilename;
    }

    public function deleteFile(string $filename): void
    {
        $filePath = $this->getTargetDirectory() . '/' . $filename;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
