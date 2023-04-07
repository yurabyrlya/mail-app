### Project Title

**Introduction**

This is a sample project to demonstrate the steps required to run the project on your local machine using Docker and Laravel.

**Installation**

## How to Run the Application

1. Run the following command to start the containers:

`docker-compose up -d`


2. Access the `mail-app` container terminal


3. Run the following command to run the database migrations inside of container:

`php artisan migrate`


4. Open the application in a web browser by navigating to [http://localhost:8000](http://localhost:8000).

## How to Run the Tests

Run the following command to run the tests within the container:

`php artisan test`

PS:
SQL dump DB file in main app directory
`db_2023-04-07.sql`



