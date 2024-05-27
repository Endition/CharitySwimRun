<?php

namespace CharitySwimRun\classes\core;
/** specify extensions that may be loaded */
spl_autoload_extensions('.php');
spl_autoload_register('CharitySwimRun\classes\core\Autoloader::load');

class Autoloader
{

    //Wichtig File und Klassenname m�ssen gleich geschrieben sein
    static public function load($className)
    {
        $ds = DIRECTORY_SEPARATOR;
        $class = explode("\\", $className);
        $count = Count($class);
        if (isset($class[0]) && $class[0] === "Dwoo") {
            $filename = ($class[$count - 1]) . '.php';
            $pfad = "";
            for ($i = 1; $i < $count - 1; $i++) {
                $pfad .= $ds . $class[$i];
            }
            $file = DWOO_PATH . $pfad . $ds . $filename;
            #echo $file."<br />";
            #echo"</pre>";
            if (is_readable($file)) {
                include_once $file;
                return true;
            }
        }
        $paths = array("",
            $ds . "classes" . $ds . "controller",
            $ds . "classes" . $ds . "core",
            $ds . "classes" . $ds . "helper",
            $ds . "classes" . $ds . "model",
            $ds . "classes" . $ds . "pdf",
            $ds . "classes" . $ds . "renderer"
        );
        //Dateiname aus �bergebenen Klassennamen bauen
        $filename = ($class[$count - 1]) . '.php';
        foreach ($paths as $path) {
            //Pfad zusammenbauen
            $file = realpath(dirname(__FILE__) . '/../..') . $path . $ds . $filename;
            if (file_exists($file)) {
                #echo $file;
                include_once $file;
                return true;
            }
        }
        return false;
    }
}