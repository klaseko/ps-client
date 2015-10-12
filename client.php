<?php
 class ClientAPI {
    
    public $client_key = 'd737d9036f0feaf04d69fd9524ca511316173bf0e491f741';
    public $secret_key = '75ccf1a9aaff6844daaf8c7d103c3b7bc2104d870cdd268e';
    private function doAuthenticate(){
        $cURL = curl_init();

        curl_setopt($cURL, CURLOPT_URL, 'https://pay-dev.klaseko.com/oauth2/token');
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $headers[] = 'Client-Key: d737d9036f0feaf04d69fd9524ca511316173bf0e491f741';
        $headers[] = 'Client-Secret: 75ccf1a9aaff6844daaf8c7d103c3b7bc2104d870cdd268e';
        $headers[] = 'Redirect-URI: klaseko.com';

        curl_setopt($cURL, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($cURL, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($cURL);

        $data = json_decode($response);        
 
        curl_close($cURL);

        return $data;

    }


    public function getClientKeys(){
       return $this->doAuthenticate();
    }
    
    public function doHash($data){
       $hashed_data = hash('sha256',$data);
       $bcrypted_data = password_hash($hashed_data, PASSWORD_BCRYPT);
       return $bcrypted_data;
    }
    
    public  function doPost($data,$token_auth){
       $cURL = curl_init();
       curl_setopt($cURL, CURLOPT_URL, 'https://pay-dev.klaseko.com/payment');
       //curl_setopt($cURL, CURLOPT_HTTPGET, true);
       curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, 'POST');
       curl_setopt($cURL, CURLOPT_POSTFIELDS, $data);
       $headers = array();
       $headers[] = "Content-Type: application/json";
       $headers[] = "Authorization: Bearer ".$token_auth."";
       $headers[] = "Client-Key: ".$this->client_key."";
      
       curl_setopt($cURL, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
     
       $response = curl_exec($cURL);
       $data = json_decode($response);
         
       curl_close($cURL);
  
       return $data;
    }
 }
?>
