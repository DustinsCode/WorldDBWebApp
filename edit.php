<?php
    header("Content-Type: text/html; charset=UTF-8");
    session_start();

echo "<link rel='stylesheet' type='text/css' href='styles.css' />";
echo "<link href=\"https://fonts.googleapis.com/css?family=Roboto+Condensed\" rel=\"stylesheet\">";
echo "<h1>It's a big world.</h1>";

$host = '';
$user = '';
$pass = '';
$dbname = '';
$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8");


if(!isset($_POST['submit'])){


    $country = $_GET['country'];
    if ($country !== "") {
        $_SESSION['editCountry'] = $country;
    }
//    echo "wow" . $_SESSION['editCountry'];
    echo "<h3>Edit Population of " . $_SESSION['editCountry'] . "</h3>";
}else {
    echo "<h3>Edit Population of " . $_SESSION['editCountry'] . "</h3>";
}
?>

<form action="edit.php" method="POST">

    Enter new population:

    <input type="number" name="newPop">
    <input type="submit" name="submit">

</form>


<?php

if(isset($_POST['submit'])){

    //update the database
    $updateString = "UPDATE country SET Population = " . $_POST['newPop'] . " WHERE country.Name = '" . $_SESSION['editCountry'] . "';";

    if($conn->query($updateString)){
        echo "<form action='javascript: history.go(-2)'>";
            echo "Updated population to " . $_POST['newPop'] . " ";

        echo "<button>Ok</button>";
        echo "</form>";
    }
}
?>

