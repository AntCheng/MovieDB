<?php
include 'dbh.php';

$uid = $_GET['uid'];
echo "Hello, "."<i>".$uid."</i><br>";
echo "<hr />";

?>

<html>
<head>
    <title> User center </title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body class="account">
<div class="container">
    <h3><i>Update your password</i></h3>
    <form action="#bottom" method="post">
        <label for="password">New password:</label><br>
        <textarea name="new" rows="1" cols="23" maxlength="20"></textarea>
        <br><br>
        <input type="submit" value = "Submit" name="newPassword"></p>
    </form>
    <hr />

    <?php
    $accountNumber = 0;

    // helper: get account number given user name
    function getAccountNumber() {
        global $accountNumber;
        $uid = $_GET['uid'];
        $raw_accountNumber = executePlainSQL("SELECT AccountNumber FROM Users WHERE Names = '$uid'");
        if (($row = mysqli_fetch_row($raw_accountNumber)) != false) {
            $accountNumber = $row[0];
        }
        return $accountNumber;
    }

    // helper: delete button for each review
    function deleteRow($rid) {
        $uid = $_GET['uid'];
        $url_delete = "account.php?uid=".urlencode($uid)."&rid=".urlencode($rid)."#bottom";
        $delete_button = '<td><form method="POST" action='.$url_delete.'>';
        $delete_button .= '<object height="1" hspace="30"></object>';
        $delete_button .= '<input type="submit" value="delete" name="delete"></form></td>';
        echo $delete_button;
    }

    // helper: display a table of reviews with delete options
    function showReviewTable($r) {
        echo "<table style='width:100%' class='table table-stripe'>";
        echo "<thead class='thead-dark'><tr>";
        for ($i = 1; $i <= 4; $i++){
            $field_name = ucwords(strtolower(mysqli_field_name($r, $i)));
            echo "<th>$field_name</th>";
        }
        echo "</tr></thead>";
        echo "<tbody><tr>";
        while($row = mysqli_fetch_row($r)){
            for($i = 0; $i < 4; $i++){
                echo "<td>$row[$i]</td>";
            }
            deleteRow($row[3]);
            echo "</tr>";
        }
        echo "</tbody></table>";
    }

    // helper: back to main page
    function backToMain() {
        echo '<div id="bottom"></div>';
        $url_back = "main.php?uid=".urlencode($_GET[uid]);
        $back = '<form method="POST" action='.$url_back.'>';
        $back .= '<input type="submit" value="back" name="back to main"></p></form>';
        echo $back;
    }

    //get reviews from a specific user
    function displayReviewSingleUser() {
        global $db_conn;
        $accountNumber = getAccountNumber();

        $result = executePlainSQL("SELECT m.Title, r.Dates, r.Content, r.ReviewID 
                                      FROM RReview r, MovieBasicInfo m
                                      WHERE (r.MovieID = m.MovieID AND r.AccountNumber = '$accountNumber')");

        echo '<h3><i>Your reviews</i></h3><br>';
        echo '<hr />';
        showReviewTable($result);
        echo '<hr />';
        $db_conn->commit();
    }

    //get all comments associated with the user's reviews
    function displayCommentSingleUser() {

        $accountNumber = getAccountNumber();

        $result = executePlainSQL("SELECT c.CommentID, c.Dates, c.Content, r.ReviewID 
                                      FROM RReview r, CComment c
                                      WHERE (r.ReviewID = c.ReviewID AND r.AccountNumber = '$accountNumber')");
        $c = 4;
        echo '<h3><i>All comments associated with the your reviews</i></h3><br>';
        echo '<hr />';
        showTable($result, $c, false);
        echo '<hr />';
    }



    function handleDeleteReviewRequest() {
        global $db_conn;
        $rid = (int) $_GET[rid];
        //echo "rid: ".$rid;
        $accountNumber = getAccountNumber();
        $result = executePlainSQL("DELETE
                             FROM RReview
                             WHERE REVIEWID = '$rid' AND AccountNumber = '$accountNumber'");
        if (!($result = false)) {
            echo "<b>review deleted!</b>";
        } else {
            echo "error";
        }
        $db_conn->commit();
    }


    function handleUpdatePasswordRequest() {
        global $db_conn;
        global $accountNumber;

        $new_password = (isset($_POST['new'])) ? $_POST['new'] : "";
        if ($new_password == "") {
            echo "Please enter a valid password";
        } else {
            $accountNumber = getAccountNumber();
            executePlainSQL("UPDATE Users SET Passwords = '$new_password'
                     WHERE AccountNumber = '$accountNumber'");
            echo "<b>Update successful!</b>";

        }
        $db_conn->commit();

    }

    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('delete', $_POST)) {
                handleDeleteReviewRequest();
            } else if (array_key_exists('newPassword', $_POST)) {
                handleUpdatePasswordRequest();
            }
            disconnectFromDB();
        }
    }

    // HANDLE ALL GET ROUTES
    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handleGETRequest() {
        if (connectToDB()) {

            disconnectFromDB();
        }
    }

    connectToDB();
    displayReviewSingleUser();
    displayCommentSingleUser();
    backToMain();

    if (isset($_POST['newPassword']) || isset($_POST['delete'])){
        handlePOSTRequest();
    } else if (0) {
        handleGETRequest();
    }

    ?>
</div>
</body>

</html>



