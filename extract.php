<?php
	require_once 'simple_html_dom.php';
	
	$week = date('W');
	$html = file_get_html('http://www.studierendenwerk-hamburg.de/essen/' 
			. 'woche.php?haus=Philosophenturm&&kw=' . $week);
	$essenTable = $html->find('table', 1);
	
	$essen = array();
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
					$essen[$k][$i] = array();
					$essen[$k][$i]['type'] = $td->plaintext;
				}
			}
			else
			{
				$temp = str_replace("<br>","\n",$td->innertext);
				$temp = str_replace("<img src=\"images/3.gif\">","\n(mit Schweinefleisch)",$temp);
				$temp = str_replace("<img src=\"images/2.gif\">","\n(mit Alkohol)",$temp);
				$temp = str_replace("<img src=\"images/1.gif\">","\n(fleischloses Gericht)",$temp);
				$temp = strip_tags($temp);
				$essen[$j][$i]['essen'] = $temp;
			}
			$j++;
		}
	}
?>