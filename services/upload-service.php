<?php
namespace Services;

require_once dirname(__FILE__) . '/database-config.php';
require_once dirname(__FILE__) . '/../models/entities/sound.php';
require_once dirname(__FILE__) . '/sound-service.php';

use InvalidArgumentException;
use Models\Sound;


class UploadService
{
	const SOUND_UPLOAD_DIRECTORY = 'sounds';

	private $_databaseConfig;

	public function __construct(DatabaseConfig $databaseConfig)
	{
		$this->_databaseConfig = $databaseConfig;
	}

	public function uploadSound($soundFile, $soundName): array
	{
		// Create directory for uploaded sounds
		if (!file_exists(self::SOUND_UPLOAD_DIRECTORY))
		{
			mkdir(self::SOUND_UPLOAD_DIRECTORY, 0777, true);
		}

		$tempFileName = $soundFile['tmp_name'];
		$originalFileName = basename($soundFile['name']);

		$fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
		$allowedExtensions = ['mp3', 'wav'];

		// Check file extention
		if (in_array(strtolower($fileExtension), $allowedExtensions))
		{
			$newFileName = uniqid('audio_') . '.' . $fileExtension;
			$uploadPath = self::SOUND_UPLOAD_DIRECTORY . '/' . $newFileName;

			// Move to directory
			if (move_uploaded_file($tempFileName, $uploadPath))
			{
				// Add sound to database
				$soundService = new SoundService($this->_databaseConfig);

				$sound = new Sound();
				$sound->name = $soundName;
				$sound->filePath = $uploadPath;
				// TODO: Switch user
				$sound->username = 'admin';

				if ($soundService->addSound($sound))
				{
					return ['status' => 'success'];
				}
			}
		}
		else
		{
			throw new InvalidArgumentException('Неверный формат файла.');
		}

		return ['status' => 'error'];
	}
}