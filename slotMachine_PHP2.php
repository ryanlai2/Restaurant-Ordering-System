<?php
session_start();

$db_link = mysql_connect( 'localhost', 'harvarda', 'Alfredng_0097') or die('Could not connect to server.' );
$db_selected = mysql_select_db('harvarda_tina_ryan', $db_link);
$query= "select symbol, probability from slot_probabilities";

$result= mysql_query($query, $db_link) or die('Error selecting Table');

$num_rows = mysql_num_rows($result);
echo($num_rows. " Rows.." ."<br>");
;

$symbol_array = array();
$probability_array=array();
$array_pos_counter=0;

while($row= mysql_fetch_array($result)){
	$symbol_array[$array_pos_counter]=$row["symbol"];
	$probability_array[$array_pos_counter]= $row["probability"];
	$array_pos_counter++;
}

$symbol_array = implode(",", $symbol_array);
$probability_array=implode(",", $probability_array);
?>

<html>
<head>

	<h1 style="text-align:center; font-size:400% ">SLOT MACHINE GAME SIMULATION</h1>

	<div id="money_left">$0</div>


	<style type="text/css">

	body{
	background-color:#E0FFFF;

	}
	#money_left{
		border-radius: 5px;
		border: 2px solid #7CFC00;
		background-color: #216C2A;
		height: 50px;
		width: 350px;
		margin: auto;
		text-align: center;
		font-weight: bold;
		font-size: 45px;
		color: #ffffff;
	}

	#wrap1{
		width:1300px;
		margin:0 auto;
	/*	padding-top: 200px;*/
	}

	#left_col{
		float:left;
		width: 600px;
		font-weight: bold;
		font-size: 200%;

	}

	#right_col{
		float:right;
		width: 700px;
		text-align: center;
		font-size: 160%;
		font-weight: bold;
	}
	#display{
		border: 1px solid;
		border-collapse: collapse;
	}
	table,td,th{
		text-align: center;
		border: 1px solid black;
		padding: 1px;
	}

	#result_table{
		font-size: 72%;
	}

	.inline_table {
            display: inline-block;
        }

	</style>

</head>
	<script type="text/javascript">

	//document.getElementById("money_left").innerHTML=document.getElementById("moneyInput");
	function reset(){
		document.getElementById('moneyInput').disabled=false;
		document.getElementById("display").innerHTML="History";
		document.getElementById("displayMoney").innerHTML="Resetting... Please Input Info.";
		document.getElementById("displayResult").innerHTML="";
		document.getElementById("displayTries").innerHTML="";
		document.getElementById("moneyInput").value=100;
		document.getElementById("triesInput").value=10;
		document.getElementById("betLevelInput").value=1;
		document.getElementById("money_left").innerHTML="$0";
		tempTries=0;

	}

	var symbol_array='<?php echo $symbol_array; ?>'.split(",");
	var probability_array='<?php echo $probability_array; ?>'.split(",");

	for(var i=0; i<symbol_array.length; i++){
	 	console.log(symbol_array[i]);
	 }

	 console.log(typeof symbol_array[2]);
	//document.getElementById("money_left").innerHTML=document.getElementById("moneyInput");
