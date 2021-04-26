# P8_Symfony_ToDoList

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/bbd00fac4cae488cbafaf83269af7ce2)](https://app.codacy.com/gh/SiProdZz/P8_Symfony_ToDoList?utm_source=github.com&utm_medium=referral&utm_content=SiProdZz/P8_Symfony_ToDoList&utm_campaign=Badge_Grade_Settings)

PROJECT 8 ToDoList - Upgrade the quality code and add some fonctionnality of [ToDoList](https://github.com/saro0h/projet8-TodoList) project

## OBJECTIF 
<https://openclassrooms.com/fr/paths/59/projects/44/assignment>

## CONTENT PROJECT
-   UML Diagrams
-   Entity and fixtures to complete your database
-   Development project (use Issue & Pull request)
-   Some documentations that helps manage the project

## Prerequisite in your workplace
-   Php 7.4  (x64 Non Thread Safe) or (x86 for 32 bits versions) <https://windows.php.net/download#php-7.4>
-   Composer  <https://getcomposer.org/download/> (to manage dependencies and libraries)
-   Symfony command <https://symfony.com/doc/current/the-fast-track/fr/1-tools.html#symfony-cli>

## HOW TO INSTALL

### Step 1 : Copy the repository in your workplace
<code>git clone https://github.com/SiProdZz/P8_Symfony_ToDoList</code>

### Step 2 : Connect your project at your database
-   Create a file ".env.local" in the same directory as ".env" and complet it to acces at your database

### Step 3 : Install all dependencies into the project
<code>composer install</code>

### Step 4 : Create your database and add Fixtures
<code>composer prepare</code>

### Step 5 : Run symfony server and open your project
<code>symfony server:start</code>

## Manipulate test

### Connect your project at your database test
-   Create a file ".env.test.local" in the same directory as ".env.test"
-   Insert the same connexion database at ".env" file and add the prefix "_test" at the end of your database name.

### Create the database for test
<code>composer prepare-test</code>

### Run test
Use <code>vendor/bin/phpunit</code>