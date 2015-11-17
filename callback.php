<?php
  require_once('client.php');

  # Obtaining a client key and client secret
  # Contact Klaseko to get client_key and client_secret
  $client = new Client('08be42f1d6c7e24a4f305ee82632a6d429fb811cb9ed2a7f', '8f9cf1382f9abf79775723dc81706198c4fd7f262fe8282b');

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

  die(var_dump(json_decode($transaction)));