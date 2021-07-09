<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

include "conf.php"; // config file with db settings

$mysqli = mysqli_connect($host, $login, $password, $database);
$columns = array(
    'N',
    'Version',
    'Name',
    'Race',
    'Class',
    'Level',
    'Exp',
    'MaxDepth',
    'TurnsUsed',
    'Death'
);
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC'; // default: asc

?> <!---------------------------------------------- php end  -->

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Tangaria | multiplayer roguelike game</title>
<style>
html {
    padding: 3px;
}
form {
    display: inline;
}
table {
    border: 1px solid #000;
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
}
th {
    background-color: #000;
    border: 1px solid #000;
}
th:hover {
    background-color: #333;
}
th a {
    display: block;
    text-decoration:none;
    padding: 3px;
    color: #fff;
    font-weight: bold;
    font-size: 12px;
    margin-left: 5px;                
}
tr {
    background-color: #fff;
}
tr .active {
    background-color: #eee;
}            
td {
    padding-left: 12px;
    color: #000;
    border: 1px solid #ddd;
}
</style>
</head>
<body>

<?php // <-------------------------------------------- php start

// to save form value after user selected it
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

// Dropdown menu and submit button for Race and Class
$sql = "SELECT Race FROM race";
$result_rc = mysqli_query($mysqli, $sql);

echo 
'<form method="post" action="">
<select name=selectRace>';

if (!isset($_SESSION['idRace']))
{
    $_SESSION['idRace'] = $_POST['selectRace'];    
}
echo "<option value=All>All</option>";
while ($row = mysqli_fetch_array($result_rc)) 
{
    echo "<option ";
    
    if (isset($_SESSION['idRace']) && $_SESSION['idRace'] == $row['Race'])
        echo "selected ";
    echo "value='" .$row['Race']."'> ".$row['Race'] . "</option>"; 
}

echo 
'</select>
<input type="submit" name="input_sR" class="button" value="Select Race">
</form>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

$sql = "SELECT Class FROM class";
$result_rc = mysqli_query($mysqli, $sql);

echo
'<form method="post">
<select name=selectClass>';

if (!isset($_SESSION['idClass']))
{
    $_SESSION['idClass'] = $_POST['selectClass'];    
}

echo "<option value=All>All</option>";
while ($row = mysqli_fetch_array($result_rc)) 
{
    echo "<option ";
	if (isset($_SESSION['idClass']) && $_SESSION['idClass'] == $row['Class'])
			echo "selected ";
	echo "value='" .$row['Class']."'> ".$row['Class'] . "</option>"; 
}

echo 
'</select>
<input type="submit" name="input_sC" class="button" value="Select Class">
</form>';
/* End of dropdown menu and submit button for Race and Class */


// Selected choice Race-Class

if (isset($_POST['input_sR']))
{
	if ($_SESSION['idRace'] == "All")
	{
		$query = 'SELECT * FROM igroki ORDER BY ';
		$result = $mysqli -> query($query.  $column . ' ' . $sort_order);
	}
	else
	{
		$number = $_POST['selectRace'];
		$query = "SELECT * FROM igroki WHERE Race='$number' ORDER BY ";
		$result = $mysqli -> query($query.  $column . ' ' . $sort_order);
	}
}

else if (isset($_POST['input_sC']))
{
	if ($_SESSION['idClass'] == "All")
	{
		$query = 'SELECT * FROM igroki ORDER BY ';
		$result = $mysqli -> query($query.  $column . ' ' . $sort_order);
	}
	else
	{
		$number = $_POST['selectClass'];
		$query = "SELECT * FROM igroki WHERE Class='$number' ORDER BY ";
		$result = $mysqli -> query($query.  $column . ' ' . $sort_order);
	}
}
else
{
	$query = 'SELECT * FROM igroki ORDER BY ';
	$result = $mysqli -> query($query.  $column . ' ' . $sort_order);
}
						
