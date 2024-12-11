<?php
namespace Models;

require_once dirname(__FILE__) . "/base-model.php";

class LoginHistory extends BaseModel
{
	protected $fillable = ['userId', 'dateTimeLastLogin', 'nameLastDevice'];

	protected function createTable(): void
	{
		$this->createTableName("LoginHistorie");
	}

	public function jsonSerialize(): array
	{
		return [
			'userId' => $this->userId,
			'dateTimeLastLogin' => $this->dateTimeLastLogin,
			'nameLastDevice' => $this->nameLastDevice
		];
	}

	protected function getModelProperties(): array
	{
		return [
			'userId' => 'integer',
			'dateTimeLastLogin' => 'dateTime',
			'nameLastDevice' => 'string'
		];
	}
}