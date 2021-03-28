<?php
include 'dbh.php';
include 'queryDisplay.php';

$uid = $_GET[uid];
echo "Hello, ".$uid. "<br>";
echo "<hr />";
?>


<html>
<head>
    <title>Movie Info</title>
</head>

<body>

<h2>The number of movies</h2>
<form method="GET" action="main.php"> <!--refresh page when submitted-->
    <input type="hidden" id="countTupleRequest" name="countTupleRequest">
    <input type="submit" name="countTuples"></p>
</form>


<h2>Display all the movies with their info</h2>
<form method="GET" action="main.php"> <!--refresh page when submitted-->
    <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
    <input type="submit" value = "display" name="displayTuples"></p>
</form>


<form class="form-horizontal" method="POST" action="#">
    <input type="hidden" id="searchRequest" name="searchRequest">
    <div class="form_input">
        <select id="filter_category" class="form-control" name="filter_category">
            <option value="" disabled selected hidden>choose category</option>
            <option value="">n/a</option>
            <option value="Fantasy">Crime</option>
            <option value="Fantasy">Disaster</option>
            <option value="Fantasy">Fantasy</option>
            <?php echo "some function here" ?>
            <option value="all">All Category</option>
        </select>
    </div>
    <hr>
    <div class="form_input">
        <select id="filter_country" class="form-control" name="filter_country">
            <option value="" disabled selected hidden>choose country</option>
            <option value="">n/a</option>
            <option value="Canada">Canada</option>
            <option value="China">China</option>
            <option value="USA">USA</option>
            <?php "some function here" ?>
        </select>
    </div>
    <hr>
    <div class="form_input">
        <select id="filter_year" class="form-control" name="filter_year">
            <option value="" disabled selected hidden>choose year</option>
            <option value="">n/a</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <?php "year function()" ?>
        </select>
    </div>
    <hr>
    <div class="form_input">
        <select id="filter_rating" class="form-control" name="filter_rating">
            <option value="" disabled selected hidden>choose Rating</option>
            <option value="">n/a</option>
            <option value=10>10</option>
            <option value=9>9</option>
            <option value=8>8</option>
            <option value=7>7</option>
            <option value=6>6</option>
            <option value=5>5</option>
            <option value=4>4</option>
            <option value=3>3</option>
            <option value=2>2</option>
            <option value=1>1</option>
        </select>
    </div>
    <hr>
    <div class="form_input">
        Top User Rated: <input type="checkbox" id="filter_topRating" name="filter_topRating">
    </div>
    <hr>
    <div class="form_input">
        Most reviewed: <input type="checkbox" id="filter_mostreviewd" name="filter_mostreviewd">
    </div>
    <hr>
    <input type="submit" name="search" value="search" class="btn btn-primary"/>
</form>


<?php


function handleCountRequest() {
    global $db_conn;

    $result = executePlainSQL("SELECT Count(*) FROM MOVIEBASICINFO");

    if (($row = oci_fetch_row($result)) != false) {
        echo "<br> The number of movies: " . $row[0] . "<br>";
    }
}

function handleDisplayRequest(){
    $result = executePlainSQL("SELECT * FROM MOVIEBASICINFO");
    $col = 10;
    showTable($result, $col);
}

function handleSearchRequest(){
    echo "check 0";
    generalQueryAndDisplay();
}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
    echo "check before connectDB";
    if (connectToDB()) {
        echo "check0.0";
        if (array_key_exists('search', $_POST)) {
            echo "check -1";
            handleSearchRequest();
        }
        disconnectFromDB();
    }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('countTuples', $_GET)) {
            handleCountRequest();
        } else if(array_key_exists('displayTuples',$_GET)){
            handleDisplayRequest();
        }
        disconnectFromDB();
    }
}

if (isset($_POST['searchRequest'])){
    echo "before handle request call";
    handlePOSTRequest();
} else if (isset($_GET['countTupleRequest']) || isset($_GET['displayTupleRequest'])
) {///
    handleGETRequest();
}
?>
</body>
</html>


