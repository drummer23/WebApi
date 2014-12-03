<?php
/**
 * Created by PhpStorm.
 * User: rspielmann
 * Date: 02.12.2014
 * Time: 16:41
 */

//Projekt-Datei-Verzeichnis
define('PROJECT_DOCUMENT_ROOT',__DIR__);
//Projektname
$project = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace("\\", "/",__DIR__));
$projectname = end(explode('/',$project));


//Protokoll der Verbindung (HTTP oder HTTPS)
(!isset($_SERVER['HTTPS']) OR $_SERVER['HTTPS']=='off') ? $protocol = 'http://' : $protocol = 'https://';
//PROJECT Pfad (für die Verwendung im Web)


$projhttproot = $protocol. $_SERVER['HTTP_HOST'].substr($_SERVER[REQUEST_URI],0,strpos($_SERVER[REQUEST_URI],$projectname) + strlen($projectname));

define('PROJECT_HTTP_ROOT',$projhttproot);