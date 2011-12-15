<?php
	require_once 'simple_html_dom.php';
	
	$html = file_get_html('http://www.studierendenwerk-hamburg.de/essen/woche.php?haus=Philosophenturm&&kw=50');
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
				$essen[$j][$i]['essen'] = strip_tags(str_replace("<br>","\n",$td->innertext));
			}
			$j++;
		}
	}
?>