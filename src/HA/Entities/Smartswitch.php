<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 06.10.2017
 * Time: 12:44
 */

namespace fb_http\HA\Entities;

/**
 * Class Smartswitch
 * @package fb_http\HA\Entities
 *
 * @property bool $state
 * @property string $mode
 * @property bool $lock
 * @property bool $deviceLock
 */
class Smartswitch implements \JsonSerializable
{

    /**
     * @var boolean
     */
    public $state;
    /**
     * @var string
     */
    public $mode;
    /**
     * @var boolean
     */
    public $lock;
    /**
     * @var boolean
     */
    public $deviceLock;


    /**
     * Smartswitch constructor
     *
     * @param int $power
     * @param int $energy
     */
    public function __construct($state, $mode, $lock, $deviceLock)
    {
        $this->state = $state;
        $this->mode = $mode;
        $this->lock = $lock;
        $this->deviceLock = $deviceLock;
    }

    /**
     * read data from xml
     *
     * @param $xml
     * @return Smartswitch
     */
    public static function fromXML($xml)
    {
        $buf['state'] = $xml->state == 1 ? true : false;
        $buf['mode'] = (string)$xml->mode;
        $buf['lock'] = $xml->lock == 1 ? true : false;
        $buf['deviceLock'] = $xml->devicelock == 1 ? true : false;

        return new Smartswitch(
            $buf['state'],
            $buf['mode'],
            $buf['lock'],
            $buf['deviceLock']
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
