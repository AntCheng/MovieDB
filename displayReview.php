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

// get movie name
$movieName_raw = executePlainSQL("SELECT Title FROM MovieBasicInfo WHERE MovieID = '$mid'");
if (($row = oci_fetch_row($movieName_raw)) != false) {
    $movieName = $row[0];
}
echo "Check out previous reviews of "."<i>".$movieName."</i>"."<br>";


//get reviews
$result = executePlainSQL("SELECT u.Names, r.Dates, r.Content, r.ReviewID FROM RReview r, Users u
                                  WHERE (MovieID = '$mid' AND r.AccountNumber = u.AccountNumber)");
$c = 4;
showTable($result, $c);
// echo "<br>  User"." &nbsp; &nbsp; "."  Date ". "&nbsp; &nbsp;".  " Review"." &nbsp;&nbsp;&nbsp; "." ID<br>";
// while (($row = oci_fetch_row($result)) != false) {
//     echo "<br>  " . $row[0] . " &nbsp; " . $row[1]. " &nbsp;  " . $row[2]. " &nbsp;  " . $row[3]. "  <br>";
// }

// jump to addReview
$url = "addReview.php?mid=".urlencode($mid)."&uid=".urlencode($uid);
$add = "<h1>Review & Rate</h1>";
$add .=  '<form method="POST" action='.$url.'>';
$add .=  '<input type="submit" value="get started" name="get started!"></p>';
$add .=  '</form>';
echo $add;


?>


<!--
<h2>delete a review </h2>
<form method="post" action="addReview.php?mid=".urlencode($_GET[mid])>
<label for="review">your review:</label><br>
    <textarea name="review" rows="20" cols="40"></textarea>
    <br><br>
    <input type="submit" value = "Submit" name="mapReviews"></p>
</form>-->
</body>
</html>