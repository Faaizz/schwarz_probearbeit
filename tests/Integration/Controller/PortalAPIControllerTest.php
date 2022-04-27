<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Login;
use App\Entity\Portal;
use App\Repository\LoginRepository;
use App\Repository\PortalRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class PortalAPIControllerTest extends WebTestCase
{
    private $client;
    private $portalRepository;
    private $userLoginRepository;
    private $faker;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->portalRepository = static::getContainer()
            ->get(PortalRepository::class);
        $this->userLoginRepository = static::getContainer()
            ->get(LoginRepository::class);
        $this->faker = Faker\Factory::create();
    }

    public function testIndex()
    {
        $testPortal1 = new Portal();
        $testPortal1->setCountryCode($this->faker->countryCode());
        $testPortal1->setImprintLink( $this->faker->word());
        $testPortal1->setImprint( $this->faker->word());
        $this->portalRepository->add($testPortal1);

        $testPortal2 = new Portal();
        $testPortal2->setCountryCode($this->faker->countryCode());
        $testPortal2->setImprintLink( $this->faker->word());
        $testPortal2->setImprint( $this->faker->word());
        $this->portalRepository->add($testPortal2);

        $this->client->request(
            'GET',
            '/api/v1/portal/legal',
        );

        $this->assertTrue($this->client->getResponse()->isOk());

        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertTrue(count($response->portals) >= 2);
    }

    public function testCreateSuccess()
    {
        $countryCode = $this->faker->countryCode();
        $body = [
            'country_code' => $countryCode,
            'imprint_link' => $this->faker->word(),
            'imprint' => $this->faker->word(),
        ];

        $this->client->jsonRequest(
            'POST',
            '/api/v1/portal/legal',
            $body
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $imprintPortalCreated =$this->portalRepository->findOneBy([
            'countryCode' => $countryCode,
        ]);

        $this->assertNotNull($imprintPortalCreated);
    }

    public function testCreateFail()
    {
        $countryCode = '99';
        $body = [
            'country_code' => $countryCode,
            'imprint_link' => $this->faker->word(),
            'imprint' => $this->faker->word(),
        ];

        $this->client->jsonRequest(
            'POST',
            '/api/v1/portal/legal',
            $body
        );

        $this->assertTrue($this->client->getResponse()->isClientError());

        $imprintPortalCreated =$this->portalRepository->findOneBy([
            'countryCode' => $countryCode,
        ]);

        $this->assertNull($imprintPortalCreated);
    }

    public function testUpdateSuccess()
    {
        $testPortal1 = new Portal();
        $testPortal1->setCountryCode($this->faker->countryCode());
        $testPortal1->setImprintLink( $this->faker->word());
        $testPortal1->setImprint( $this->faker->word());
        $this->portalRepository->add($testPortal1);

        $newImprintLink = 'impressium';
        $body = [
            'imprint_link' => $newImprintLink,
            'imprint' => 'Hi hi hi'
        ];

        $this->client->jsonRequest(
            'PUT',
            '/api/v1/portal/legal/' . $testPortal1->getId(),
            $body,
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $imprintPortalUpdated =$this->portalRepository->find($testPortal1->getId());

        $this->assertEquals($newImprintLink, $imprintPortalUpdated->getImprintLink());
    }

    public function testUpdateFail()
    {
        $newImprintLink = 'impressium';
        $body = [
            'imprint_link' => $newImprintLink,
            'imprint' => 'Hi hi hi'
        ];

        $this->client->jsonRequest(
            'PUT',
            '/api/v1/portal/legal/999',
            $body
        );

        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testDelete()
    {
        $countryCode = $this->faker->countryCode();
        $testPortal1 = new Portal();
        $testPortal1->setCountryCode($countryCode);
        $testPortal1->setImprintLink( $this->faker->word());
        $testPortal1->setImprint( $this->faker->word());
        $this->portalRepository->add($testPortal1);

        $imprintID = $testPortal1->getId();

        $imprintPortal = $this->portalRepository->find($imprintID);

        $this->assertEquals($countryCode, $imprintPortal->getCountryCode());

        $this->client->jsonRequest(
            'DELETE',
            '/api/v1/portal/legal/' . $imprintID,
        );

        $imprintPortal = $this->portalRepository->find($imprintID);

        $this->assertNull($imprintPortal);
    }

    public function testDeleteFail()
    {
        $this->client->jsonRequest(
            'DELETE',
            '/api/v1/portal/legal/999',
        );

        $this->assertTrue($this->client->getResponse()->isNotFound());
    }
}
