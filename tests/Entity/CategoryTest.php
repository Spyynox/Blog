<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    public function getEntity(): Category
    {

        return (
            new Category()
        )
            ->setTitle('Category 01');
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $category = $this->getEntity();

        $errors = $container->get('validator')->validate($category);

        $this->assertCount(0, $errors);
    }

    public function testInvalidCategoryObject()
    {
        self::bootKernel();
        $container = static::getContainer();

        $category = $this->getEntity();
        $category->setTitle('');

        $errors = $container->get('validator')->validate($category);
        $this->assertCount(1, $errors);
    }
}
