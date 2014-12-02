<?php
/**
 * Created by PhpStorm.
 * User: rspielmann
 * Date: 14.10.2014
 * Time: 13:44
 */

namespace WebApi;


class Resolver {


	private $HTTPMethod;
	private $apiClasses = array('REST');
	private $APIObj;

	public function __construct()
	{
		//Die Anfrage-Methode aus der Server-Variable erfahren
		$this->HTTPMethod = $_SERVER['REQUEST_METHOD'];
		//Auflösen des Pfades (und Instantiieren des API-Objektes)
		$this->pathResolver();
		//Die jeweilige Behandlung auswählen:
		switch($this->HTTPMethod)
		{
			case 'GET': $this->APIObj->isGET();break;
			case 'POST': $this->APIObj->isPOST();break;
			case 'PUT': $this->APIObj->isPUT();break;
			case 'DELETE': $this->APIObj->isDELETE();break;
			default: $this->error();
		}
	}

	private function pathResolver()
	{
		//Wenn keine API angegeben wurde.
		if ($_GET['path'] == "") die('Keine API angegeben.');

		//Der erste Parameter ist der Pfad (aufsplittet am Slash)
		$data = explode('/', $_GET['path']);

		//Ist eine Klasse für die Behandlung vorgesehen?
		if (!in_array($data[0], $this->apiClasses))
			die('Keine API namens <em>' . $data[0] . '</em> vorhanden!');

		//API-Objekt festlegen:
		$api = '\WebAPI\\' . $data[0] . 'API';

		//API-Objekt erstellen
		$this->APIObj = new $api($data);
	}

	private function error()
	{
		echo 'Die HTTP-Methode '.$this->HTTPMethod.'wird nicht unterstützt';
	}

}

//Hier wird ein Objekt der Klasse automatisch erstellt.
//So muss diese Klasse nur inkludiert werden, um "bereit" zu sein.
new Resolver();