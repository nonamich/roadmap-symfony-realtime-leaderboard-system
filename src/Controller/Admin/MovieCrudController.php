<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Services\FileUploaderService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MovieCrudController extends AbstractCrudController
{
    public function __construct(private FileUploaderService $fileUploaderService) {}

    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $fields = parent::configureFields($pageName);

        $fields[] = ImageField::new('poster')
            ->setUploadDir('public/' . $this->fileUploaderService::UPLOADS_DIRECTORY)
            ->setUploadedFileNamePattern(function (UploadedFile $file) {
                return $this->fileUploaderService::UPLOADS_DIRECTORY . DIRECTORY_SEPARATOR . $this->fileUploaderService->getNewFilename($file);
            });

        return $fields;
    }

}
