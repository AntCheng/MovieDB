<?php


$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : False;
}

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;
    $result = $db_conn-> query($cmdstr);
    return $result;
}

function executeBoundSQL($cmdstr, $list, $types) { // takes a list of inputs and input types and binds them to reusable sql
    global $db_conn, $success;
    $stmt = $db_conn->prepare($cmdstr);
    $stmt->bind_param($types, $list[0], $list[1]);
    $result = $stmt->execute();
    return $result;
}

function connectToDB() {
    global $db_conn;

    // Your username is (CWL_ID) and the password is a(student number). For example,
    // platypus is the username and a12345678 is the password.

    $db_conn= new mysqli("us-cdbr-east-04.cleardb.com", "b144ad00f588e0", "1e2e241d");
            if ($db_conn->connect_error) {
                debugAlertMessage('Connect Failed' . $db_conn->connect_error);
                return false;
            } else {
                debugAlertMessage('Successfully Connected to MYSQL');
                return true;
            }

        }

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    $db_conn->close();
}

function showTable($r, $c, $pic) {
    echo "<table style='width:100%' class='table table-striped'>";
    echo "<thead class='thead-dark'><tr>";
    for ($i = 1; $i <= $c; $i++){
        $field_name = ucwords(strtolower(mysqli_field_name($r, $i)));
        echo "<th>$field_name</th>";
    }
    if ($pic) {
        echo "<th>Picture</th>";
    }
    echo "</tr></thead>";
    echo "<tbody><tr>";
    while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
        for($i = 0; $i < $c; $i++){
            echo "<td>$row[$i]</td>";
        }
        if ($pic) {
            $out = '<td width="150" height="200">';
            $out .= '<img src="'.$row[$c].'" width="150" height="200"/></td>';
            echo $out;
        }
        echo "</tr>";
    }
    echo "</tbody></table>";
}



