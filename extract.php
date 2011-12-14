<?php
	require_once 'simple_html_dom.php';
	
	$html = file_get_html('http://www.studierendenwerk-hamburg.de/essen/woche.php?haus=Philosophenturm&&kw=50');
	$essenTable = $html->find('table', 1);
	
	echo '<pre>';
	print_r($essenTable);
?>