<?php
include 'dbh.php';
?>


<html>
<head>
    <title>Review</title>
</head>

<body>


       <h2>Display all reviews of xxx</h2>
       <form method="GET" action="review.php"> <!--refresh page when submitted-->
           <input type="hidden" id="displayReviewsRequest" name="displayReviewsRequest">
           <input type="submit" value = "display" name="displayReviews"></p>
       </form>

       <hr />

       <h2>Write your review!</h2>
       <form method="POST" action="review.php"> <!--refresh page when submitted-->
           <label for="review">your review:</label><br>
           <textarea name="review" rows="20" cols="40"></textarea>
           <br><br>
           <input type="submit" value = "Submit" name="mapReviews"></p>
       </form>



<?php




function handleDisplayRequest(){
    // TODO: SESSION to get current current movie that is being reviewed
    global $db_conn;///
    // $mid = $_SESSION['mid'];
    $result = executePlainSQL("SELECT u.Names, r.Content 
                                      FROM RReview r, Users u
                                      WHERE MovieID = '1' AND r.AccountNumber = u.AccountNumber");
    echo "<br>  User   Review  <br>";
    while (($row = oci_fetch_row($result)) != false) {
        echo "<br>  " . $row[0] . "      " . $row[1].  "  <br>";
    }
}

function handleMapRequest(){
    // TODO: SESSION to get current user information and current movie that is being reviewed
    // TODO: get current date
    global $db_conn;///
    $Review = (isset($_POST['review'])) ? $_POST['review'] : null;
    // $rating = (isset($_POST['mapRatingRequest'])) ? $_POST['mapRatingRequest'] : null;
    //$mid = $_SESSION['mid'];
    //$AccountNumber = $_SESSION['AccountNumber'];
    //$result = executePlainSQL("INSERT INTO RReview
    //                          VALUES (0, 0, '$Review', '1-Jan-2021', 8, 100, '$mid', '$AccountNumber');");

    if ($Review === "") {
        echo 'please write a review, try again!';
        exit;
    } else {
        $tuple = array ($Review);
    }
    executeBoundSQL("INSERT INTO RReview
                            VALUES (0, 0, '$Review', '1-Jan-2021', 8, 199, 1, 692630)", $tuple);

    echo "<br>Saving review...<br>";
    $committed = oci_commit($db_conn);

    // Test whether commit was successful. If error occurred, return error message
    if (!$committed) {
        $error = oci_error($db_conn);
        echo 'Commit failed. Oracle reports: ' . $error['message'];

    }
    header('refresh:1; url=main.php');
    exit;
}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
    if (connectToDB()) {
        if(array_key_exists('mapReviews',$_POST)){
            handleMapRequest();
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

if (isset($_POST['mapReviews'])) {
    handlePOSTRequest();
} else if (isset($_GET['displayReviewsRequest'])) {///
    handleGETRequest();
}
?>
</body>
</html>
