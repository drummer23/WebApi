<?php
/**
 * Created by PhpStorm.
 * User: rspielmann
 * Date: 14.10.2014
 * Time: 16:18
 */

use WebApi\HTML;
use WebApi\Request;

require('common.php');

function __autoload($class) {
    include 'classes/' . $class . '.php';
}


//Kopf erstellen
HTML::printHead();
//Body erstellen
HTML::printBody();
//Überschrift erstellen
HTML::printHeadline("WebApi");

//Neues Request Objekt
$req = new Request();

//Formlar anzeigen
$req->displayForm();


echo '<br /><div style="text-decoration:underline;font-weight:bold;">Antwort des Aufrufs</div><br />';

echo '<div style="border:1px solid gray;">';
//Request absetzen
$req->startRequest();
echo '</div>';

HTML::printFoot();