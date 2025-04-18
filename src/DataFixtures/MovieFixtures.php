<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Services\FileUploaderService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class MovieFixtures extends Fixture
{
    private FakerGenerator $faker;

    public function __construct(private FileUploaderService $uploader, private HttpClientInterface $httpClient)
    {
        $this->faker = $this->createFaker();
    }

    private function createFaker()
    {
        $faker = FakerFactory::create();

        $faker->addProvider(new FakerPicsumImagesProvider($faker));

        return $faker;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = $this->createFaker();
        $allowedGenres = [
            'Action',
            'Adventure',
            'Animation',
            'Comedy',
            'Romance',
            'Crime',
            'Horror',
            'Documentary',
            'Drama',
            'Sci-fi',
            'Western',
            'Fantasy',
            'Thriller',
        ];

        for ($i = 0; $i < 10; $i++) {
            $genres = $faker->randomElements($allowedGenres, $faker->numberBetween(2, 4));
            $poster = $this->downloadPoster();
            $movie = new Movie();

            $movie->setTitle($faker->sentence(3));
            $movie->setDescription($faker->text());
            $movie->setDurationInMins($faker->numberBetween(50, 120));
            $movie->setLanguage($faker->languageCode());
            $movie->setCountry($faker->countryCode());
            $movie->setGenres($genres);
            $movie->setReleaseDate($faker->dateTime());
            $movie->setPoster($poster);

            $manager->persist($movie);
        }

        $manager->flush();
    }

    private function downloadPoster()
    {
        $filesystem = new Filesystem();
        $externalPosterUrl = $this->faker->imageUrl(width: 550, height: 800);
        $response = $this->httpClient->request('GET', $externalPosterUrl);
        $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException();
        }

        $filesystem->dumpFile($tempPath, $response->getContent());

        $uploadedFile = new UploadedFile(
            $tempPath,
            basename($tempPath),
            mime_content_type($tempPath),
            null,
            true
        );

        return $this->uploader->upload($uploadedFile);
    }
}
