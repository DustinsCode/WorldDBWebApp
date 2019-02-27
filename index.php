<?php
header("Content-Type: text/html; charset=UTF-8");
session_start();

echo "<link rel='stylesheet' type='text/css' href='styles.css' />";
echo "<link href=\"https://fonts.googleapis.com/css?family=Roboto+Condensed\" rel=\"stylesheet\">";

$host = 'cis.gvsu.edu';
$user = 'thurstdu';
$pass = 'thurstdu';
$dbname = 'thurstdu';
$conn = new mysqli($host, $user, $pass, $dbname);

//Query to obtain continents
$getstr = "SELECT DISTINCT continent FROM country;";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $conn->set_charset("utf8");
    // $_SESSION['db'] = $conn;
}

$conts = $conn->query($getstr);
?>

<h1>It's a big world.</h1>
<h4>Dustin Thurston</h4>
<p><img src="me.jpg" alt="It's me" height=64px width=64px/></p>
<form action="countries.php" method="GET">
    Select a continent of interest: 
    <select name="cont">
        <?php
            while ($row = $conts->fetch_assoc()){
                print "<option value=" . $row['continent'] . ">" . $row['continent'] ."</option>";
            }

            //Destroy the persisting session data so that we don't get the wrong results on the next page
            session_destroy();
        ?>
    </select>
    <button>Submit</button>
    
</form>
