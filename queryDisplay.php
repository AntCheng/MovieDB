<?php
//include 'dbh.php';


function generalQueryAndDisplay(){
    
    $sql = "SELECT m.MovieID FROM MovieBasicInfo m WHERE m.Years>0";
    $isAND = "check 1";
    //echo $isAND;

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

    /*
    SELECT mm.MovieID
FROM (SELECT m.MovieID, max(rating)
      FROM MovieBasicInfo m --sql , moviebsaicinfo m
      GROUP BY Categories) as mm;
    */
    //Try to find the max rating movie given some movieID
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
                        SELECT m.MOVIEID, Count(*) as nreview
                        FROM ($sql) m, RREVIEW r
                        WHERE m.MovieID = r.MovieID
                        GROUP BY m.MOVIEID)
            SELECT sd.MovieID
            FROM  sd
            WHERE sd.nreview = (SELECT max(sd.nreview)
                                FROM sd)";

    }


    //echo "check 2";
    Display($sql);
}

function Display($sql){  
    $targetMovie = "<h2>Search result</h2>";
    $result = executePlainSQL($sql);
    $empty = 0;
    while(($row = oci_fetch_row($result)) != false){
        // echo $row[0];
        $empty = 1;
        $movieInfo = executePlainSQL('SELECT * FROM MovieBasicInfo m WHERE m.MovieID ='.$row[0]);
        $movieInfo = oci_fetch_row($movieInfo);
        // echo $movieInfo[0];
        //echo $movieInfo['TITLE'];
        $url = "displayReview.php?mid=".urlencode($movieInfo[2])."&uid=".urlencode($_GET[uid]);
        $targetMovie .= '<div class="row">';
        $targetMovie .=     '<div class="col-4">';
        $targetMovie .=     '<img class="mov-pic" src="'.$movieInfo[7].'"  width="200" height="250">';
        $targetMovie .=     '</div>';
        
        $targetMovie .=     '<div class="col-8">';
        $targetMovie .=         '<p><i>'.$movieInfo[0].'</i></p>';
        $targetMovie .=         '<p><i>'.$movieInfo[1].'</i></p>';
        $targetMovie .=         '<p><i>'.$movieInfo[3].'</i></p>';
        $targetMovie .=         '<p><i>'.$movieInfo[4].'</i></p>';
        $targetMovie .=         '<p><i>'.$movieInfo[5].'</i></p>';
        $targetMovie .=         '<p><i>'.$movieInfo[6].'</i></p>';

        $targetMovie .=         '<form method="POST" action='.$url.'>';
        $targetMovie .=         '<input type="submit" value="moreInfo" name="moreInfo"></p>';
        $targetMovie .=         '</form>';
        $targetMovie .=     '</div>';

        $targetMovie .= '</div>';
        $targetMovie .= '<hr>';

    }
    if($empty ==0){
        echo "<h4><i>Unfortunately, No such movies in the database</i></h4>";
    }
    echo '<div class="container">';
    echo $targetMovie;
    echo '</div>';
}

function queryCatRequest(){
    $sql =" WITH mm AS (SELECT m.Categories, max(rating) as rati
                        FROM MovieBasicInfo m 
                        GROUP BY m.Categories)
    SELECT m.MovieID
        FROM  mm, MovieBasicInfo m
            WHERE mm.Categories = m.Categories AND m.rating = mm.rati";
    Display($sql);
}

function queryAllReview(){
    $sql ="SELECT m.movieID
    FROM MovieBasicInfo m
    WHERE NOT EXISTS((SELECT u.AccountNumber
                      FROM Users u)
                     MINUS
                     (SELECT r.AccountNumber
                      FROM RREVIEW r
                      WHERE r.MovieID = m.movieID))";
    Display($sql);
}

function queryWelcomeCat(){
    $sql ="SELECT Categories
    FROM MovieBasicInfo
    GROUP BY Categories
    HAVING avg(rating) > (SELECT avg(rating) FROM MovieBasicInfo)";
    echo "<h3><i> The welcome categories are: </i></h3>";
    $result = executePlainSQL($sql);
    while(($row = oci_fetch_row($result)) != false){
        echo "<h3><i> $row[0]   &nbsp;&nbsp;<h3><i>";
    }
}