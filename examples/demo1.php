<?php

    require '../vendor/autoload.php';

    $xmlFilePath = 'data/test.xml';

    $xmlStream = fopen($xmlFilePath, 'r');

// open the stream to read all nodes recursively (defaults to two levels)
//    $reader = \Coco\xmlReader\XmlReader::openStream($xmlStream, 2);

// or set the reader to find all repeating <item /> tags
    $reader = \Coco\xmlReader\XmlReader::openUniqueNodeStream($xmlStream, 'item');

    $iterator = $reader->process();

    foreach ($iterator as $k => $v)
    {
        print_r($v);
    }