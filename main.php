<?php
include 'dbh.php';

if(!isset($_GET['uid'])){
    header('refresh:1; url=login.php');
    exit();
}
echo "The current user is  " .$_GET['uid'];
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

        <h2>Display all the movies with their info</h2> 
        <form method="GET" action="main.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value = "display" name="displayTuples"></p>
        </form>

        <form method='GET' action="main.php">


        </form>


        <?php
        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM MOVIEBASICINFO");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of movies: " . $row[0] . "<br>";
            }
        }

        function handleDisplayRequest(){
            global $db_conn;///
            //require 'login.php';
            $result = executePlainSQL("SELECT * FROM MOVIEBASICINFO");
            echo "<br>  Title Years MovieID Lengths Categories Country Rating NumberOfUpvotes NumberOfWatchings NumberOfComments  <br>";
            while (($row = oci_fetch_row($result)) != false) {
                echo "<br>  " . $row[0] . "    " . $row[1] . $row[2] . "    " . $row[3] . "    " . $row[4] . "    " . $row[5] . "    " . $row[6] . "    " . $row[7] . "    " . $row[8] . "    " . $row[9] . " <br>";
            }
            
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                
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
                }

                disconnectFromDB();
            }
        }

		if (0) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['displayTupleRequest'])) {///
            handleGETRequest();
        }
		?>
	</body>
</html>


