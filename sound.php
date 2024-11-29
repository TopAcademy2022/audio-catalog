<?php

class Sound implements JsonSerializable
{
    private $_filePath;

    public function __construct($filePath)
    {
        $this->_filePath = $filePath;
    }

    public function jsonSerialize(): array
    {
        return ['filePath' => $this->_filePath];
    }

    public static function getSongsFromDirectory(): array
    {
        $sounds = [];
        $allFiles = new DirectoryIterator(dirname(__FILE__)."/sounds");

        foreach ($allFiles as $soundFile)
        {
            if ($soundFile->isFile())
            {
                $sound = new Sound("sounds/{$soundFile->getFilename()}");
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