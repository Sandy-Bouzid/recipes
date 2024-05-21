<?php

namespace App\DataFixtures\Provider;

class IngredientsProvider
{
    private $ingredients = [
        "farine",
        "sucre",
        "sel",
        "poivre",
        "huile d'olive",
        "beurre",
        "lait",
        "œufs",
        "levure",
        "bicarbonate de soude",
        "vanille",
        "cannelle",
        "gingembre",
        "miel",
        "chocolat",
        "tomates",
        "ail",
        "oignons",
        "carottes",
        "céleri",
        "pommes de terre",
        "riz",
        "pâtes",
        "poulet",
        "poisson"
    ];

    private $units = [
        "grammes",
        "kilogrammes",
        "litres",
        "millilitres",
        "tasses",
        "cuillères à soupe",
        "cuillères à café",
        "centilitres",
        "décilitres",
        "pincées"
    ];

    public function getAllIngredients()
    {
        return $this->ingredients;
    }

    public function getUnit()
    {
        return $this->units[array_rand($this->units)];
    }
}
