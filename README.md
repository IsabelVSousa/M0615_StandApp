# Stand App

## Overview

Stand App is a PHP web application for discovering, booking, and managing stand-up comedy shows. It connects standard users with organizers (admins) who publish events, allowing users to explore the lineup and book their tickets easily.

**Authors:** Isabel Sousa / Mauricio Patiño  
**Module:** MP0487 / M0615 — Entorns de desenvolupament / UX·UI  
**School:** Stucom · Group DAM1/DAW1

---

## Features

### Standard User
- Registration with email, phone and password validation
- Login and logout with PHP session
- View and update personal profile (name and password)
- Browse all available events
- View event details
- Book tickets for events
- Delete account

### Admin User (Organizer)
- Registration with NIF/NIE/CIF and mandatory profile photo
- Login and logout with PHP session
- View organizer profile
- Create events with image, description, location and date
- Edit and delete their own events
- View their events in the profile panel
- Delete account

### Home Page
- Dynamic upcoming events carousel loaded from the database
- Non-logged users see only the 3 nearest upcoming events
- Logged-in users see all events
- "Discover more" link redirects to login if no session, or to all events if logged in

---

## Project Structure

```
M0615_StandApp/
├── Controller/
│   ├── UserController.php       ← login, logout, register, register_Admin, updateProfile, deleteProfile
│   ├── EventController.php      ← create, readAll, readAllPublic, readOne, update, delete
│   └── EntradaController.php    ← reservar, getMisReservas
├── Model/
│   └── MF_StandApp.sql          ← database schema
└── View/
    ├── Home.php                 ← landing page with events carousel
    ├── LogIn.php                ← login form
    ├── register.php             ← standard user registration
    ├── register_Admin.php       ← organizer/admin registration
    ├── perfil.php               ← standard user profile
    ├── perfil_admin.php         ← organizer profile with event management
    ├── Event.php                ← event detail and booking
    ├── event_create.php         ← event creation form (admin only)
    ├── event_edit.php           ← event edit form (admin only)
    └── eventos_todos.php        ← full event listing (logged-in users only)
```

---

## Database

The `standapp` database contains the following tables:

| Table | Description |
|---|---|
| `persona` | System users (admin and standard) |
| `evento` | Events created by admins |
| `entrada` | Bookings made by standard users |
| `perfil` | Profile associated with each person |
| `valorar` | Event ratings |

---

## How It Works

### Class Diagram

```mermaid
classDiagram
    class User {
        +string IDPersona
        +string tipo
        +string nombreApellido
        +string telefono
        +string email
        +string contraseña
        +string imagen
    }

    class UserController {
        -PDO conn
        +__construct()
        +login(): void
        +lougout(): void
        +register(): void
        +register_Admin(): void
        +updateProfile(): void
        +deleteProfile(): void
    }

    class Evento {
        +string IDEvento
        +string descripcion
        +string descripcion_larga
        +string comediante
        +datetime fechahora
        +string ubicacion
        +string imagen_evento
        +string IDPersona
    }

    class EventController {
        -PDO conn
        +__construct()
        +create(): void
        +readAll(): array
        +readAllPublic(): array
        +readOne(idEvento): array
        +update(): void
        +delete(): void
    }

    class Entrada {
        +string IDEntrada
        +string IDPersona
        +string IDEvento
        +int precio
        +timestamp fecha_reserva
    }

    class EntradaController {
        -PDO conn
        +__construct()
        +reservar(): void
        +getMisReservas(idPersona): array
    }

    UserController --> User : manages
    EventController --> Evento : manages
    EntradaController --> Entrada : manages
    Entrada --> User : belongs to
    Entrada --> Evento : belongs to
    Evento --> User : created by
```

---

### Login Sequence Diagram

