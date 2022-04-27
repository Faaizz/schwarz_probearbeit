<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Login;
use App\Entity\Portal;
use App\Repository\LoginRepository;
use App\Repository\PortalRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class PortalControllerTest extends WebTestCase
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

    protected function getLoginUser(): Login
    {
        $testLoginUser = new Login();
        $testLoginUser->setUsername($this->faker->userName);
        $testLoginUser->setPassword($this->faker->password);
        $this->userLoginRepository->add($testLoginUser);

        return $testLoginUser;
    }

    protected function getLoginAdmin(): Login
    {
        $testLoginAdmin = new Login();
        $testLoginAdmin->setUsername($this->faker->userName);
        $testLoginAdmin->setPassword($this->faker->password);
        $testLoginAdmin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $this->userLoginRepository->add($testLoginAdmin);

        return $testLoginAdmin;
    }

    public function imprintProvider(): iterable
    {
        return [
            'index_imprint_no_auth' => [
                'route' => '/portal/legal',
                'method' => 'GET',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
            'index_imprint_user_auth' => [
                'route' => '/portal/legal',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'index_imprint_admin_auth' => [
                'route' => '/portal/legal',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => true,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'show_create_imprint_no_auth' => [
                'route' => '/portal/legal/new',
                'method' => 'GET',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
            'show_create_imprint_user_auth' => [
                'route' => '/portal/legal/new',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => true,
                'isOk' => false,
            ],
            'show_create_imprint_admin_auth' => [
                'route' => '/portal/legal/new',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => true,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'add_page_no_auth' => [
                'route' => '/portal/new',
                'method' => 'GET',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
            'add_page_user_auth' => [
                'route' => '/portal/new',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'add_page_admin_auth' => [
                'route' => '/portal/new',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => true,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
        ];
    }

    /**
     * @dataProvider imprintProvider
     */
    public function testImprint(string $route, string $method, bool $isAuth, bool $isAdmin, bool $isRedirect, bool $isForbidden, bool $isOk)
    {
        if ($isAuth) {
            if ($isAdmin) {
                $this->client->loginUser($this->getLoginAdmin());
            } else {
                $this->client->loginUser($this->getLoginUser());
            }
        }
        $this->client->request($method, $route);

        $this->assertEquals($isRedirect, $this->client->getResponse()->isRedirect());
        $this->assertEquals($isForbidden, $this->client->getResponse()->isForbidden());
        $this->assertEquals($isOk, $this->client->getResponse()->isOk());

    }

    public function imprintEditProvider(): iterable
    {
        return [
            'show_update_imprint_no_auth' => [
                'route' => '/portal/legal/edit/',
                'method' => 'GET',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
            'show_update_imprint_user_auth' => [
                'route' => '/portal/legal/edit/',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'show_update_imprint_admin_auth' => [
                'route' => '/portal/legal/edit/',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => true,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'delete_imprint_no_auth' => [
                'route' => '/portal/legal/delete/',
                'method' => 'GET',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
            'delete_imprint_user_auth' => [
                'route' => '/portal/legal/delete/',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => true,
                'isOk' => false,
            ],
            'delete_imprint_admin_auth' => [
                'route' => '/portal/legal/delete/',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => true,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
        ];
    }

    /**
     * @dataProvider imprintEditProvider
     */
    public function testShowUpdateAndDeleteImprint(string $route, string $method, bool $isAuth, bool $isAdmin, bool $isRedirect, bool $isForbidden, bool $isOk)
    {
        $testPortal = new Portal();
        $testPortal->setCountryCode('de');
        $testPortal->setImprintLink('impressum');
        $testPortal->setImprint("Impressum");
        $this->portalRepository->add($testPortal);

        if ($isAuth) {
            if ($isAdmin) {
                $this->client->loginUser($this->getLoginAdmin());
            } else {
                $this->client->loginUser($this->getLoginUser());
            }
        }
        $this->client->request($method, $route . $testPortal->getId());

        $this->assertEquals($isRedirect, $this->client->getResponse()->isRedirect());
        $this->assertEquals($isForbidden, $this->client->getResponse()->isForbidden());
        $this->assertEquals($isOk, $this->client->getResponse()->isOk());
    }

    public function imprintProviderWithPortal(): iterable
    {
        return [
            'create_imprint_no_auth' => [
                'route' => '/portal/legal/new',
                'method' => 'POST',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
            ],
            'create_imprint_user_auth' => [
                'route' => '/portal/legal/new',
                'method' => 'POST',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => true,
            ],
            'create_imprint_admin_auth' => [
                'route' => '/portal/legal/new',
                'method' => 'POST',
                'isAuth' => true,
                'isAdmin' => true,
                'isRedirect' => true,
                'isForbidden' => false,
            ],
        ];
    }

    /**
     * @dataProvider imprintProviderWithPortal
     */
    public function testImprintWithPortal(string $route, string $method, bool $isAuth, bool $isAdmin, bool $isRedirect, bool $isForbidden)
    {
        $body = [
            'country_code' => $this->faker->countryCode(),
            'imprint_link' => $this->faker->word(),
            'imprint' => $this->faker->word(),
        ];

        if ($isAuth) {
            if ($isAdmin) {
                $this->client->loginUser($this->getLoginAdmin());
            } else {
                $this->client->loginUser($this->getLoginUser());
            }
        }
        $this->client->request($method, $route, $body);

        $this->assertEquals($isRedirect, $this->client->getResponse()->isRedirect());
        $this->assertEquals($isForbidden, $this->client->getResponse()->isForbidden());
    }

    function testCreateImprint()
    {
        $body = [
            'country_code' => $this->faker->countryCode(),
            'imprint_link' => $this->faker->word(),
            'imprint' => $this->faker->word(),
        ];

        $this->client->loginUser($this->getLoginAdmin());
          
        $this->client->request('POST', '/portal/legal/new', $body);

        $createdImprint = $this->portalRepository->findOneBy([
            'countryCode' => $body['country_code'],
        ]);

        $this->assertNotNull($createdImprint);
        $this->assertEquals($body['imprint_link'], $createdImprint->getImprintLink());
        $this->assertEquals($body['imprint'], $createdImprint->getImprint());
    }
    
    function testCreateImprintValidation()
    {
        $body = [
            'country_code' => 'pp',
            'imprint_link' => $this->faker->word(),
            'imprint' => $this->faker->word(),
        ];

        $this->client->loginUser($this->getLoginAdmin());
          
        $this->client->request('POST', '/portal/legal/new', $body);

        $createdImprint = $this->portalRepository->findOneBy([
            'countryCode' => $body['country_code'],
        ]);

        $this->assertNull($createdImprint);
    }
}
