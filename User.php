<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
  <HEAD>
    <LINK href="style.css" rel="stylesheet" type="text/css">
  </HEAD>
  <BODY>
    <P class="special">This paragraph should have special green text.
  </BODY>
</HTML>


<?php
session_start();
echo "Hey ".$_SESSION['userName'];
echo '<li><a href="/Logout.php">Logout</a></li>';

$host="whale.csse.rose-hulman.edu";
$username="333PokemonBattle";
$password="333PokemonBattle";
$db_name="pokemonBattle"; 

$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");
$person = $_SESSION['userName'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if($_POST['edit']=="create"){
$result = mssql_query("Exec CreateParty @userName='$person'",$conn);
}else if($_POST['edit'] == "send"){
$sendValue = $_POST['send'];
$teamID = $_POST['teamID'];
$result = mssql_query("Exec sendRequest $teamID,$sendValue");
}else if($_POST['edit'] == "response"){
if($_POST['request'] == "Accept with Team"){
$BID = $_POST['BattleID'];
$Persona = $_POST['teamID'];
$result = mssql_query("Exec acceptRequest $Persona ,$BID");
}
}
}
?>

<H2> Battle Requests</H2>

<?php
echo' 
Send Battle Request to User:
<form name = "meh" action ="" method = "POST">
<input name = "send" text="Username">
<input type="hidden" name ="edit" value="send">
<select name ="teamID" >';
$posts = mssql_query("Exec SelectParties '$person'",$conn);
while ($row = mssql_fetch_array($posts)) {
echo '<option value="' . $row[0]. '" . >Team #'.$row[0].'</option>';
}
echo'
</select>
<input name = "value" value ="Send" type="Submit">
</form>';

?>

Sent Battle Requests:
<?php
echo '<table><tr><td>Sent to</td><td>With Team</td><td></td></tr>';
$reqs = mssql_query('Exec getOpenRequests "'.$person.'"');
while( $req = mssql_fetch_array($reqs)){

echo' 
<tr><td>' .$req[1] .'</td><td>' . $req[0] . '</td>
<td><form name= "accept request" action="" method ="POST">
<input type="submit" name="request" value="Cancel"></td>
<input type="hidden" name ="edit" value="cancel">
</tr></form>';

}
echo'</table>';
?>

<h4>Oustanding Requests:</h4>

<?php
$reqs = mssql_query('Exec recievedRequests "'.$person.'"');
while( $req = mssql_fetch_array($reqs)){

echo' 
Request from User:' .$req[1] .'
<form name= "accept request" action="" method ="POST">
<input type="submit" name="request" value="ignore">
<input type="submit" name="request" value="Accept with Team">
<input type="hidden" name ="edit" value="response">
<input type="hidden" name="BattleID" value="' . $req[2] . '">
<select name="teamID">
';
$posts = mssql_query("Exec SelectParties '$person'",$conn);
while ($row = mssql_fetch_array($posts)) {
echo '<option value="' . $row[0]. '" . >Team #'.$row[0].'</option>';
}
echo'
</select>
</form>';

}
?>


<H2>Active Teams</H2>
<body background = "/images/pw_bg.png">

<?php

