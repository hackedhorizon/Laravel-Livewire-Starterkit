[![codecov](https://codecov.io/github/hackedhorizon/Laravel-Livewire-Starterkit/graph/badge.svg?token=F1JJKTA809)](https://codecov.io/github/hackedhorizon/Laravel-Livewire-Starterkit)
[![tests](https://github.com/hackedhorizon/Laravel-Livewire-Starterkit/actions/workflows/build.yml/badge.svg)](https://github.com/hackedhorizon/Laravel-Livewire-Starterkit/actions/workflows/build.yml)
[![code style](https://github.com/hackedhorizon/Laravel-Livewire-Starterkit/actions/workflows/php-code-style.yml/badge.svg)](https://github.com/hackedhorizon/Laravel-Livewire-Starterkit/actions/workflows/php-code-style.yml)

# Simple Authentication Scaffolding with Laravel Livewire

This repository contains a Laravel Livewire application featuring registration and authentication functionality. It's akin to Breeze + Livewire but without the Volt syntax.

## Features

-   **Registration**: Users can register with their name, username, email, and password. Real-time registration validation, including field validation and reCAPTCHA verification, is implemented.
-   **Rate Limiter**: Every component can be rate-limited using the RateLimiterService.
-   **Language Switching**: Users can select their preferred language using the language switcher component. Their language preference is stored in the session and, if authenticated, in the database. It supports Hungarian and English languages by default.
-   **Event Handling**: Failed login attempts trigger an event that is listened to by the FailedLoginAttemptListener, which logs details of the attempt and stores it in the database. Most of these features are optional and can be turned off in the `.env` file.
-   **Customizable Design**: A minimal design is provided, allowing for easy customization.

## Configuration

-   **Recaptcha**: Configure Recaptcha settings in the `.env` file (`RECAPTCHA_SITE_KEY` and `RECAPTCHA_SECRET_KEY`).
-   **Language Localization**: Update the available locales and their translations in the `config/app.php` file.
-   **Email Verification**: Configure email verification settings in the `.env` file (`MAIL_*` variables).

## Installation with Docker

1. After cloning the repository to your local machine, create the `.env` file:

    ```
    cp .env.example .env
    ```

2. Open the `.env` file and modify the following:

    ```
    DB_USERNAME=sail
    DB_PASSWORD=password
    DB_HOST=mysql
    ```

3. Enable/disable functionalities:

    ```
    EMAIL_VERIFICATION=true
    GOOGLE_RECAPTCHA=true
    LOCALIZATION=true
    GOOGLE_RECAPTCHA_SITE_KEY=YOUR_SITE_KEY
    GOOGLE_RECAPTCHA_SECRET_KEY=YOUR_SECRET_KEY
    ```

4. Install Docker dependencies:

    ```
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

5. Start the application, generate an app key, install npm packages, and migrate database tables:

    ```
    sail up -d
    sail artisan key:generate
    sail npm i
    sail artisan migrate
    ```

6. Creating a default user:
   If you want to create a default user for testing purposes, seed the database with the following command:

    ```
    sail artisan db:seed
    ```

    This will create a default user with the following credentials:

    - username: test
    - password: password

## Run tests

```
sail artisan test
```

## Contributing

Contributions are welcome!

## License

This project is licensed under the MIT License.
