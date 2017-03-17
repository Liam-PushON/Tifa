<?php

final class Tifa {
    public static $modules = array();

    public function init() {
        self::loadCoreModules();
        self::initCoreModules();
        self::loadClientModules();
    }

    private function loadCoreModules() {
        $core_modules = scandir($core_module_path = 'Code/Core/etc/modules/');
        foreach ($core_modules as $core_module):
            if (!is_dir($core_module)):
                $xml = simplexml_load_file($core_module_path . $core_module);
                if($xml):
                    self::loadCoreModule($xml);
                endif;
            endif;
        endforeach;
    }

    private function loadCoreModule($module_xml) {
        $module_config = self::getModuleConfig($module_xml);
        include($main_path = 'Code/'.$module_xml->config->children()[0]->location.'/modules/'.self::getModuleNamespace($module_xml).'/main.php');
        $name = (string)$module_xml->config->children()[0]->name;
        self::$modules[(string)$module_xml->config->children()[0]->getName()] = new $name();
    }

    private function initCoreModules(){
        foreach (self::$modules as $module):
            $module->init();
        endforeach;
    }


    private function getModuleConfig($module_xml){
        $config_path = 'Code/'.$module_xml->config->children()[0]->location.'/modules/'.self::getModuleNamespace($module_xml).'/etc/config.xml';
        return simplexml_load_file($config_path);
    }

    private function getModuleNamespace($module_xml){
        return str_replace('_', '/', $module_xml->config->children()[0]->getName());
    }

    private function loadClientModules() {

    }

    public static function _get($get) {
        return isset(self::$modules[$get]) ? self::$modules[$get] : false;
    }

}