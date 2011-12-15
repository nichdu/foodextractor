<?php
	mb_internal_encoding("UTF-8"); 
	ini_set('default_charset', 'UTF-8');
	date_default_timezone_set('Europe/Berlin');
	header("Content-type: text/xml;charset=UTF-8");
	
	$_GET['id'] = 13;
	
	ob_start();
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	echo "\n";
	
	// Aufbau: <essen week="n"><mon><food><name></><desc></><student></><mitarbeiter></></food></mon><tue> usw
	
	// Ausgabewoche bestimmen
	$week = date('W');
	if (isset($_GET['week'])) 
	{
		if ($_GET['week'] > 0 && $_GET['week'] < 54)
		{
			$w = $_GET['week'];
			preg_match('/^([1-9]|[1-4][0-9]|5[0-3])$/', $w, $we);
			$week = $we[0];
		}
	}
	
	if (isset($_GET['id']))
	{
		preg_match('/^([0-9]|1[0-5])$/', $_GET['id'],$men);
		$menID = $men[0];
	}
	else
	{
		$menID = -1;
	}
	
	$essen = unserialize(file_get_contents('essen/' . $week . '.ess'));
	
	$e = array();
	if ($menID >= 0)
	{
		$e[$menID] = $essen[$menID];
	}
	else
	{
		$e = $essen;
	}
	echo '<essen week="' . $week . '">' . "\n";
	foreach ($e as $id => $mensaEssen)
	{
		if ($id == 'week')
		{
			continue;
		}
		echo '<mensa><id>' . $id . '</id>';
		foreach ($mensaEssen as $k => $v)
		{
			if ($k == 'name')
			{
				// name der Mensa in Key speichern
				echo '<name>' . $v . '</name>';
			}
			else
			{
				$key = 'err';
				// Keys fuer Wochentage generieren
				switch ($k)
				{
					case 1:
						$key = 'mon';
						break;
					case 2:
						$key = 'tue';
						break;
					case 3:
						$key = 'wed';
						break;
					case 4:
						$key = 'thu';
						break;
					case 5:
						$key = 'fri';
						break;
					default:
						throw new InvalidArgumentException();
				}
				echo "<$key>\n";
				foreach ($v as $id => $essen)
				{
					if ($essen['essen'] == "" || $essen['essen'] == '&nbsp;')
					{
						continue;
					}
					echo '<food>';
					echo '<type><id>' . $id . '</id>';
					echo '<name>' . $essen['type'] . '</name></type>' . "\n";
					echo '<desc>' . $essen['essen'] . "</desc>\n";
					echo '</food>'. "\n";
				}
				echo "</$key>\n";
			}
		}
		echo '</mensa>';
	}
	echo '</essen>';
	$out = ob_get_contents();
	ob_end_clean();
	
	$out = str_replace('&auml', 'ä', $out);
	$out = str_replace('&Auml', 'Ä', $out);
	$out = str_replace('&ouml', 'ö', $out);
	$out = str_replace('&Ouml', 'Ö', $out);
	$out = str_replace('&uuml', 'Ü', $out);
	$out = str_replace('&szlig', 'ß', $out);
	echo $out;