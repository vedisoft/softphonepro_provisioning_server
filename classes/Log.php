<?php

if (!defined('READFILE')) {
    exit('Error, you have not access to this file');
}

class Log
{

    protected $logsDir;
    protected $fileName;

    public function __construct($logsDir)
    {
        $this->logsDir = $logsDir;

        if (!file_exists($logsDir)) {
            mkdir($logsDir);
        }

        if (!file_exists($logsDir) || !is_writable($logsDir)) {
            throw new Exception("The Folder '{$logsDir}' does not exist or not writable");
        }

        $this->fileName = $logsDir . date('Y-m-d') . '.log';
    }

    public function write($message)
    {
        $message = sprintf("[%s]: %s\r\n", date('Y-m-d H:i:s'), $message);

        file_put_contents($this->fileName, $message, FILE_APPEND);
    }

}
