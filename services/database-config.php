<?php
namespace Services;


use Illuminate\Database\Capsule\Manager;
use PDO;
use PDOException;
use DirectoryIterator;


class DatabaseConfig
{
	const DEFAULT_MODELS_DIRECTORY = 'models/entities';

	const DEFAULT_MODELS_NAMESPACE = 'Models';

	private function getAllModelNames($directoryName, $excludedModels): array
	{
		$allModels = [];

		$allFiles = new DirectoryIterator($directoryName);
		foreach ($allFiles as $modelAsFile)
		{
			if ($modelAsFile->isFile())
			{
				// Rename file to class (model-name.php to ModelName)
				$modelNameWithDash = str_replace('.php', '', $modelAsFile->getFilename());
				$modelNameParts = explode('-', $modelNameWithDash);
				for ($i = 0; $i < count($modelNameParts); $i++)
				{
					$modelNameParts[$i] = ucfirst($modelNameParts[$i]);
				}

				$allModels[] = implode('', $modelNameParts);
			}
		}

		return array_diff($allModels, $excludedModels);
	}

	private function createDatabase($databaseName): void
	{
		try
		{
			$databaseConnection = new PDO(
				"{$_ENV['DB_CONNECTION']}:host={$_ENV['DB_HOST']}",
				$_ENV['DB_USERNAME'],
				$_ENV['DB_PASSWORD']
			);
			$databaseConnection->query("CREATE DATABASE $databaseName");
		}
		catch (PDOException $exception)
		{
			// Use logger
		}
	}

	private function createTable($modelName): void
	{
		$tableName = null;

		// Update last char (history -> historie) for adding s.
		if (substr($modelName, -1) === 'y')
		{
			$tableName = substr($modelName, 0, -1) . 'ie' . 's';
		}
		else
		{
			$tableName = "{$modelName}s";
		}

		if (!Manager::schema()->hasTable($tableName))
		{
			$className = self::DEFAULT_MODELS_NAMESPACE . '\\' . $modelName;
			$modelAsObject = new $className();
			$modelAsObject->createSchema($tableName);
		}
	}

	public function __construct()
	{
		// Create configuration for db connect
		$connectionManager = new Manager();
		$connectionManager->addConnection([
			'driver' => $_ENV['DB_CONNECTION'],
			'host' => $_ENV['DB_HOST'],
			'database' => $_ENV['DB_DATABASE'],
			'username' => $_ENV['DB_USERNAME'],
			'password' => $_ENV['DB_PASSWORD'],
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => '',
		]);

		$connectionManager->setAsGlobal();
		$connectionManager->bootEloquent();

		// Create database in DBMS
		$this->createDatabase($_ENV['DB_DATABASE']);

		// Excluded models (name model = name of class)
		$excludedModels = ['BaseModel'];

		$pathToRootcatalog = str_replace('services', '', __DIR__);
		$allModels = $this->getAllModelNames(
			$pathToRootcatalog . self::DEFAULT_MODELS_DIRECTORY,
			$excludedModels
		);

		// Including all models from directory
		$phpModels = glob($pathToRootcatalog . self::DEFAULT_MODELS_DIRECTORY . '/*.php');
		foreach ($phpModels as $phpModel)
		{
			require_once $phpModel;
		}

		// Create models in database
		foreach ($allModels as $modelName)
		{
			$this->createTable($modelName);
		}
	}
}