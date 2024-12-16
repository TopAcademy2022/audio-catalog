<?php

require_once 'vendor/autoload.php';
require_once dirname(__FILE__) . '/services/database-config.php';
require_once dirname(__FILE__) . '/services/sound-service.php';
require_once dirname(__FILE__) . '/services/upload-service.php';

use Dotenv\Dotenv;
use Services\DatabaseConfig;
use Services\SoundService;
use Services\UploadService;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$databaseConfig = new DatabaseConfig();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'text/plain;charset=UTF-8')
{
	$json = file_get_contents('php://input');
	$json = json_decode($json);
	if ($json->{"login"} == "alex")
	{
		echo 'true';
	}
	else
	{
		echo 'false';
	}
}
// TODO: Also check rules
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio']))
{
	$uploadResult = ['status' => 'error'];

	if ($_FILES['audio']['error'] === UPLOAD_ERR_OK)
	{
		$uploadService = new UploadService($databaseConfig);
		$uploadResult = $uploadService->uploadSound($_FILES['audio'], $_POST['soundName']);
	}

	echo json_encode($uploadResult, JSON_UNESCAPED_UNICODE);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	$soundService = new SoundService($databaseConfig);
	echo json_encode($soundService->getAllSounds(), JSON_UNESCAPED_UNICODE);
}
