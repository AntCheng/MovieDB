<?php
include 'dbh.php';
?>

<html>

<head>
    <title>Review Page</title>
</head>

<body>
<link rel="stylesheet" href="./file/css/bootstrap.min.css">
<link rel="stylesheet" href="./css/style.css">

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
    echo "Check out previous reviews of " . "<i>" . $movieName . "</i>" . "<br>";
}

function displayReviewSingleMovie() {
    global $mid;
    //get reviews of a specific movie
    $result = executePlainSQL("SELECT u.Names, r.Dates, r.Content, r.ReviewID FROM RReview r, Users u
                                  WHERE (MovieID = '$mid' AND r.AccountNumber = u.AccountNumber)");
    $c = 4;
    showTable($result, $c);
}


displayMovieName();
displayReviewSingleMovie();

// jump to addReview
$url = "addReview.php?mid=".urlencode($mid)."&uid=".urlencode($uid);
$add = "<h1>Review & Rate</h1>";
$add .=  '<form method="POST" action='.$url.'>';
$add .=  '<input type="submit" value="get started" name="get started!"></p>';
$add .=  '</form>';
echo $add;


?>


</body>
</html>