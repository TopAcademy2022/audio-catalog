<?php

class Sound implements JsonSerializable
{
    private $_filePath;
    private $_username;

    public function __construct($filePath, $username)
    {
        $this->_filePath = $filePath;
        $this->_username = $username;
    }

    public function jsonSerialize(): array
    {
        return [
            'filePath' => $this->_filePath,
            'username' => $this->_username
        ];
    }

    public static function getSongsFromDirectory(): array
    {
        $sounds = [];
        $allFiles = new DirectoryIterator(dirname(__FILE__)."/sounds");

        $usernames = 
        [
            "user1",
        ];

        $i = 0;
        foreach ($allFiles as $soundFile)
        {
            if ($soundFile->isFile())
            {
                $username = isset($usernames[$i]) ? $usernames[$i] : "unknown";
                $sound = new Sound("sounds/{$soundFile->getFilename()}", $username);
                $sounds[] = $sound;
                $i++;
            }
        }

        return $sounds;
    }
}

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    echo json_encode(Sound::getSongsFromDirectory(), JSON_UNESCAPED_UNICODE);
}