```mermaid
sequenceDiagram
    actor User
    participant LogInPage as LogIn.php
    participant Controller as UserController
    participant Database as MySQL

    User->>LogInPage: Enter email and password
    LogInPage->>Controller: POST UserController.php (login)
    Controller->>Database: SELECT IDPersona, nombreApellido, email, contraseña, tipo FROM persona WHERE email = ?
    Database-->>Controller: user row

    alt user found
        Controller->>Controller: password_verify(psw, hash)
        alt correct password
            Controller->>Controller: store SESSION (IDPersona, nombre, email, tipo)
            alt tipo === admin
                Controller->>User: redirect to perfil_admin.php
            else tipo === standard
                Controller->>User: redirect to perfil.php
            end
        else wrong password
            Controller->>User: redirect to LogIn.php?error=1
        end
    else user not found
        Controller->>User: redirect to LogIn.php?error=2
    end
```

---

### Register Sequence Diagram — Standard User

```mermaid
sequenceDiagram
    actor User
    participant RegisterPage as register.php
    participant Controller as UserController
    participant Database as MySQL

    User->>RegisterPage: Fill in registration form
    RegisterPage->>Controller: POST UserController.php (register)

    Controller->>Controller: validate empty fields
    Controller->>Controller: validate email format (filter_var)
    Controller->>Controller: validate phone (preg_match ^6[0-9]{8}$)
    Controller->>Controller: validate password length (8-20 chars)
    Controller->>Controller: validate both passwords match

    alt validation fails
        Controller->>User: redirect to register.php?error=...
    else validation OK
        Controller->>Database: SELECT IDPersona FROM persona WHERE email = ?
        Database-->>Controller: result

        alt email already registered
            Controller->>User: redirect to register.php?error=email_ya_registrado
        else email available
            Controller->>Controller: password_hash(psw, PASSWORD_DEFAULT)
            Controller->>Database: INSERT INTO persona (IDPersona, tipo, nombreApellido, telefono, email, contraseña)
            Database-->>Controller: success
            Controller->>User: redirect to Home.php?exito=registro_ok
        end
    end
```

---

### Register Sequence Diagram — Admin

```mermaid
sequenceDiagram
    actor Admin
    participant RegisterPage as register_Admin.php
    participant Controller as UserController
    participant Database as MySQL
    participant FileSystem as Server

    Admin->>RegisterPage: Fill in form with NIF and profile photo
    RegisterPage->>Controller: POST UserController.php (register_Admin)

    Controller->>Controller: validate empty fields
    Controller->>Controller: validate mandatory image
    Controller->>Controller: validate NIF/NIE/CIF (preg_match)
    Controller->>Controller: validate email format
    Controller->>Controller: validate phone number
    Controller->>Controller: validate password (8-20 chars)
    Controller->>Controller: validate both passwords match

    alt validation fails
        Controller->>Admin: redirect to register_Admin.php?error=...
    else validation OK
        Controller->>Database: SELECT IDPersona FROM persona WHERE email = ?
        Database-->>Controller: result

        alt email already registered
            Controller->>Admin: redirect to register_Admin.php?error=email_ya_registrado
        else email available
            Controller->>Controller: password_hash(psw, PASSWORD_DEFAULT)
            Controller->>Database: INSERT INTO persona (IDPersona, tipo, nombreApellido, telefono, email, contraseña)
            Database-->>Controller: success
            Controller->>FileSystem: move_uploaded_file → img/perfiles/
            Controller->>Admin: redirect to Home.php?exito=registro_ok
        end
    end
```

---

## Technical Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- XAMPP (Apache + MySQL)
- PDO extension enabled

## Installation

1. Clone the repository into `htdocs/`:
```
git clone https://github.com/IsabelVSousa/M0615_StandApp.git
```

2. Import the schema in phpMyAdmin:
   - Open `http://localhost/phpmyadmin`
   - Create the `standapp` database
   - Import the file `Model/MF_StandApp.sql`

3. Access the application:
```
http://localhost/M0615_StandApp/View/Home.php
```
