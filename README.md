# Eskimi
This is a very simple SSP dashboard. Advertising campaigns can be created, edited and view.


## Getting Started

### Dependencies and Installation

Git clone the application on your local machine. 

CD into the cloned directory, copy and rename the .env.example file .env

Supply the parameters to your database to the .env file

1. ``compser install`` to install vendor packages

2. ``php artisan key:generate``

3. ``npm install`` to install npm packages


### Setting up Docker

cd into the laradock folder of the application and 

1. copy and rename the .env.example file .env

2. Supply the neccessary environment variable

3. run ``docker-compose up -d nginx mysql`` to install docker dependencies


### Running the Application

cd into the root of the application and  run ``php artisan migrate`` database migration

To build the application run ``npm run production`` in the root of the application

To view the application, go to your browser and type ``http://app-name``


### Loading Seeders

To load seeders, cd into the root of the app and run ``php artisan db:seed``
