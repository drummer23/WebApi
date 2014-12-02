<?php
/**
 * Created by PhpStorm.
 * User: rspielmann
 * Date: 14.10.2014
 * Time: 10:52
 */

namespace WebApi;


class RESTAPI
{

    private $allowedObj = array('file','test');
    private $path;
    private $resource;
    private $filename;
    private $fileHandle;

    public function __construct($data)
    {
        //Original-Pfad (nur für Debug-Zwecke)
        $this->path = implode('/',$data);
        //erstes Element (die API-Bezeichnung wird gelöscht)
        array_shift($data);

        //Prüfen, ob der erste Parameter ein erlaubtes
        //Objekt bezeichnet
        if(!in_array($data[0],$this->allowedObj))
        {
            echo '<div style="color:red;">';
            echo 'Bitte geben Sie eine gültige Ressource an.';
            echo '</div>';
            return false;
        }

        if(isset($data[1]) AND is_numeric($data[1]))
        {
            //ID der angesprochenen Ressource
            $id = intval($data[1]);
            //Ressourcenname
            $this->resource = $data[0].'/'.$id;
            //Dateiname festlegen
            $this->filename = PROJECT_DOCUMENT_ROOT . '/API/files/'.$data[0].'/'.$id;
        }
        else
        {
            //Keine ID angegeben: nur POST zum Anlegen
            //einer neuen Ressource ist erlaubt
            $this->resource = $data[0];
            //Filename verweist auf ein Verzeichnis
            $this->filename = PROJECT_DOCUMENT_ROOT . '/API/files/'.$data[0];
        }
    }

    private function writeFile($id)
    {
        //Ist der Pfad ein Verzeichnis?
        if(!is_dir($this->filename))
        {
            echo '<div style="color:red;">';
            echo 'Keine gültige URL für POST.';
            echo '</div>';
            return false;
        }

        //Öffnen der Datei
        $this->fileHandle = @fopen($this->filename.'/'.$id,'w+');
        if(!$this->fileHandle)
        {
            echo '<div style="color:red;">';
            echo 'Probleme mit dem Dateisystem.';
            echo '</div>';
            return false;
        }
        return true;
    }

	private function openFile()
	{
		if(is_file($this->filename))
		{
		//Öffnen der Datei
			$this->fileHandle = @fopen($this->filename,'w+');
			if(!$this->fileHandle)
			{
				echo '<div style="color:red;">';
				echo 'Probleme mit dem Dateisystem.';
				echo '</div>';
				return false;
			}
		}
		else
		{
			echo '<div style="color:red;">';
			echo 'Ressource: '.$this->resource.' ist nicht vorhanden.';
			echo '</div>';
			return false;
		}
		return true;
	}

	public function __destruct()
	{
		if($this->fileHandle) fclose($this->fileHandle);
	}

	public function isGET()
	{
		echo '<strong>GET</strong> wurde aufgerufen auf path: '.$this->path;
		//Im Fehlerfall des Konstruktors: abbrechen
		if(($this->resource==''))return false;

		//Anzeigen des Dateiinhalts der angesprochenen Ressource:
		if(is_file($this->filename))
		{
			echo '<div style="color:green;">';
			readfile($this->filename);
			echo '</div>';
		}
		else
		{
			echo '<div style="color:red;">';
			echo 'Ressource: '.$this->resource.' ist nicht vorhanden.';
			echo '</div>';
		}
	}

	public function isPOST()
	{
		echo '<strong>POST</strong> wurde aufgerufen auf path: ' . $this->path;
		//Neue ID generieren
		$id = time();
		//Im Fehlerfall des Konstruktors, abbrechen
		if (($this->resource == '') OR (!$this->writeFile($id)))
			return false;

		//Dateiinhalt schreiben
		fwrite($this->fileHandle, $_POST['postparam']);
		echo '<div style="color:green;">';
		echo 'Ressource:' . $this->resource . ' wurde angelegt.';
		echo '</div>';
	}

	public function isPUT()
	{
		echo '<strong>PUT</strong> wurde aufgerufen auf path: '.$this->path;
		//Im Fehlerfall des Konstruktors abbrechen
		if(($this->resource=='') OR (!$this->writeFile()))
			return false;

		try
		{
			//Input der PUT-HTTP Methode
			$s = fopen("php://input", "r");
			while($kb = fread($s, 1024))
			{
				fwrite($this->fileHandle, $kb, 1024);
			}
			fclose($s);
			echo '<div style="color:green;">';
			echo 'Ressource: '.$this->resource.' wurde angelegt.';
			echo '</div>';
		}
		catch(Exception $e) {
			echo 'Fehler aufgetreten';
		}

		//Generell sollte man diesen Header schicken!
		//header("HTTP/1.1 201 Created");

	}

	public function isDELETE()
	{
		echo '<strong>DELETE</strong> wurde aufgerufen auf path: '.$this->path;
		//Im Fehlerfall des Konstruktors abbrechen
		if($this->resource=='')return false;
		//Element wird gelöscht..
		if(is_file($this->filename))
		{
			if(unlink($this->filename))
			{
				echo '<div style="color:green;">';
				echo 'Ressource <em>'.$this->resource.'</em> wurde gelöscht.';
				echo '</div>';
			}
			else
			{
				echo '<div style="color:red;">';
				echo 'Ressource: '.$this->resource.' konntenicht gelöscht werden.';
				echo '</div>';
			}
		}
		else
		{
			echo '<div style="color:red;">';
			echo 'Ressource: '.$this->resource.' ist nicht vorhanden.';
			echo '</div>';
		}
	}

} 