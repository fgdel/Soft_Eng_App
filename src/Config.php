<?php
/**
 * Created by PhpStorm.
 * User: vagrant
 * Date: 15/08/16
 * Time: 12:56
 */

namespace Phizzle;

class Config
{
    private static $instance = null;

    private $data =[];

    private function __construct()
    {
        // Load the database settings
        $configPath = __DIR__ . '/Config/app.json';
        $json = file_get_contents($configPath);
        $asAssociativeArray = true;
        $this->data = json_decode($json, $asAssociativeArray);

    }

    public static function getInstance()
    {
        if(self::$instance == null){
            self::$instance = new config();
        }

        return self::$instance;
    }

    public function get($key)
    {

        return $this->data[$key];
    }
}