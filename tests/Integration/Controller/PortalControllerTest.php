<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Login;
use App\Entity\PortalPage;
use App\Repository\LoginRepository;
use App\Repository\PortalPageRepository;
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
            ->get(PortalPageRepository::class);
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

    public function pageProvider(): iterable
    {
        return [
            'index_pages_no_auth' => [
                'route' => '/portal/pages',
                'method' => 'GET',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
            'index_pages_user_auth' => [
                'route' => '/portal/pages',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'index_pages_admin_auth' => [
                'route' => '/portal/pages',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => true,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'show_create_page_no_auth' => [
                'route' => '/portal/pages/new',
                'method' => 'GET',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
            'show_create_page_user_auth' => [
                'route' => '/portal/pages/new',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => true,
                'isOk' => false,
            ],
            'show_create_page_admin_auth' => [
                'route' => '/portal/pages/new',
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
     * @dataProvider pageProvider
     */
    public function testPage(string $route, string $method, bool $isAuth, bool $isAdmin, bool $isRedirect, bool $isForbidden, bool $isOk)
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

    public function pageEditProvider(): iterable
    {
        return [
            'show_update_page_no_auth' => [
                'route' => '/portal/pages/edit/',
                'method' => 'GET',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
            'show_update_page_user_auth' => [
                'route' => '/portal/pages/edit/',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'show_update_page_admin_auth' => [
                'route' => '/portal/pages/edit/',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => true,
                'isRedirect' => false,
                'isForbidden' => false,
                'isOk' => true,
            ],
            'delete_page_no_auth' => [
                'route' => '/portal/pages/delete/',
                'method' => 'GET',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
                'isOk' => false,
            ],
            'delete_page_user_auth' => [
                'route' => '/portal/pages/delete/',
                'method' => 'GET',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => true,
                'isOk' => false,
            ],
            'delete_page_admin_auth' => [
                'route' => '/portal/pages/delete/',
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
     * @dataProvider pageEditProvider
     */
    public function testShowUpdateAndDeletePage(string $route, string $method, bool $isAuth, bool $isAdmin, bool $isRedirect, bool $isForbidden, bool $isOk)
    {
        $testPortal = new PortalPage();
        $testPortal->setCountryCode('de');
        $testPortal->setPagePath('impressum');
        $testPortal->setContent("Impressum");
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

    public function pageProviderWithPortal(): iterable
    {
        return [
            'create_page_no_auth' => [
                'route' => '/portal/pages/new',
                'method' => 'POST',
                'isAuth' => false,
                'isAdmin' => false,
                'isRedirect' => true,
                'isForbidden' => false,
            ],
            'create_page_user_auth' => [
                'route' => '/portal/pages/new',
                'method' => 'POST',
                'isAuth' => true,
                'isAdmin' => false,
                'isRedirect' => false,
                'isForbidden' => true,
            ],
            'create_page_admin_auth' => [
                'route' => '/portal/pages/new',
                'method' => 'POST',
                'isAuth' => true,
                'isAdmin' => true,
                'isRedirect' => true,
                'isForbidden' => false,
            ],
        ];
    }

    /**
     * @dataProvider pageProviderWithPortal
     */
    public function testPageWithPortal(string $route, string $method, bool $isAuth, bool $isAdmin, bool $isRedirect, bool $isForbidden)
    {
        $body = [
            'country_code' => $this->faker->countryCode(),
            'page_path' => $this->faker->word(),
            'content' => $this->faker->word(),
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

    function testCreatePage()
    {
        $body = [
            'country_code' => $this->faker->countryCode(),
            'page_path' => $this->faker->word(),
            'content' => $this->faker->word(),
        ];

        $this->client->loginUser($this->getLoginAdmin());
          
        $this->client->request('POST', '/portal/pages/new', $body);

        $createdPage = $this->portalRepository->findOneBy([
            'countryCode' => $body['country_code'],
        ]);

        $this->assertNotNull($createdPage);
        $this->assertEquals($body['page_path'], $createdPage->getPagePath());
        $this->assertEquals($body['content'], $createdPage->getContent());
    }
    
    function testCreatePageValidation()
    {
        $body = [
            'country_code' => 'pp',
            'page_path' => $this->faker->word(),
            'content' => $this->faker->word(),
        ];

        $this->client->loginUser($this->getLoginAdmin());
          
        $this->client->request('POST', '/portal/pages/new', $body);

        $createdPage = $this->portalRepository->findOneBy([
            'countryCode' => $body['country_code'],
        ]);

        $this->assertNull($createdPage);
    }
}
