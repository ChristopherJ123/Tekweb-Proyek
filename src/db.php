<?php
$db_server = "auth-db1031.hstgr.io"; // Host end
$db_username = "u967403313_admin"; // Host
$db_password = "ProyekTekweb123*"; // Password
$db_name = "u967403313_proyekTekweb"; // Nama database

function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

global $conn;
try {
    $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
} catch (mysqli_sql_exception $e) {
    console_log("Could not connect to the database.");
}

if ($conn) {
//    console_log("You are connected to the database.");
} else console_log("Could not connect to the database.");