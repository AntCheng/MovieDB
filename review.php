<?php
include 'dbh.php';

?>


<html>
<head>
    <title>Add a review</title>

</head>

<body>

<label for="movies">Select a movie to review/rate:</label>

<form method="post" action="review.php">
  <select name="movie" id="movies">
      <option value="">--Please choose a movie--</option>
      <option value="1">Monster Hunter</option>
      <option value="2">Greenland</option>
      <option value="3">The Little Things</option>
      <option value="4">Endgame</option>
      <option value="5">The Yinyang Master</option>
  </select>
  <input type="submit" value="submit" name="submitMovie" />
</form>


<?php

$mid = 0;
$rating = 0;



function handleSelectRequest() {
    global $mid;
    if(isset($_POST['submitMovie'])) {
        $mid = $_POST['movie'];
    }
    echo "The ID of movie you choose: ".$mid. "<br>";
}

function handleRatingRequest() {
    global $rating;
    if(isset($_POST['submitRating'])) {
        $rating = $_POST['rating'];
    }
    echo "your rating for this movie: ".$rating. "<br>";

}



function handleDisplayRequest(){
    global $mid;
    if(isset($_POST['submitMovie'])) {
        $mid = $_POST['movie'];
    }
    $result = executePlainSQL("SELECT u.Names, r.Content 
                                      FROM RReview r, Users u
                                      WHERE MovieID = '$mid' AND r.AccountNumber = u.AccountNumber");
    echo "<br>  User   Review  <br>";
    while (($row = oci_fetch_row($result)) != false) {
        echo "<br>  " . $row[0] . "      " . $row[1].  "  <br>";
    }
}


function handleMapRequest(){
    global $db_conn;///
    $mid = $_POST['movie'];
    if(isset($_POST['submitRating'])) {
        $Rating = $_POST['rating'];
    }
    $Review = (isset($_POST['review'])) ? $_POST['review'] : null;


    if ($Review === "") {
        echo 'please write a review, try again!';
        exit;
    } else if ($Rating === "") {
        echo 'please give a rating, try again!';
        exit;
    } else {
       /* $raw_name = executePlainSQL("SELECT Names FROM CurrentUser");
        while (($row = oci_fetch_row($raw_name)) != false) {
            $name = $row[0];
        }
        $raw_accountNumber = executePlainSQL("SELECT AccountNumber FROM Users WHERE (Names = '$name')");
        while (($row = oci_fetch_row($raw_accountNumber)) != false) {
            $accountNumber = $row[0];
        }*/
        $raw_number = executePlainSQL("SELECT COUNT(*) FROM RReview");
        while (($row = oci_fetch_row($raw_number)) != false) {
            $number = $row[0];
        }
        $number++;   // set review id to be #ofrows + 1
        executePlainSQL("INSERT INTO RReview
                            VALUES (0, 0, '$Review', '1-Jan-2021', '$Rating', '$number', '$mid', '$accountNumber')");
    }

    echo "<br>Saving review...<br>";
    $committed = oci_commit($db_conn);

    // Test whether commit was successful. If error occurred, return error message
    if (!$committed) {
        $error = oci_error($db_conn);
        echo 'Commit failed. Oracle reports: ' . $error['message'];

    }
    header('refresh:1; url=main.php');
    //exit;
}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
    if (connectToDB()) {
        if(array_key_exists('mapReviews',$_POST)){
            handleMapRequest();
        } if(array_key_exists('submitMovie',$_POST)) {
            handleSelectRequest();
        } if (array_key_exists('submitRating',$_POST)) {
            handleRatingRequest();
        }

        disconnectFromDB();
    }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    if (connectToDB()) {
        if(array_key_exists('displayReviews',$_GET)){
            handleDisplayRequest();
        }

        disconnectFromDB();
    }
}

if (isset($_POST['mapReviews']) || isset($_POST['submitMovie'])
    || isset($_POST['submitRating'])) {
    handlePOSTRequest();
} else if (isset($_GET['displayReviewsRequest'])) {///
    handleGETRequest();
}
?>


<hr />

<h2>Display all reviews of xxx</h2>
<form method="GET" action="review.php"> <!--refresh page when submitted-->
    <input type="hidden" id="displayReviewsRequest" name="displayReviewsRequest">
    <input type="submit" value = "display" name="displayReviews"></p>
</form>

<hr />

<h1>Rate the movie</h1>
<form action="review.php" method="post">
    <input type="radio" name="rating" value="5">
    <label for="5"> 5</label><br>
    <input type="radio" name="rating" value="6">
    <label for="6"> 6</label><br>
    <input type="radio" name="rating" value="7">
    <label for="7"> 7</label><br>
    <input type="radio" name="rating" value="8">
    <label for="8"> 8</label><br>
    <br>
    <input type="submit" value="Submit" name="submitRating">
</form>

<h2>Write your review!</h2>
    <form action="review.php" method="post"> <!--refresh page when submitted-->
        <label for="review">your review:</label><br>
        <textarea name="review" rows="20" cols="40"></textarea>
        <br><br>
        <input type="submit" value = "Submit" name="mapReviews"></p>
    </form>
 </body>
</html>
