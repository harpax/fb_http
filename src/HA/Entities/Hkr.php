<?php
/**
 * Created by PhpStorm.
 * User: apsczolla
 * Date: 06.10.2017
 * Time: 14:03
 */

namespace fb_http\HA\Entities;


class Hkr implements \JsonSerializable
{

    /**
     * HKR ERR MSGs
     */
    const HKR_ERR = [
        0 => 'kein Fehler',
        1 => 'Keine Adaptierung möglich. Gerät korrekt am Heizkörper montiert?',
        2 => 'Ventilhub zu kurz oder Batterieleistung zu schwach. Ventilstößel per Hand
         mehrmals öffnen und schließen oder neue Batterien einsetzen.',
        3 => 'Keine Ventilbewegung möglich. Ventilstößel frei?',
        4 => 'Die Installation wird gerade vorbereitet.',
        5 => 'Der Heizkörperregler ist im Installationsmodus und kann auf das Heizungsventil
         montiert werden.',
        6 => 'Der Heizkörperregler passt sich nun an den Hub des Heizungsventils an.'
    ];

    /**
     * @var float
     */
    public $tIst;
    /**
     * @var float
     */
    public $tSoll;
    /**
     * @var float
     */
    public $tKomfort;
    /**
     * @var float
     */
    public $tAbsenk;
    /**
     * @var boolean
     */
    public $batteryLow;
    /**
     * @var boolean
     */
    public $lock;
    /**
     * @var boolean
     */
    public $deviceLock;
    /**
     * @var integer
     */
    public $errorCode;
    /**
     * @var string
     */
    public $errorMsg;
    /**
     * @var integer
     */
    public $nextChange_time;
    /**
     * @var integer
     */
    public $nextChange_temperature;

    /**
     * Hkr constructor.
     * @param float $tIst
     * @param float $tSoll
     * @param float $tKomfort
     * @param float $tAbsenk
     * @param bool $batteryLow
     * @param bool $lock
     * @param bool $deviceLock
     * @param int $errorCode
     * @param string $errorMsg
     * @param int $nextChange_time
     * @param int $nextChange_temperature
     */
    public function __construct($tIst, $tSoll, $tKomfort, $tAbsenk, $batteryLow, $lock, $deviceLock, $errorCode, $errorMsg, $nextChange_time, $nextChange_temperature)
    {
        $this->tIst = $tIst;
        $this->tSoll = $tSoll;
        $this->tKomfort = $tKomfort;
        $this->tAbsenk = $tAbsenk;
        $this->batteryLow = $batteryLow;
        $this->lock = $lock;
        $this->deviceLock = $deviceLock;
        $this->errorCode = $errorCode;
        $this->errorMsg = $errorMsg;
        $this->nextChange_time = $nextChange_time;
        $this->nextChange_temperature = $nextChange_temperature;
    }


    /**
     * read data from xml
     *
     * @param $xml
     * @return Hkr
     */
    public static function fromXML($xml)
    {

        $buf['tIst'] = self::calcTemp($xml->tist);
        $buf['tSoll'] = self::calcTemp($xml->tsoll);
        $buf['tKomfort'] = self::calcTemp($xml->komfort);
        $buf['tAbsenk'] = self::calcTemp($xml->absenk);
        $buf['batteryLow'] = $xml->batterylow == 1 ? true : false;
        $buf['lock'] = $xml->lock == 1 ? true : false;
        $buf['deviceLock'] = $xml->devicelock == 1 ? true : false;
        $buf['errorCode'] = (int)$xml->errorcode;
        $buf['errorMsg'] = self::HKR_ERR[$buf['errorCode']];
        $buf['nextChange_time'] = (int)($xml->nextchange->endperiod - time());
        $buf['nextChange_temperature'] = self::calcTemp($xml->nextchange->tchange);

        return new Hkr(
            $buf['tIst'],
            $buf['tSoll'],
            $buf['tKomfort'],
            $buf['tAbsenk'],
            $buf['batteryLow'],
            $buf['lock'],
            $buf['deviceLock'],
            $buf['errorCode'],
            $buf['errorMsg'],
            $buf['nextChange_time'],
            $buf['nextChange_temperature']
        );
    }


    /**
     * get the HumanReadale TEMP from 0x10 - 0x38
     *
     * @param $in
     * @return float|int
     */
    private function calcTemp($in)
    {
        return round($in / 2, 1);
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