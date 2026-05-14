<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$solus_base_url = getenv('SOLUSVM_BASE_URL');
if ($solus_base_url === false || $solus_base_url === '') {
    if (isset($_SERVER['SOLUSVM_BASE_URL'])) {
        $solus_base_url = $_SERVER['SOLUSVM_BASE_URL'];
    } elseif (isset($_ENV['SOLUSVM_BASE_URL'])) {
        $solus_base_url = $_ENV['SOLUSVM_BASE_URL'];
    } else {
        $solus_base_url = 'https://45.82.65.93';
    }
}

$solus_api_token = getenv('SOLUSVM_API_TOKEN');
if ($solus_api_token === false || $solus_api_token === '') {
    if (isset($_SERVER['SOLUSVM_API_TOKEN'])) {
        $solus_api_token = $_SERVER['SOLUSVM_API_TOKEN'];
    } elseif (isset($_ENV['SOLUSVM_API_TOKEN'])) {
        $solus_api_token = $_ENV['SOLUSVM_API_TOKEN'];
    } else {
        $solus_api_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiMDk2YjFkOWU1Y2NjMGU1MGI1NWZkNTQzOGZmODA0ZTc3NzNjZDcxNzNlZGYzMGI4ZGJiOTk0MGVhZTQ3M2M0MTk2MTI1MmJjMDU4MTM3NWUiLCJpYXQiOjE3Nzg1ODE0MzQuNTMyNDE1LCJuYmYiOjE3Nzg1ODE0MzQuNTMyNDE4LCJleHAiOjQ5MzQyNTUwMzQuNTIxODc5LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.feQSWt2mKfUPHfB1xIgfOVoFFtUiQ2P66oFtZJ9ILkMnsmqRhpmG7UggElFT66suFXG0scWmyrqWUIjpy3ffoLKU7WtSremPmA_c4fUIAd1ep6O9zjOYWyv1IPHAW1aCuJsXKnfqVPCzxVEIHedP7mxZPPKinF49VNaWxU5lM5QF34TiZmq0UoY7pmqn9M91eU-qvjgmAK1PXNt-VFk0UHt8f4rQ5DufcgLYsBLnAHTJwDRvdqWwP-OMXi0UcimSir5_PBgva81rDaHgEZ8nPtbAtN8t-TeFK687ju-pseH4t4k3002ZI_AGh4bOdVAVurNW4md8pPCevg4I3INNcZyAxhQhrh9CmfbR18Qj-cB5c2j-A8xee-R-7p_GNO4EnoVNPdoyj-FTx_po1GUP8olf8XcgPgrsPdKyeevfdCyb5n3kNEmBxU_bETEOZ-5v1QUMu-uG2L6GjBm0wxqQs5-CqEAh6xArQzz3iSrcZt6XqkNpjGz2mTVBxkU21tUQtWEGeWB-4vX7AE4e629D5G-7eYrAdmuQbaEGSmBmd4Dj92xn8DC2LwMtVcgcV376f-5iRFQ-hAC8okrR2Q81whuCcpZsUd_7gi0Lh5OIO98WcDg2JqnxdK1_WKyG6tFmDJF4cBs2Cw5lXZ3pxDxfzk5MYuQynXXJrlOTOMEINMU';
    }
}

$config['solusvm'] = array(
    // Example: https://solus.example.com
    'base_url' => $solus_base_url,
    // API token for Authorization: Bearer <token>
    'api_token' => $solus_api_token,
    // API path for SolusVM 2.0 admin API
    'api_prefix' => '/api/v1',
    // Request timeout in seconds
    'timeout' => 30,
);
