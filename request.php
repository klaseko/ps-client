<?php
  require_once('client.php');

  # Obtaining a client key and client secret
  # Contact Klaseko to get client_key and client_secret
  $client = new Client('08be42f1d6c7e24a4f305ee82632a6d429fb811cb9ed2a7f', '8f9cf1382f9abf79775723dc81706198c4fd7f262fe8282b');

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

  $redirect_url = $client->payment_switch_url . "/payment?t=" . json_decode($response)->transaction_token;
  header("Location: $redirect_url");
