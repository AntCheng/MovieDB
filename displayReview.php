<?php
include 'dbh.php';
?>

<html>

<head>
    <title>Review Page</title>
</head>

<body>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php
$mid = $_GET[mid];
$uid = $_GET[uid];

echo "Hello, ".$uid. "<br>";
echo "<hr />";

function displayMovieName() {
    global $mid;
    // get movie name
    $movieName_raw = executePlainSQL("SELECT Title FROM MovieBasicInfo WHERE MovieID = '$mid'");
    if (($row = oci_fetch_row($movieName_raw)) != false) {
        $movieName = $row[0];
    }
    echo "<h4>"."Check out previous reviews of " . "<i>" . $movieName . "</i>" . "</h4>"."<br>";
}

function displayReviewSingleMovie() {
    global $mid;
    //get reviews of a specific movie
    $result = executePlainSQL("SELECT u.Names, r.Dates, r.Content, r.ReviewID FROM RReview r, Users u
                                  WHERE (MovieID = '$mid' AND r.AccountNumber = u.AccountNumber)");
    $c = 4;
    showTable($result, $c, false);
}


displayMovieName();
displayReviewSingleMovie();

// jump to addReview
$url = "addReview.php?mid=".urlencode($mid)."&uid=".urlencode($uid);
$add = "<h4>Review & Rate</h4>";
$add .=  '<form method="POST" action='.$url.'>';
$add .=  '<input type="submit" value="get started" name="get started!"></p>';
$add .=  '</form>';
echo $add;


?>


</body>
</html>