var tempTries=0;
	function run(){
		document.getElementById('moneyInput').disabled=true;
		var donePlaying=false;
		var spin1=0, spin2=0, spin3=0;
		var num1="", num2="", num3="";
		var money= Number(document.getElementById("moneyInput").value);
		var betLevel= Number(document.getElementById("betLevelInput").value);
		var tries= Number(document.getElementById("triesInput").value);
		
		var moneyInit= money;
		document.getElementById("displayMoney").innerHTML= ("Money before Bet: $" + money);
		
		while( tries>0){
			if((money-payMoney(betLevel))<0){
				break;
			}
			tries--;
			tempTries++;
			money-=payMoney(betLevel);
			spin1=Math.floor((Math.random()*(100+1)));
			spin2=Math.floor((Math.random()*(100+1)));
			spin3=Math.floor((Math.random()*(100+1)));

			num1=spin(spin1);
			num2=spin(spin2);
			num3=spin(spin3);

			switch(betLevel){
				case 1: money+=getMoneyWon(num1,num2,num3);
						break;
				case 2: money+=(5*getMoneyWon(num1,num2,num3));
						break;
				case 3: money+=(25*getMoneyWon(num1,num2,num3));
						break;
				case 4: money+=(50*getMoneyWon(num1,num2,num3));
						break;						
			}

			var para=document.createElement("P");
			var txt=document.createTextNode(num1 + " | "+ num2+ " | "+ num3 + "   Total Remaining:   $" + money);
			para.appendChild(txt);
			document.getElementById("display").appendChild(para);
			document.getElementById("moneyInput").value=money;

	
		}

		document.getElementById("money_left").innerHTML=("$" + money);
		document.getElementById("displayTries").innerHTML=("Played " + tempTries + " Times.");
		
		if(tries==0 && money<moneyInit){
			document.getElementById("displayResult").innerHTML=("Money Left: $" + money + ".  You lost $" + (moneyInit-money) +"!");	
		}
		else if((moneyInit-payMoney(betLevel))==0 || (moneyInit-payMoney(betLevel)<0)){
			document.getElementById("displayResult").innerHTML=("Not Enough Money!")
		}

		else if((money-payMoney(betLevel))>0)
		document.getElementById("displayResult").innerHTML=("Money Left: $" + money + ".  You won $" + (money-moneyInit) +"!");	
	}

		function payMoney(betLevel){
			switch(betLevel){
				case 1:return 2;
				case 2: return 10;
				case 3: return 50;
				case 4: return 100;
				default: return 0;

			}
		}

			var percentageC=Number(getProbabilityWithSymbol("C"));
			var percentage7=Number(getProbabilityWithSymbol("7"));
			var percentage1B=Number(getProbabilityWithSymbol("1B"));
			var percentage2B=Number(getProbabilityWithSymbol("2B"));
			var percentage3B=Number(getProbabilityWithSymbol("3B"));
		console.log(percentageC);	
		console.log(percentage7);
		console.log(percentage1B);
		console.log(percentage2B);
		console.log(percentage3B);
		function getProbabilityWithSymbol(symbol){
			if(symbol_array.indexOf(symbol)!=-1){
					return probability_array[symbol_array.indexOf(symbol)];
				}
			else{
					return "Couldn't find Probability!";
				}

		}

		function spin(spinNum){
			if(spinNum>=0 && spinNum<percentageC) return "C ";
			if(spinNum>=percentageC && spinNum<(percentageC+percentage7)) return "7 ";
			if(spinNum>=(percentageC+percentage7) && spinNum<(percentageC+percentage7+percentage1B)) return "1B";
			if(spinNum>=(percentageC+percentage7+percentage1B) && spinNum<(percentageC+percentage7+percentage1B+percentage2B)) return "2B";
			if(spinNum>=(percentageC+percentage7+percentage1B+percentage2B) && spinNum<=(100))return "3B";
			else{
				return "Not valid";
			}
		}

		function getMoneyWon(num1, num2, num3){
			var result= ""+num1 + "" +num2 + "" +num3;
			if(result=="7 7 7 ") return 70;
			if(result== "3B3B3B") return 10;
			if(result == "1B1B1B") return 6;
			if(result == "2B2B2B") return 7;
			if(result =="C C C ") return 50;
			else if(
				(num1=="1B" || num1=="2B"  || num1=="3B") &&
				(num2=="1B" || num2=="2B" || num2=="3B") &&
				(num3=="1B" || num3=="2B" || num3=="3B") ) return 2;
		
			else if(num1=="C " || num2=="C " || num3=="C ") return 1;
			else return 0;
		}


	</script>

	<body>

	<div id="wrap1">


		<div id="left_col">
			<p>
		<table>
		<tr>
			<th> Bet Level</th>
			<th> Money Bet</th>
			<th>Win Amount</th>
		</tr>

		<tr>
			<th>1</th>
			<th>$2</th>
			<th>1 X</th>
		</tr>
		<tr>
			<th>2</th>
			<th>$10</th>
			<th>5 X</th>
		</tr>
		<tr>
			<th>3</th>
			<th>$50</th>
			<th>25 X</th>
		</tr>
		<tr>
			<th>4</th>
			<th>$100</th>
			<th>50 X</th>
		</tr>
	</table>


			</p>


	<p>Enter  Money: &nbsp<input type="text" style="font-size:60%" id="moneyInput" value="100"> </p>
	<p>Enter  Bet Level: &nbsp<input type="text" style="font-size:60%" id="betLevelInput" value="1"> </p>
	<p>Enter Spins: &nbsp<input type="text" id="triesInput" style="font-size:60%" value="2"> </p>


	
	<button id="runButton" onclick="run()"> Play/Keep-Playing</button>

	<button id="resetButton" onclick="reset()">Leave/Reset</button>

	<p>
		<div id= "displayMoney"> </div>
	</p>

	<p>
		<div id= "displayResult"> </div>
	</p>
		<p>
		<div id= "displayTries"> </div>
	</p>

	</div>

		<div id="right_col">
		<p>
		<table class="inline_table">
		<tr>
			<th> Symbol</th>
			<th> Meaning</th>
			<th> Chance </th>
		</tr>


		<tr>
			<th>7</th>
			<th>Seven</th>
			<th>15%</th>
		</tr>
		<tr>
			<th>C</th>
			<th>Cherry</th>
			<th>20%</th>
		</tr>
		<tr>
			<th>1B</th>
			<th>One Bar</th>
			<th>30%</th>
		</tr>
		<tr>
			<th>2B</th>
			<th>Two Bar</th>
			<th>20%</th>
		</tr>
		<tr>
			<th>3B</th>
			<th>Three Bar</th>
			<th>15%</th>
		</tr>
	</table>

	<table class="inline_table" id="result_table" >
		<tr>
			<th> Result</th>
			<th> Winnings</th>
		</tr>


		<tr>
			<th>777</th>
			<th>$90</th>
		</tr>
		<tr>
			<th>CCC</th>
			<th>$50</th>
		</tr>
		<tr>
			<th>3B3B3B</th>
			<th>$10</th>
		</tr>
		<tr>
			<th>2B2B2B</th>
			<th>$7</th>
		</tr>
		<tr>
			<th>1B1B1B</th>
			<th>$6</th>
		</tr>
		
		
		<tr>
			<th>Only Bars</th>
			<th>$2</th>
		</tr>
		<tr>
			<th>At least 1 C</th>
			<th>$1</th>
		</tr>
	</table>
		</p>	

			<div id="display">History</div>
		</div>

</body>
</html>