<?php
namespace App\tests;

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
}
