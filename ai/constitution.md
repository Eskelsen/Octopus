# AI Constitution — Octopus

## Purpose

Guide AI agents to produce code aligned with Octopus: an effort management system forked from Nanoframework.

## Core Doctrine

Octopus inherits the Nanoframework doctrine. All outputs MUST follow:

* **Crueza (Rawness)**
  Prefer native PHP features. Avoid abstractions, wrappers, and unnecessary layers.

* **Explicitude (Explicitness)**
  Code must be direct and readable. Side effects must be visible.

* **Planicidade (Flatness)**
  Avoid deep structures, nested layers, or excessive indirection.

---

## Architectural Constraints

* No controllers, services, or repositories unless strictly necessary
* No dependency injection containers
* No complex routing systems
* No class hierarchies for simple flows

## Product Direction

* Effort is the central object of the system
* Prefer clear records of work, time, responsibility, status, and evidence
* Keep workflows visible in streams instead of hiding them behind generic layers
* Domain words should be concrete: effort, task, project, person, estimate, log, status

---

## Preferred Patterns

* File-based routing via `streams/`
* Direct execution with minimal indirection
* Simple functions over classes when possible
* Return arrays or simple objects

---

## Forbidden Patterns

* Overengineering
* Laravel/Symfony-like structures
* Excessive OOP abstraction
* Hidden side effects

---

## Stream Rules

* Streams are the entrypoint of logic
* Named as: `*-stream.php`
* Must be simple, direct, and readable
* Avoid splitting logic unless necessary

---

## Decision Heuristics

When in doubt:

1. Choose the simplest solution
2. Prefer duplication over abstraction
3. Prefer clarity over reuse
4. Prefer explicit flow over magic

---

## Output Expectations

AI must:

* Produce minimal, working code
* Avoid introducing new layers
* Respect existing structure
* Not "improve" architecture beyond doctrine
