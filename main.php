<?php
include 'dbh.php';
include 'queryDisplay.php';

displayHeader();
?>


<html>
<head>
    <title>Movie Info</title>
</head>
<h2><?php $result = executePlainSQL("SELECT Count(*) FROM MOVIEBASICINFO");
    if (($row = oci_fetch_row($result)) != false)
        echo "<br> All " . $row[0] . " movies:<br>";
    ?>
    <?php $result = executePlainSQL("SELECT * FROM MOVIEBASICINFO");
    $col = 10;
    showTable($result, $col);
    ?>
</h2>
<hr>

<form class="form-horizontal" method="POST" action="#">
    <input type="hidden" id="searchRequest" name="searchRequest">
    <div class="form_input">
        <select id="filter_category" class="form-control" name="filter_category">
            <option value="" disabled selected hidden>choose category</option>
            <option value="">n/a</option>
            <option value="Crime">Crime</option>
            <option value="Disaster">Disaster</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Action">Action</option>
            <option value="Drama">Drama</option>
            <option value="Sci-Fi">Sci-Fi</option>
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
            <option value="2021">2021</option>
            <option value="2020">2020</option>
            <option value="2019">2019</option>
            <option value="2018">2018</option>
            <option value="2017">2017</option>
            <option value="1987">1987</option>
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


<h2>Find the best rating movie in each category</h2>
<form method="POST" action="#"> <!--refresh page when submitted-->
    <input type="hidden" id="catRequest" name="catRequest">
    <input type="submit" value = "display" name="category_best"></p>
</form>

<h2>Find the movies that have been reviewed by all users</h2>
<form method="POST" action="#"> <!--refresh page when submitted-->
    <input type="hidden" id="allReviewRequest" name="allReviewRequest">
    <input type="submit" value = "display" name="allReview"></p>
</form>

<h2>Find the welcome category</h2>
<form method="POST" action="#"> <!--refresh page when submitted-->
    <input type="hidden" id="wellCatRequest" name="wellCatRequest">
    <input type="submit" value = "display" name="wellCat"></p>
</form>


<?php

function handleSearchRequest(){
    echo "check 0";
    generalQueryAndDisplay();
}

function handleCatRequest(){
    queryCatRequest();
}

function handleAllReviewRequest(){
    queryAllReview();
}

function handleWelcomeCatRequest(){
    queryWelcomeCat();
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
        }else if(array_key_exists('category_best',$_POST)){
            handleCatRequest();
        }else if(array_key_exists('allReview',$_POST)){
            handleAllReviewRequest();
        }else if(array_key_exists('wellCat',$_POST)){
            handleWelcomeCatRequest();
        }
        disconnectFromDB();
    }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    if (connectToDB()) {

        disconnectFromDB();
    }
}

function displayHeader() {
    $uid = $_GET[uid];
    echo "Hello, ".$uid;

    $url_user = "account.php?uid=".urlencode($_GET[uid]);
    $userCenter = '<form method="POST" action='.$url_user.'>';
    $userCenter .= '<object height="1" hspace="350"></object>';
    $userCenter .= "Update your password or reviews";
    $userCenter .=  '<input type="submit" value="Go" name="go">';
    $userCenter .=  '</form>';
    echo $userCenter.'<hr />';
}


if (isset($_POST['searchRequest']) || isset($_POST['catRequest']) || isset($_POST['allReviewRequest']) || isset($_POST['wellCatRequest'])){
    handlePOSTRequest();
} else if (0) {
    handleGETRequest();
}
?>
</body>
</html>