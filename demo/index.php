<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Budapest');
require '../lib/HTML5DOMDocument/Internal/QuerySelectors.php';
require '../lib/HTML5DOMDocument.php';
require '../lib/HTML5DOMElement.php';
require '../lib/HTML5DOMNodeList.php';
require '../lib/HTML5DOMTokenList.php';
require '../lib/class.DOM_Extractor.php';
require '../lib/class.Request.php';
require '../lib/class.ParallelRequest.php';
require '../class.WebScraper.php';

$urls = [['URL' => 'https://google.com/search?q=pub+crawl+budapest']];
$rules = [
	'results' => [
		'@selector' => ".g",
		'@each' => [
			'title'=> ['@selector' => "h3"],
			'description' => ["@selector" => ".st"],
			'link'=> [
				'@selector' => "a@href"
				]
			]
		]
	];

$scraper = new WebScraper($rules);
var_dump($scraper->start($urls));

?>