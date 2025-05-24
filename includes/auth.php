<?php
// includes/auth.php

function startSessionIfNotStarted() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function requireLogin() {
    startSessionIfNotStarted();
    
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit();
    }
}

function redirectAfterLogin() {
    startSessionIfNotStarted();
    
    if (isset($_SESSION['redirect_url'])) {
        $redirect_url = $_SESSION['redirect_url'];
        unset($_SESSION['redirect_url']);
        header("Location: $redirect_url");
        exit();
    }
    
    // المسار الافتراضي إذا لم يكن هناك redirect_url
    header('Location: index.php');
    exit();
}