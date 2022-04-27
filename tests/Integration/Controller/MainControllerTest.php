<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Portal;
use App\Repository\PortalRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    private $client;
    private $portalRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->portalRepository = static::getContainer()
            ->get(PortalRepository::class);
    }

    public function testListPagesNoAuth()
    {
        $this->client->request('GET', '/');

        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testLegalPageNotExist()
    {
        $testData = [
            'countryCode' => 'en',
            'imprintLink' => 'legal-notice',
            'imprint' => 'Legal Notice',
        ];

        $this->client->request(
            'GET', 
            sprintf('/legal/%s/%s', $testData['countryCode'], $testData['imprintLink'])
        );

        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testLegalPageNoAuth()
    {
        $testData = [
            'countryCode' => 'bs',
            'imprintLink' => 'legal-notice',
            'imprint' => 'Legal Notice',
        ];
        $newLegalPage = new Portal();
        $newLegalPage->setCountryCode($testData['countryCode']);
        $newLegalPage->setImprint($testData['imprint']);
        $newLegalPage->setImprintLink($testData['imprintLink']);
        $this->portalRepository->add($newLegalPage);

        $this->client->request(
            'GET', 
            sprintf('/legal/%s/%s', $testData['countryCode'], $testData['imprintLink'])
        );

        $this->assertTrue($this->client->getResponse()->isOk());
    }
}
