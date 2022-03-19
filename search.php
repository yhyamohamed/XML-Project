<?php

class Operations
{

    public function __construct()
    {
        $this->xmldoc = new DOMDocument();
        $this->xmldoc->load('smple.xml', LIBXML_NOBLANKS);
    }
    public function search($name)
    {
        $xml = simplexml_load_file('smple.xml');
        $name = $_POST['serach-key'];
        $parent = $this->xmldoc->getElementsByTagName('person')[0];
        foreach ($parent->childNodes as $node) {
            $tagsFound = $xml->xpath("//person/$node->nodeName/name[text()='$name']");

            if ($tagsFound) {
                return $node->nodeName;
            }
        }
    }
   
}
