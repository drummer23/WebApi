<?php
/**
 * Created by PhpStorm.
 * User: rspielmann
 * Date: 14.10.2014
 * Time: 16:18
 */


use WebApi\HTML;


//require_once('classes/API.php');
//require_once('classes/HTML.php');
//require_once('classes/Request.php');
//require_once('classes/Resolver.php');
//require_once('classes/RESTAPI.php');

//function my_autoloader($class) {
//    include 'classes/' . $class . '.php';
//}

function __autoload($class) {
    include 'classes/' . $class . '.php';
}

//spl_autoload_register('my_autoloader');

//Kopf erstellen
HTML::printHead();
//Body erstellen
HTML::printBody();
//Überschrift erstellen
HTML::printHeadline("WebApi");

//Neues Request Objekt
$req = new WebAPI\Request();

//Formlar anzeigen
$req->displayForm();


echo '<br /><div style="text-decoration:underline;font-weight:bold;">Antwort des Aufrufs</div><br />';

echo '<div style="border:1px solid gray;">';
//Request absetzen
$req->startRequest();
echo '</div>';

HTML::printFoot();