$posts = mssql_query("Exec BattleParties '$person'",$conn);
while ($row = mssql_fetch_array($posts)) {
	$PartyID = htmlspecialchars($row[0], ENT_QUOTES);
	$nameID = htmlspecialchars($row[1], ENT_QUOTES);
    echo '<strong>User Team#:' . $PartyID . ' vs User:' . $nameID . '</strong>';	
	$active = mssql_query("Exec getActivePokemon $PartyID",$conn);
	$active = mssql_fetch_array($active);
	
	$active2 = mssql_query("Exec getEnemyActivePokemon $PartyID",$conn);
	$active2 = mssql_fetch_array($active2);

	$fallback = mssql_query("Exec SelectFallback $PartyID",$conn);
	$fallback = mssql_fetch_Array($fallback);
	
	$pokemon = mssql_query("Exec SelectPokemon '$row[0]'",$conn);
	$x = 1;
	echo '<table>
	<tr><td>Status</td>
	<td>Pending</td>
	<td>Switch</td>
	</tr>';
	
	$hp1 = ($active[2]+90)*2;
	$hp2 = ($active2[2]+90)*2;
	$battle = $row[2];
	
	while ($x<=6){
		if($poke = mssql_fetch_array($pokemon)){

		}else{
			$poke[0]="";
			$poke[1]="";
		}
		echo '<tr>';
		switch($x){
		case 1:
			echo '<td>'."$active2[0] HP:$active2[1]/$hp2". '</td><td rowspan="2"></td>';
			break;
		case 2:
			if($active2[0]==""){
			echo'<td>Selecting Pokemon</td>';
			}else{
			$ratio = intval(100*$active2[1]/$hp2);
			echo '<td><div style="width :'. $ratio.'%" class="HealthBar"></div></td>'; 
			echo $ratio;
			}
			break;
		case 3: 
			echo '<td rowspan="2"></td><td>'."$active[0] HP:$active[1]/$hp1". '</td>';
			break;
		case 4:
			if($active[0]==""){
			echo '<td> Select a Pokemon </td>';
			}else{
			$ratio = intval(100*$active[1]/$hp1);
			echo '<td><div style="width :'.$ratio .'%" class="HealthBar"></div></td>'; 
			echo $ratio;
			}
			break;
		case 5:
		if($active2[0]!="" and $active[0]!=""){
			echo '<form method="post" action="MakeMove.php"><input type="hidden" name ="type" value="Attack">
				<input type="hidden" name ="BID" value ='. $battle.'>
				<input type="hidden" name="poke1" value='. $active[7] .'>
				<input type="hidden" name="poke2" value='. $active2[3] .'>
			';
			if($active[3]==""){
				echo '<td><input type="submit" style="width:100px" name ="move" value ="Struggle"></td>';
			}else{
				echo '<td><input type="submit" style="width:100px" name ="move" value ="'. $active[3] .'"></td>';
			}
			if($active[4]==""){
				echo '<td><input type="submit" style="width:100px" name ="move" value ="Struggle"></td>';
			}else{
				echo '<td><input type="submit" style="width:100px" name ="move" value ="'. $active[4] .'"></td>';
			}
			echo '</form>';
			}else{
			echo '<td>--</td><td>--</td>';
			}
			
			
			break;
		case 6:
				if($active2[0]!="" and $active[0]!=""){
			echo '<form method="post" action="MakeMove.php"><input type="hidden" name ="type" value="Attack">';
			if($active[5]==""){
				echo '<td><input type="submit" style="width:100px" name ="move" value ="Struggle"></td>';
			}else{
			echo '<td><input type="submit" style="width:100px" name ="move" value ="'. $active[5] .'"></td>';
			}
			if($active[6]==""){
				echo '<td><input type="submit" style="width:100px" name ="move" value ="Struggle"></td>';
			}else{
				echo '<td><input type="submit" style="width:100px" name ="move" value ="'. $active[6] .'"></td>';
			}
			echo '</form>';
			break;
			}else{
			echo '<td>--</td><td>--</td>';
			}
			default:
			break;
		}
		if($poke[1]!=""){
		if(!$active[0]==""){
			echo '<td><form method="POST" action="MakeMove.php"><input type="hidden" name ="type" value="Switch">
				<input type="hidden" name ="BID" value ='. $battle.'>
				<input type="hidden" name="poke1" value='. $active[7] .'>
				<input type="hidden" name="poke2" value='. $active2[3] .'>
				<input type="hidden" name="Switch" value='.$poke[1].'>
				<input type="submit" style="width:100px" value ='.$poke[0].'>
				</form></td></tr>';
				}else{
			echo '<td><form method="POST" action="MakeMove.php"><input type="hidden" name ="type" value="Switch">
				<input type="hidden" name ="BID" value ='. $battle.'>
				<input type="hidden" name="poke1" value='. $fallback[1] .'>
				<input type="hidden" name="poke2" value=0>
				<input type="hidden" name="Switch" value='.$poke[1].'>
				<input type="submit" style="width:100px" value ='.$poke[0].'>
				</form></td></tr>';
				
				
				}
		}else{
			echo '<td></td>';
		}
		$x = $x+1;
	}
	echo '</table>';
	
}

echo '<H2>Idle Teams</H2>';
$posts = mssql_query("Exec SelectParties '$person'",$conn);
while ($row = mssql_fetch_array($posts)) {
	$nameID = htmlspecialchars($row[0], ENT_QUOTES);
    echo '<form method="get" action="ViewTeam.php"><p><strong>Team ID:' . $nameID . '</strong>' .
    '<input type="submit" value="Edit Team"> ' .
	'<span id ="main">' .
	'<input type="hidden" name="TeamID" value=' . $row[0] . ' /> </form></span>';
	
	
	$pokemon = mssql_query("Exec SelectPokemon '$row[0]'",$conn);
	$stats = mssql_query("Exec getStats $row[0]");
	$stats = mssql_fetch_array($stats);
	$x = 1;
	echo "<table>
	<tr><td>Record</td>
	<td>$stats[0]:$stats[1]</td>
	</tr>";
	while ($poke = mssql_fetch_array($pokemon)){
		echo '<tr>
		<td>Pokemon #' . $x .': ' . $poke[0] . '</td>
		<td>Stuff</td>
		</tr>';
		$x = $x+1;
	}
	echo '</table>';
}

?>

<form method="post" action =""><input type="hidden" name ="edit" value="create"><input type="submit" value ="Create New Team"></form>
