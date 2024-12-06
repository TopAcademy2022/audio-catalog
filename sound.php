<?php

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $json = file_get_contents('php://input');
    $json = json_decode($json);
    if($json->{"login"} == "alex")
    {
        echo'alert(true)';
    }
    else
    {
        echo'alert(false)';
    }
}

class Sound implements JsonSerializable
{
    private $_filePath;
    
    private $_username;

	private $_name;
    public function __construct($filePath, $name, $username)
    {
        $this->_filePath = $filePath;
		$this->_name = $name;
        $this->_username = $username;
    }

    public function jsonSerialize(): array
    {
        return [
            'filePath' => $this->_filePath,
			'name' => $this->_name,
            'username' => $this->_username
        ];
    }

    public static function getSongsFromDirectory(): array
    {
        $sounds = [];
        $allFiles = new DirectoryIterator(dirname(__FILE__) . "/sounds");


        foreach ($allFiles as $soundFile) {
            if ($soundFile->isFile()) {
                $sound = new Sound("sounds/{$soundFile->getFilename()}", "example", "admin");
                $sounds[] = $sound;
            }
        }

        return $sounds;
    }
}

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    echo json_encode(Sound::getSongsFromDirectory(), JSON_UNESCAPED_UNICODE);
}