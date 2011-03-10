<?php

require_once 'Scraper.php';

abstract class CategoryScraper extends Scraper {
	protected $categoriesPattern;
	protected $categoriesPatternUrlPosition;
	
	protected function findPageUrls() {
		$pageUrls = array();
		
		preg_match_all($this->getCategoriesPattern(), $this->readUrl($this->getStartUrl()), $matches);
		foreach ($matches[$this->getCategoriesPatternUrlPosition()] as $pageUrl) {
			$pageUrl = (stripos($pageUrl, 'http://') !== FALSE ? '' : 'http://' . $this->getDomain()) . $pageUrl;
			$pageUrls[] = $pageUrl;
			
			preg_match_all($this->getPagesPattern(), $this->readUrl($pageUrl), $matches);
			foreach ($matches[$this->getPagesPatternUrlPosition()] as $categoriesPageUrl) {
				$categoriesPageUrl = (stripos($categoriesPageUrl, 'http://') !== FALSE ? '' : 'http://' . $this->getDomain()) . $categoriesPageUrl;
				$pageUrls[] = $categoriesPageUrl;
			}
		}
		
		return $pageUrls;
	}
	
	protected function getCategoriesPattern() {
		return $this->categoriesPattern;
	}
	
	protected function setCategoriesPattern($value) {
		$this->categoriesPattern = $value;
	}
	
	protected function getCategoriesPatternUrlPosition() {
		return $this->categoriesPatternUrlPosition;
	}
	
	protected function setCategoriesPatternUrlPosition($value) {
		$this->categoriesPatternUrlPosition = $value;
	}
}