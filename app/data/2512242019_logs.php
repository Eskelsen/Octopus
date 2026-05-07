<?php

# logs

$up[] = 'CREATE TABLE nano_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,

    user_id INTEGER,
    message TEXT NOT NULL,

    created_at TEXT NOT NULL
);';

$down[] = 'DROP TABLE IF EXISTS nano_logs';
