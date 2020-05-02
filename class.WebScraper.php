<?php


class WebScraper {

	private $DOM;
	private $rules;

	public function __construct($rules) {
		$this->rules = $rules;
		$this->DOM = new DOM_Extractor;
		$this->DOM->setRules($rules);
	}

	public function start($opts) {
		$batch = $this->getData($opts);
		return $this->processData($batch);
	}

	private function getData($opts) {
		$async = new ParallelRequest($opts);
		return $async->awaitAll()->response();
	}

	private function processData($batch) {
		$res = [];
		foreach ($batch as $key => $html) {
			$res[$key] = $this->DOM->load($html)->parse();
		}
		return $res;
	}
}