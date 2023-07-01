<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogListTest extends WebTestCase
{
    public function testBlogList(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('blog_list')
        );

        $this->assertResponseStatusCodeSame(200);

    }
}
