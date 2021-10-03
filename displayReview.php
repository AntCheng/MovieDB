<?php
include 'dbh.php';
?>

<html>

<head>
    <title>Review Page</title>
</head>

<body class="displayReview">
<link rel="stylesheet" href="./css/style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<?php
$mid = $_GET[mid];
$uid = $_GET[uid];

echo "Hello, "."<i>".$uid."</i><br>";
echo "<hr />";
echo '<div class="container">';

function displayMovieName() {
    global $mid;
    // get movie name
    $movieName_raw = executePlainSQL("SELECT Title FROM MovieBasicInfo WHERE MovieID = '$mid'");
    if (($row = mysqli_fetch_row($movieName_raw)) != null) {
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

function displayMovieInfo(){
    global $mid;

    $movieInfo = executePlainSQL('SELECT * FROM MovieBasicInfo m WHERE m.MovieID ='.$mid);
    $movieInfo = mysqli_fetch_row($movieInfo);

    $url = "displayReview.php?mid=".urlencode($movieInfo[2])."&uid=".urlencode($_GET[uid]);
    $targetMovie = '<div class="row">';
    $targetMovie .=     '<div class="col-4">';
    $targetMovie .=     '<img class="mov-pic" src="'.$movieInfo[7].'"  width="200" height="250">';
    $targetMovie .=     '</div>';
    $targetMovie .=     '<div class="col-2">';
    $targetMovie .=     '</div>';
    $targetMovie .=     '<div class="col-6">';
    $targetMovie .=         '<p>Title:&nbsp<i><b>'.$movieInfo[0].'</b></i></p>';
    $targetMovie .=         '<p>Year:&nbsp<i><b>'.$movieInfo[1].'</b></i></p>';
    $targetMovie .=         '<p>Length:&nbsp<i><b>'.$movieInfo[3].'</b></i></p>';
    $targetMovie .=         '<p>Category:&nbsp<i><b>'.$movieInfo[4].'</b></i></p>';
    $targetMovie .=         '<p>Country:&nbsp<i><b>'.$movieInfo[5].'</b></i></p>';
    $targetMovie .=         '<p>Rating:&nbsp<i><b>'.$movieInfo[6].'</b></i></p>';

    $targetMovie .=     '</div>';

    $targetMovie .= '</div>';
    $targetMovie .= '<hr>';

    //echo '<div class="container">';
    echo $targetMovie;
    //echo '</div>';

}

if (connectToDB()) {
    displayMovieInfo();
    displayMovieName();
    displayReviewSingleMovie();
}


// jump to addReview
$url = "addReview.php?mid=".urlencode($mid)."&uid=".urlencode($uid);
$add = "<h4>Review & Rate</h4>";
$add .=  '<form method="POST" action='.$url.'>';
$add .=  '<input type="submit" value="get started" name="get started!"></p>';
$add .=  '</form>';
echo $add;


?>

</div>
</body>
</html>