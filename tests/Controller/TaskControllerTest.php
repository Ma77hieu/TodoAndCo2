<?php
namespace App\tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }

    /**
     * Check if task list is working when user logged in
     */
    public function testListTaskOk(): void
    {
        $this->loginUser(true);
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertStringContainsString(
            'Pressing au coin de la rue',
            $this->client->getResponse()->getContent()
        );
    }
    /**
     * Check if task list is working when user NOT logged in
     */
    public function testListTaskNotOk(): void
    {
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertStringContainsString(
            'pour accéder à votre liste de tâches',
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * Tests when a user not logged in try to create task
     */
    public function testCreateTaskNotOk(): void
    {
        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertStringContainsString(
            '<form action="/login_check" method="post">',
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * Tests a correct task creation
     */
    public function testCreateTaskOk(): void
    {
        $this->loginUser(false);
        $crawler = $this->client->request('GET', '/tasks/create');
        //finding the add_user button
        $buttonCrawlerNode = $crawler->selectButton('add_task');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['task[title]'] = 'rdv dentiste';
        $form['task[content]']= 'Lundi 22 Oct 21, docteur machoire, 3 rue de la couronne';

        // submit the Form object
        $this->client->submit($form);

        $this->assertStringContainsString(
            'La tâche a été bien été ajoutée.',
            $this->client->getResponse()->getContent()
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

}
