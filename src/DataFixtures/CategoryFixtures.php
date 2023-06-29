<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setTitle('Mode');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Beauté');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Voyage');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Mode de vie');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Personnel');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Technologie');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Santé');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Fitness');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Bien-être');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Business');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Education');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Nourritures');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Recettes');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Relations');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Lifetime');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Musique');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Véhicule');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Marketing');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Services');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Finance');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Sports');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Divertissement');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Produit');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Loisirs');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Parents');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Animaux');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Photographie');
        $manager->persist($category);
        
        $category = new Category();
        $category->setTitle('Autres');
        $manager->persist($category);

        $manager->flush();
    }
}
