<?php
namespace Models;

require_once dirname(__FILE__) . "/base-model.php";

class User extends BaseModel
{
	protected $fillable = ['login', 'password'];

	protected function createTable(): void
	{
		$this->createTableName($this);
	}

	public function jsonSerialize(): array
	{
		return [
			'login' => $this->login,
			'password' => $this->password
		];
	}

	protected function getModelProperties(): array
	{
		return [
			'login' => 'string',
			'password' => 'string'
		];
	}
}