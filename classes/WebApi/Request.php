<?php
/**
 * Created by PhpStorm.
 * User: rspielmann
 * Date: 14.10.2014
 * Time: 15:20
 */

namespace WebApi;


class Request {

	private $path;
	private $api;
	private $postparam;
	private $showHeader;


	public function __construct()
	{
		//Pfad, API und POST-Param merken
		$this->path = isset($_POST['path']) ? $_POST['path'] : '';
		$this->api = isset($_POST['api']) ? $_POST['api'] : '';
		$this->postparam = isset($_POST['postparam']) ? $_POST['postparam'] : '';
		$this->showHeader = isset($_POST['showHeader']) ? $_POST['showHeader'] : '';
	}

	public function displayForm()
	{
		//Formular erstellen:
		echo '<form method="post">';
		echo 'API:<select name="api">';

        $selected = ($this->api == 'REST') ? 'selected' : '';
        echo '<option '.$selected.' value="REST">REST</option>';
		$selected = ($this->api == 'JSON') ? 'selected' : '';
		echo '<option '.$selected.' value="JSON">JSON</option>';
		echo '</select>';

		echo 'PATH:<input type="input" name="path" value="'. htmlentities($this->path,ENT_QUOTES,"UTF-8").'"/>';

		echo '<input type="submit" name="method" value="GET"/>';
		echo '<input type="submit" name="method" value="DELETE"/>';

		echo '<input type="text" name="postparam" value="'.$this->postparam.'"/>';
		echo '<input type="submit" name="method" value="PUT"/>';
		echo '<input type="submit" name="method" value="POST"/><br />';

		$checked = ($this->showHeader) ? 'checked' : '';
		echo '<input type="checkbox" '.$checked.' name="showHeader" value="showHeader"/>HTTP-Header anzeigen';
		echo '</form>';
	}

	public function startRequest()
	{
		//Wenn keine Methode ausgewählt wurde, abbrechen!
		if(!isset($_POST['method']))return false;
		//Neues cURL-Objekt erstellen
		$ch = curl_init();

		//URL für den Aufruf setzen
		curl_setopt($ch, CURLOPT_URL, 'WebAPI/Api/'.$this->api.'/'.$this->path);

		//Soll der HTTP-Header angezeigt werden?
		if($this->showHeader != '') curl_setopt($ch, CURLOPT_HEADER, 1);

		//Die Parameter für die jeweilige Methode setzen
		switch($_POST['method'])
		{
			case 'GET':
				curl_exec($ch);
				break;
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
				curl_exec($ch);
				break;
			case 'DELETE':
				//Speziellen Request absetzen: DELETE
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"DELETE");
				curl_exec($ch);
				break;
			case 'PUT':
				curl_setopt($ch,CURLOPT_PUT,true);
				//Der zu sendende String (Inhalt des Textfelds)
				$putString = $this->postparam;
				//temporäre Datei erstellen
				$putData = tmpfile();
				//Datei schreiben
				fwrite($putData, $putString);
				//Dateizeiger wieder auf den Anfang der Datei setzen
				fseek($putData, 0);
				//Datei zum Übertragen per PUT einlesen
				curl_setopt($ch, CURLOPT_INFILE, $putData);
				//Länge der Datei setzen (Wichtig!!!)
				curl_setopt($ch,
					CURLOPT_INFILESIZE,strlen($putString));
				curl_exec($ch);
				//FileHandler der temporären Datei schließen
				fclose($putData);
				break;
			default:
				echo 'Keine gültige HTTP-Methode für den Aufruf angegeben!';
		}
		//cURL-Objekt schließen und damit aufräumen
		curl_close($ch);

	}

}