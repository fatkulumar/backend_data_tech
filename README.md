<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Setup Laravel 11.x
[Laravel 10.x requires a minimum PHP version of 8.1](https://laravel.com/docs/11.x/releases)

## Installation
```bash
composer install
```
```bash
copy .env-example to .env
```

## Libraries
- [Laravel Breeze API](https://laravel.com/docs/10.x/starter-kits)

## Petunjuk Pembuatan End Point Postman
1. Buat Folder Auth untuk endpoint login di Postman, kemudian ketik kode ini di Pre-request Script untuk mendapatkan csrf-token sekaligus membuat environment variable
```
pm.sendRequest({
    url: 'http://localhost:8000/sanctum/csrf-token',
    method: 'GET'
}, function(error, response, {cookies}) {
    pm.collectionVariables.set('csrf-token', cookies.get('XSRF-TOKEN'))
})
```

2. Buat endpoint untuk melihat user yang sedang login, di dalam folder tersebut dengan url GET http://localhost:8000/api/user dengan header 
    Accept: application/json
    Referer: http://localhost:8000

3. Buat endpoint Login/(untuk semua endpoint) di dalam folder tersebut dengan header
    Accept: application/json
    X-XSRF-TOKEN: {{csrf-token}}

## Structure
- app
    - Console
    - DataTransferObject
    - Exception
    - Http
    - Models
    - Providers
    - Repositories
    - Services
    - Traits
        - Acessor
- bootstrap
- config
- database
    - factories
    - migrations
    - seeders
- public
- resources
    - css
    - js
    - views
        - email
- routes
- storage
    - app
    - framework
    - logs
- tests
