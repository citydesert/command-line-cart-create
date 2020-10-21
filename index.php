<?php
if(!file_exists("BillingClass.php")){print "BillingClass File not found!";exit;} // check if class file exists
if(!file_exists("HandleInput.php")){print "HandelInput File not found!";exit;} // check if class file exists


include("BillingClass.php"); // including Billing class
include("HandleInput.php"); // including Inbut handler class file

$createCart = new BillingClass(); // calling billing class
$handleInput = new handleInput($createCart); // handle input method
$createCart->currency=USD; // set default currency

if(!$_SERVER[HTTP_HOST]){
	$handleInput->get_input();
	$handleInput->get_output();
	$handleInput->output="";
	$createCart->reset();
}else{

	if($_POST["command"]){
		$handleInput->get_input($_POST["command"]);
		$posted = $_POST["command"]."<hr>".$handleInput->get_output();
		$handleInput->output="";
		$createCart->reset();
	}else{
		$posted = "command result will be here";
	}
	print '<html><head><title>Items With Offers</title>
	<style>*{box-sizing:border-box;margin:0px;padding:0px;}header{text-align:center;width:100%;padding:10%;background:#345;color:#fff;}#result{text-align:center;background:#789;color:#ee0;height:50%;width:100%;}</style>
	</head>
	
	<body>
	<header><h1>Please Write Required Command</h1></header>
	<section>
	<form method="post" action="index.php"><textarea name="command" style="padding:5px;font-size:20px;width:100%;background:#eee;">createCart --bill-currency=USD T-shirt T-shirt shoes jacket</textarea><br><input type="submit" value="submit" style="width:100%;"/></form>
	</section>
	<section id="result">
	'.$posted.'
	</section>
	</body>
	</html>
	';

}

?> 