# command-line-cart-create
to test this script please run this command:
example hosted script on: http://banha.freevar.com/createcart/

This is a free script created as part of challenge
Stands on PHP and can be excluded by browser or command line: -
The main category "BILLINGCLASS" contains necessary Methods and
The Public variables for issuing an invoice.

selectItem ($ var) // This is to add items to your invoice
setCurrency ($ var) // a currency address like USD, EGP ...
offerHandler () // Offer Handels on Products and if TargetedAnotherProduct ()
Tax // to add tux to the invoice before the show!
getBill () // Returns an itemized invoice

Although you can use the attached DatabaseClass.php class to handle DB file in json format (if you want to use the local json file as a db)or modify it to handle mysql requests

To test the patch file, open the .bat file, modify it if php.exe is not in C:\php\php.exe

@Echo OFF

"C:\php\php.exe" index.php %*

then enter any command of this

productList // for a product list
currencyList // return list of currencies
offerList // return list of avilable offers
createCart // to create invoice list items with a separate space

Example: 
createCart T-shirt T-shirt shoes jacket
createCart --bill-currency=EGP T-shirt T-shirt shoes
createCart --bill-currency=USD T-shirt shoes jacket

