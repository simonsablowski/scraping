<?php

require 'config.php';

ini_set('max_execution_time', $executionTime);

foreach ($scrapers as $scraperName) {
	$scraperName = 'Scraper' . $scraperName;
	require $scraperName . '.php';
	$Scraper = new $scraperName;
	
	$Scraper->run();
}