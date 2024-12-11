<?php
namespace Models;


use JsonSerializable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;


abstract class BaseModel extends Model implements JsonSerializable
{
	protected $table;

	// By default disable time stamps (created_at and updated_at)
	public $timestamps = false;

	abstract protected function getModelProperties(): array;

	abstract protected function createTable(): void;

	protected function getBlueprintFunctionForTable(): callable
	{
		return function (Blueprint $table): void
		{
			$table->increments('id');
			foreach ($this->getModelProperties() as $modelPropertyKey => $modelPropertyValue)
			{
				$table->$modelPropertyValue($modelPropertyKey);
			}
		};
	}

	protected function createTableName($model): void
	{
		$this->table = ucfirst(class_basename($model)) . 's';
	}

	public function __construct()
	{
		$this->createTable();
	}

	public function createSchema($tableName): void
	{
		Manager::schema()->create($tableName, $this->getBlueprintFunctionForTable());
	}
}
