<?php
  require_once('client.php');
  
  $client = new ClientAPI; 
  
  # Authentication
  $data = $client->getClientKeys(); 
  $token_auth  = $data->access_token;
  $client_secret = $data->refresh_token;
 
  # Information
  $data = array();
  $data['client_key'] = $client->client_key;
  $data['title'] = $_POST['title'];
  $data['email'] = $_POST['email'];
  $data['description'] = $_POST['description'];
  $data['currency'] = 'PHP';
  $data['total']=$_POST['first_item_price']+$_POST['second_item_price']+$_POST['third_item_price'];
  $data['urls']['callback'] = 'https://client/callback/url';
  $data['urls']['postback'] = 'https://client/postback/iurl';
  $data['ref_no'] = substr(crypt($data['client_key']),6);
  $data['mobile_no'] = $_POST['mobile_no'];
  $data['items'][0]['name'] = $_POST['first_item_name'];
  $data['items'][0]['price'] = $_POST['first_item_price'];
  $data['items'][1]['name'] = $_POST['second_item_name'];
  $data['items'][1]['price'] = $_POST['second_item_price'];
  $data['items'][2]['name'] = $_POST['third_item_name'];
  $data['items'][2]['price'] = $_POST['third_item_price'];
  $data['info'][0]['title'] = 'Information I';
  $data['info'][0]['value'] = $_POST['first_item_description'];
  $data['info'][1]['title'] = 'Information II';
  $data['info'][1]['value'] = $_POST['second_item_description'];
  $data['info'][2]['title'] = 'Information III';
  $data['info'][2]['value'] = $_POST['third_item_description'];
  
  
  $signature = $client->doHash($_POST['title'].$_POST['email'].'PHP'.$data['total'].$_POST['description'].$data['ref_no'].$_POST['email'].$_POST['mobile_no'].$client->secret_key); 
  
  
  $data['signature'] = $signature;
 
  $json_data = json_encode($data, JSON_UNESCAPED_SLASHES );
 
  $response = $client->doPost($json_data,$token_auth);
  
  print_r($response);
  $redirect_url = 'https://pay-dev.klaseko.com/payment?t='.$response->transaction_token; 
  # echo $redirect_url;
  header("Location: $redirect_url");
  # redirect('https://pay-dev.klaseko.com/payments?t="{$response}"');
  # header("Location: $response"); 
  #header("Location: https://pay-dev.klaseko.com/payment?t=$response");
?>
