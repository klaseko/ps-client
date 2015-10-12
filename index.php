<html>
<head>
<title>PHP Client</title>
</head>
<body>
<form method='post' action='request.php' style='align:center;'>
 <fieldset style='width:40%;margin: 0 auto;'>
  <legend>PHP Client | Form</legend>
  <fieldset>
  <legend> Basic Information</legend>
 <!-- <div style='float:right;'>
    <label>Client Key:</label>
     <input type='text' name='client_key' size='30px' value='<?php echo $client_key; ?>' readonly />
  </div> -->
  <div style='float:right;'>
    <label> Title:</label>
    <input type='text' name='title' size='30px' value='Enrollment' readonly /> 
  </div>
  <div style='float:right;'> 
    <label> Email Address:</label>
    <input type='text' name='email' size='30px'/> 
  </div>
  <div style='float:right'>
    <label>Description:</label>
    <input itype='text' name='description' size='30px' />
  </div>
  <div style='float:right;'>
    <label>Mobile No:</label>
    <input type='text' name='mobile_no' size='30px'/>
  </div>
  <div style='float:right'>
    <label></label>
  </div>
  </fieldset>
  <fieldset>
  <legend>Klaseko Cart</legend>
  <div style='float:right;'>
    <label><input type='text'name="first_item_name" size='15px;' placeholder='ITEM NAME' /> :</label>
    <input type='text' size='30px' name='first_item_price' placeholder='ITEM PRICE' />
  </div> 
  <div style='float:right;'>
    <label>Description : </label>
    <input type='text'name='first_item_description'  size='30px'/>
  </div>
  <div style='float:right;'>
    <label><input type='text' name='second_item_name' size='15px;' placeholder='ITEM NAME' /> :</label>
    <input type='text' size='30px' name='second_item_price' placeholder='ITEM PRICE' />
  </div> 
  <div style='float:right;'>
    <label>Description : </label>
    <input type='text' name='second_item_description' size='30px'/>
  </div>
  <div style='float:right;'>
    <label><input type='text'size='15px;' name='third_item_name'  placeholder='ITEM NAME' /> :</label>
    <input type='text' size='30px' name='third_item_price' placeholder='ITEM PRICE' />
  </div> 
  <div style='float:right;'>
    <label>Description : </label>
    <input type='text' name='third_item_description' size='30px'/>
  </div>
  </fieldset>
  <br/>
  <div style='float:right;'>
    <input type='submit' value='Submit' />
    <input type='reset'  value='Reset Form' />
  </div>
 </fieldset>
</form>
</body>
</html>