// End selected choice Race-Class
if ($result)
{
    $up_down = str_replace(array(
        'ASC',
        'DESC'
    ) , array(
        'up',
        'down'
    ) , $sort_order);
    $asc_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
    $sort_active = ' class="active"';

?> <!---------------------------------------------- php end  -->

<table>

<tr>
<th style="width:1%;"><a href="index.php?column=N&order=
<?php echo $asc_desc; ?>"># <img src="triangles.png" width="8"></img><i
<?php echo $column == 'N' ? '-' . $up_down : ''; ?>"></i></a></th>

<th style="width:6%;"><a href="index.php?column=Version&order=
<?php echo $asc_desc; ?>">Version <img src="triangles.png" width="8"></img><i
<?php echo $column == 'Version' ? '-' . $up_down : ''; ?>"></i></a></th>

<th><a href="index.php?column=Name&order=
<?php echo $asc_desc; ?>">Name <img src="triangles.png" width="8"></img><i
<?php echo $column == 'Name' ? '-' . $up_down : ''; ?>"></i></a></th>

<th><a href="index.php?column=Race&order=
<?php echo $asc_desc; ?>">Race <img src="triangles.png" width="8"></img><i
<?php echo $column == 'Race' ? '-' . $up_down : ''; ?>"></i></a></th>

<th><a href="index.php?column=Class&order=
<?php echo $asc_desc; ?>">Class <img src="triangles.png" width="8"></img><i
<?php echo $column == 'Class' ? '-' . $up_down : ''; ?>"></i></a></th>

<th><a href="index.php?column=Level&order=
<?php echo $asc_desc; ?>">Lvl <img src="triangles.png" width="8"></img><i
<?php echo $column == 'Level' ? '-' . $up_down : ''; ?>"></i></a></th>

<th><a href="index.php?column=Exp&order=
<?php echo $asc_desc; ?>">Exp <img src="triangles.png" width="8"></img><i
<?php echo $column == 'Exp' ? '-' . $up_down : ''; ?>"></i></a></th>

<th><a href="index.php?column=MaxDepth&order=
<?php echo $asc_desc; ?>">MaxDepth <img src="triangles.png" width="8"></img><i
<?php echo $column == 'MaxDepth' ? '-' . $up_down : ''; ?>"></i></a></th>

<th><a href="index.php?column=TurnsUsed&order=
<?php echo $asc_desc; ?>">Turns <img src="triangles.png" width="8"></img><i
<?php echo $column == 'TurnsUsed' ? '-' . $up_down : ''; ?>"></i></a></th>

<th><a href="index.php?column=Death&order=
<?php echo $asc_desc; ?>">Death <img src="triangles.png" width="8"></img><i
<?php echo $column == 'Death' ? '-' . $up_down : ''; ?>"></i></a></th>
</tr>

<?php while ($row = $result->fetch_assoc()) : ?>

<tr>
<td<?php echo $column == 'N' ? $sort_active : '';?>>
<?php echo $row['N'];?></td>

<td<?php echo $column == 'Version' ? $sort_active : ''; ?>>
<?php echo $row['Version']; ?></td>

<td<?php echo $column == 'Name' ? $sort_active : ''; ?>>
<a target="_blank" href="chars/<?php echo $row['File']; ?>"><?php echo $row['Name']; ?></a></td>

<td<?php echo $column == 'Race' ? $sort_active : ''; ?>>
<?php echo $row['Race']; ?></td>

<td<?php echo $column == 'Class' ? $sort_active : ''; ?>>
<?php echo $row['Class']; ?></td>

<td<?php echo $column == 'Level' ? $sort_active : ''; ?>>
<?php echo $row['Level']; ?></td>

<td<?php echo $column == 'Exp' ? $sort_active : ''; ?>>
<?php echo $row['Exp']; ?></td>

<td<?php echo $column == 'MaxDepth' ? $sort_active : ''; ?>>
<?php echo $row['MaxDepth']; ?></td>

<td<?php echo $column == 'TurnsUsed' ? $sort_active : ''; ?>>
<?php echo $row['TurnsUsed']; ?></td>

<td<?php echo $column == 'Death' ? $sort_active : ''; ?>>
<?php echo $row['Death']; ?></td>
</tr>

<?php endwhile; ?>

</table>
</body>
</html>

<?php

    $result->free();

}

?>