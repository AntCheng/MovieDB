<html>
<head>
    <title>CPSC 304 Project</title>
</head>

<body>
<h2>Welcome to movie database!</h2>

<hr />

<!-- create an account and accountID is a random number generated based on username-->
<h2>If you do not have an account, please sign up!</h2>
<form method="POST" action="oracle_test_basic.php"> <!--refresh page when submitted-->
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
    Username: <input type="text" name="Names"> <br /><br />
    Password: <input type="password" name="Passwords"> <br /><br />

    <input type="submit" value="Sign Up" name="insertSubmit"></p>
</form>

<hr />

<h2>If you already have an account, please click log in!</h2>
<input type="button" onclick="window.location.href='login.php'" value="Log In">


<?php
$success = True;
$db_conn = NULL;
$show_debug_alert_messages = False;
function debugAlertMessage($message) {
    global $show_debug_alert_messages;
    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}
function executeBoundSQL($cmdstr, $list) {
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);
    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }
        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>This user name is already used. Please use a different username.<br>";
            $success = False;
        } else {
            echo "<br>Sign Up Successfully! Going to Log In Page in 3 seconds.<br>";
            header('refresh:3; url=login.php');
        }
    }
}
function connectToDB() {
    global $db_conn;
    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_guorunhe", "a17975517", "dbhost.students.cs.ubc.ca:1522/stu");
    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error();
        echo htmlentities($e['message']);
        return false;
    }
}
function disconnectFromDB() {
    global $db_conn;
    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}
function handleInsertRequest() {
    global $db_conn;
    $tuple = array (
        $n = $_POST['Names'],
        $p = $_POST['Passwords'],
        $x = bin2hex(uniqid()),
    );
    if (($n == '') || ($p == '')) {
        header('refresh:5; url=oracle_test_basic.php');
        echo "<br>Username or password cannot be null. Autorefresh in 5 seconds.<br>";
        exit;
    }
    $alltuples = array (
        $tuple
    );
    executeBoundSQL("INSERT INTO Users VALUES ('$x', '$n', '$p')", $alltuples);
    OCICommit($db_conn);
}
function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('insertQueryRequest', $_POST)) {
            handleInsertRequest();
        }
        disconnectFromDB();
    }
}
if (isset($_POST['insertSubmit'])) {
    handlePOSTRequest();
}
?>
</body>
</html>