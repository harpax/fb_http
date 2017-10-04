<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 03.10.2017
 * Time: 17:24
 */


namespace fb_http\HA\Entities;


class Device implements \JsonSerializable
{
    /**
     * @var string
     */
    private $ain;
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $functionBitMask;
    /**
     * @var string
     */
    private $FWVersion;
    /**
     * @var string
     */
    private $manufacturer;
    /**
     * @var string
     */
    private $productName;
    /**
     * @var string
     */
    private $name;
    /**
     * @var boolean
     */
    private $present;
    /**
     * @var boolean
     */
    private $switchState;
    /**
     * @var string
     */
    private $switchMode;
    /**
     * @var boolean
     */
    private $switchLock;
    /**
     * @var boolean
     */
    private $switchDeviceLock;
    /**
     * @var integer
     */
    private $power;
    /**
     * @var integer
     */
    private $energy;
    /**
     * @var integer
     */
    private $temperature;
    /**
     * @var integer
     */
    private $temperatureOffset;

    /**
     * Device constructor
     *
     * @param string $ain
     * @param string $id
     * @param string $functionBitMask
     * @param string $FWVersion
     * @param string $manufacturer
     * @param string $productName
     * @param string $name
     * @param bool $present
     * @param bool $switchState
     * @param string $switchMode
     * @param bool $switchLock
     * @param bool $switchDeviceLock
     * @param int $power
     * @param int $energy
     * @param int $temperature
     * @param int $temperatureOffset
     */
    public function __construct($ain, $id, $functionBitMask, $FWVersion, $manufacturer, $productName, $name, $present, $switchState, $switchMode, $switchLock, $switchDeviceLock, $power, $energy, $temperature, $temperatureOffset)
    {
        $this->ain = $ain;
        $this->id = $id;
        $this->functionBitMask = $functionBitMask;
        $this->FWVersion = $FWVersion;
        $this->manufacturer = $manufacturer;
        $this->productName = $productName;
        $this->name = $name;
        $this->present = $present;
        $this->switchState = $switchState;
        $this->switchMode = $switchMode;
        $this->switchLock = $switchLock;
        $this->switchDeviceLock = $switchDeviceLock;
        $this->power = $power;
        $this->energy = $energy;
        $this->temperature = $temperature;
        $this->temperatureOffset = $temperatureOffset;
    }

    public static function fromXML($xml)
    {
        // get attributes 1st
        foreach ($xml->attributes() as $k => $v) {
            if ($k == 'identifier') {
                $buf['ain'] = (string)$v;
            } else {
                $buf[$k] = (string)$v;
            }
        }
        // then the tags
        $buf['name'] = (string)$xml->name;
        $buf['present'] = $xml->present == 1 ? true : false;
        $buf['switchState'] = $xml->switch->state == 1 ? true : false;
        $buf['switchMode'] = (string)$xml->switch->mode;
        $buf['switchLock'] = $xml->switch->lock == 1 ? true : false;
        $buf['switchDeviceLock'] = $xml->switch->devicelock == 1 ? true : false;
        $buf['power'] = (int)$xml->powermeter->power;
        $buf['energy'] = (int)$xml->powermeter->energy;
        $buf['temperature'] = (float)($xml->temperature->celsius / 10);
        $buf['temperatureOffset'] = (float)($xml->temperature->offset / 10);

        return new Device(
            $buf['ain'],
            $buf['id'],
            $buf['functionbitmask'],
            $buf['fwversion'],
            $buf['manufacturer'],
            $buf['productname'],
            $buf['name'],
            $buf['present'],
            $buf['switchState'],
            $buf['switchMode'],
            $buf['switchLock'],
            $buf['switchDeviceLock'],
            $buf['power'],
            $buf['energy'],
            $buf['temperature'],
            $buf['temperatureOffset']
        );
    }

    /**
     * @return string
     */
    public function getAin()
    {
        return $this->ain;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFunctionBitMask()
    {
        return $this->functionBitMask;
    }

    /**
     * @return string
     */
    public function getFWVersion()
    {
        return $this->FWVersion;
    }

    /**
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isPresent()
    {
        return $this->present;
    }

    /**
     * @return bool
     */
    public function isSwitchState()
    {
        return $this->switchState;
    }

    /**
     * @return string
     */
    public function getSwitchMode()
    {
        return $this->switchMode;
    }

    /**
     * @return bool
     */
    public function isSwitchLock()
    {
        return $this->switchLock;
    }

    /**
     * @return bool
     */
    public function isSwitchDeviceLock()
    {
        return $this->switchDeviceLock;
    }

    /**
     * @return int
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * @return int
     */
    public function getEnergy()
    {
        return $this->energy;
    }

    /**
     * @return int
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * @return int
     */
    public function getTemperatureOffset()
    {
        return $this->temperatureOffset;
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
