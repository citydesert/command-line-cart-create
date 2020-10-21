<?php
if(!file_exists("DatabaseClass.php")){print "DatabaseClass File not found!";exit;} // check if class file exists
include("DatabaseClass.php");
if(!defined("STDIN")) {define("STDIN", fopen('php://stdin','r'));} // check if commandline inbut mode avilable , if not define it

class BillingClass extends DataBase{
	public $itemsList=Array(); // all items in database type(array)
	public $selectedItemsIds=Array(); // selected items array
	public $currency; // selected currenry tag
	public $subtotal=0; // subtotal result
	public $taxes=0; // tax amount
	public $totalTaxes=0; // total tax
	public $offersList=Array(); //offers array to be handled with method offersHandle
	public $discountMessage="";
	public $discounts=0;
	
	function __construct(){
		$this->init();
		$db = $this->dbArray;
		$this->itemsList=$db["products"]; // products table
		$this->setCurrency("USD"); // default currency USD
		$this->taxes = 14; // default value of taxes
		$this->offersList=$db["offers"]; // offers tavle
	}
	
	function selectItem($item){
		
		$selectedItem = $this->get_item("products","title",$item);
		if($selectedItem!==false){
		array_push($this->selectedItemsIds,$selectedItem["id"]);
		}
	}
	
	
	function setCurrency($cur){
		$selectedCurrencyArray=$this->get_item("currencies","title",$cur);
		$this->currency = $cur;
		$this->selectedCurrencyPrice=(int) $selectedCurrencyArray["price"];
		$this->selectedCurrencySign=$selectedCurrencyArray["sign"];
	}
	
	
	function offersHandle(){
		if($this->selectedItemsIds[0]=="")return false;
		$itemCountAr = array_count_values($this->selectedItemsIds); // an array for counting items 
		$preventDiscount2=1;$preventDiscount1=1;
		foreach($this->offersList as $k=>$v){

				if(!in_array($v["discount_on"],$this->selectedItemsIds)){continue;} // if selected items havent offers
				if(!in_array($v["discount_target"],$this->selectedItemsIds)){continue;} // if selected target items havent choosen
					$v; // Array of offers which binded to choosen products
					$mainItemCount=$itemCountAr[$v["discount_on"]]; // how many selected this product repeated ;
					$targetItemCount=$itemCountAr[$v["discount_target"]]; // how many targeted by offer product repeated and selected;
					
					if($mainItemCount>=$v["discount_amount"]){
						$allowedNumberOfDiscounts=floor($mainItemCount/$v["discount_amount"]);
						$allowedDiscounts = $allowedNumberOfDiscounts<=$targetItemCount?$allowedNumberOfDiscounts:$targetItemCount;
						$itemAr=$this->get_item("products","id",$v["discount_target"]);
						$itemWithout=($itemAr["price"]*$allowedDiscounts);
						$itemDiscount=(($v["discount_percent"]*$itemWithout)/100);
						
						$this->discountMessage.="\t".$v["discount_percent"]."% off ".$itemAr["title"].": -".round($itemDiscount*$this->selectedCurrencyPrice,3).$this->selectedCurrencySign."\n";

					}
					
					$this->discounts = ($this->discounts)+($itemDiscount);
					(int) $this->discounts;
					
			}
					
	}
	

	function getBill(){
		
		foreach((Array)$this->selectedItemsIds as $v){
			//if($this->selectedItemsIds[0]=="") continue;
			$selectedItem = $this->get_item("products","id",$v);
			$this->subtotal=(($this->subtotal+$selectedItem["price"])); // loop selected items * prices
		}
		$this->setCurrency($this->currency);
		$this->subtotal = round($this->subtotal*($this->selectedCurrencyPrice),2); // output subtotal price (items price * currency value)
		$this->totalTaxes = round(($this->taxes*$this->subtotal/100),2); // output taxed on subtotal only
		$this->offersHandle();
		if(strlen($this->discountMessage)>2){
		$this->discountMessage = "Discounts:\n".$this->discountMessage;
		}
	}
	
	function reset(){
	
	$this->selectedItemsIds=Array();
	$this->subtotal=0; // subtotal result
	$this->totalTaxes=0; // total tax
	$this->discountMessage="";
	$this->discounts=0;
			
	
	}
	
}


?>