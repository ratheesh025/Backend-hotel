<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (!current_user_id()) {
    respond(true, ['loggedIn' => false]);
}

respond(true, ['loggedIn' => true, 'user' => ['id' => $_SESSION['user_id'], 'name' => $_SESSION['user_name']]]);
