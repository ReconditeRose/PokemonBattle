<?php
session_start();
$host="whale.csse.rose-hulman.edu"; // Host name 
$username="333PokemonBattle"; // Mysql username 
$password="333PokemonBattle"; // Mysql password 
$db_name="pokemonBattle"; // Database name 

// Connect to server and select databse.
$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");
$ID=$_POST['type']; 
$var1 = $_POST['poke1'];
$var2 = $_POST['poke2'];
$BID = $_POST['BID'];
$Switch = "0";

if($ID == "Attack"){
$Move = $_POST['move'];
mssql_query("Exec QueueCommand $var1,$var2,1,$Move,$BID,$Switch");
}else if($ID =="Switch"){
$Switch = $_POST['Switch'];
echo $Switch . 'Hello'; 
mssql_query("Exec QueueCommand $var1,$var2,2,NULL,$BID,$Switch");
}
//header('Location:User.php');
?>