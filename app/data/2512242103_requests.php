<?php

# requests

$up[] = 'CREATE TABLE nano_requests (
    id INTEGER PRIMARY KEY,

    request_id TEXT NOT NULL UNIQUE,
    created_at TEXT NOT NULL
);';

$down[] = 'DROP TABLE IF EXISTS nano_requests';
