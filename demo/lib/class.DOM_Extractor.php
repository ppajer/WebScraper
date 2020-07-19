<?php

class DOM_Extractor {

	private $html;
	private $DOM;
	private $rules;

	public function __construct($rules = null, $html = null) {
		$this->DOM = new IvoPetkov\HTML5DOMDocument();
		if ($rules) {
			$this->setRules($rules);
		}
		if ($html) {
			$this->load($html);
		}
	}

	public function setRules($rules) {
		if (is_string($rules)) {
			if (strpos($rules, '{') === false) {
				$rules = file_get_contents($rules);
			}
			$rules = json_decode($rules, true);
		}
		$this->rules = $this->verifyRules($rules);
		return $this;
	}

	public function load($html) {
		$this->DOM->loadHTML($html);
		return $this;
	}

	public function parse($rules = null, $context = false) {
		$result = array();
		if (is_null($rules)) {
			$rules = $this->rules;
		}
		foreach ($this->verifyRules($rules) as $key => $rule) {

			// Don't loop over instructions as data keys
			if (strpos($key, '@') !== false) {
				continue;
			}

			// Attribute lookup
			if (strpos($rule['@selector'], '@') !== false) {
				$rule['@selector'] = explode('@', $rule['@selector']);
				$selector = $rule['@selector'][0];
				$attribute = $rule['@selector'][1];
			} else {
				$selector = $rule['@selector'];
				$attribute = false;
			}

			$nodes = $this->getNodes($selector, $context);
			$result[$key] = $this->processNodes($nodes, $rule, $attribute);
			
		}
		return $result;
	}

	private function getNodes($selector, $context = false) {
		if (!$context) {
			$context = $this->DOM;
		}
		return $context->querySelectorAll($selector);
	}

	private function processNodes($nodes, $rule, $attr = false) {
		$result = array();

		foreach ($nodes as $node) {
			if (isset($rule['@each'])) {
				$result[] = $this->parse($rule['@each'], $node);
			} else {
				$result[] = $attr ? $node->getAttributeNode($attr)->nodeValue : $node->innerHTML;
			}
		}

		// Return string if single value
		return ((count($result) != 0) AND (count($result) > 1)) ? $result : $result[0];
	}

	private function verifyRules($rules) {
		if (!is_array($rules) OR empty($rules)) {
			throw new Exception("DOM_Extractor::rules must be a non-empty Array", 1);
		}
		return $rules;
	}
}

?>