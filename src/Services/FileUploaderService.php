<?php
namespace App\Services;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileUploaderService
{
    public const string UPLOADS_DIRECTORY = 'uploads';

    public function __construct(
        private ParameterBagInterface $params,
        #[Autowire('%kernel.project_dir%/public')] private string $publicDirectory)
    {
    }

    public function upload(UploadedFile $uploadedFile): string
    {
        $newFilename = $this->getNewFilename($uploadedFile);
        $uploadedFile = $uploadedFile->move($this->publicDirectory . DIRECTORY_SEPARATOR . self::UPLOADS_DIRECTORY, $newFilename);

        return self::UPLOADS_DIRECTORY . DIRECTORY_SEPARATOR . $newFilename;
    }

    public function getNewFilename(UploadedFile $file)
    {
        return uniqid() . '.' . $file->guessExtension();
    }
}
