
# Keycloak WebDav 

This project is meant to be a WebDav Server using Keycloak roles for it's [Principals](https://sabre.io/dav/principals/).

## Requirements
 
- PHP
- [phpredis](https://github.com/phpredis/phpredis)
- Composer
- A running keycloak instance
- A Client with `Direct access grants` enabled
- A running Redis Instance

## Configuration

To configure the server, you just need to create a `.env` file following the example.

Do not forget to create the users_path, as well as to not forget to add a final `/` to the path.

## Installation

In order to start the server, simply run the following commands:

```sh
composer update
php -S localhost:8080 index.php 
```
