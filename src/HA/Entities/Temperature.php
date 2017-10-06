<?php
/**
 * Created by PhpStorm.
 * User: apsczolla
 * Date: 06.10.2017
 * Time: 13:53
 */

namespace fb_http\HA\Entities;


class Temperature implements \JsonSerializable
{

    /**
     * @var float
     */
    public $celsius;
    /**
     * @var float
     */
    public $offset;


    /**
     * Temperature constructor
     *
     * @param int $power
     * @param int $energy
     */
    public function __construct($celsius, $offset)
    {
        $this->celsius = $celsius;
        $this->offset = $offset;
    }

    /**
     * read data from xml
     *
     * @param $xml
     * @return Temperature
     */
    public static function fromXML($xml)
    {

        $buf['celsius'] = (float)($xml->celsius / 10);
        $buf['offset'] = (float)($xml->offset);

        return new Temperature(
            $buf['celsius'],
            $buf['offset']
        );
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
