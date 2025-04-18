<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Services\FileUploaderService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
        yield IdField::new('id')->setDisabled()->setRequired(false);
        yield ImageField::new('poster')
            ->setUploadDir($this->fileUploaderService::UPLOAD_DIR)
            ->setBasePath($this->fileUploaderService::BASE_PATH)
            ->setUploadedFileNamePattern(function (UploadedFile $file) {
                return $this->fileUploaderService->getUniqFilename($file);
            });
        yield TextField::new('title');
        yield TextareaField::new('description')->hideOnIndex();
        yield NumberField::new('durationInMins');
        yield TextField::new('language');
        yield DateField::new('releaseDate');
        yield TextField::new('country');
        yield ArrayField::new('genres');
    }

}
