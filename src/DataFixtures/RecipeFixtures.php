<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\IngredientsProvider;
use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Quantity;
use App\Entity\Recipe;
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
        $faker->addProvider(new IngredientsProvider());

        $ingredients = array_map(fn (string $name) => (new Ingredient())
            ->setName($name)
            ->setSlug(strtolower($this->slugger->slug($name))), $faker->getAllIngredients);

        foreach ($ingredients as $ingredient) {
            $manager->persist($ingredient);
        }

        $categories = ['Entrée', 'Plat', 'Dessert'];

        for ($i = 0; $i < count($categories); $i++) {
            $category = (new Category())
                ->setName($categories[$i])
                ->setSlug(strtolower($this->slugger->slug($categories[$i])))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($category);
            $this->addReference($categories[$i], $category);
        }

        for ($i = 1; $i <= 15; $i++) {
            $title = $faker->unique()->foodname();
            $recipe = (new Recipe())
                ->setTitle($title)
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setContent($faker->paragraph(10, true))
                ->setDuration($faker->numberBetween(5, 80))
                ->setCategory($this->getReference($faker->randomElement($categories)))
                ->setUser($this->getReference('USER' . $faker->numberBetween(1, 10)));

            foreach ($faker->randomElements($ingredients, $faker->numberBetween(3, 7)) as $ingredient) {
                $recipe->addQuantity((new Quantity())
                        ->setAmount($faker->numberBetween(1, 200))
                        ->setUnit($faker->getUnit)
                        ->setIngredient($ingredient)
                );
            }

            $manager->persist($recipe);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
