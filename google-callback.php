<?php

require 'vendor/autoload.php';

$client = new Google_Client();

$client->setClientId("REMOVED_GOOGLE_CLIENT_ID");
$client->setClientSecret("REMOVED_GOOGLE_CLIENT_SECRET");
$client->setRedirectUri("http://localhost/expense-management/google-callback.php");

$client->addScope("email");
$client->addScope("profile");

$login_url = $client->createAuthUrl();

header("Location: ".$login_url);
exit();