# Large XML Reader

#####    

## Installation

You can install the package via composer:

```bash
composer require coco-project/xml-reader
```

Read and process HUGE Xml files from any source.

## Usage

Import the Reader class and use one of the two static constructors.

```php
<?php

    $xmlFilePath = 'data/test.xml';

    $xmlStream = fopen($xmlFilePath, 'r');

// open the stream to read all nodes recursively (defaults to two levels)
//    $reader = \Coco\xmlReader\XmlReader::openStream($xmlStream, 2);

// or set the reader to find all repeating <item /> tags
    $reader = \Coco\xmlReader\XmlReader::openUniqueNodeStream($xmlStream, 'item');

```

In general, the unique node stream performs better than the normal one.

**IMPORTANT LIMITATION**: Unique node reader does not support nested nodes with the same tag.

Once you get the reader instance, use the process method to retrieve a generator for the nodes.

You can use this generator as an iterator.

```php
<?php

$iterator = $reader->process();

foreach ($iterator as $nodeData) {
        //assoc array
        print_r($v);
}
```

The process method accepts a limit param to read a maximum of `$limit` nodes.

## Testing

``` bash
composer test
```

## License

---

MIT
