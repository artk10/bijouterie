<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\Category;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'une catégorie
        $category = new Category();
        $category->setName('Bracelets');
        $manager->persist($category);

        // Création d'un produit
        $product = new Product();
        $product->setName('Bracelet en or');
        $product->setDescription('Un bracelet élégant avec un revêtement doré.');
        $product->setPrice(49.99);
        $product->setImage('bracelet.jpg');
        $product->setCategory($category);

        $manager->persist($product);
        $manager->flush();
    }
}
