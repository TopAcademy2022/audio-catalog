<?php

require_once 'vendor/autoload.php';
require_once dirname(__FILE__) . '/services/database-config.php';
require_once dirname(__FILE__) . '/services/sound-service.php';

use Dotenv\Dotenv;
use Services\DatabaseConfig;
use Services\SoundService;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$databaseConfig = new DatabaseConfig();
$soundService = new SoundService($databaseConfig);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$json = file_get_contents('php://input');
	$json = json_decode($json);
	if ($json->{"login"} == "alex")
	{
		echo 'alert(true)';
	}
	else
	{
		echo 'alert(false)';
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	echo json_encode($soundService->getAllSounds(), JSON_UNESCAPED_UNICODE);
}
