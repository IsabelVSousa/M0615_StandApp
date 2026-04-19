# Stand App

## Overview

Stand App is a PHP web application for user authentication and registration. It includes:

- `LogIn.php` for user login.
- `register.php` for standard user registration.
- `register_Admin.php` for admin/organizer registration.
- `Controller/UserController.php` for authentication and registration logic.
- A MySQL table `persona` used to store user data.

## Project Structure

- `Controller/`
  - `UserController.php`: handles login, logout, register, and register admin requests.
- `View/`
  - `LogIn.php`: login page and form.
  - `register.php`: standard user registration page.
  - `register_Admin.php`: organizer/admin registration page.
  - `perfil.php`: user profile page after login.
  - `Home.php`: landing page after successful registration.
- `Model/`
  - `MF_StandApp.sql`: database schema and seed data.

## User Class Diagram

```mermaid
classDiagram
    class User {
        +string IDPersona
        +string tipo
        +string nombreApellido
        +string telefono
        +string email
        +string contrasenia
    }
    class UserController {
        -mysqli conn
        +__construct()
        +login(): void
        +lougout(): void
        +register(): void
        +register_Admin(): void
    }
    UserController --> User : manages
```

### Notes

- The database entity in the application is stored in the `persona` table.
- `tipo` distinguishes standard users from admin/organizer users.

## Login Sequence Diagram

```mermaid
sequenceDiagram
    actor Usuario
    participant LogInPage as LogIn.php
    participant Controller as UserController
    participant Database as MySQL

    Usuario->>LogInPage: Completa correo y contraseña
    LogInPage->>Controller: POST /Controller/UserController.php (login)
    Controller->>Database: SELECT persona WHERE email = ?
    Database-->>Controller: fila de usuario
    Controller->>Controller: verifica contraseña
    alt contraseña correcta
        Controller->>Usuario: redirige a ../View/perfil.php
    else contraseña incorrecta
        Controller->>Usuario: redirige a ../View/LogIn.php?error=1
    end
```

## Register Sequence Diagram

```mermaid
sequenceDiagram
    actor Usuario
    participant RegisterPage as register.php
    participant Controller as UserController
    participant Database as MySQL

    Usuario->>RegisterPage: Completa formulario de registro
    RegisterPage->>Controller: POST /Controller/UserController.php (register)
    Controller->>Controller: valida campos, email, teléfono, contraseña
    alt validación OK
        Controller->>Database: SELECT IDPersona FROM persona WHERE email = ?
        Database-->>Controller: resultado de existencia
        alt email no existe
            Controller->>Database: INSERT INTO persona (...)
            Database-->>Controller: éxito
            Controller->>Usuario: redirige a ../View/Home.php?exito=registro_ok
        else email ya registrado
            Controller->>Usuario: redirige a ../View/register.php?error=email_ya_registrado
        end
    else validación falla
        Controller->>Usuario: redirige a ../View/register.php?error=...
    end
```

