# WebScraper
A straightforward web scraper written in PHP, with support for parallel processing and HTML5.

## Installation

To start using this package, add it to your `composer.json` file and call `composer install`, then include the generated `autoload.php` in your project. Alternatively, download and include the package along with its dependencies directly into your project.

### Dependencies

- [PHP DOM Extractor](https://github.com/ppajer/PHP-DOM-Extractor)
- [PHP Request](https://github.com/ppajer/PHP-Request)

## Usage

The scraper takes 2 inputs: an array of Request Options that define the resources to gather, and an array of Extracton Rules to specify what data we're looking for in those resources. For more information on [Request Options](https://github.com/ppajer/PHP-Request#multiple-requests---parallelrequest) or [Extraction Rules](https://github.com/ppajer/PHP-DOM-Extractor#defining-extraction-rules), read the respective docs.

```(php)
require 'autoload.php';

$rules = 'path/to/rules.json';
$options = [
	'foo' => ['URL' => 'https://...']
];

$scraper = new WebScraper($rules);
$result = $scraper->start($options);
```