<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 06.10.2017
 * Time: 12:33
 */

namespace fb_http\HA\Entities;

/**
 * Class Powermeter
 * @package fb_http\HA\Entities
 *
 * @property int $power
 * @property int $energy
 */
class Powermeter implements \JsonSerializable
{

    /**
     * @var integer
     */
    public $power;
    /**
     * @var integer
     */
    public $energy;

    /**
     * Powermeter constructor
     *
     * @param int $power
     * @param int $energy
     */
    public function __construct($power, $energy)
    {
        $this->power = $power;
        $this->energy = $energy;
    }

    /**
     * read data from xml 
     * 
     * @param $xml
     * @return Powermeter
     */
    public static function fromXML($xml)
    {

        $buf['power'] = (int)$xml->power;
        $buf['energy'] = (int)$xml->energy;

        return new Powermeter(
            $buf['power'],
            $buf['energy']
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
