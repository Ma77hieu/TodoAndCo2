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

### 6. Tests configuration
Create a .env.test.local file by copying the .env.test file. 
Add the DATABASE_URL constant corresponding to your test database

### 7. Run tests with coverage report
In order to run all the required tests,
run  `symfony php bin/phpunit --testsuite alltests --coverage-text`