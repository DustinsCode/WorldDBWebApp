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

    $country = $_GET['city'];

    $queryString = "SELECT COUNT(*) FROM city, country WHERE city.countrycode = country.code AND country.Name = '" . $country . "';";

    $count = $conn->query($queryString);
    while ($row = $count->fetch_assoc()) {
        echo "<h3>There are " . $row['COUNT(*)'] . " cities in " . $country . ", ". $_SESSION['continent'] . "</h3>";
        break;
    }

?>


<table border="1">
    <tr>
        <th>City</th>
        <th>District</th>
        <th>Population</th>
    </tr>
    <?php

    $cityQueryString = "SELECT city.Name, city.District, city.Population FROM city, country WHERE city.countrycode = country.code AND country.Name = '" . $country . "';";
    $cities = $conn->query($cityQueryString);

    while($tableRow = $cities->fetch_assoc()){
        echo "<tr>";
            echo "<td>" . $tableRow['Name'] . "</td>";
            echo "<td>" . $tableRow['District'] . "</td>";
            echo "<td>" . $tableRow['Population'] . "</td>";
        echo "</tr>";
    }
    ?>

</table>
