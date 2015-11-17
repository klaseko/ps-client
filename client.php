<?php
  class Client {

    public $payment_switch_url = "http://localhost:3003";
    public $client_key = "";
    public $client_secret = "";

    function __construct($client_key, $client_secret){
      $this->client_key = $client_key;
      $this->client_secret = $client_secret;
    }

    # Obtaining an access token
    # https://github.com/klaseko/ps-client#obtaining-an-access-token
    public function getAccessTokens(){
      # Build the request
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL             => $this->payment_switch_url . '/oauth2/token',
        CURLOPT_HTTPGET         => true,
        CURLOPT_VERBOSE         => true,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_HTTPHEADER      => array(
          'Content-Type: application/json',
          'Accept: application/json',
          'Client-Key: ' . $this->client_key,
          'Client-Secret: ' . $this->client_secret,
          'Redirect-URI: https://klaseko.com'
        )
      ));

      # Execute the request
      $response = curl_exec($curl);

      # Capture error
      $err = curl_error($curl);

      # Close connection
      curl_close($curl);

      if (!empty($err)) {
        die("Could not get access token. Error: " . $err);
      }

      return $response;
    }

    # Retrieving trasnaction_token
    public function getTransactionToken($inbound_parameters, $access_token){
      # Build the request
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL             => $this->payment_switch_url . '/payment',
        CURLOPT_POST            => true,
        CURLOPT_VERBOSE         => true,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_POSTFIELDS      => $inbound_parameters,
        CURLOPT_HTTPHEADER      => array(
          "Content-Type: application/json",
          "Authorization: Bearer " . $access_token,
          "Client-Key: " . $this->client_key
        )
      ));

      # Execute the request
      $response = curl_exec($curl);

      # Capture error
      $err = curl_error($curl);

      # Close connection
      curl_close($curl);

      if (!empty($err)) {
        die("Could not get transaction token. Error: " . $err);
      }

      return $response;
    }

    # Retrieving transaction record
    public function getTransactionRecord($transaction_token, $access_token) {
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL             => $this->payment_switch_url . "/transaction/" . $transaction_token . "?include=payment_records,logs",
        CURLOPT_HTTPGET         => true,
        CURLOPT_VERBOSE         => true,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_HTTPHEADER      => array(
          "Content-Type: application/json",
          "Authorization: Bearer $access_token",
          "Client-Key: $this->client_key"
        ),
      ));

      # Execute the request
      $response = curl_exec($curl);

      # Capture error
      $err = curl_error($curl);

      # Close connection
      curl_close($curl);

      if (!empty($err)) {
        die("Could not get transaction record. Error: " . $err);
      }

      return $response;
    }

    # Helpers functions
    public function getRefno($length = 6) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $refno = '';
      for ($i = 0; $i < $length; $i++) {
        $refno .= $characters[rand(0, $charactersLength - 1)];
      }
      return $refno;
    }
  }
?>
