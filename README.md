# EchoesVerse

This Laravel project is an API-only social media clone, designed to provide the foundational backend functionalities for
a social networking platform. It includes user management, post creation, like and comment functionalities, as well as
basic authentication and authorization mechanisms.

## Features

- **User Management**: Allows users to register, login, update their profiles, and delete their accounts.
- **Post Management**: Users can create, read, update, and delete their posts.
- **Like System**: Users can like posts.
- **Follow System**: Users can follow other users.
- **Comment System**: Users can comment on posts.
- **Authentication**: Basic authentication using sanctum tokens.
- **Authorization**: Ensures that users can only modify their own data.

## Installation

1. Clone the repository to your local machine:

2. Navigate to the project directory:

    ```bash
    cd EchoesVerse
    ```

3. Install dependencies using Composer:

    ```bash
    composer install
    ```

4. Create a copy of the `.env.example` file and rename it to `.env`. Update the necessary environment variables such as
   database credentials.

5. Generate an application key:

    ```bash
    php artisan key:generate
    ```

6. Run the database migrations and seed the database:

    ```bash
    php artisan migrate --seed
    ```

7. Serve the application:

    ```bash
    php artisan serve
    ```

## Using docker

1. Installing composer dependencies

  ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
   ``` 

2. Starting docker image

  ```bash
    ./vendor/bin/sail up
   ```

3. Install dependencies using Composer:

    ```bash
   ./vendor/bin/sail composer install
    ```

4. Create a copy of the `.env.example` file and rename it to `.env`. Update the necessary environment variables such as
   database credentials.

5. Generate an application key:

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6. Run the database migrations and seed the database:

    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

## Usage

- Register a new user using the `/api/register` endpoint with a `POST` request.
- Login using the `/api/login` endpoint with a `POST` request to obtain a sanctum token.
- Use the obtained token to authenticate subsequent requests by including it in the `Authorization` header as a Bearer
  token.
- Explore the available endpoints for user management, post management, likes, and comments as defined in the API
  documentation.

## API Documentation

The API endpoints and their functionalities are documented in detail. You can access the API documentation at
`/dos/api` after running the application.

## Contributing

Contributions are welcome! If you encounter any bugs or have suggestions for improvements, please feel free to open an
issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE). Feel free to use, modify, and distribute it as per the terms
of the license.
