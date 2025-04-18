<?php
namespace App\Services;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileUploaderService
{
    public const string BASE_PATH = "uploads";
    public const string UPLOAD_DIR = "/public/uploads";

    public readonly string $absoluteUploadDir;

    public function __construct(
        #[Autowire('%kernel.project_dir%')] readonly public string $projectDir
    ) {
        $this->absoluteUploadDir = $projectDir . DIRECTORY_SEPARATOR . static::UPLOAD_DIR;
    }

    public function upload(UploadedFile $uploadedFile): string
    {
        $uniqFilename = $this->getUniqFilename($uploadedFile);
        $uploadedFile = $uploadedFile->move($this->absoluteUploadDir, $uniqFilename);

        return $uniqFilename;
    }

    public function getUniqFilename(UploadedFile $file)
    {
        return uniqid() . '.' . $file->guessExtension();
    }
}
