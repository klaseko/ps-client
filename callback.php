<?php
  require_once('client.php');

  # Obtaining a client key and client secret
  # Contact Klaseko to get client_key and client_secret
  $client = new Client('a74f5911d41465783132d253071bb7d9e3643cae9602dc14', '41dc1a371688b72594b7bf980981edcc03641896d19b7f38');

  $tokens  = json_decode($client->getAccessTokens());

  # 1. process callback
  #
  # ...

  $transaction_token = $_GET['token'];
  $ref_no            = $_GET['ref_no'];
  $status            = $_GET['status'];
  $signature         = $_GET['signature'];

  # ...
  #
  # get transaction details from the payment switch
  $transaction = $client->getTransactionRecord($transaction_token, $tokens->access_token);
  error_log("response:" . $transaction);

  die(var_dump(json_decode($transaction)));