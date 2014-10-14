<?php
/**
 * Created by PhpStorm.
 * User: rspielmann
 * Date: 14.10.2014
 * Time: 16:18
 */

require_once('classes/API.php');
require_once('classes/HTML.php');
require_once('classes/Request.php');
require_once('classes/Resolver.php');
require_once('classes/RESTAPI.php');


//Kopf erstellen
WebApi\HTML::printHead();
//Body erstellen
WebApi\HTML::printBody();
//Überschrift erstellen
WebApi\HTML::printHeadline("WebApi");

//Neues Request Objekt
$req = new WebAPI\Request();

//Formlar anzeigen
$req->displayForm();


echo '<br /><div style="text-decoration:underline;font-weight:bold;">Antwort des Aufrufs</div><br />';

echo '<div style="border:1px solid gray;">';
//Request absetzen
$req->startRequest();
echo '</div>';

WebApi\HTML::printFoot();