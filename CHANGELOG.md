<!--
  References:
    http://keepachangelog.com/
    http://semver.org/
-->

# Change Log
Documentation of the changes made on each releases on the payment switch.

## [v1.3.0] - 2015-Jan-27
### Added
- include date settled in return info
- now supports callback urls with params

### Changes
- rearrange payment options
  - OTC Bank
  - OTC Non Bank
  - Credit Card
  - Debit Card
  - Online Banking
  - Mobile


### Fixes
- fix currency typo
- fixes incorrect total when item price is a formatted number

## [v1.2.0] - 2015-Dec-23
### Added
- slack notifications for fully paid transactions

### Changes
- new layout for the new client form

### Fixes
- paypal total not including tax for taxable clients
- fix duplicate ref number loop bug
- updated transaction information details to show gateway fees and taxes
- fixed cancel and continue urls in email confirmations
- fixed radio button bug when 'back' is pressed from paypal payment page
- fixed bug where total is not updated when going back to the payment page from payment gateway

## [v1.1.0] - 2015-Dec-18
### Added
- option to tax customer or client
- option to charge gateway fee to customers
- web access fee charged per enrollee
- gateway fee is dynamically computed
- info that additional charges may be charged depending on the mode/processor selected
- link to a list of banks that have additional fees
- gateway fee, tax and web access/payment fee in the fees breakdown
- gateway fee settings
- web access and payment fee settings
- tax settings
- clients can now get transaction record through /transaction/:token

### Changed
- callback and postback now includes signature
- new layout for update client and settings page


## [v1.0.0] - 2015-Nov-18
<center>(ﾉ◕ヮ◕)ﾉ*:･ﾟ✧ ✧ﾟ･: *ヽ(◕ヮ◕ヽ)</center>
