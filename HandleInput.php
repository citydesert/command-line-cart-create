<?php

if(!defined("STDIN")) {define("STDIN", fopen('php://stdin','r'));} // check if commandline inbut mode avilable , if not define it

class HandleInput{
		
		public $newItems;
		public $billing;
		public $output;
		
		function __construct($B){
			$this->billing=$B;
		}
		
		function get_input($cl=false){
			//// INPUT WEB PAGE OR COMMAND 
				if($_SERVER[HTTP_HOST]){
					$newItems=$cl;
				}else{
					echo "Enter your commmand : \n";
					$newItems=fgets(STDIN);
				}
			preg_replace("/\n/","",trim($newItems)); // removing any new line caused by cmd
			$this->newItems = explode(" ",trim($newItems));//converting input line into array of items
			return $this->switcher($this->newItems);
		}
		
		function switcher($s){
			$t=strtolower($s[0]);
			switch($t){
				case productlist:
					return $this->product_list();
				break;
				case currencylist:
					return $this->curency_list();
				break;
				case offerlist:
					return $this->offer_list();
				break;
				case createcart:
					return $this->createCart();
				break;
				default:
					return $this->output="Invalid command!";
			}
		}
		
		function product_list(){
				foreach($this->billing->itemsList as $v){
					$this->output.= "ID:".$v["id"]."\tTitle:".$v["title"]."\t Price: ".$v["price"]."\n";
				}
				return $this->get_output();
		}
		function curency_list(){
				foreach($this->billing->dbArray["currencies"] as $v){
					$this->output.= "ID:".$v["id"]."\tTitle:".$v["title"]."\t Price: ".$v["price"]."\n";
				}
				return $this->get_output();
		}
		function offer_list(){
				foreach($this->billing->dbArray["offers"] as $v){
					$this->output.= "ID:".$v["id"]."\t if:".$this->billing->get_item("products","id",$v["discount_on"])["title"]." >= ".$v["discount_amount"]."  --> \t Target:".$this->billing->get_item("products","id",$v["discount_target"])["title"]."\t Price: -".$v["discount_percent"]."% \n";
				}
				return $this->get_output();
		}
		function createCart(){
				for($i=0;$i<count($this->newItems);$i++){
					if(strpos($this->newItems[$i],"--bill-currency")===0){
						$this->billing->setCurrency(explode("=",$this->newItems[$i])[1]);
					}else{
					$this->billing->selectItem($this->newItems[$i]);
					}
				}
				$this->billing->getBill();
					$outlist="ID -- \t Title -- \t  Price -- \t Qty.\n";
				foreach(array_count_values($this->billing->selectedItemsIds) as $k=>$v){
					$outlistArrrr=$this->billing->get_item("products","id",$k);
					$outlist.=$outlistArrrr["id"]." -- \t".$outlistArrrr["title"]." -- \t".$outlistArrrr["price"]." -- \t".$v."\n";
				}
				
				$out.= "Selected Products: \n".$outlist."\n";
				$out.= "Subtotal: ".$this->billing->subtotal.$this->billing->selectedCurrencySign."\n";
				$out.= "Taxes: ".$this->billing->totalTaxes.$this->billing->selectedCurrencySign."\n";
				$out.= $this->billing->discountMessage."\n";
				$totalPrice = round(($this->billing->subtotal)+($this->billing->totalTaxes)-($this->billing->discounts),2);
				$out.= "Total price: ".$totalPrice.$this->billing->selectedCurrencySign."\n";
				$this->output=$out;
				return $this->get_output();
		}
		function get_output(){
			if(!$_SERVER[HTTP_HOST]){
				print $this->output;
				$this->get_input();
			}else{
				return preg_replace("/\n/i","<br>",$this->output);
			}
		}
		

}

?>