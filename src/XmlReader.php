<?php

    declare (strict_types = 1);

    namespace Coco\xmlReader;

    use Exception;
    use Generator;
    use Prewk\XmlStringStreamer;

class XmlReader
{
    /**
     * XmlReader Instance
     *
     * @var XmlStringStreamer
     */
    protected XmlStringStreamer $xml;

    /**
     * Constructor
     *
     * @param XmlStringStreamer $xml
     */
    protected function __construct(XmlStringStreamer $xml)
    {
        $this->xml = $xml;
    }

    /**
     * Returns an instance of the reader from the passed stream.
     * You may setup the depth of the nodes you want to iterate by
     * using the second parameter (defaults to 2).
     *
     * @param resource $stream
     * @param int      $depth
     * @param array    $options
     *
     * @throws Exception
     */
    public static function openStream($stream, int $depth = 2, array $options = []): static
    {
        if (!is_resource($stream)) {
            throw new Exception('Invalid resource passed to openStream');
        }

        $parserOptions = array_merge(static::getOptions($options), [
            'captureDepth' => $depth,
        ]);

        $xml = XmlStringStreamer::createStringWalkerParser($stream, $parserOptions);

        return new static($xml);
    }

    /**
     * Returns an instance of the reader from the passed stream that
     * iterates through the nodes that have the passed tag.
     *
     * @param resource $stream
     * @param string   $tag
     * @param array    $options
     *
     * @throws Exception
     */
    public static function openUniqueNodeStream($stream, string $tag, array $options = []): static
    {
        if (!is_resource($stream)) {
            throw new Exception('Invalid resource passed to openStream');
        }

        $parserOptions = array_merge(static::getOptions($options), [
            'uniqueNode' => $tag,
        ]);

        $xml = XmlStringStreamer::createUniqueNodeParser($stream, $parserOptions);

        return new static($xml);
    }

    /**
     * Iterate through the xml document and return an array for each node.
     * If you set a limit, the reader will stop after that number of nodes read.
     *
     * Warning: Recursive tags are not supported!
     *
     * @param ?int $limit
     *
     * @return Generator
     */
    public function process(int $limit = null): Generator
    {
        $read = 0;
        while ($node = $this->xml->getNode()) {
            $xmlElement = simplexml_load_string($node, null, LIBXML_NOCDATA);
            yield json_decode(json_encode((array)$xmlElement), true);
            $read++;

            if ($limit && $read >= $limit) {
                break;
            }
        }

        return yield from [];
    }

    protected static function getOptions($options = []): array
    {
        $defaultOptions = [
            'expectGT' => true,
        ];

        return array_merge($defaultOptions, $options);
    }
}
