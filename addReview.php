<?php
include 'dbh.php';

$mid = $_GET[mid];
$uid = $_GET[uid];
echo "Hello, ".$uid. "<br>";
echo "<hr />";
$url = "main.php?uid=".urlencode($uid);
?>


<html>

<head>
    <title> Review and Rate </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
<form method="post" action=>
    <div class="form-group">
        <label><h4>Rating: from 1 to 10</h4></label>
        <form method="POST">
            <!-- <div class="form_input">
                <input type="radio" name="rating" value="1">
                <label for="1"> 1</label><br>
                <input type="radio" name="rating" value="2">
                <label for="2"> 2</label><br>
                <input type="radio" name="rating" value="3">
                <label for="3"> 3</label><br>
                <input type="radio" name="rating" value="4">
                <label for="4"> 4</label><br>
                <input type="radio" name="rating" value="5">
                <label for="5"> 5</label><br>
                <input type="radio" name="rating" value="6">
                <label for="6"> 6</label><br>
                <input type="radio" name="rating" value="7">
                <label for="7"> 7</label><br>
                <input type="radio" name="rating" value="8">
                <label for="8"> 8</label><br>
                <input type="radio" name="rating" value="9">
                <label for="9"> 9</label><br>
                <input type="radio" name="rating" value="10">
                <label for="10"> 10</label><br>
            </div> -->

            <input type="range" min="1" max="10" step="1" value="1" id="rating" name="rating" style="width:500px" onchange='document.getElementById("r").value = document.getElementById("rating").value;'/>
            <input type="text" name="r" id="r" value="1" size="1" style="border:3px solid #FB6107; width:35px; font-size: 20px; background-color: #1BE7FF; text-align: center;" disabled/>
            <br/>
    </div>
    <div class="form-group">
        <label><h4>Review:</h4></label>
        <br>

        <textarea class="form-control" name="review" rows="10" cols="20"></textarea><br>
    </div>
    <input type="submit" class="btn btn-primary" name="submit">
</form>
</body>
<hr />


<?php


function handleMapRequest() {
    global $db_conn;
    global $mid;
    global $uid;

    $Rating = (isset($_POST['rating'])) ? $_POST['rating'] : null;
    $Review = (isset($_POST['review'])) ? $_POST['review'] : null;

    if ($Review === "") {
        echo 'please write a review, try again!';
        exit;
    } else if ($Rating === 0) {
        echo 'please give a rating, try again!';
        exit;
    } else {
        $Rating = (int) $Rating;
        $uid = $_GET['uid'];
        $accountNumber = 0;

        // get account number
        $raw_accountNumber = executePlainSQL("SELECT AccountNumber FROM Users WHERE Names = '$uid'");
        if (($row = oci_fetch_row($raw_accountNumber)) != false) {
            $accountNumber = $row[0];
        }
        // get number of reviews
        $number = 0;
        $raw_number = executePlainSQL("SELECT COUNT(*) FROM RReview");
        while (($row = oci_fetch_row($raw_number)) != false) {
            $number = $row[0];
        }
        $number = 2 * $number + 1;   // set review id to be 2 * #of current reviews + 1

        $date = date('j-M-Y');
        executePlainSQL("INSERT INTO RReview
                            VALUES (0, 0, '$Review', '$date', $Rating, '$number', '$mid', '$accountNumber')");
    }

    echo "<br>Review Saved!<br>";
    $committed = oci_commit($db_conn);

    // Test whether commit was successful. If error occurred, return error message
    if (!$committed) {
        $error = oci_error($db_conn);
        echo 'Commit failed. Oracle reports: ' . $error['message'];

    }

}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
    if (connectToDB()) {
        if(array_key_exists('rating',$_POST) || array_key_exists('review',$_POST)){
            handleMapRequest();
        }

        disconnectFromDB();
    }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    /*    if (connectToDB()) {
            if(array_key_exists('displayReviews',$_GET)){
                handleDisplayRequest();
            }
            disconnectFromDB();
        }*/
}


if (isset($_POST['submit']) || isset($_POST['return'])) {
    handlePOSTRequest();
}
//else if (isset($_GET['displayReviewsRequest'])) {///
//    handleGETRequest();
//}


?>



<form method="post" action=<?= $url ?>>
    <input type="hidden" id="backRequest" name="backRequest">
    <input type="submit" value = "Back" name="return"></p>
</form>


</html>
