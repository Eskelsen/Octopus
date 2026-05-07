<?php

# users

$up[] = 'CREATE TABLE nano_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,

    name TEXT NOT NULL,

    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,

    status TEXT NOT NULL DEFAULT "active", -- active blocked suspended

    created_at TEXT NOT NULL
);';

$down[] = 'DROP TABLE IF EXISTS nano_users';
