<?php
session_start();
$host="whale.csse.rose-hulman.edu"; // Host name 
$username="olsonmc"; // Mysql username 
$password="33upCosh"; // Mysql password 
$db_name="pokemonBattle"; // Database name 

// Connect to server and select databse.
$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");

// username and password sent from form 
$poke=$_POST['poke']; 
$ID=$_POST['PartyID']; 

// To protect MySQL injection (more detail about MySQL injection)
$poke = stripslashes($poke);
$ID = stripslashes($ID);
$poke = mysql_real_escape_string($poke);
$ID = mysql_real_escape_string($ID);
$sql="Exec AddPokemon '$poke','$ID'";
$result=mssql_query($sql);

header("location:AddPokemon.php?TeamID=".$ID);
?>