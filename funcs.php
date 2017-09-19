<?php
$config = parse_ini_file('./config.ini');

function db_connect()
{
    $config = parse_ini_file('./config.ini');
    // Define connection as a static variable, to avoid connecting more than once
    static $connection;

    // Try and connect to the database, if a connection has not been established yet
    if (!isset($connection)) {
        // Load configuration as an array. Use the actual location of your configuration file
        $connection = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);
    }

    // If connection was not successful, handle the error
    if ($connection === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error();
    }
    return $connection;
}

function db_query($query)
{
    // Connect to the database
    $connection = db_connect();

    // Query the database
    $result = mysqli_query($connection, $query);

    return $result;
}

function db_error()
{
    $connection = db_connect();
    return mysqli_error($connection);
}

function db_quote($value)
{
    $connection = db_connect();
    return "'" . mysqli_real_escape_string($connection, $value) . "'";
}


function create_request_table()
{
    $query = "SELECT id FROM request";
    $table_exists = db_query($query);

    if (!$table_exists) {
        $query = "CREATE TABLE request (
                          id int(11) AUTO_INCREMENT,
                          created DATETIME,
                          modified DATETIME,
                          status INT NOT NULL,
                          iaa_id VARCHAR(255) UNIQUE NOT NULL,
                          authority VARCHAR(255) NOT NULL,
                          ref_id VARCHAR(255),
                          PRIMARY KEY  (ID)
                          )";
        $result = db_query($query);
        return $result;
    }
    return false;
}

function insert_payment_request($iaaId, $authority)
{
    $dt = new DateTime();
    $now = $dt->format('Y-m-d H:i:s');
    $query = "INSERT INTO `request` (`status`,`iaa_id`,`authority`,`created`) VALUES (" . 0 . ",'" . $iaaId . "','" . $authority . "','" . $now . "')";
    $result = db_query($query);
    return $result;
}

function update_payment_request($authority, $refId)
{
    $dt = new DateTime();
    $now = $dt->format('Y-m-d H:i:s');
    $query = "UPDATE request SET status = 1, ref_id= '".$refId."', modified = '".$now."' WHERE authority = '".$authority."'";
    echo $query;
    $result = db_query($query);
    return $result;
}

function find_request_by_authority($authority)
{
    $query = "SELECT * FROM request WHERE authority = $authority";

    $connection = db_connect();
    $result = $connection->query($query);
    $ret = null;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ret = array("iaaId" => $row['iaa_id'], "status" => $row['status']);
        }
    }
    $connection->close();
    return $ret;
}


function get_tx_info($iaaId)
{
    global $config;
    $url = $config['utadocAddress'] . "/tnxInfoByIaaId?id=" . $iaaId;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}

function sold($iaaId)
{

    global $config;
    $url = $config['utadocAddress'] . "/sold";
    $params = 'id=' . $iaaId;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    return $response;
}


?>