# ToDoAndCo app

### 1.Clone the repository
run `git clone https://github.com/Ma77hieu/TodoAndCo2.git`

### 2.Install required libraries
run `composer install`

### 3.Configure your database inside the .env file
place the correct values in the DATABASE_URL constant of the .env file

### 4.Create your database
run `php bin/console doctrine:database:create`

### 5.Apply the project migrations
in order to create the database tables, run
`php bin/console doctrine:migrations:migrate`

### 6.Launch the server
in order to launch the local server, run
`symfony serve`

### 7. Tests configuration and fixtures
Create a .env.test.local file by copying the .env.test file. 
Add the DATABASE_URL constant corresponding to your test database
You can find the content of the test database corresponding to the tests in the file
testDbFixture/TodoAndCo_test.sql

### 8. Run tests with coverage report
In order to run all the required tests,
run  `symfony php bin/phpunit --testsuite alltests --coverage-html coverageReportHtml`
To view the coverage report, open the dashboard.html file inside the coverageReportHtml directory