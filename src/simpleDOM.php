<?php
namespace Absolu\SimpleDOM;

use DOMDocument;
use DOMXPath;
use DOMElement;

class SimpleDOM {
    private DOMDocument $doc;

    public function __construct(string $html) {
        libxml_use_internal_errors(true);
        $this->doc = new DOMDocument();
        $this->doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    }

    public function querySelector(string $selector): ?DOMElement {
        $xpath = new DOMXPath($this->doc);
        $query = $this->cssToXpath($selector);
        $nodes = $xpath->query($query);
        return $nodes->length > 0 ? $nodes->item(0) : null;
    }

    private function cssToXpath(string $selector): string {
        if (str_starts_with($selector, '#')) {
            $id = substr($selector, 1);
            return "//*[@id='$id']";
        }
        if (str_starts_with($selector, '.')) {
            $class = substr($selector, 1);
            return "//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]";
        }
        return "//" . $selector;
    }

    public function getHTML(): string {
        return $this->doc->saveHTML();
    }
}