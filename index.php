<?php

# Nanoframework

define('WEB', __DIR__ . '/');

define('IDEMPOTENCY', bin2hex(random_bytes(32)));

include WEB . 'app/start.php';
