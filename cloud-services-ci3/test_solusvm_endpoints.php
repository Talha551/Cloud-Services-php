<?php
define('BASEPATH', true);

require 'application/libraries/Solusvm_client.php';

$client = new Solusvm_client();

$results = [
    'list_servers' => $client->list_servers(),
    'list_applications' => $client->list_applications(),
    'list_os_images' => $client->list_os_images(),
    'list_plans' => $client->list_plans(),
    'list_locations' => $client->list_locations(),
];

file_put_contents('test_solusvm_results.json', json_encode($results, JSON_PRETTY_PRINT));

echo "Endpoint tests completed. Results saved to test_solusvm_results.json\n";