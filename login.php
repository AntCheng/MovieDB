<?php
include 'dbh.php';
?>

    <title>Welcome! Please login!</title>
    <h2>Welcome! Please login!</h2>
    <form action="login.php" method="post">
        <label>Username:</label>
        <input type="text" name="Names">
        <br /><br />
        <label>Password:</label>
        <input type="password" name="Passwords">
        <br /><br />
        <input type="submit" name="login" value="Log In">

<?php

function handleLoginRequest() {
    global $db_conn;
    $n = $_POST['Names'];
    $p = $_POST['Passwords'];
    if (($n == '') || ($p == '')) {
        header('refresh:1; url=login.php');
        echo "<br>Username or password cannot be empty. Auto-refresh in 1 second.<br>";
        exit;
    }
    $q = executePlainSQL("SELECT Count(*) FROM Users WHERE (Users.names='$n' and Users.passwords='$p')");
    $result = oci_fetch_row($q);
    if ($result[0]==1) {
        // updateUser($n, $p);
        echo "<br>Logged In Successfully!<br>";
        header('refresh:0.5; url=main.php?uid='.$n);
    } else if ($result[0] == 0) {
        header('refresh:1; url=login.php');
        echo "<br>Username or password wrong. Auto-refresh in 1 seconds.<br>";
    }
    OCICommit($db_conn);
}

// function updateUser($n, $p) {
//     executePlainSQL("UPDATE CurrentUser c
//               SET c.Names = '$n', c.Passwords = '$p'");
// }

function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('login', $_POST)) {
            handleLoginRequest();
        }
        disconnectFromDB();
    }
}

if (isset($_POST['login'])) {
    handlePOSTRequest();
}
?>