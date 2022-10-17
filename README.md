# MTS TASK

## Installation
1. Clone the project locally on your machine by running in your terminal `git clone https://github.com/marcmedhat6211/mts-task.git`
2. CD to the project's directory by running `cd mts-task/`
3. If you are at the root of your directory run `composer install` to install all the dependencies for the project

## Usage
- Please add your database environment variables at the .env file which is located at the root directory of the project (please note that they are all required fields)
  - An example of the database environment variables is: </br>
    `DB_HOST=localhost`</br>
    `DB_NAME=mts`</br>
    `DB_USER=root`</br>
    `DB_PASSWORD=`</br>
    `DB_SERVER=mysql:host`</br>

**Now you are all done, I made 2 options for this to work(you can choose whatever you like) :** 
1. **Auto Creation:** All you have to do hit the route `{DOMAIN}/mts-task/public/` which will then perform many things:
   1. The database tables are going to be created
   2. If any error happened during the process, a revert is going to happen and all tables are going to be removed
   3. Then It will access the data.xlsx file which is located at `mts-task/files/data.xlsx` and it will fetch all the data from it
   4. Then It will insert all the data in the database
   5. Then It will print messages on the screen describing the process that happened
2. **Manual Creation:** These are small steps at a time which you can perform them at the following order
    1. Hit the route `{DOMAIN}/mts-task/public/action/drop` to drop the database tables if exists
    2. Hit the route `{DOMAIN}/mts-task/public/action/create` to create the database tables
    3. Hit the route `{DOMAIN}/mts-task/public/action/sync` to sync all the data from the Excel file to the database
    4. Hit the route `{DOMAIN}/mts-task/public/action/print` to print the JSON data to the user browser 
  
## Features Brief
- Created a MVC structure
- Used psr-4
- Created a DotEnv class to handle the .env environment variables (class located at `mts-task/src/Core/DotEnv.php`)
- Created a Container class to handle the Dependency Injection across the project

## Extra Files Includes
1. Manually created ERD file located at `mts-task/ERD.png`
2. Auto created ERD file by phpmyadmin at `mts-task/erd_from_phpmyadmin.svg`