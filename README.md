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
Contact Klaseko. To generate your client key and client secret, we'll need the following:
- Company Name
- Company Address
- Contact Person
- Contact Email
- Logo URL (or send us an image file)

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

Header          | Value
----------------|--------------------------
`Content-Type`  | application/json
`Authorization` | Bearer `access_token`
`Client-Key`    | `client_key`

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
`info`                | array   | Each element is a JSON value object with keys `title` and `value` for each item
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
# Ruby
transaction_string = title + email + currency + total + description + ref_no + email + mobile_no + client_tracking_id + client_secret
# => "EnrollmentPHP4510.50New payment from customer@client.comH56uBxcustomer@client.com09123456891231131-A-231f17bac0022c920497604033bb97aa09477"
transaction_hash = Digest::SHA2.new(256)
transaction_hash << transaction_string
# => #<Digest::SHA2:256 688e43af5be1b9ac050d871590b19b25c668aa9ff8e5d3eafd3b070e172d5911>
signature = BCrypt::Password.create(transaction_hash)
# => "$2a$10$1ethNPkv7zs9qtwCTdofyeAtMpqXqo9sgPF3/Ok4yBV4d/J3duu9a"
@payload[:signature] = signature
```

> You must *strictly* follow the prescribed order for the SHA2 string.

> Read about BCrypt [here](http://bcrypt.sourceforge.net/).
> Read about SHA256 [here](https://tools.ietf.org/html/rfc6234)


### 2. Redirect user to payment page
Once the inbound parameters are validated by PS, a transaction token will be returned.
```json
{
  "transaction_token": "qMM0m7wMjpJVM-9EFWYHRB1ZKkE"
}
```
The token must be used as the parameter upon user redirect to the following url:

`https://pay.klaseko.com/payment?t=<transaction_token>`

The page will display the transaction summary and will allow the user to choose a payment method.


### 3. Payment Processing
The PS processes the payment transaction and communicates with the 3rd party payment gateway chosen by the user. After the payment is processed, the PS will send a postback, and redirect the user to the supplied urls.

#### Postback
Whenever the transaction's status changes, a `POST` request is sent to the postback url with following payload:

```
{
  "total": 16300,
  "status": "PAID",
  "ref_no": "1DSGFP-CA9D7D6",
  "token": "2mo_gfRGfeXTWuuFEco72Q",
  "amount_paid": 16300,
  "gateway_tracking_ids": {
    "paypal": "AP-2CM385490S385752Y"
  },
  "client_tracking_id": "1DSGFP",
  "fees": [
    {
      "name": "Klaseko Fee",
      "price": 150.0
    }
  ],
  "subtotal": 16000,
  "mode": "CREDIT",
  "bank_name": "PayPal",
  "computed_gateway_fee": 150,
  "date_settled": "Tue, 15 Aug 2017 00:00:00 UTC +00:00",
  "expiration_date": "Fri, 18 Aug 2017 06:49:21 UTC +00:00"
}
```
Use this to update your records related to the payment.

#### Callback
A callback is sent after a postback request (except for postbacks sent outside of the current session, i.e. after OTC payments) to redirect the user back to the client's domain.

```php
# What the callback looks like
GET 'http://client.com/callback.php?ref_no=<ref_no>&token=<token>&signature=<signature>&status=<status>'
```

### Retrieving Transaction Record
Send a GET request to `/transaction/<transaction_token>`

`GET https://pay.klaseko.com/transaction/<transaction_token>`

with the following headers:

Header          | Value
----------------|--------------------------
`Content-Type`  | application/json
`Authorization` | Bearer `access_token`
`Client-Key`    | `client_key`

You can add an `include` parameter which accepts `payment_records` and/or `logs`.

Example:

`GET https://pay.klaseko.com/transaction/876vLY5VLyLTR6RRjKfluNluK0g?include=payment_records,logs`

The payment switch will return a JSON object:

Key                  | Type   | Description
---------------------|--------|---------------
title                | string | Title of the transaction
description          | string | Description of the transaction
email                | string | Email address used for the transaction
mobile_no            | string | Mobile number used for the transaction
info                 | array  | An array containing basic information of the transaction. Structured as `[{"title" => "Student", "value" => "Francis Borbe"}, {"title" => "Program", "value" => "Computer Science"}]`
ref_no               | string | Referrence number of the transaction. Can be used to find the transaction in the backoffice
token                | string | Transaction token. Used for processing the transaction
status               | string | Status of the transaction. Values could be `'NEW', 'PENDING', 'PAID', 'EXPIRED', 'FAILED', 'CANCELLED'`
client_tracking_id   | string | Contains the id used by client (schools) to track who is paying for the transaction. This could be a student id for example.
currency             | string | Currency used for the trasnaction. e.g. `PHP, USD`
items                | array  | An array containing the line items with prices of the transcation. Structured as `[{"name" => "Tuition Fee", "price" => 5000}, {"name" => "Misc. Fee", "price" => 500}]`
fees                 | array  | An array containing the fees charged by Klaseko. Structured as `[{"name" => "Access Fee (Klaseko)", "price" => 100}]`
subtotal             | double | The total of all the transaction items (stored in the items field)
tax                  | double | Tax based on the subtotal. Tax is 0 for non-taxable clients
computed_gateway_fee | double | The amount charged by the chosen payment gateway (e.g. PayPal, Dragonpay, etc.)
total                | double | Total amount that the customer will pay. This includes the `subtotal, tax, computed_gateway_fee and Klaseko fees.`
amount_paid          | double | Amount that the customer actually paid.
mode                 | string | Payment pethod chosen by the customer. Possible values: `'CREDIT', 'DEBIT', 'ONLINE_BANK', 'OTC_BANK' (over the counter bank), 'OTC_NONBANK' (over the counter non-banks), 'MOBILE'`
gateway_tracking_ids | object | The tracking ID returned by the chosen payment gateway. This can be used to track the payment made in the payment gateway. e.g. `[{"paypal" => "AP-9Y090333P6386951A"}, "dragonpay" => "VWWMD8"]`
bank_name            | string | Name of the bank where the customer process the payment. For paypal transaction, the value of this is `'PayPal'`
date_settled         | string | The date the transaction was `'PAID'`
payment_records      | array  | An array containing the records of payments made
logs                 | array  | An array containing the activities made on the transaction

#### Status Codes

Code       | Description
-----------|----
PAID       | Transaction is settled.
FAILED     | An error was encountered while processing payment.
CANCELLED  | Payment was cancelled.
PENDING    | Waiting for (OTC) payment.
