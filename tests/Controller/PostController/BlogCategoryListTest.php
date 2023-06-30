<?php

namespace App\Tests\Functional;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogCategoryListTest extends WebTestCase
{
    public function testBlogCategoryList(): void
    {
        $client = static::createClient();

        $categoryRepository = $client->getContainer()->get(CategoryRepository::class);
        $category = $categoryRepository->findOneBy([]);

        $urlGenerator = $client->getContainer()->get('router');

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('blog_posts_in_category', ['id' => $category->getId()])
        );

        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorExists('h2');
        $this->assertSelectorTextContains('h2', $category->getTitle());

        $this->assertSelectorExists('.pagination');

    }
}
