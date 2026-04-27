<?php

# requests

$up[] = 'CREATE TABLE octopus_requests (
    id INTEGER PRIMARY KEY,

    request_id TEXT NOT NULL UNIQUE,
    created_at TEXT NOT NULL
);';

$down[] = 'DROP TABLE IF EXISTS octopus_requests';
