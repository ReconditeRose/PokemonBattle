<!DOCTYPE html>

<?php
$host="whale.csse.rose-hulman.edu"; // Host name 
$username="333PokemonBattle"; // Mysql username 
$password="333PokemonBattle"; // Mysql password 
$db_name="pokemonBattle"; // Database name 

$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
$type = $_GET['CallType'];
$response = false;
if($type == 'PokemonTeam'){
	$username = $_GET['Username'];
	$password = $_GET['Password'];
	$TeamID = $_GET['TeamID'];
	$query = "MobileUser $username,$password,$TeamID";
	$response = true;
}
if($type == 'Login'){
	$username = $_GET['Username'];
	$password = $_GET['Password'];
	$sql="Authenticate '$username', '$password'";
	$result=mssql_query($sql);
	$return = array('a' => mssql_num_rows($result));
	echo json_encode($return);
}

if($response == true){
$result = mssql_query($query);
$result = mssql_fetch_array($result);
echo $result[0];
echo json_encode($result[0]);
}
}
?>