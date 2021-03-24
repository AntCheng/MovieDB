<title>Welcome! Please login!</title>

<form action="login.php" method="post">
	<label>Username:</label>
	<input type="text" name="Names">
				
	<label>Password:</label>
	<input type="password" name="Passwords">
				
			
	<input type="submit" name="login" value="Log In">

<?php 
//this tells the system that it's no longer just parsing html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executeBoundSQL($cmdstr, $list) {
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection. 
See the sample code below for how this function is used */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
        return $statement;
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
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}

function handleLoginRequest() {
    global $db_conn;
    $tuple = array (
        $n = $_POST['Names'],
        $p = $_POST['Passwords']
    );
    if (($n == '') || ($p == '')) {
        header('refresh:3; url=login.php');
        echo "<br>Username or password cannot be null. Autorefresh in 3 seconds.<br>";
        exit;
    }
    $alltuples = array (
        $tuple
    );
    $q = executeBoundSQL("SELECT Count(*) FROM Users WHERE (Users.names='$n' and Users.passwords='$p')", $alltuples);
    $result = oci_fetch_row($q);    
    if ($result[0] == 1) {
        echo "<br>Logged In Successfully!<br>";
        header('refresh:1; url=main.php');
    } else if ($result[0] == 0) {
        header('refresh:3; url=login.php');
        echo "<br>Username or password wrong. Autorefresh in 3 seconds.<br>";
    }
    OCICommit($db_conn);
}

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