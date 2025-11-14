# CampusConnect (Prototype)

Petit prototype Laravel pour gérer des réservations de salles et matériels sur un campus.

Pré-requis
- PHP 8.1+ (le projet a été testé sur PHP 8.2)
- Composer
- MySQL (base `connectcampus`)
- Node.js + npm (pour compiler les assets Tailwind/Vite)

Installation rapide (Windows / PowerShell)

1. Installer les dépendances PHP

```powershell
composer install
copy .env.example .env
php artisan key:generate
# configurer DB dans .env (DB_DATABASE=connectcampus, DB_USERNAME=root, DB_PASSWORD=)
```

2. Créer la base et exécuter les migrations

```powershell
php artisan migrate
php artisan storage:link
```

3. Installer les dépendances JS et compiler

```powershell
npm install
npm run dev
```

4. Seeders (comptes de démonstration)

```powershell
php artisan db:seed
```

Comptes fournis (seeders)
- admin@campus.local / password  (rôle `admin`)
- teacher@campus.local / password (rôle `teacher`)
- student@campus.local / password (rôle `student`)

Fonctionnalités implémentées
- Authentification (Laravel Breeze, Blade)
- Module 2 : Réservation de salles/matériels
  - Création de réservations (students/teachers)
  - Validation/approbation par les admins
  - Consultation des disponibilités de base (blocage des chevauchements)
- Seeders, tests PHPUnit (Feature) pour les flux principaux

Structure importante
- `app/Models` : `Room`, `Equipment`, `Reservation`, `User`
- `app/Http/Controllers` : controllers pour rooms/equipments/reservations
- `app/Policies/ReservationPolicy.php` : règles d'autorisation
- `resources/views` : vues Blade (layout Tailwind)

Tests

```powershell
./vendor/bin/phpunit --testsuite=Feature
```

Notes
- Tailwind est utilisé pour un style rapide (Breeze + Vite). Si `npm` n'est pas installé, installe Node.js LTS et réexécute `npm install`.
- Sanctum est installé pour prise en charge future d'API/token.

Prochaines améliorations possibles
- Module 3 : Gestion de projets étudiants (upload de fichiers, membres)
- Interface d'administration plus complète (filtres, calendrier)
- Notifications par email lors de l'approbation/rejet

---
Livré par l'équipe de développement — prêt pour continuer le Module 3.
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
