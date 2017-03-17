<?php
if(!file_exists('Code/Core/system/install.lock')){
    include_once ('Code/Core/system/install.php');
    return;
}else{
    include_once ('Code/Core/Core.php');
    Tifa::init();
}