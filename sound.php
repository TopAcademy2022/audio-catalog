<?php

class Sound implements JsonSerializable
{
    private $_filePath;

    private $_name;

    public function __construct($filePath, $name)
    {
        $this->_filePath = $filePath;
        $this->_name = $name;
    }

    public function jsonSerialize(): array
    {
        return ['filePath' => $this->_filePath, 'name' => $this->_name];
    }

    public static function getSongsFromDirectory(): array
    {
        $sounds = [];
        $allFiles = new DirectoryIterator(dirname(__FILE__) . "/sounds");

        foreach ($allFiles as $soundFile) {
            if ($soundFile->isFile()) {
                $sound = new Sound("sounds/{$soundFile->getFilename()}", "example");
                $sounds[] = $sound;
            }
        }

        return $sounds;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo json_encode(Sound::getSongsFromDirectory(), JSON_UNESCAPED_UNICODE);
}