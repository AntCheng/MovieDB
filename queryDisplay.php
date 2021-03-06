<?php



function generalQueryAndDisplay(){

    $sql = "SELECT m.MovieID FROM MovieBasicInfo m WHERE m.Years>0 ";

    $category = (isset($_POST['filter_category'])) ? $_POST['filter_category'] : "";
    $country = (isset($_POST['filter_country'])) ? $_POST['filter_country'] : "";
    $year = (isset($_POST['filter_year'])) ? $_POST['filter_year'] : "";
    $rating = (isset($_POST['filter_rating'])) ? $_POST['filter_rating'] : "";

    if($category!=""){
        $sql .= "AND m.Categories = '" .$_POST['filter_category']. "'";
    }
    if($country!=""){
        $sql .= "AND m.country = '" .$_POST['filter_country']. "'";
    }
    if($year!=""){
        $sql .= "AND m.years = '" .$_POST['filter_year']. "'";
    }
    if($rating!=""){
        $sql .= "AND m.rating = '" .$_POST['filter_rating']. "'";
    }

    // Try to find the max rating movie given some movieID
    if(isset($_POST['filter_topRating'])){
        $sql =
        "SELECT mb.MovieID
        FROM ($sql) nm, MovieBasicInfo mb
        WHERE nm.MovieID = mb.MovieID AND NOT EXISTS (SELECT * FROM ($sql) nm2, MovieBasicInfo mb2 WHERE nm2.MovieID = mb2.MovieID AND mb2.rating > mb.rating)";
    }

    //Find the most reviewed movies
    if(isset($_POST['filter_mostreviewd'])){
        $sql =
            "WITH sd AS (
                        SELECT m.MovieID, Count(*) as nreview
                        FROM ($sql) m, RReview r
                        WHERE m.MovieID = r.MovieID
                        GROUP BY m.MovieID)
            SELECT sd.MovieID
            FROM  sd
            WHERE sd.nreview = (SELECT max(sd.nreview)
                                FROM sd)";
    }
    Display($sql);
}

function Display($sql){

    $targetMovie = '<div id="result"></div>'."<br><h2>Search result</h2>";
    $result = executePlainSQL($sql);
    $empty = 0;
    while(($row = mysqli_fetch_row($result)) != null){

        $empty = 1;
        $movieInfo = executePlainSQL('SELECT * FROM MovieBasicInfo m WHERE m.MovieID ='.$row[0]);
        $movieInfo = mysqli_fetch_row($movieInfo);

        $url = "displayReview.php?mid=".urlencode($movieInfo[2])."&uid=".urlencode($_GET[uid]);
        $targetMovie .= '<div class="row">';
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

        $targetMovie .=         '<form method="POST" action='.$url.'>';
        $targetMovie .=         '<input type="submit" value="More Info" name="moreInfo"></p>';
        $targetMovie .=         '</form>';
        $targetMovie .=     '</div>';

        $targetMovie .= '</div>';
        $targetMovie .= '<hr>';

    }
    if($empty ==0){
        echo "<h4><i>Unfortunately, No such movies in the database</i></h4>";
    }
    echo '<div class="overflow-auto">';
    echo '<div class="container">';
    echo $targetMovie;
    echo '</div></div>';
}

function queryCatRequest(){
    $sql =
        "WITH mm AS (SELECT m.Categories, max(rating) as rati
                        FROM MovieBasicInfo m 
                        GROUP BY m.Categories)
    SELECT m.MovieID
        FROM  mm, MovieBasicInfo m
            WHERE mm.Categories = m.Categories AND m.rating = mm.rati";
    Display($sql);
}

function queryAllReview(){
    $sql =
        "SELECT m.movieID
    FROM MovieBasicInfo m
    WHERE NOT EXISTS 
        (SELECT u.AccountNumber
         FROM Users u
         WHERE u.AccountNumber NOT IN (SELECT r.AccountNumber
                                       FROM RReview r
                                       WHERE r.MovieID = m.MovieID))";
    Display($sql);
}

function queryWelcomeCat(){
    $sql ="SELECT Categories
    FROM MovieBasicInfo
    GROUP BY Categories
    HAVING avg(rating) > (SELECT avg(rating) FROM MovieBasicInfo)";
    echo "<h5> The popular categories are: </h5>";
    $result = executePlainSQL($sql);
    while(($row = mysqli_fetch_row($result)) != false){
        echo "<h5><i> $row[0]   &nbsp;&nbsp;<h5><i>";
    }
}