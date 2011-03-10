<?php

abstract class Scraper {
	protected $dataDirectory = 'data';
	protected $domain;
	protected $startUrl;
	protected $pagesPattern;
	protected $pagesPatternUrlPosition;
	protected $itemsPattern;
	protected $itemsPatternUrlPosition;
	protected $log;
	
	abstract public function __construct();
	
	public function run() {
		printf("<pre>");
		
		printf("Running %s Scraper ...\n", $this->getName());
		
		$this->openLog();
		
		foreach ($this->findPageUrls() as $pageUrl) {
			printf("Scanning %s ...\n", $pageUrl);
			
			foreach ($this->findItems($pageUrl) as $item) {
				printf("... found %s ...\n", $item);
				
				$this->writeToLog($item);
			}
		}
		
		$this->closeLog();
		
		printf("</pre>");
	}
	
	protected function openLog() {
		$this->setLog(fopen(sprintf('%s%s.txt', $this->getDataDirectory() ? $this->getDataDirectory() . '/' : '', $this->getDomain()), 'w+'));
	}
	
	protected function writeToLog($item) {
		fwrite($this->getLog(), sprintf("%s\n", $item));
	}
	
	protected function closeLog() {
		fclose($this->getLog());
	}
	
	protected function findPageUrls() {
		$pageUrls = array();
		
		preg_match_all($this->getPagesPattern(), $this->readUrl($this->getStartUrl()), $matches);
		foreach ($matches[$this->getPagesPatternUrlPosition()] as $pageUrl) {
			$pageUrl = (stripos($pageUrl, 'http://') !== FALSE ? '' : 'http://' . $this->getDomain()) . $pageUrl;
			$pageUrls[] = $pageUrl;
		}
		
		return $pageUrls;
	}
	
	protected function findItems($pageUrl) {
		$items = array();
		
		preg_match_all($this->getItemsPattern(), $this->readUrl($pageUrl), $matches);
		foreach ($matches[$this->getItemsPatternUrlPosition()] as $item) {
			$items[] = $item;
		}
		
		return $items;
	}
	
	protected function getName() {
		return str_replace('Scraper', '', get_class($this));
	}
	
	protected function readUrl($url) {
		return file_get_contents($url);
	}
	
	protected function getDataDirectory() {
		return $this->dataDirectory;
	}
	
	protected function setDataDirectory($value) {
		$this->dataDirectory = $value;
	}
	
	protected function getDomain() {
		return $this->domain;
	}
	
	protected function setDomain($value) {
		$this->domain = $value;
	}
	
	protected function getStartUrl() {
		return $this->startUrl;
	}
	
	protected function setStartUrl($value) {
		$this->startUrl = $value;
	}
	
	protected function getPagesPattern() {
		return $this->pagesPattern;
	}
	
	protected function setPagesPattern($value) {
		$this->pagesPattern = $value;
	}
	
	protected function getPagesPatternUrlPosition() {
		return $this->pagesPatternUrlPosition;
	}
	
	protected function setPagesPatternUrlPosition($value) {
		$this->pagesPatternUrlPosition = $value;
	}
	
	protected function getItemsPattern() {
		return $this->itemsPattern;
	}
	
	protected function setItemsPattern($value) {
		$this->itemsPattern = $value;
	}
	
	protected function getItemsPatternUrlPosition() {
		return $this->itemsPatternUrlPosition;
	}
	
	protected function setItemsPatternUrlPosition($value) {
		$this->itemsPatternUrlPosition = $value;
	}
	
	protected function getLog() {
		return $this->log;
	}
	
	protected function setLog($value) {
		$this->log = $value;
	}
}