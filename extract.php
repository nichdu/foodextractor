<?php
	mb_internal_encoding("UTF-8"); 
	ini_set('default_charset', 'UTF-8');
	date_default_timezone_set('Europe/Berlin');
	require_once 'simple_html_dom.php';
	require_once 'mensen.php';
	
	
	// Wochennummer setzen
	$week = date('W');
	$essen = array();
	$essen['week'] = $week;
	
	
	// ID fuer Mensa generieren
	$menID = 0;
	
	// Durch alle Mensen iterieren
	foreach ($mensen as $mensa => $url)
	{
		// Speiseplan-HTML laden
		$html = file_get_html('http://www.studierendenwerk-hamburg.de/essen/' 
				. 'woche.php?haus=' . $url . '&&kw=' . $week);
		// Tabelle mit Speiseplan finden
		$essenTable = $html->find('table', 1);
		
		$essen[$menID] = array();
		$essen[$menID]['name'] = $mensa;
		// Arrays fuer Wochentage anlegen
		for ($k=1;$k<=5;$k++)
		{
			$essen[$menID][$k] = array();
		}
		
		// Durch Tabellenzeilen iterieren (verschiedene Essen)
		$i = 0;
		foreach($essenTable->find('tr') as $tr)
		{
			$i++;
			// Zeilen 1 und 2 sind Wochentage bzw eine Leerzeile
			if ($i < 3) 
			{
				continue;
			}
			
			// Durch Spalten iterieren (Wochentage)
			$j = 0;
			foreach ($tr->find('td') as $td)
			{
				// erste Spalte ist Benennung des Essens
				if ($j == 0) 
				{
					if (preg_match('/^Bitte/',trim($td->plaintext)) == 1)
					{
						// Beilagensortiment soll kein Element werden, wirft Fehler
						continue;
					}
					for ($k=1;$k<=5;$k++)
					{
						$essen[$menID][$k][$i] = array();
						$essen[$menID][$k][$i]['type'] = $td->plaintext;
					}
				}
				else
				{
					// essen der Wochentage in Array speichern, zuvor Textbeschreibungen einfuegen und 
					// Zeilenumbrueche entfernen
					$temp = str_replace("<img src=\"images/3.gif\">"," (mit Schweinefleisch)",$temp);
					$temp = str_replace("<img src=\"images/2.gif\">"," (mit Alkohol)",$temp);
					$temp = str_replace("<img src=\"images/1.gif\">"," (fleischloses Gericht)",$temp);
					$temp = strip_tags($temp);
					
//					$temp = htmlentities($temp);
					$temp = preg_replace('\r\n|\r|\n', ' ', $temp);
					$essen[$menID][$j][$i]['essen'] = $temp;
				}
				$j++;
			}
		}
		$menID++;
	}
	
	// Array in Datei speichern
//	$essen = str_replace("Â","",$essen);
	$ser = serialize($essen);
//	$ser = mb_convert_encoding($ser, "UTF-8");
	file_put_contents('essen/' . $week . '.ess', $ser);
?>