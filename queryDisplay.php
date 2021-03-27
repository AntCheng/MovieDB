<?php
//include 'dbh.php';


function generalQueryAndDisplay(){
    
    $sql = "SELECT m.MovieID FROM MovieBasicInfo m WHERE m.Years>0";
    $isAND = "check 1";
    echo $isAND;

    $category = (isset($_POST['filter_category'])) ? $_POST['filter_category'] : "";
	$country = (isset($_POST['filter_country'])) ? $_POST['filter_country'] : "";
	$year = (isset($_POST['filter_year'])) ? $_POST['filter_year'] : "";
    $rating = (isset($_POST['filter_rating'])) ? $_POST['filter_rating'] : "";
    
    if($category!=""){
        $sql .= "AND m.Categories = '" .$_POST['filter_category']. "'";
    }
    if($country!=""){
        $sql .= "AND m.county = '" .$_POST['filter_country']. "'";
    }
    if($year!=""){
        $sql .= "AND m.year = '" .$_POST['filter_year']. "'";
    }
    if($rating!=""){
        $sql .= "AND m.rating = '" .$_POST['filter_rating']. "'";
    }
    echo "check 2";
    Display($sql);
}

function Display($sql){
    echo "check 3";
    //require 'dbh.php';
    echo 'check 4';
    $targetMovie = "<h2>Movies</h2>";
    echo 'check 5';
    $result = executePlainSQL($sql);
    echo 'check 6';
    while(($row = oci_fetch_row($result)) != false){
        // echo $row[0];
        $movieInfo = executePlainSQL('SELECT * FROM MovieBasicInfo m WHERE m.MovieID ='.$row[0]);
        $movieInfo = oci_fetch_row($movieInfo);
        // echo $movieInfo[0];
        //echo $movieInfo['TITLE'];
        $targetMovie .=  '<h2>'.$movieInfo[0].'</h2>';
        $targetMovie .=  '<form method="POST" action="review.php?mid='.$movieInfo[2].'">';
        $targetMovie .=     '<input type="submit" value="moreInfo" name="moreInfo"></p>';
        $targetMovie .=  '</form>';
    }
    echo $targetMovie;

}

?>