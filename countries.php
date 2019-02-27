<?php
    header("Content-Type: text/html; charset=UTF-8");

    session_start();
echo "<link rel='stylesheet' type='text/css' href='styles.css' />";
echo "<link href=\"https://fonts.googleapis.com/css?family=Roboto+Condensed\" rel=\"stylesheet\">";
echo "<h1>It's a big world.</h1>";

    $contVar = urldecode($_GET['cont']);
    $host = 'cis.gvsu.edu';
    $user = 'thurstdu';
    $pass = 'thurstdu';
    $dbname = 'thurstdu';
    $conn = new mysqli($host, $user, $pass, $dbname);    
    $conn->set_charset("utf8");

    if($contVar == ""){
        $contVar = "empty";
    }
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $conn->set_charset("utf8");
    // $_SESSION['db'] = $conn;
}

    if(!isset($_SESSION['continent']) && strpos($_SESSION['continent'], $contVar) == false){
        //query for number of all countries with selected continent
        $queryString = "SELECT Continent, COUNT(*) FROM country WHERE continent LIKE " . "'" . $contVar . "%' GROUP BY Continent";
        $result = $conn->query($queryString);
        // set h1 to say number of countries found and the selected continent
        while ($row = $result->fetch_assoc()) {
            echo "<h3>There are " . $row['COUNT(*)'] . ' countries in ' . $row['Continent'] . "</h3>";
            $_SESSION['numCountries'] = $row['COUNT(*)'];
            $_SESSION['continent'] = $row['Continent'];
            break;
        }
    }else{
        echo "<h3>There are " . $_SESSION['numCountries'] . ' countries in ' . $_SESSION['continent'] . "</h3>";
    }

    //query and make a table for country.name, capital (name of city, not code), 
    // head of state, and population
    if(isset($_GET['sort'])){
        $sortVal = $_GET['sort'];
        if($sortVal == "pop"){
            $tableQuery = "SELECT country.Name, city.Name as capitol, country.HeadOfState, country.Population FROM country LEFT JOIN city ON country.capital = city.ID WHERE country.continent = '" . $_SESSION['continent'] . "' ORDER BY country.population;";
        }else{
            $tableQuery = "SELECT country.Name, city.Name as capitol, country.HeadOfState, country.Population FROM country LEFT JOIN city ON country.capital = city.ID WHERE country.continent = '" . $_SESSION['continent'] . "';";
        }
    }else{
        $tableQuery = "SELECT country.Name, city.Name as capitol, country.HeadOfState, country.Population FROM country LEFT JOIN city ON country.capital = city.ID WHERE country.continent = '" . $_SESSION['continent'] . "';";

    }
    $tableResult = $conn->query($tableQuery);
?>

<head>
    <style type="text/css">
        .pop {
            text-align: right;
        }

        .officialLanguage {
            color: red;
            font-weight: bold;
        }

        p{
            display: inline;
        }
    </style>
</head>


<form action="countries.php">
        Sort results by:
        <div>
        <input type="radio" name="sort" value="alpha">Country <br>
        <input type="radio" name="sort" value="pop">Population <br>
        <button>Sort</button>
        </div>
</form>
<?php

?>

<table border=1>
    <tr>
        <th>Country</th>
        <th>Capital</th>
        <th>Head of State</th>
        <th>Population</th>
        <th>Language(s) Spoken</th>
    </tr>
    <?php
        while ($row = $tableResult->fetch_assoc()){
            echo '<tr>';
                echo '<td><a href="cities.php?city=' . $row['Name'] . '">' . $row['Name'] . '</a></td>';
                echo '<td>' . $row['capitol']. '</td>';
                echo '<td>' . $row['HeadOfState'] . '</td>';
                echo '<td class="pop"><a href="edit.php?country=' . $row['Name'] .'">' . $row['Population'] . '</a></td>';
                echo '<td>';
                    //TODO: query for top 5 languages spoken in each country
                    $languageQueryString = "SELECT Language, Percentage, IsOfficial FROM countrylanguage, country WHERE countrylanguage.CountryCode = country.code AND country.name = '" . $row['Name'] . "' ORDER BY Percentage desc LIMIT 5;";
                    $languageResult = $conn->query($languageQueryString);
        //                var_dump($languageResult);
                    $numResults = mysqli_num_rows($languageResult);
                    $counter = 0;
                    while($innerRow = $languageResult->fetch_assoc()){
                        if (++$counter == $numResults) {
                            // last row
                            if($innerRow['IsOfficial'] == 'T'){
                                echo '<p class="officialLanguage">' . $innerRow['Language'] . ' (' . $innerRow['Percentage'] . ')</p>';

                            }else {
                                echo '<p>' . $innerRow['Language'] . ' (' . $innerRow['Percentage'] . ')' . '</p>';
                            }
                        }else {
                            // not last row
                            if ($innerRow['IsOfficial'] == 'T') {
                                echo '<p class="officialLanguage">' . $innerRow['Language'] . ' (' . $innerRow['Percentage'] . '), </p>';
                            } else {
                                echo '<p> ' . $innerRow['Language'] . ' (' . $innerRow['Percentage'] . '), </php>';
                            }
                        }
                    }
                echo '</td>';
            echo '</tr>';
        }
    ?>
</table>
