# Jobsity PHP Stock API Challenge

A modern, production-ready PHP REST API for stock information, built with [Slim Framework](https://www.slimframework.com/) and following SOLID, Clean Code, and PSR-12 standards. The app features JWT authentication, asynchronous email notifications, Dockerized infrastructure, and a robust, testable architecture.

---

## Table of Contents
- [Features](#features)
- [Architecture](#architecture)
- [SOLID, Clean Code & Best Practices](#solid-clean-code--best-practices)
- [Getting Started](#getting-started)
- [Environment Variables](#environment-variables)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [License](#license)

---

## Features
- **User Registration & JWT Authentication**
- **Stock Querying** via [stooq.com](https://stooq.com/)
- **Query History** per user
- **Asynchronous Email Notifications** (RabbitMQ + SwiftMailer)
- **Dockerized** for easy setup
- **PSR-12, SOLID, Clean Code**
- **Unit/Integration Testing** (PHPUnit)
- **Postman Collection** for quick API testing

---

## Architecture
- **Slim 4**: Lightweight, fast, and PSR-7/PSR-15 compliant
- **Eloquent ORM**: Database abstraction
- **Dependency Injection**: [PHP-DI](https://php-di.org/)
- **Async Processing**: RabbitMQ for email notifications
- **Logging**: Monolog
- **Validation**: awurth/slim-validation
- **Testing**: PHPUnit
- **Strict separation** of Controllers, Domain, and Infrastructure

**Directory Structure:**
```
├── app/            # DI, routes, middleware
├── assets/         # Postman collection
├── db/             # Migrations, seeds
├── public/         # Entry point (index.php)
├── src/            # Application code
│   ├── Controllers/
│   └── Domain/
├── tests/          # PHPUnit tests
├── docker-compose.yml
├── composer.json
├── setup-env.sh
└── ...
```

---

## SOLID, Clean Code & Best Practices
- **Single Responsibility**: Each class has one responsibility (e.g., `StockHistoryController`, `MailService`).
- **Open/Closed**: Easily extendable via interfaces and dependency injection.
- **Liskov Substitution**: Domain models and repositories are swappable.
- **Interface Segregation**: Services and repositories are decoupled.
- **Dependency Inversion**: All dependencies injected via constructors.
- **PSR-12**: All code formatted to PSR-12 standard.
- **Type Safety**: Strict types and type hints throughout.
- **Error Handling**: Consistent, secure error responses.
- **Environment Variables**: All secrets/configs via `.env`.

---

## Getting Started

### Prerequisites
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Composer](https://getcomposer.org/)

### Quick Start
```sh
composer setup
```
This will:
- Copy `.env.sample` to `.env`
- Start all Docker containers (PHP, MySQL, RabbitMQ)
- Install dependencies
- Run migrations and seeders

### Manual Setup
1. Copy `.env.sample` to `.env` and fill in your environment variables.
2. Run `docker compose up -d`
3. Run migrations and seeds:
   ```sh
   docker exec jobsity-slim-1 bash -c "composer install; composer dump-autoload; vendor/bin/phinx migrate; vendor/bin/phinx seed:run -s UserSeeder; exit"
   ```

---

## Environment Variables
See `.env.sample` for all required variables. Key variables:
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, ...
- `JWT_SECRET`: Secret for JWT signing
- `MAILER_HOST`, `MAILER_PORT`, `MAILER_USERNAME`, `MAILER_PASSWORD`: SMTP config
- `RMQ_HOST`, `RMQ_PORT`, `RMQ_USERNAME`, `RMQ_PASSWORD`, `RMQ_VHOST`: RabbitMQ config

---

## API Documentation

### Authentication
| Endpoint         | Method | Body                                    | Description                |
|------------------|--------|-----------------------------------------|----------------------------|
| `/users`         | POST   | `{ "email": string, "password": string }` | Register a new user        |
| `/login`         | POST   | `{ "email": string, "password": string }` | Obtain JWT access token    |

### Stock & History (Require JWT)
Add header: `Authorization: Bearer <token>`

| Endpoint         | Method | Query Params         | Description                                 |
|------------------|--------|---------------------|---------------------------------------------|
| `/stock`         | GET    | `q` (e.g. aapl.us)  | Query stock info, triggers email notification|
| `/history`       | GET    |                     | Get your stock query history                |

### Utility
| Endpoint         | Method | Description         |
|------------------|--------|---------------------|
| `/hello/{name}`  | GET    | Test endpoint       |
| `/bye/{name}`    | GET    | Test endpoint (JWT) |

### HTTP Status Codes
- `200`: Success
- `201`: Resource created
- `400`: Bad request
- `401`: Unauthorized
- `404`: Not found

### Example Requests
See [`assets/Jobsity.postman_collection.json`](assets/Jobsity.postman_collection.json) for ready-to-use Postman requests.

---

## Asynchronous Email Notifications
- When you query `/stock`, the result is sent to your email asynchronously via RabbitMQ.
- To run the consumer (in a separate terminal):
  ```sh
  docker exec -it jobsity-slim-1 php consumer.php
  ```

---

## Testing
- Run all tests:
  ```sh
  composer test
  ```
- Tests are located in the `tests/` directory.
- PHPUnit configuration: `phpunit.xml`

---

## Troubleshooting
- **Ports in use**: Ensure 8080 (API), 3306 (MySQL), 5672/15672 (RabbitMQ) are free.
- **.env issues**: Double-check all environment variables are set.
- **Database**: If migrations fail, try `docker-compose down -v` to reset volumes.
- **Mail**: Use [Mailtrap](https://mailtrap.io/) or similar for SMTP testing.

---

## License
[MIT License](LICENSE)

---

## Author
Moises Garcia (<devchallengermoises@gmail.com>)

---

## Contributing
PRs welcome! Please follow PSR-12 and SOLID principles. Add tests for new features.
