# Restaurant Api

Application made for recruitment process.

This application will be an api for searching restaurant from data source

### Prerequisites

- Composer
- PHP 7.2 

### Installing

    _$ git clone https://github.com/bartlomiejmarszal/RestaurantApi.git
    _$ composer install
    _$ bin/console doctrine:migrations:migrate
    
### Testing

`PhpUnit` is included in project files. 

To run test please follow instructions below

### Import Database
Place import data file in dircetory below:
       
       var/data_source/backend-data.json
       
### Usage

Application provide endpoint that list restaurants from database. 
User may use paramters to filter results

Available filters:
- `name={string}` filtering restaurants by name
- `city={string}` filtering restaurants by city
- `cuisine={string}` filtering restaurants by cuisine
- `search={string}` filtering restaurants by name, city and cuisine
- `location={float,float,integer}` filtering restaurants by given location and distance desired to look in