<?php
session_start();

$db_link = mysql_connect( 'localhost', 'user', 'pass') or die('Could not connect to server.' );
$db_selected = mysql_select_db('harvarda_tina_ryan', $db_link);
$query= "select dish_output_name, dish_name, id, dish_price from appetizers";

$result= mysql_query($query, $db_link) or die('Error selecting Table');

$num_rows = mysql_num_rows($result);
echo($num_rows. " Rows.." ."<br>");
;

$translate_array_id = array();
$translate_array_name=array();
$translate_array_output=array();
$array_price=array();
$array_pos_counter=0;
?>

<html>
<style type="text/css">
	table{
		border-collapse:collapse;
		text-align: center;
	}
	td,th{
		border: 1px solid;
		padding: 3px 7px 2px 7px;
	}
	#total_amount{
		border-radius: 5px;
		border: 2px solid #7CFC00;
		background-color: #216C2A;
		height: 25px;
		width: 120px;
		margin: auto;
		text-align: center;
		font-weight: bold;
		font-size: 20px;
		color: #ffffff;
	}
</style>

<head>
</head>

<body>
	<div id="total_amount" style="text-align:center" >$0.00</div>
	<table style="text-align:center">
		<tr>
			<th>Quantity</th>
			<th>ID</th>
			<th>Dish Name</th>
			<th>Price</th>

		</tr>
	<?php
	while($row = mysql_fetch_array($result)){	
		$translate_array_id[$array_pos_counter]=$row["id"];
		$translate_array_name[$array_pos_counter]=$row["dish_name"];
		$translate_array_output[$array_pos_counter]=$row["dish_output_name"];
		$array_price[$array_pos_counter]= $row["dish_price"];
		echo "<tr>";
		echo "<td>";
		echo "<input type=text";
		echo " id= ";
		echo $row["id"];
		echo " name= " . $row["dish_name"];
		echo" value=0>";
		echo "</td>";
		echo "<td>";
		echo $row["id"];
		echo "</td>";
		echo "<td>";
		echo $row["dish_output_name"];
		echo "</td>";
		echo "<td>";
		echo "$". $row["dish_price"];
		echo "</td>";
		echo "</tr>";
		$array_pos_counter++;
		}
	$translate_array_id = implode(",", $translate_array_id);
	$translate_array_name = implode(",", $translate_array_name);
	$translate_array_output = implode(",", $translate_array_output);
	$array_price= implode(",", $array_price);

	?>
	<tr>
		<td>
		<button style="width:100%"  onclick="run()">Submit</button>
		</td>
	</tr>
	</table>
	<p>  </p>

<div id="output">
	<table id="receiptList" border="1">
		<tr>
			<th>DEC</th>
			<th>INC</th>
			<th>Dish</th>
			<th>Quantity</th>
			<th>SubTotal</th>
		<tr>	


	</table>
</div>	

</body>

