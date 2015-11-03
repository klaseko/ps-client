<?php
 class ClientAPI {
    
    public $client_key = '85f102e0bde93549acab1288d5bf220a3e566f9010a5cc8b';
    public $client_secret = 'b4f77e2ae76b9cd84cecbcbf2ce418208b3b977796075515';
    private function doAuthenticate(){
        $cURL = curl_init();

        curl_setopt($cURL, CURLOPT_URL, 'https://pay-test.klaseko.com/oauth2/token');
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $headers[] = 'Client-Key:'.$this->client_key;
        $headers[] = 'Client-Secret:'.$this->client_secret;
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
       curl_setopt($cURL, CURLOPT_URL, 'https://pay-test.klaseko.com/payment');
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
