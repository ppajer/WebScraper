<?php

class Request {
	
	const UA_MOZILLA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0';
	const UA_CHROME = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36';
	const UA_SAFARI = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36';
	const UA_EXPLORER = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)';
	const UA_EDGE = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36 Edg/81.0.416.62';
	const UA_OPERA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36 OPR/68.0.3618.56';

	const CONTENT_TYPE_XML = 'application/xml';
	const CONTENT_TYPE_XHTML = 'application/xhtml+xml';
	const CONTENT_TYPE_TXT = 'text/plain';
	const CONTENT_TYPE_TTF = 'font/ttf';
	const CONTENT_TYPE_MJS = 'text/javascript';
	const CONTENT_TYPE_JSONLD = 'application/ld+json';
	const CONTENT_TYPE_JSON = 'application/json';
	const CONTENT_TYPE_JS = 'text/javascript';
	const CONTENT_TYPE_CSV = 'text/csv';
	const CONTENT_TYPE_CSS = 'text/css';

	public $UA;
	public $URL;
	public $method;
	public $content;
	public $headers;
	public $contentType;
	public $follow;

	private $ch;
	private $response;

	public function __construct($url) {
		$this->URL = $url;
		$this->defaults();
		$this->ch = curl_init();
		$this->setupCurl($this->ch);
	}

	protected function defaults() {
		$this->UA = self::UA_SAFARI;
		$this->method = 'GET';
		$this->content = null;
		$this->headers = null;
		$this->contentType = self::CONTENT_TYPE_TXT;
		$this->follow = true;
		$this->return = 1;
	}

	protected function _set($key, $val) {
		$this->$key = $val;
		$this->setupCurl($this->ch);
		return $this;
	}

	public function URL($url) {
		return $this->_set('URL', $url);
	}

	public function userAgent($UA) {
		return $this->_set('UA', $UA);
	}

	public function method($method) {
		return $this->_set('method', $method);
	}

	public function content($content) {
		return $this->_set('content', $content);
	}

	public function headers($headers) {
		return $this->_set('headers', $headers);
	}

	public function contentType($contentType) {
		return $this->_set('contentType', $contentType);
	}

	public function follow($follow) {
		return $this->_set('follow', $follow);
	}

	public function send() {
		$this->response = curl_exec($this->ch);
	}

	public function response() {
		return $this->response;
	}

	protected function init() {
		return curl_init();
	}

	protected function setupCurl($ch) {
		curl_setopt($ch, CURLOPT_USERAGENT, $this->UA);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->return);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->follow);
		if (!is_null($this->content)) {
			if ($this->method === 'GET') {
				$this->URL = $this->URL.'?'.http_build_query($this->content);
			} else {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->content);
			}
		}
		curl_setopt($ch, CURLOPT_URL, $this->URL);
		if (!is_null($this->headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		}
	}


}

?>