<script type="text/javascript">

	var translate_array_id='<?php echo $translate_array_id; ?>'.split(",");
	var translate_array_name='<?php echo $translate_array_name; ?>'.split(",");
	var translate_array_output='<?php echo $translate_array_output; ?>'.split(",");
	var array_price='<?php echo $array_price; ?>'.split(",");

	var receipt_id_arr=[];
	var receipt_quantity_arr=[];
	window.onload=function(){
		document.getElementById("output").onclick=function(event){
			var target=event.target || event.srcElement;
			var targetID=target.id.substring(19);
			if(target && target.className=='decrement' && getQuantityWithID(targetID)<2){
					parentDiv=target.parentNode.parentNode;
					parentDiv.parentNode.removeChild(parentDiv);
					receipt_quantity_arr[getIndexWithID(targetID)]=0;
					document.getElementById("total_amount").innerHTML="$" + getTotal();
					
					return false;
			}
			else if(target && target.className=='increment'){
				receipt_quantity_arr[getIndexWithID(targetID)]++;
				document.getElementById('quantity_prim_key_' + targetID).innerHTML=receipt_quantity_arr[getIndexWithID(targetID)];
				document.getElementById('subTotal_prim_key_' + targetID).innerHTML="$" + 
									(getQuantityWithID(targetID)*getPriceWithID(targetID)).toFixed(2);
				document.getElementById("total_amount").innerHTML="$" + getTotal();					

			}else if(target && target.className=='decrement'){
				receipt_quantity_arr[getIndexWithID(targetID)]--;
				document.getElementById('quantity_prim_key_' + targetID).innerHTML=receipt_quantity_arr[getIndexWithID(targetID)];
				document.getElementById('subTotal_prim_key_' + targetID).innerHTML="$" + 
									(getQuantityWithID(targetID)*getPriceWithID(targetID)).toFixed(2);
				document.getElementById("total_amount").innerHTML="$" + getTotal();					
			}

			
			
		
			// document.getElementById("total_amount").innerHTML=getTotal();						
		}
	}
		function run(){
			var elements=document.getElementsByTagName("input");
			for(var i=0; i<elements.length; i++){
				if(isNaN(elements[i].value)){
					alert("Please enter only Numbers in Input");
					return;
				}
				else if(elements[i].value!=0){
					addDiv(elements[i].name);
					elements[i].value=0;
				}
			}
			document.getElementById("total_amount").innerHTML="$" + getTotal();			
			for(var i=0; i<receipt_quantity_arr.length; i++){

			console.log("ID:"+ receipt_id_arr[i] + "     Quantity:" + receipt_quantity_arr[i]+ "    SubTotal:$" + (getPriceWithID(receipt_id_arr[i])*receipt_quantity_arr[i]).toFixed(2));
			}
		}

		function getTotal(){
			var subTotal=0;

			for(var i=0; i<receipt_id_arr.length; i++){
				subTotal+=(getPriceWithID(receipt_id_arr[i])*receipt_quantity_arr[i]);
			}
			return  subTotal.toFixed(2);
		}	



		function addDiv(name){
			receipt_id_arr.push(getIDWithName(name));
			receipt_quantity_arr.push(Number(document.getElementById(getIDWithName(name)).value));
			var table=document.getElementById("receiptList");
			var row= document.createElement('tr');
			var decrement= document.createElement('td');
			var increment= document.createElement('td');
			var dishName= document.createElement('td');
			var quantity=document.createElement('td');
			var subTotal=document.createElement('td');
			var img1=document.createElement("img");
			var img2=document.createElement("img");
			
			img1.className="decrement";
			img1.src="http://www.steelneeds.com/image/minus_sign.png";
			img1.name=getPriceWithName(name);
			img1.id="decrement_Prim_Key_" + getIDWithName(name);


			img2.className="increment";
			img2.src="http://www-personal.umich.edu/~jensenl/images/BoxedPlusSign.gif";
			img2.name=getPriceWithName(name);
			img2.id="increment_Prim_Key_" + getIDWithName(name);

			decrement.appendChild(img1);
			increment.appendChild(img2);
			dishName.innerHTML=translateNameToOutput(name);
			quantity.innerHTML=Number(getQuantityWithName(name));
			quantity.id="quantity_prim_key_"+ getIDWithName(name);
			subTotal.innerHTML="$" + (getQuantityWithName(name)*getPriceWithName(name)).toFixed(2);
			subTotal.id="subTotal_prim_key_" + getIDWithName(name);
			
			
			row.appendChild(decrement);
			row.appendChild(increment);
			row.appendChild(dishName);
			row.appendChild(quantity);
			row.appendChild(subTotal);

			table.appendChild(row);
			
		}


function translateNameToOutput(name){
	for(var i=0; i<translate_array_name.length; i++){
		if(translate_array_name[i]==name){
			return translate_array_output[i];
		}
	}
		return "Output could not be found";
}

function translateIdToOutput(ID){
	for(var i=0; i<translate_array_id.length; i++){
		if(translate_array_id[i]==ID){
			return translate_array_output[i];
		}
	}
		return "Output could not be found";
}

function getPriceWithName(name){
	for(var i=0; i<translate_array_name.length; i++){
		if(translate_array_name[i]==name){
			return array_price[i];
		}
	}
		return "Price could not be found";
}

function getPriceWithID(ID){
	var name=getNameWithID(ID);
	return getPriceWithName(name);
}
function getNameWithID(ID){
	for( var i=0; i<translate_array_id.length; i++){
		if(translate_array_id[i]==ID){
			return translate_array_name[i];
		}
	}
	return "Name could not be found";
}

function getIDWithName(name){
	for(var i=0; i<translate_array_name.length; i++){
		if(translate_array_name[i]==name){
			return translate_array_id[i];
		}
	}
		return "ID could not be found";
}
function getIndexWithID(id){
	for(var i=0; i<receipt_id_arr.length; i++){
		if(receipt_id_arr[i]==id){
			return i;
		}
	}
	return 0;
}

function getQuantityWithID(id){

	for(var i=0; i<receipt_id_arr.length; i++){
		if(receipt_id_arr[i]==id){
			return receipt_quantity_arr[i];
		}
	}
	return "Quantity not found.";
}

function getQuantityWithName(name){
	var id=getIDWithName(name);
	return getQuantityWithID(id);
}


	</script>

</html>
