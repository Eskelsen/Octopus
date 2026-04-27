# Coding Rules — Octopus

## General

* Keep files small and readable
* Avoid unnecessary abstractions
* Prefer procedural over OOP when simpler

---

## Streams

* Must be direct and explicit
* Avoid hidden logic
* Prefer inline logic over indirection

---

## Functions

* Use only when reused
* Avoid “god helpers”

---

## Naming

* Streams: `kebab-case-stream.php`
* Variables: `$snake_case`
* Functions: `snake_case()`

---

## Database

* Keep queries simple
* Avoid ORM-like abstractions
* Use `octopus_` table names for application data

---

## Error Handling

* Be explicit
* Avoid silent failures

---

## Don’ts

* No premature abstraction
* No deep nesting
* No “clean architecture” patterns
