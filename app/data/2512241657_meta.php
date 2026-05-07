<?php

# meta

$up[] = 'CREATE TABLE nano_meta (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    meta TEXT NOT NULL,
    meta_key TEXT NOT NULL,
    value TEXT,
    UNIQUE (meta, meta_key)
);';

$down[] = 'DROP TABLE IF EXISTS nano_meta';
