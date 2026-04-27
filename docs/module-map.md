# Module Map — Octopus

## Entry Point

* `index.php` → bootstraps the application

## Bootstrap

* `app/start.php`

  * loads config
  * initializes session
  * resolves and executes stream

## Core

Located in `app/Core/`

* `Web.php` → routing and response handling
* `Session.php` → session lifecycle
* `Access.php` → access control
* `Data.php` → data access layer

## Streams

Located in `app/streams/`

* File-based routing
* Each `*-stream.php` is a route
* Executed via `include`

Examples:

* `login-stream.php`
* `home-stream.php`

## Views

* `app/views/`
* Simple PHP templates

## Functions

* `app/functions/`
* Global helpers (use sparingly)

## Data

* `app/data/`
* migrations and metadata

## Storage

* `octopus.sqlite` → default database

---

## Execution Flow

1. Request hits `index.php`
2. Bootstrap via `start.php`
3. Route resolved by `Web::match()`
4. Stream file is included
5. Output is returned
