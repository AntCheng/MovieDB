<?php
include 'dbh.php';
include 'queryDisplay.php';
?>


<html>
    <head>
        <title>Movie Info</title>
    </head>

    <body>

        <h2>The number of movies</h2>
        <form method="GET" action="main.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>

        <h2>Review/Rate a movie</h2>
        <form method="GET" action="review.php"> <!--refresh page when submitted-->
            <input type="hidden" id="reviewRequest" name="reviewRequest">
            <input type="submit" name="review"></p>
        </form>

        <h2>Display all the movies with their info</h2> 
        <form method="GET" action="main.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value = "display" name="displayTuples"></p>
        </form>


        <form class="form-horizontal" method="POST" action="#">
								<div class="form_input">
									<select id="filter_category" class="form-control" name="filter_category">
										<option value="" disabled selected hidden>choose category</option>
										<option value="">n/a</option> 
									    <?php echo "some function here" ?>
										<option value="all">All Category</option> 
									</select>
								</div>
								<hr>
								<div class="form_input">
									<select id="filter_language" class="form-control" name="filter_langauge">
										<option value="" disabled selected hidden>choose Language</option>
										<option value="">n/a</option> 
										<?php "some function here" ?>
	                				</select>
								</div>
								<hr>
								<div class="form_input">
									<select id="filter_year" class="form-control" name="filter_year">
										<option value="" disabled selected hidden>choose year</option>
										<option value="">n/a</option> 
										<?php "year function()" ?>
	                				</select>
								</div>
								<hr>
								<div class="form_input">
									<select id="filter_rating" class="form-control" name="filter_rating">
										<option value="" disabled selected hidden>choose Rating</option>
										<option value="">n/a</option> 
										<option value=5>5</option> 
										<option value=4>4</option> 
										<option value=3>3</option> 
										<option value=2>2</option> 
										<option value=1>1</option> 
	                				</select>
								</div>
								<hr>
								<div class="form_input">
									Top User Rated: <input type="checkbox" id="filter_topRating" name="filter_topRating">
								</div>
								<hr>
								<div class="form_input">
									Most reviewed: <input type="checkbox" id="filter_maxActivities" name="filter_maxActivities">
								</div>
								<hr>
								<input type="submit" name="search" value="search" class="btn btn-primary"/>
		</form>


        <?php

        echo $_GET[uid];

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM MOVIEBASICINFO");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of movies: " . $row[0] . "<br>";
            }
        }

        function handleReviewRequest(){

            header('url=review.php');

        }

        function handleDisplayRequest(){
            global $db_conn;///
            //require 'login.php';
            $result = executePlainSQL("SELECT * FROM MOVIEBASICINFO");
            echo "<br>  Title Years MovieID Lengths Categories Country Rating NumberOfUpvotes NumberOfWatchings NumberOfComments  <br>";
            while (($row = oci_fetch_row($result)) != false) {
                echo "<br>  " . $row[0] . "    " . $row[1] . $row[2] . "    " . $row[3] . "    " . $row[4] . "    " . $row[5] . "    " . $row[6] . "    " . $row[7] . "    " . $row[8] . "    " . $row[9] . " <br>";
            }
/*            $mid = '4';
            $uid = $_GET['uid'];
            echo $uid;
            header('refresh:1; url=review.php?uid=' .$uid.'&mid=' .$mid);*/
            
        }

        function handleSearchRequest(){
            queryAndDisplay();
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('search', $_POST)) {
                    handleSearchRequest();
                }
                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                } else if(array_key_exists('displayTuples',$_GET)){
                    handleDisplayRequest();
                } else if(array_key_exists('review',$_GET)) {
                    handleReviewRequest();
                }

                disconnectFromDB();
            }
        }

		if (0) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['displayTupleRequest'])
                || isset($_GET['reviewRequest'])) {///
            handleGETRequest();
        }
		?>
	</body>
</html>


