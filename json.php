<?php
	header('Content-type: application/json');
	
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
	
	if (!file_exists('essen/' . $week . '.ess')) 
	{
		$err = array('error' => array ( 'id' => 1,
										'textDE' => utf8_encode("Keine Daten für Woche $week verfügbar"),
										'textEN' => "No data for week $week available"));
		die(json_encode($err));
	}
	
	$essen = unserialize(file_get_contents('essen/' . $week . '.ess'));
	
	$e = array();
	if ($menID >= 0)
	{
		if ($menID <= 15)
		{
			$e[$menID] = $essen[$menID];
			$e['week'] = $week;
		}
		else
		{
			$err = array('error' => array ( 'id' => 2,
											'textDE' => utf8_encode("Keine Daten für angegebene Mensa-ID ($menID) vorhanden"),
											'textEN' => "No data for mensa id $menID available"));
		}
	}
	else
	{
		$e = $essen;
	}
	
	foreach ($a as $k => $v)
	{
		print('1. Schleife'.PHP_EOL);
		if ($k === 'week')
		{
			continue;
		}
		foreach ($v as $k2 => $v2)
		{
			if ($k2 === 'name')
			{
				continue;
			}
			foreach ($v2 as $k3 => $v3)
			{
				if ((trim($v3['essen']) == ''))
				{
					unset($e[$k][$k2][$k3]);
				}
				else
				{
					$e[$k][$k2][$k3]['essen'] = utf8_encode($v3['essen']);
				}
			}
		}
	}
	$b = json_encode($e);
	echo $b;
?>