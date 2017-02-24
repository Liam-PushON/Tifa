<?php

final class Tifa {
    public static $modules = array();

    public function init() {
        $inactive_modules = array();
        $unloaded_modules = array();
        $loaded_modules = array();
        $module_path = 'Code/etc/modules/';
        $module_dir = scandir($module_path);
        foreach($module_dir as $module){
            if(!is_dir($module)){
                $xml = simplexml_load_file($module_path . $module);
                if(self::isModuleActive($xml)){
                    if(self::checkModuleDependancies($xml)){
                        echo 'Loaded: '.$module.'<br>';
                    }
                }else{
                    array_push($inactive_modules, self::getModuleNameSpace($xml));
                }
            }
        }
    }


    private function loadModule($xml) {

    }

    private function isModuleActive($xml){
        return $xml->config->children()[0]->active == 'true' ? true : false;
    }

    private function getModuleNameSpace($xml){
        return isset($xml->config->children()[0]) ? $xml->config->children()[0]->getName() : 'null';
    }

    private function getModulePath($xml){
        return 'Code/'.$xml->config->children()[0]->location.'/modules/' . str_replace('_', '/', self::getModuleNameSpace($xml));
    }

    private function checkModuleDependancies($xml){
        $config = self::getModuleConfig($xml);
        if(isset($config->config->load_without_dependancies) && $config->config->load_without_dependancies == 'true'){
            return true;
        }
        foreach($config->config->dependancies->dependancy as $dependancy){
            $dep_xml = simplexml_load_file('Code/etc/modules/'.$dependancy.'.xml');
            if(!self::isModuleActive($dep_xml)){
                return false;
            }
            $dep_config  =self::getModuleConfig($dep_xml);
            if(!isset(self::$modules[$dep_config->config->children()[0]->getName()])){
                return false;
            }
            return true;
        }
    }


    function getModuleConfig($xml){
        return simplexml_load_file(self::getModulePath($xml).'/etc/config.xml');
    }


    public static function _get($get) {
        return isset(self::$modules[$get]) ? self::$modules[$get] : false;
    }

}