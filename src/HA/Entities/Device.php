<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 03.10.2017
 * Time: 17:24
 */


namespace fb_http\HA\Entities;

/**
 * Device Entity
 * @package fb_http\HA\Entities
 *
 * @property string $ain
 * @property integer $id
 * @property string $functionBitMask
 * @property array $funcs
 * @property string $deviceType
 * @property string $FWVersion
 * @property string $manufacturer
 * @property string $productName
 * @property string $name
 * @property boolean $present
 * @property string $alarm
 * @property Hkr $hkr
 * @property Powermeter $powermeter
 * @property Temperature $temperature
 * @property Smartswitch $switch
 */
class Device implements \JsonSerializable
{
    /**
     * bitmask operators
     */
    const ALARM             = 1 <<  4;
    const THERMOSTAT        = 1 <<  6;
    const POWERMETER        = 1 <<  7;
    const TEMPERATURESENSOR = 1 <<  8;
    const SWITCH            = 1 <<  9;
    const REPEATER          = 1 << 10;

    /**
     * @var string
     */
    public $ain;
    /**
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $functionBitMask;
    /**
     * @var array
     */
    public $funcs;
    /**
     * @var string
     */
    public $deviceType;
    /**
     * @var string
     */
    public $FWVersion;
    /**
     * @var string
     */
    public $manufacturer;
    /**
     * @var string
     */
    public $productName;
    /**
     * @var string
     */
    public $name;
    /**
     * @var boolean
     */
    public $present;
    /**
     * @var string
     */
    public $alarm;
    /**
     * @var Hkr|null
     */
    public $hkr;
    /**
     * @var Powermeter|null
     */
    public $powermeter;
    /**
     * @var Temperature|null
     */
    public $temperature;
    /**
     * @var Smartswitch|null
     */
    public $switch;

    /**
     * Device constructor.
     * @param string $ain
     * @param string $id
     * @param string $functionBitMask
     * @param array $funcs
     * @param string $deviceType
     * @param string $FWVersion
     * @param string $manufacturer
     * @param string $productName
     * @param string $name
     * @param bool $present
     * @param string $alarm
     * @param Hkr|null $hkr
     * @param Powermeter|null $powermeter
     * @param Temperature|null $temperature
     * @param Smartswitch|null $switch
     */
    public function __construct($ain, $id, $functionBitMask, array $funcs, $deviceType, $FWVersion, $manufacturer, $productName, $name, $present, $alarm, $hkr, $powermeter, $temperature, $switch)
    {
        $this->ain = $ain;
        $this->id = $id;
        $this->functionBitMask = $functionBitMask;
        $this->funcs = $funcs;
        $this->deviceType = $deviceType;
        $this->FWVersion = $FWVersion;
        $this->manufacturer = $manufacturer;
        $this->productName = $productName;
        $this->name = $name;
        $this->present = $present;
        $this->alarm = $alarm;
        $this->hkr = $hkr;
        $this->powermeter = $powermeter;
        $this->temperature = $temperature;
        $this->switch = $switch;
    }


    public static function fromXML(\SimpleXMLElement $xml)
    {
        // get attributes 1st
        foreach ($xml->attributes() as $k => $v) {
            if ($k == 'identifier') {
                $buf['ain'] = (string)$v;
            } else if ($k == 'functionbitmask') {
                $buf[$k] = (string)$v;
                $buf += self::getFuncs($v);
            } else {
                $buf[$k] = (string)$v;
            }
        }
        // then the tags
        $buf['name'] = (string)$xml->name;
        $buf['present'] = $xml->present == 1 ? true : false;

        $buf['alarm'] = $buf['funcs']['alarm'] ? (string)$xml->alarm->state ?: null : null;
        // powermeter
        $buf['powermeter'] = $buf['funcs']['powermeter'] ? Powermeter::fromXML($xml->powermeter) : null;
        // temp sensor
        $buf['temperature'] = $buf['funcs']['temperaturesensor'] ? Temperature::fromXML($xml->temperature) : null;
        // switch
        $buf['switch'] = $buf['funcs']['switch'] ? Smartswitch::fromXML($xml->switch) : null;
        // hkr
        $buf['hkr'] = $buf['funcs']['thermostat'] ? Hkr::fromXML($xml->hkr) : null;

        return new Device(
            $buf['ain'],
            $buf['id'],
            $buf['functionbitmask'],
            $buf['funcs'],
            $buf['deviceType'],
            $buf['fwversion'],
            $buf['manufacturer'],
            $buf['productname'],
            $buf['name'],
            $buf['present'],
            $buf['alarm'],
            $buf['hkr'],
            $buf['powermeter'],
            $buf['temperature'],
            $buf['switch']
        );
    }


    /**
     * read functionbitmask
     *
     * @todo: turn this into an object
     * @param $bitmask
     * @return array
     */
    private static function getFuncs($bitmask)
    {
        $buf = [];
        $buf['funcs']['alarm'] = (boolean)($bitmask & self::ALARM);
        $buf['funcs']['thermostat'] = (boolean)($bitmask & self::THERMOSTAT);
        $buf['funcs']['powermeter'] = (boolean)($bitmask & self::POWERMETER);
        $buf['funcs']['temperaturesensor'] = (boolean)($bitmask & self::TEMPERATURESENSOR);
        $buf['funcs']['switch'] = (boolean)($bitmask & self::SWITCH);
        $buf['funcs']['repeater'] = (boolean)($bitmask & self::REPEATER);
        if ($buf['funcs']['thermostat']) {
            $buf['deviceType'] = 'HKR';
        } else if ($buf['funcs']['switch']) {
            $buf['deviceType'] = 'switch';
        } else if ($buf['funcs']['repeater']) {
            $buf['deviceType'] = 'repeater';
        } else {
            $buf['deviceType'] = '';
        }

        return $buf;
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
