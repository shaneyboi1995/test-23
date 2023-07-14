# totallywicked/test-23

**This project will be based primarily on your ability to fulfill the task 
requirements. Any potential design skills are a bonus, but usability, 
performance and security will be taken into account.**

## Introduction
This project provides a starting point which will allow you to create your own 
web-based encyclopedia based on the cartoon Rick and Morty.

## Project Requirements
To get started, you'll need the following:

 - PHP
 - [Composer](https://getcomposer.org/)
 - git
 
 You are free to use whatever composer packages, php frameworks and front-end libraries that you 
 wish as long as **you use your OWN php solution for API communication**.

## Task Requirements
In order to complete this challenge, you MUST create an encyclopedia with minimal 
functionality using php in an object oriented manner and having corect MVC structure.

Your solution MUST allow the user to browse the full list of Rick and Morty characters 
in a convenient manner (pagination), as well as offer some form of search and filtering functionality. 

Your solution MUST also display basic information for a specific character, including:

 - At least one image
 - Name
 - Species
 - Origin
 - Episodes in which they appear
 
A RESTful API is available at [Rick and Morty](https://rickandmortyapi.com/) 
which will provide you with all the data that you will need. You do not need 
to create an account nor authenticate in order to consume the API, however please 
be aware that this API is rate-limited, so that can also be addressed in your code.

You must consider your project to be "production-ready" on submission, therefore please 
address any potential risks and attack vectors.
 
It is advised that you spend no more than two to three hours on this assignment.
 
## Submission
To submit your solution, please fork this repository, commit your changes and provide us a link 
to your finished version. You don't have to host it anywhere, we should be 
able to just run it from your code.

Please keep commiting changes as you go.

## Copyright
All trademarks as the property of their respective owners.

## Project setup
- Composer install
- setup webserver using either apache or nginx
- app has a dependency on redis so an redis instance needs setting up
- set the redis details in the .env file
- sit back, relax and enjoy!

