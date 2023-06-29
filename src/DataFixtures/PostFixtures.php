<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\DataFixtures\CategoryFixtures;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CategoryRepository $categoryRepository
    ) {
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $this->userRepository->findAll();
        $categories = $this->categoryRepository->findAll();

        for ($i = 0; $i < 150; $i++) {
            $post = new Post();
            $post->setTitle($faker->words(4, true))
                ->setContent($faker->realText(1800))
                ->setImage($faker->imageUrl())
                ->setPublished(true)
                ->setUserId($users[mt_rand(0, count($users) -1)])
                ->addCategory($categories[mt_rand(0, count($categories) -1)])
            ;

            $manager->persist($post);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class
        ];
    }
}
