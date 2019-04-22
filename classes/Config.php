<?php

if (!defined('READFILE')) {
    exit('Error, you have not access to this file');
}

class Config
{

    protected $templatesDir;
    protected $configsDir;

    public function __construct($configsDir, $templatesDir)
    {
        $this->configsDir = $configsDir;
        $this->templatesDir = $templatesDir;
    }

    public function generate($login)
    {
        $configBase = $this->getConfigBase($login);
        $config = $this->prepareConfigByTemplates($configBase);

        return array(
            array('config' => base64_encode($config)),
        );
    }

    protected function getConfigBase($login)
    {
        $path = $this->configsDir . $login . '.ini';

        if (!file_exists($path) || !is_readable($path)) {
            throw new Exception("Configuration for '{$login}' does not exist or not readable ", 701);
        }

        return file_get_contents($path);
    }

    protected function prepareConfigByTemplates($config)
    {
        preg_match_all('#{% include (.+?) %}#is', $config, $arrayIncludes);

        if (empty($arrayIncludes)) {
            return $config;
        }

        foreach ($arrayIncludes[0] as $key => $include) {
            $path = $this->templatesDir . str_replace(array('"', "'"), '', $arrayIncludes[1][$key]);

            if (!file_exists($path) || !is_readable($path)) {
                throw new Exception("Template '{$path}' does not exist or not readable ", 702);
            }

            $config = str_replace($include, file_get_contents($path), $config);
        }

        return $config;
    }

}
