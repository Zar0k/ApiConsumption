# ApiConsumption

## Setup

- composer install

### Usage with one parameter

 - php index.php Spain

### [Output]
 - Country language code: ES
 - Spain speaks same language with these countries: Uruguay, Bolivia, Argentina..

### In case of two parameters given, application tells if the countries talking the same language or not
  
  - php index.php Spain Poland
  
### [Output]
  - Spain and Poland do not speak the same language
  
### Unit tests:

  - ./vendor/bin/phpunit tests

