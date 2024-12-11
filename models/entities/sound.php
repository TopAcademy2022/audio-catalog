<?php
namespace Models;

require_once dirname(__FILE__) . "/base-model.php";

class Sound extends BaseModel
{
	// Eloquent magic
	protected $fillable = ['name', 'filePath', 'username'];

	protected function createTable(): void
	{
		$this->createTableName($this);
	}

	public function jsonSerialize(): array
	{
		return [
			'name' => $this->name,
			'filePath' => $this->filePath,
			'username' => $this->username
		];
	}

	protected function getModelProperties(): array
	{
		return [
			'name' => 'string',
			'filePath' => 'string',
			'username' => 'string'
		];
	}
}