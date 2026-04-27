# Anti-Patterns — Nanoframework

## Overengineering

Creating layers like:

* Controllers
* Services
* Repositories

❌ Not allowed

---

## Hidden Logic

* Magic methods
* Indirect execution
* Dynamic resolution without clarity

---

## Deep Nesting

```php
if (...) {
  if (...) {
    if (...) {
```

❌ Avoid

---

## Generic Helpers Explosion

* Too many global functions
* Unclear responsibilities

---

## Framework Emulation

Trying to recreate:

* Laravel
* Symfony

❌ This is NOT the goal

---

## Premature Reuse

Abstracting too early instead of duplicating
