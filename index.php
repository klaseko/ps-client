<html>
  <head>
    <title>PHP Client</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
  </head>
  <body class="container">
    <div class="col-xs-8 col-xs-offset-2">
      <h1 class="page-header">
        PHP Client <small>example implementation in PHP</small>
      </h1>
      <form method='POST' action='request.php'>
        <div class="form-group">
          Title: <input class="form-control" type="text" name="title">
        </div>
        <div class="form-group">
          Description: <input class="form-control" type="text" name="description">
        </div>
        <div class="form-group">
          Email: <input class="form-control" type="text" name="email">
        </div>
        <div class="form-group">
          Currency: <input class="form-control" type="text" name="currency" value="PHP" readonly="true">
        </div>
        <div class="form-group">
          Mobile No.: <input class="form-control" type="text" name="mobile_no">
        </div>
        <div class="form-group">
          Client Tracking ID: <input class="form-control" type="text" name="client_tracking_id">
        </div>
        <div class="form-group">
          Items:
          <hr>
          <div class="row">
            <div class='col-xs-6'>
              Name: <input class="form-control" type="text" name="items[0][name]">
            </div>
            <div class='col-xs-6'>
              Price: <input class="form-control" type="text" name="items[0][price]">
            </div>
          </div>
          <div class="row">
            <div class='col-xs-6'>
              Name: <input class="form-control" type="text" name="items[1][name]">
            </div>
            <div class='col-xs-6'>
              Price: <input class="form-control" type="text" name="items[1][price]">
            </div>
          </div>
        </div>
        <div class="form-group">
          Total: <input class="form-control" type="text" name="total">
        </div>
        <div class="form-group">
          Info:
          <hr>
          <div class="row">
            <div class='col-xs-6'>
              Name: <input class="form-control" type="text" name="info[0][title]">
            </div>
            <div class='col-xs-6'>
              Value: <input class="form-control" type="text" name="info[0][value]">
            </div>
          </div>
          <div class="row">
            <div class='col-xs-6'>
              Name: <input class="form-control" type="text" name="info[1][title]">
            </div>
            <div class='col-xs-6'>
              Value: <input class="form-control" type="text" name="info[1][value]">
            </div>
          </div>
        </div>
        <div class="form-group">
          URLs:
          <hr>
          <div class="row">
            <div class='col-xs-6'>
              Callback: <input class="form-control" type="text" name="urls[callback]">
            </div>
            <div class='col-xs-6'>
              Postback: <input class="form-control" type="text" name="urls[postback]">
            </div>
          </div>
        </div>
        <input type="submit" class="btn btn-default">
      </form>
    </div>
  </body>
</html>
