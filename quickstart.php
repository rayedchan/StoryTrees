<?php
// Make sure to enable the Drive API and SDK
require_once 'lib/google-api-php-client/src/Google_Client.php';
require_once 'lib/google-api-php-client/src/contrib/Google_DriveService.php';

$client = new Google_Client();

// Get your credentials from the APIs Console
$client->setClientId('252710550228-a8orak4ouab9k1ss09mugn87ckg7vdnc.apps.googleusercontent.com');
$client->setClientSecret('VnfRGvu2nrLcwE7Z39oP91SB');
$client->setRedirectUri('http://localhost/StoryTrees/index.php'); // The URL contains the Authentication token
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

$service = new Google_DriveService($client);

$authUrl = $client->createAuthUrl();

//Request authorization
//Run this php file in the terminal
//php <file_name>
print "Please visit:\n$authUrl\n\n";
print "Please enter the auth code:\n";
$authCode = trim(fgets(STDIN));

// Exchange authorization code for access token
$accessToken = $client->authenticate($authCode);
$client->setAccessToken($accessToken);

//Insert a file
$file = new Google_DriveFile();
$file->setTitle('My document');
$file->setDescription('A test document');
$file->setMimeType('text/plain');

$data = file_get_contents('document.txt');

$createdFile = $service->files->insert($file, array(
      'data' => $data,
      'mimeType' => 'text/plain',
    ));

print_r($createdFile);
?>