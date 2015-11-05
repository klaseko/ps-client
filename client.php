<?php
  class ClientAPI {

    public $client_key = '';
    public $client_secret = '';

    function __construct($client_key, $client_secret){
      $this->client_key = $client_key;
      $this->client_secret = $client_secret;
    }

    # Obtaining an access token
    # https://github.com/klaseko/ps-client#obtaining-an-access-token
    private function getAccessTokens(){
      $headers = array(
        'Content-Type: application/json',
        'Accept: application/json',
        'Client-Key: ' . $this->client_key,
        'Client-Secret: ' . $this->client_secret,
        'Redirect-URI: https://klaseko.com'
      );

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, 'https://pay-dev.klaseko.com/oauth2/token');
      curl_setopt($curl, CURLOPT_HTTPGET, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

      $response = curl_exec($curl);

      return $response;
    }

    public function getTransactionToken($inbound_parameters = []){
      # Get the access_token
      $tokens = json_decode($this->getAccessTokens());

      # Build the request
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL             => 'https://pay-dev.klaseko.com/payment',
        CURLOPT_POST            => true,
        CURLOPT_VERBOSE         => true,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_POSTFIELDS      => $inbound_parameters,
        CURLOPT_HTTPHEADER      => array(
          "Content-Type: application/json",
          "Authorization: Bearer $tokens->access_token",
          "Client-Key: $this->client_key"
        )
      ));

      # Execute the request and get response
      $response = json_decode(curl_exec($curl));
      curl_close($curl);

      return $response->transaction_token;
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
