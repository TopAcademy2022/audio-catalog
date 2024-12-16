<?php
namespace Services;

require_once dirname(__FILE__) . '/../models/entities/sound.php';
require_once dirname(__FILE__) . '/database-config.php';

use InvalidArgumentException;
use Models\Sound;


class SoundService
{
	private $_databaseConfig;

	public function __construct(DatabaseConfig $databaseConfig)
	{
		$this->_databaseConfig = $databaseConfig;
	}

	public function getAllSounds(): array
	{
		return Sound::all()->toArray();
	}

	public function addSound(object $soundObject): bool
	{
		if (!is_a($soundObject, 'Models\Sound'))
		{
			throw new InvalidArgumentException('Переданный объект не является экземпляром Models\Sound.');
		}

		return $soundObject->save();
	}
}