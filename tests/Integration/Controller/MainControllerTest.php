<?php

namespace App\Tests\Integration\Controller;

use App\Entity\PortalPage;
use App\Repository\PortalPageRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    private $client;
    private $portalRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->portalRepository = static::getContainer()
            ->get(PortalPageRepository::class);
    }

    public function testListPagesNoAuth()
    {
        $this->client->request('GET', '/');

        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testLegalPageNotExist()
    {
        $testData = [
            'countryCode' => 'EN',
            'pagePath' => 'legal-notice',
            'content' => 'Legal Notice',
        ];

        $this->client->request(
            'GET', 
            sprintf('/pages/%s/%s', $testData['countryCode'], $testData['pagePath'])
        );

        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testLegalPageNoAuth()
    {
        $testData = [
            'countryCode' => 'BS',
            'pagePath' => 'legal-notice',
            'content' => 'Legal Notice',
        ];
        $newLegalPage = new PortalPage();
        $newLegalPage->setCountryCode($testData['countryCode']);
        $newLegalPage->setContent($testData['content']);
        $newLegalPage->setPagePath($testData['pagePath']);
        $this->portalRepository->add($newLegalPage);

        $this->client->request(
            'GET', 
            sprintf('/pages/%s/%s', $testData['countryCode'], $testData['pagePath'])
        );

        $this->assertTrue($this->client->getResponse()->isOk());
    }
}
