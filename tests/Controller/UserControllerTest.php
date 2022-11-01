<?php
namespace App\tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }

    /**
     * Testing user creation failure when email already exists in database
     */
    public function testforbiddenCreateUser(): void
    {
        $crawler = $this->client->request('GET', '/users/create');
        //finding the add_user button
        $buttonCrawlerNode = $crawler->selectButton('add_user');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['user[username]'] = 'Fabien';
        $form['user[password][first]'] = 'TestPwd2022!';
        $form['user[password][second]']= 'TestPwd2022!';
        $form['user[email]']= 'test@hotmail.com';
        $form['user[roles]']->select('ROLE_USER');


        // submit the Form object
        $this->client->submit($form);

        $this->assertStringContainsString(
            'Cet email a déjà un compte associé',
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * Tests a correct user creation
     */
    public function testCreateUserOk(): void
    {

        $crawler = $this->client->request('GET', '/users/create');
        //finding the add_user button
        $buttonCrawlerNode = $crawler->selectButton('add_user');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['user[username]'] = 'Fabien';
        $form['user[password][first]'] = 'TestPwd2022!';
        $form['user[password][second]']= 'TestPwd2022!';
        $form['user[email]']= 'test2@hotmail.com';
        $form['user[roles]']->select('ROLE_USER');


        // submit the Form object
        $this->client->submit($form);

        $this->assertStringContainsString(
            'utilisateur a bien été ajouté',
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * Test access to users list if admin
     */
    public function testListUserOk(): void
    {
        $this->loginUser(true);
        $crawler = $this->client->request('GET', '/admin/users');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test access to users list denied if not admin
     */
    public function testListUserNotAdmin(): void
    {
        $this->loginUser(false);
        $crawler = $this->client->request('GET', '/admin/users');
        $this->assertEquals(
            403,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Login a user for tests
     * @param $isAdmin true if need to log user that is admin
     */
    public function loginUser($isAdmin)
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        if ($isAdmin == true) {
            $testUser = $userRepository->findOneByEmail('mat@hotmail.com');
        } else {
            $testUser = $userRepository->findOneByEmail('test@hotmail.com');
        }

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
    }

    /**
     * Test access to user modification page is denied if not admin
     */
    public function testUserModifNotAdmin(): void
    {
        $this->loginUser(false);
        $crawler = $this->client->request('GET', '/admin/users/1/edit');
        $this->assertEquals(
            403,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Test access to user modification page is denied if not admin
     */
    public function testUserModifOk(): void
    {
        $this->loginUser(true);
        $crawler = $this->client->request('GET', '/admin/users/1/edit');
        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }


}
