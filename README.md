# Klaseko Payment Switch

## Client Documentation

### Payment Switch API Environments
The Klaseko Payment Switch has two (2) environments `staging` and `production`.
The following endpoints address our two environments:
* **Staging** (for testing): `https://pay-test.klaseko.com`
* **Production** (live transactions): `https://pay.klaseko.com`

The client must make an HTTP request with the corresponding HTTP method (or "verb") to the endpoint that the client needs. For example, this is what a new payment operation will look like:

`POST https://pay.klaseko.com/payment`

For the request to be complete, make sure the client has the appropriate HTTP headers and a valid JSON payload.


### Authentication & Headers

The Klaseko Payment Switch API uses [OAuth 2.0](https://oauth.net/2/). It is a web standard authorization framework/protocol described in [RFC 6749](https://tools.ietf.org/html/rfc6749). Klaseko will be providing all authorized clients with a `client_key` and `client_secret`.

The client must use their client key and client secret to obtain an access token.


#### Obtaining a client key and client secret
Contact Klaseko


#### Obtaining an access token
Access tokens are credentials used to access protected resources.  An access token is a string representing an authorization issued to the client. ([source](https://tools.ietf.org/html/rfc6749#section-1.4))

To obtain an access token, the client must make a request to the token endpoint

`GET https://pay.klaseko.com/oauth2/token`

with the following headers:

Header          | Value
----------------|--------------------------
`Client-Key`    | f17bac0022c9204976040...
`Client-Secret` | 0022e410eedf3cc252153...
`Redirect-URI`  | https://client/redirect/uri

> NOTE: Client `redirect_uri`s should ideally be SSL/TLS


Once the client is authenticated, the payment switch will return a JSON object:

```json
{
  "access_token": "bHRzqOwy6Y+IyyIN45S6cBi+lKgw7uil",
  "refresh_token": "41gDvK8lOvPiU2CBb2cW/ol33gpq0z/x",
  "expires": "2015-06-24T10:31:37Z"
}
```

Parameter       | Type   | Description
----------------|--------|--------------------
`access_token`  | string | Clients must have this in the headers of **all requests** to the payment switch API
`refresh_token` | string | Once the access token expires, use the refresh token to obtain a new access token
`expires`       | [ISO 8601](https://www.iso.org/iso/home/standards/iso8601.htm) date/time string | expiry date/time of the access token.


#### Refreshing an expired `access_token`
Access tokens expire in 10 minutes. To obtain a new access token, the client must make a request to the token endpoint

`GET https://pay.klaseko.com/oauth2/token`

with the following headers:

Header          | Value
----------------|--------------------------
`Grant-Type`    | refresh_token
`Refresh-Token` | 41gDvK8lOvPiU2CBb2cW/o...


## Payments
### Create a Payment
To initiate payment, submit a POST request to the PS containing the inbound parameters to:

`POST https://pay.klaseko.com/payment`


### 1. Send Inbound Parameters

#### Headers

Header         | Value
---------------|--------------------------
`Content-Type` | application/json
`Access-Token` | Bearer ZdcnVOYstURZdJWBMZ8mem8tkfl7fM7L
`Client-Key`   | ee721c8738a0e26611dd70e910623fb808cb3a28a325d9Cf

#### Inbound Parameters:

Parameter             | Type    | Description
----------------------|---------|--------------------
`client_key`          | string  | Unique identifier given to the client by Klaseko
`title`               | string  | Title of the transaction from the client's side
`email`               | string  | Customer user's email address
`currency`            | string  | Currency of the request. Current supported currency is `PHP`
`total`               | decimal | Price to be paid in the transaction. Up to two (2) decimal places only
`description`         | string  | One-line string, 255 chars max
`urls`                | json    | key-value json object containing the client's `callback` and `postback` urls. All other keys will be disregarded by PS
`ref_no`              | string  | Tracking/transaction ID from your system
`mobile_no`           | string  | Customer's mobile number
`items`               | array   | Items list. Each element is a JSON value object with keys `name` and `price` for each item
`signature`           | string  | See [Generating a payment signature](#generating-the-payment-signature) for details
`client_tracking_id`  | string  | Tracking ID of the customer who initiated the transaction (i.e. Student ID, Customer No, etc.)

> Item prices are summed and matched with total



#### Sample request

```
curl -v https://pay.klaseko.com/payment \
-H "Content-Type:application/json" \
-H "Authorization: Bearer ZdcnVOYstURZdJWBMZ8mem8tkfl7fM7L" \
-H "Client-Key: ee721c8738a0e26611dd70e910623fb808cb3a28a325d9Cf" \
-d '{
  "client_key": "ee721c8738a0e26611dd70e910623fb808cb3a28a325d9Cf",
  "title": "Enrollment",
  "email": "gerardcruz@live.com",
  "currency": "PHP",
  "total": 4510.50,
  "description": "New payment from customer@client.com",
  "urls": {
    "callback": "https://client/callback/url",
    "postback": "https://client/postback/url"
  },
  "ref_no": "H56uBx",
  "mobile_no": "09123456789",
  "items": [
    {
      "name": "Test transaction",
      "price": 510.50
    },
    {
      "name": "Test transaction 2",
      "price": 4000
    }
  ],
  "info": [
    {
      "title": "Info One",
      "value": "Info one value 123"
    },
    {
      "title": "Info Two",
      "value": "This is the second info"
    }
  ],
  "client_tracking_id": "1231131-A-231"
  "signature": "$2a$10$T69d81tafHSNMbEpQ54iM.03.WhLJt8P1.SqfdSt9DvVX.ZP6bole"
}'
```


#### Generating the payment signature
The signature is a mechanism which will ensure the authenticity of each request.

Algorithm:
```ruby
transaction_string = title + email + currency + price + description + ref_no + email + mobile_no + client_tracking_id + client_secret
# => "EnrollmentPHP4510.50New payment from customer@client.comH56uBxcustomer@client.com09123456891231131-A-231f17bac0022c920497604033bb97aa09477"
transaction_hash = Digest::SHA2.new(256)
transaction_hash << transaction_string
# => #<Digest::SHA2:256 688e43af5be1b9ac050d871590b19b25c668aa9ff8e5d3eafd3b070e172d5911>
signature = BCrypt::Password.create(transaction_hash)
# => "$2a$10$1ethNPkv7zs9qtwCTdofyeAtMpqXqo9sgPF3/Ok4yBV4d/J3duu9a"
```

> You must *strictly* follow the prescribed order for the SHA2 string.

> Read about BCrypt [here](http://bcrypt.sourceforge.net/).
> Read about SHA256 [here](https://tools.ietf.org/html/rfc6234)


### 2. Redirect user to payment page
Once the inbound parameters are validated by PS, a transaction token will be returned. The token must be used as the parameter upon user redirect to the following url:

`https://pay.klaseko.com/payment?t=<transaction_token>`

The page will display the transaction summary and will allow the user to choose a payment method.


### 3. Payment Processing
The PS processes the payment transaction and communicates with the 3rd party payment gateway chosen by the user. After the payment is processed, the PS will redirect the user to the callback url supplied with the inbound parameters.

`callback` is the url of the Client system the user will be redirected to after paying or initiating a payment on the PS. For instance, after a successful credit card payment, the user will be redirected to the callback url. Another example, after a user initiates a bank deposit payment, he will be redirected to the same callback url as well.

`callback` is called during the ff scenarios:

1.  Successful payment - full payment via credit card, internet bank transfer, or etc
2.  Failed payment - an error occurred during payment
3.  Cancelled payment - the user cancels the transaction
4.  Pending payment - the user has initiated an over the counter deposit and will pay at a later time.


### 4: Postback (only for pending payments)
Postbacks are triggered by the PS for transactions with a Pending status. PS calls the supplied postback url with specific parameters.

`POST https://client.com/postback?ref_no=<ref_no>&token=<token>&status=<status_code>`


#### Status Codes

Code       | Description
-----------|----
PAID       | Success
FAILED     | Failed
CANCELLED  | Cancelled
PENDING    | Pending (pending OTC or bank deposit)
