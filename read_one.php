<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, HEAD, OPTIONS, POST, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, X-CSRF-TOKEN');

require 'vendor/autoload.php';
require_once 'config.php';
// Using Medoo namespace
use Medoo\Medoo;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if (isset($_GET['database']) && isset($_GET['keyword']) && isset($_GET['acces_token'])) { //Checks if cookies value exists
    $database = $_GET["database"];
    $keyword = $_GET["keyword"];
    $acces_token = $_GET["acces_token"];

    if ($acces_token === $acces_token_config) {
	    $limit = $_GET["limit"];
	    getKeyWords($database, $keyword, $limit);
    } else {
    	echo json_encode(array('Message' => 'Wrong Access Token!'));
    }
}

function getKeyWords($database, $keyword, $limit) {
	// MYSQL
	define('DB_HOST', '127.0.0.1');
	define('DB_PORT', '3306');
	// define('DB_NAME', 'image');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'admin');

	// Initialize
	$DB = new Medoo([
	    'database_type' => 'mysql',
	    'database_name' => $database,
	    'server' => DB_HOST,
	    'port' => DB_PORT,
	    'username' => DB_USER,
	    'password' => DB_PASSWORD
	]);
	$platforms = $DB->select("keyword", [
		"keyword"
	], [
		"keyword[~]" => $keyword,
		"LIMIT" => $limit
	]);
	echo json_encode($platforms);
}

