<?php

final class Tifa {
    public static $modules = array();


    public function init() {
        $loaded_modules = array();
        $module_path = 'Code/etc/modules/';
        $module_dir = scandir($module_path);
        foreach ($module_dir as $module) {
            if (!is_dir($module)) {
                $xml = simplexml_load_file($module_path . $module);
                echo '<br>';
                if ($xml->config->children()[0]->active == 'true') {
                    loadModule($xml);
                }

                $config = simplexml_load_file('Code/' . $loc . '/etc/config.xml');

                echo '> ' . $xml->config->children()[0]->getName() . '<br>';
                if (isset($config->config->dependancies->dependancy)) {
                    foreach ($config->config->dependancies->dependancy as $dep) {
                        echo ' - - - - ' . $dep . '<br>';
                    }
                }

                try {
                    self::$modules[$name] = new $name;
                } catch (Exception $e) {
                    echo $e->getMessage() . '<br>';
                    echo $e->getTraceAsString();
                    echo 'unable to load module: ' . $name;
                }
            }
        }
    }


    private function loadModule($xml) {
        $loc = $xml->config->children()[0]->location;
        $loc = $loc . '/modules/' . str_replace('_', '/', $xml->config->children()[0]->getName());
        $name = (string)$xml->config->children()[0]->name;
        include_once('Code/' . $loc . '/main.php');
    }


    public static function _get($get) {
        return isset(self::$modules[$get]) ? self::$modules[$get] : false;
    }

}