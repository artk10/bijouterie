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
        // Création des catégories
        $bracelets = new Category();
        $bracelets->setName('Bracelets');
        $manager->persist($bracelets);

        $bagues = new Category();
        $bagues->setName('Bagues');
        $manager->persist($bagues);

        $colliers = new Category();
        $colliers->setName('Colliers');
        $manager->persist($colliers);

        $boucles = new Category();
        $boucles->setName('Boucles d\'oreilles');
        $manager->persist($boucles);

        // Création de quelques produits pour chaque catégorie
        $product1 = new Product();
        $product1->setName('Bracelet en or');
        $product1->setDescription('Un bracelet élégant avec un revêtement doré.');
        $product1->setPrice(49.99);
        $product1->setImage('bracelet.jpg');
        $product1->setCategory($bracelets);
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Bague en argent');
        $product2->setDescription('Une bague magnifique en argent massif.');
        $product2->setPrice(79.99);
        $product2->setImage('bague.jpg');
        $product2->setCategory($bagues);
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('Collier en perles');
        $product3->setDescription('Un collier raffiné avec des perles naturelles.');
        $product3->setPrice(99.99);
        $product3->setImage('collier.jpg');
        $product3->setCategory($colliers);
        $manager->persist($product3);

        $product4 = new Product();
        $product4->setName('Boucles d\'oreilles en diamant');
        $product4->setDescription('De magnifiques boucles d\'oreilles avec des diamants.');
        $product4->setPrice(199.99);
        $product4->setImage('boucles.jpg');
        $product4->setCategory($boucles);
        $manager->persist($product4);

        $manager->flush();
    }
}
