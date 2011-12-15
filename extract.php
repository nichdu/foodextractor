<?php
	require_once 'simple_html_dom.php';
	require_once 'mensen.php';
	
	$week = date('W');
	$essen = array();
	$essen['week'] = $week;
	
	foreach ($mensen as $mensa => $url)
	{
		$html = file_get_html('http://www.studierendenwerk-hamburg.de/essen/' 
				. 'woche.php?haus=' . $url . '&&kw=' . $week);
		$essenTable = $html->find('table', 1);
		
		$essen[$mensa] = array();
		for ($k=1;$k<=5;$k++)
		{
			$essen[$k] = array();
		}
		$i = 0;
		foreach($essenTable->find('tr') as $tr)
		{
			$i++;
			if ($i < 3) 
			{
				continue;
			}
			
			$j = 0;
			foreach ($tr->find('td') as $td)
			{
				if ($j == 0) 
				{
					for ($k=1;$k<=5;$k++)
					{
						$essen[$mensa][$k][$i] = array();
						$essen[$mensa][$k][$i]['type'] = $td->plaintext;
					}
				}
				else
				{
					$temp = str_replace("<br>","\n",$td->innertext);
					$temp = str_replace("<img src=\"images/3.gif\">","\n(mit Schweinefleisch)",$temp);
					$temp = str_replace("<img src=\"images/2.gif\">","\n(mit Alkohol)",$temp);
					$temp = str_replace("<img src=\"images/1.gif\">","\n(fleischloses Gericht)",$temp);
					$temp = strip_tags($temp);
					$essen[$mensa][$j][$i]['essen'] = $temp;
				}
				$j++;
			}
		}
	}
?>