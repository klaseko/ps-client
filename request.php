<?php
  require_once('client.php');

  # Obtaining a client key and client secret
  # Contact Klaseko to get client_key and client_secret
  $client = new Client('a74f5911d41465783132d253071bb7d9e3643cae9602dc14', '41dc1a371688b72594b7bf980981edcc03641896d19b7f38');

  $tokens  = json_decode($client->getAccessTokens());

  $payload = array(
    "client_key"          => $client->client_key,
    "title"               => $_POST['title'],
    "email"               => $_POST['email'],
    "currency"            => $_POST['currency'],
    "total"               => $_POST['total'],
    "description"         => $_POST['description'],
    "urls"                => $_POST['urls'],
    "ref_no"              => strtoupper($client->getRefno()),
    "mobile_no"           => $_POST['mobile_no'],
    "client_tracking_id"  => $_POST['client_tracking_id'],
    "items"               => $_POST['items'],
    "info"                => $_POST['info'],
    "urls"                => $_POST['urls']
  );

  # Generating the payment signature
  # https://github.com/klaseko/ps-client#generating-the-payment-signature
  $signature_string     = $payload['title'] . $payload['email'] . $payload['currency'] . $payload['total'] . $payload['description'] . $payload['ref_no'] . $payload['email'] . $payload['mobile_no'] . $payload['client_tracking_id'] . $client->client_secret;
  $hashed_string        = hash('sha256', $signature_string);
  $crypted_signature    = password_hash($hashed_string, PASSWORD_BCRYPT);
  $payload['signature'] = $crypted_signature;

  $response = $client->getTransactionToken(json_encode($payload), $tokens->access_token);
  error_log("response:" . $response);

  $redirect_url = $client->payment_switch_url . "/payment?t=" . json_decode($response)->transaction_token;
  header("Location: $redirect_url");
