<?php
// Secure the session settings
ini_set('session.cookie_secure', '1');     // Use only HTTPS for cookies
ini_set('session.cookie_httponly', '1');   // Disable JavaScript access to cookies
ini_set('session.use_strict_mode', '1');   // Reject uninitialized session IDs
ini_set('session.use_only_cookies', '1');  // Prevent session IDs in URLs
ini_set('session.cookie_samesite', 'Strict'); // Prevent cross-site request forgery (CSRF)

session_start(); // Start the session
?>