<?php
include 'dbh.php';
?>

    <html>

    <head>
        <title>Movie Info</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>

    <body class="signup">

    <div class="container-fluid">
        <img src="./img/abstractPic.jpg" class="img-fluid" alt="the background">
        <div class="row justify-content-md-center">
            <div class="col-md-auto">
                <title>Sign up</title>
                <h2>Create a new account</h2>
                <form action="signUp.php" method="post">
                    <label>Username:</label>
                    <input type="text" name="Names">
                    <br /><br />
                    <label>Password:</label>
                    <textarea name="Passwords" rows="1" cols="23" maxlength="20"></textarea> &nbsp;
                    <label>Confirm password:</label>
                    <textarea name="Confirmation" rows="1" cols="23" maxlength="20"></textarea>
                    <br /><br />
                    <input type="submit" name="signup" value="Sign Up"></form>
            </div>
        </div>
    </body>
    </html>

<?php

function handleDifference($p, $c) {
    if ($p != $c) {
        header("refresh:1; url='signUp.php'");
        echo "<br>Passwords do not match. Auto-refresh in 1 second.<br>";
        exit;
    }
}

function handleSignUpRequest() {
    global $db_conn;
    var_dump($db_conn);
    $n = $_POST['Names'];
    $p = $_POST['Passwords'];
    $c = $_POST['Confirmation'];
    if (($n == '') || ($p == '')) {
        header("refresh:1; url='signUp.php'");
        echo "<br>Username or password cannot be empty. Auto-refresh in 1 second.<br>";
        exit;
    }
    handleDifference($p, $c);
    $num = executePlainSQL("SELECT COUNT(*) FROM Users");
    $num = mysqli_fetch_row($num);
    executePlainSQL("INSERT INTO Users
                                 VALUES ($num[0] + 1, '$n', '$p')");
    echo "Sign-up successful! Redirecting to main page...";
    header('refresh:2; url=main.php?uid='.$n);
    $db_conn->commit();
}


function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('signup', $_POST)) {
            handleSignUpRequest();
        }
        disconnectFromDB();
    }
}

if (isset($_POST['signup'])) {
    handlePOSTRequest();
}

?>