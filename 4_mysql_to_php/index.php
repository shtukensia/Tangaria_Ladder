<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

include "../include/conf.php"; // config file with db settings

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
    'Death',
    'Account',
    'Date',
    'Timestamp'
);
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[12];
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'asc' ? 'ASC':'DESC'; // default: desc

?> <!---------------------------------------------- php end  -->

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Tangaria | multiplayer roguelike game</title>
<link rel="stylesheet" type="text/css" href="styles.css">
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
$result_r = mysqli_query($mysqli, $sql);
$sql = "SELECT Class FROM class";
$result_c = mysqli_query($mysqli, $sql);

// Form Select Race
echo 
'<form method="post" action="">
<select name=selectRace>';

if (!isset($_SESSION['idRace']))
{
    $_SESSION['idRace'] = $_POST['selectRace'];    
}
echo "<option value=All>All</option>";
while ($row = mysqli_fetch_array($result_r)) 
{
    echo "<option ";
    
    if (isset($_SESSION['idRace']) && $_SESSION['idRace'] == $row['Race'])
        echo "selected ";
    echo "value='" .$row['Race']."'> ".$row['Race'] . "</option>"; 
}

echo 
'</select>
<input type="submit" name="input_sR" class="button" value="Select Race">';



// Form Select Class

echo
'<form method="post">
<select name=selectClass>';

if (!isset($_SESSION['idClass']))
{
    $_SESSION['idClass'] = $_POST['selectClass'];    
}

echo "<option value=All>All</option>";
while ($row = mysqli_fetch_array($result_c)) 
{
    echo "<option ";
	if (isset($_SESSION['idClass']) && $_SESSION['idClass'] == $row['Class'])
			echo "selected ";
	echo "value='" .$row['Class']."'> ".$row['Class'] . "</option>"; 
}

echo 
'</select>
<input type="submit" name="input_sC" class="button" value="Select Class"></form>';

echo 
'<form id="accountFilterForm">
<label for="nameFilter" style="margin-left: 10px;">Name:</label>
<input type="text" id="nameFilter">
<label for="deathFilter" style="margin-left: 10px;">Death:</label>
<input type="text" id="deathFilter">
<label for="accountFilter" style="margin-left: 10px;">Account:</label>
<input type="text" id="accountFilter">
<button type="button" onclick="filterByCriteria()">Show</button>
</form>';


/* End of dropdown menu and submit button for Race and Class */


// Selected choice Race-Class

if (isset($_POST['input_sR']) or isset($_POST['input_sC']))
{
	$numberC = $_POST['selectClass'];
	$numberR = $_POST['selectRace'];
	$query_raw = "SELECT * FROM igroki";
	
	if ($_SESSION['idRace'] !="All" && $_SESSION['idClass'] == "All")
	{
		$query_raw = "SELECT * FROM igroki WHERE Race='$numberR'";
	}
	else if ($_SESSION['idRace'] =="All" && $_SESSION['idClass'] != "All")
	{
		$query_raw = "SELECT * FROM igroki WHERE Class='$numberC'" ;
	}
	else if ($_SESSION['idRace'] !="All" && $_SESSION['idClass'] != "All")
	{
		$query_raw = "SELECT * FROM igroki WHERE Class='$numberC' AND Race='$numberR'";
	}

	$query = $query_raw . "ORDER BY ";
	$result = $mysqli -> query($query.  $column . ' ' . $sort_order);
	if ($_SESSION['idRace'] == "All" && $_SESSION['idClass'] == "All")
	{
		$query = 'SELECT * FROM igroki ORDER BY ';
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

<th><a href="index.php?column=Account&order=
<?php echo $asc_desc; ?>">Player <img src="triangles.png" width="8"></img><i
<?php echo $column == 'Account' ? '-' . $up_down : ''; ?>"></i></a></th>

<th><a href="index.php?column=Timestamp&order=
<?php echo $asc_desc; ?>">Date <img src="triangles.png" width="8"></img><i
<?php echo $column == 'Timestamp' ? '-' . $up_down : ''; ?>"></i></a></th>
</tr>

<?php while ($row = $result->fetch_assoc()) : ?>

<tr <?php if ($row['Winner']=='Winner'){echo 'style="background-color: #9ACD32;"';}?>>
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

<td<?php echo $column == 'Account' ? $sort_active : ''; ?>>
<?php echo $row['Account']; ?></td>

<td<?php echo $column == 'Date' ? $sort_active : ''; ?>>
<?php echo $row['Date']; ?></td>
</tr>


<?php endwhile; ?>

</table>

<script>
    function filterByCriteria() {
        const characterName = document.getElementById("nameFilter").value.trim();
        const deathValue = document.getElementById("deathFilter").value.trim();
        const accountName = document.getElementById("accountFilter").value.trim();
        const rows = document.querySelectorAll("table tr");
        
        for (let i = 1; i < rows.length; i++) { 
            const cells = rows[i].querySelectorAll("td");
            const nameCell = cells[2].textContent.trim(); 
            const deathCell = cells[9].textContent.trim();
            const accountCell = cells[10].textContent.trim();

            if (
                (characterName && nameCell !== characterName) || 
                (deathValue && deathCell !== deathValue) || 
                (accountName && accountCell !== accountName)
            ) {
                rows[i].style.display = "none";
            } else {
                rows[i].style.display = "";
            }
        }
    }

    document.body.addEventListener('keydown', function(e) {
        const targetId = e.target.id;
        if ((targetId === "nameFilter" || targetId === "deathFilter" || targetId === "accountFilter") &&
             e.keyCode === 13) { // Check if the pressed key was "Enter"
            e.preventDefault();  // Prevent the form from submitting
            filterByCriteria();  // Call common function instead
        }
    });

</script>

</body>
</html>

<?php

    $result->free();

}

?>