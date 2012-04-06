<?php
require_once '../BIG.php';

// Did we get all necessary parameters?
if (empty($_POST['registration'])) {
    sendResponse(400, array('error' => "Missing required field 'Registration Number'"));
}

if (empty($_POST['name'])) {
    sendResponse(400, array('error' => "Missing required field 'Name'"));
}

if (empty($_POST['birthdate'])) {
    sendResponse(400, array('error' => "Missing required field 'Birthdate'"));
}

// Find the record
$repo = new BIGRepository();
$record = $repo->fetchByNameAndBirthDate(
    $_POST['name'], 
    Datetime::createFromFormat('d-m-Y', $_POST['birthdate'])
);
if (!$record) {
    sendResponse(400, array('error' => "No records with that registration number were found"));
}

// Does the name of the record approximately match the name we were given? If so, success!
if (stripString($_POST['name']) !== stripString($record->getFullName())) {
    sendResponse(400, array('error' => "Name/Birthdate does not match"));
}

// Does the retrieved record have a matching registration number?
if ($record->getRegistrationNumber() !== $_POST['registration']) {
    sendResponse(400, array('error' => 'Registration number does not match'));
}

sendResponse(200, $record->getData());




// Rough functions
function stripString($string) {
    return preg_replace("/[^a-z]/", '', strtolower($string));
}
function sendResponse($statusCode, $body)
{
    header("HTTP/1.1 {$statusCode}");
    header('Content-Type: application/json');
    echo json_encode($body);
    die;
}
