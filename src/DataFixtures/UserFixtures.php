<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private ParameterBagInterface $params, private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        if ($manager->getRepository(User::class)->count([]) === 0) {
            $user = new User();
            $adminEmail = $this->params->get('admin_email');
            $adminPassword = $this->params->get('admin_password');

            $user->setEmail($adminEmail);
            $user->setPassword($this->hasher->hashPassword($user, $adminPassword));
            $user->setRoles(['ROLE_SUPER_ADMIN']);
            $user->setIsVerified(true);

            $manager->persist($user);
            $manager->flush();
        }
    }
}
