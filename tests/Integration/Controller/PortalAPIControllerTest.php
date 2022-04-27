<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Login;
use App\Entity\PortalPage;
use App\Repository\LoginRepository;
use App\Repository\PortalPageRepository;
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
            ->get(PortalPageRepository::class);
        $this->userLoginRepository = static::getContainer()
            ->get(LoginRepository::class);
        $this->faker = Faker\Factory::create();
    }

    public function testIndex()
    {
        $testPortal1 = new PortalPage();
        $testPortal1->setCountryCode($this->faker->countryCode());
        $testPortal1->setPagePath( $this->faker->word());
        $testPortal1->setContent( $this->faker->word());
        $this->portalRepository->add($testPortal1);

        $testPortal2 = new PortalPage();
        $testPortal2->setCountryCode($this->faker->countryCode());
        $testPortal2->setPagePath( $this->faker->word());
        $testPortal2->setContent( $this->faker->word());
        $this->portalRepository->add($testPortal2);

        $this->client->request(
            'GET',
            '/api/v1/portal/pages',
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
            'page_path' => $this->faker->word(),
            'content' => $this->faker->word(),
        ];

        $this->client->jsonRequest(
            'POST',
            '/api/v1/portal/pages',
            $body
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $portalPageCreated =$this->portalRepository->findOneBy([
            'countryCode' => $countryCode,
        ]);

        $this->assertNotNull($portalPageCreated);
    }

    public function testCreateFail()
    {
        $countryCode = '99';
        $body = [
            'country_code' => $countryCode,
            'page_path' => $this->faker->word(),
            'content' => $this->faker->word(),
        ];

        $this->client->jsonRequest(
            'POST',
            '/api/v1/portal/pages',
            $body
        );

        $this->assertTrue($this->client->getResponse()->isClientError());

        $portalPageCreated =$this->portalRepository->findOneBy([
            'countryCode' => $countryCode,
        ]);

        $this->assertNull($portalPageCreated);
    }

    public function testUpdateSuccess()
    {
        $testPortal1 = new PortalPage();
        $testPortal1->setCountryCode($this->faker->countryCode());
        $testPortal1->setPagePath( $this->faker->word());
        $testPortal1->setContent( $this->faker->word());
        $this->portalRepository->add($testPortal1);

        $newpagePath = 'impressium';
        $body = [
            'page_path' => $newpagePath,
            'content' => 'Hi hi hi'
        ];

        $this->client->jsonRequest(
            'PUT',
            '/api/v1/portal/pages/' . $testPortal1->getId(),
            $body,
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $portalPageUpdated =$this->portalRepository->find($testPortal1->getId());

        $this->assertEquals($newpagePath, $portalPageUpdated->getPagePath());
    }

    public function testUpdateFail()
    {
        $newpagePath = 'impressium';
        $body = [
            'page_path' => $newpagePath,
            'content' => 'Hi hi hi'
        ];

        $this->client->jsonRequest(
            'PUT',
            '/api/v1/portal/pages/999',
            $body
        );

        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testDelete()
    {
        $countryCode = $this->faker->countryCode();
        $testPortal1 = new PortalPage();
        $testPortal1->setCountryCode($countryCode);
        $testPortal1->setPagePath( $this->faker->word());
        $testPortal1->setContent( $this->faker->word());
        $this->portalRepository->add($testPortal1);

        $pageID = $testPortal1->getId();

        $portalPage = $this->portalRepository->find($pageID);

        $this->assertEquals($countryCode, $portalPage->getCountryCode());

        $this->client->jsonRequest(
            'DELETE',
            '/api/v1/portal/pages/' . $pageID,
        );

        $portalPage = $this->portalRepository->find($pageID);

        $this->assertNull($portalPage);
    }

    public function testDeleteFail()
    {
        $this->client->jsonRequest(
            'DELETE',
            '/api/v1/portal/pages/999',
        );

        $this->assertTrue($this->client->getResponse()->isNotFound());
    }
}
