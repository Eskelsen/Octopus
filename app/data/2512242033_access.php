<?php

# access

$up[] = 'CREATE TABLE octopus_access (
    id INTEGER PRIMARY KEY,

    request_id TEXT NOT NULL,
    user_id INTEGER,

    method TEXT NOT NULL,
    path TEXT NOT NULL,
    ip TEXT,

    created_at TEXT NOT NULL
);';

$down[] = 'DROP TABLE IF EXISTS octopus_access';
