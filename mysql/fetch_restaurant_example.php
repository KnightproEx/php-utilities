<?php

require_once('customer_example.php');
require_once('response.php');

$input = json_decode(file_get_contents('php://input'));
$db = new Customer($input);
$response = new DataResponse();

// error detection
if (!$db->fetch_restaurant()) {
    $response->error = true;
    $response->errorMsg = $db->getError();
    exit(json_encode($response));
}

// outputs
$count = $db->getRow();
$response->count = $count;

if ($count === 1) {
    $row = $db->getResult()->fetch_assoc();
    $response->data = [
        'id' => $row['res_id'],
        'email' => $row['res_email'],
        'title' => $row['res_title'],
        'desc' => $row['res_desc'],
    ];
}

// print response
echo json_encode($response);
