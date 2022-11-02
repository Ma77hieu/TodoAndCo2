<?php
namespace App\tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }

    /**
     * Tests a correct user login
     */
    public function testLoginOk(): void
    {
        $crawler = $this->client->request('GET', '/login');
        //finding the add_user button
        $buttonCrawlerNode = $crawler->selectButton('login_btn');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['_username'] = 'user1';
        $form['_password']= 'Test42!';

        // submit the Form object
        $this->client->submit($form);

        $this->assertStringContainsString(
            'Se déconnecter',
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * Tests an incorrect user login
     */
    public function testLoginNotOk(): void
    {
        $crawler = $this->client->request('GET', '/login');
        //finding the add_user button
        $buttonCrawlerNode = $crawler->selectButton('login_btn');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['_username'] = 'user28';
        $form['_password']= 'password';

        // submit the Form object
        $this->client->submit($form);

        $this->assertStringContainsString(
            'Invalid credentials.',
            $this->client->getResponse()->getContent()
        );
    }
}