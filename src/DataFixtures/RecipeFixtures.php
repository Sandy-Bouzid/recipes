<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        $categories = ['Entrée', 'Plat', 'Dessert'];

        for ($i = 0; $i < count($categories); $i++) {
            $category = new Category();
            $category->setName($categories[$i])
                ->setSlug(strtolower($this->slugger->slug($categories[$i])))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($category);
            $this->addReference($categories[$i], $category);
        }

        for ($i = 1; $i <= 15; $i++) {
            $recipe = new Recipe();
            $title = $faker->foodname();
            $recipe->setTitle($title)
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setContent($faker->paragraph(10, true))
                ->setDuration($faker->numberBetween(5, 80))
                ->setCategory($this->getReference($faker->randomElement($categories)))
                ->setUser($this->getReference('USER' . $faker->numberBetween(1, 10)));
            $manager->persist($recipe);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
