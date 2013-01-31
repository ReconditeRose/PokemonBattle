<!DOCTYPE html>
<style>
table,td,th
{
border:1px solid black;
}
table
{
width:100%;
}
th
{
height:50px;
}
</style>
</head>

<?php
session_start();
$host="whale.csse.rose-hulman.edu"; // Host name 
$username="olsonmc"; // Mysql username 
$password="33upCosh"; // Mysql password 
$db_name="pokemonBattle"; // Database name 

// Connect to server and select databse.
$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");

echo '<li><a href="/User.php">Home</a></li>'
?>

<body background = "/images/pw_bg.png">

<html>
<head>
<title>Welcome!</title>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$id = $_POST['PokeID'];
mssql_query("Exec DeletePoke $id");
header("Location:ViewTeam.php?TeamID=" . $_GET['TeamID']);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

echo '<form action = "DeleteParty.php" method = "post"><input type = "submit" value = "Delete Party">
<input type = "hidden" name ="TeamID" value = '.$_GET['TeamID'].'></form>';

	$id = $_GET['TeamID'];

	echo '<H1>Team #' . $id . '</H1>';
		
	$result = 'Select pokemonName, Attack,Defense,[Sp Attack],[Sp Defense],Speed,HP,Move1,Move2,Move3,Move4,PokemonID from UserPokemon where PartyNO=' . $id ;
	$poke = mssql_query($result,$conn) or die(‘error’);
		echo '<table>';
	while($indPoke = mssql_fetch_array($poke)){

echo '<TR>
<TD>' . $indPoke[0] . '</TD>
<TD><FORM METHOD="POST" ACTION="">
<INPUT TYPE="hidden" name = "PokeID" VALUE='.$indPoke[11].'>
<INPUT TYPE="submit" VALUE="Delete">
</FORM></TD>

<TD><FORM METHOD="GET" ACTION="AddMove.php">
<INPUT TYPE="hidden" name = "PokeID" VALUE='.$indPoke[11].'>
<INPUT TYPE="submit" VALUE="Add Move">
</FORM></TD><TD><TD><TD>

</TR>
<TR>
<TD>Atk:'.$indPoke[1].'</TD>
<TD>Def:'.$indPoke[2].'</TD>
<TD>SP Atk:'.$indPoke[3].'</TD>
<TD>SP Def:'.$indPoke[4].'</TD>
<TD>Spd:'.$indPoke[5].'</TD>
<TD>HP:'.$indPoke[6].'</TD>
</TR>

<TR>
<TD>Moves</TD>
<TD>'.$indPoke[7].'<TD>
<TD>'.$indPoke[8].'</TD>
<TD>'.$indPoke[9].'</TD>
<TD>'.$indPoke[10].'</TD>
</TR>';		
	}
	'</TABLE>';
	echo '<Form action = "AddPokemon.php" method = "get"><p>' .
		'<input type = "submit" value = "Add Pokemon"><input type ="hidden" name = "TeamID" value = ' . $id . '></form>';
}else{
	echo "You seem to be in the wrong place?";

}
?> 

