<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 03.10.2017
 * Time: 15:45
 */

namespace fb_http\HA;

use fb_http\FBHttp;
use fb_http\ResultList;
use fb_http\HA\Entities\Device;

class HomeAutomation extends FBHttp
{
    /**
     * HTTPInterface constructor.
     *
     * @param string $password
     * @param string $boxIp either box ip or box domain (eg `fritz.box`)
     * @param string $username
     */
    public function __construct($password, $boxIp='fritz.box', $username='')
    {
        $this->boxIp = $boxIp;
        $this->getSid($password, $username);
    }

    /**
     * gets a list of available AINs
     *
     * @param boolean $returnArray if false cs-list is returned else an array
     * @return string list of known AINs
     */
    public function getSwitchList($returnArray=false)
    {
        $response = $this->do_homeauto('getswitchlist');
        if ($returnArray) {
            $response = array_map('trim', explode(',', $response));
        }
        return $response;
    }

    /**
     * activates switch for a given ain
     *
     * @param string $ain the id of the actuator
     * @return bool
     */
    public function setSwitchOn($ain)
    {
        return $this->do_homeauto('setswitchon', $ain);
    }

    /**
     * activates switch for a given ain
     *
     * @param string $ain the id of the actuator
     * @return bool
     */
    public function setSwitchOff($ain)
    {
        return $this->do_homeauto('setswitchoff', $ain);
    }

    /**
     * toggles the switch for a given ain
     *
     * @param string $ain the id of the actuator
     * @return bool
     */
    public function setSwitchToggle($ain)
    {
        return $this->do_homeauto('setswitchtoggle', $ain);
    }

    /**
     * returns current switch state
     *
     * @param $ain
     * @return bool|string 0 or 1 or `inval` if unknown
     */
    public function getSwitchState($ain)
    {
        return $this->do_homeauto('getswitchstate', $ain);
    }

    /**
     * returns whether a switch is connected or not
     * state change on disconnect will show up with some delay
     *
     * @param $ain
     * @return bool
     */
    public function getSwitchPresent($ain)
    {
        return $this->do_homeauto('getswitchpresent', $ain);
    }

    /**
     * returns current power usage in mW
     *
     * @param $ain
     * @return int|string current power usage or `inval` if unkonwn
     */
    public function getSwitchPower($ain)
    {
        return $this->do_homeauto('getswitchpower', $ain);
    }

    /**
     * returns total power usage in Wh since last reset of switch
     *
     * @param $ain
     * @return int|string current power usage or `inval` if unkonwn
     */
    public function getSwitchEnergy($ain)
    {
        return $this->do_homeauto('getswitchenergy', $ain);
    }

    /**
     * returns the name for a switch
     *
     * @param $ain
     * @return string
     */
    public function getSwitchName($ain)
    {
        return $this->do_homeauto('getswitchname', $ain);
    }

    /**
     * returns basic function features for all devices
     *
     * @return ResultList
     */
    public function getDeviceListInfos()
    {
        $response = $this->do_homeauto('getdevicelistinfos');
        $rl = $this->createResultList($response);

        return $rl;
    }

    /**
     * returns the temperature for a switch
     *
     * @param $ain
     * @return string
     */
    public function getTemperature($ain)
    {
        return $this->do_homeauto('gettemperature', $ain);
    }

    /**
     * returns the setpoint temperature for a switch
     *
     * @param $ain
     * @return string
     */
    public function getHKRTSetpoint($ain)
    {
        return $this->do_homeauto('gethkrtsoll', $ain);
    }

    /**
     * returns the comfort temperature for a switch
     *
     * @param $ain
     * @return string
     */
    public function getHKRTComfort($ain)
    {
        return $this->do_homeauto('gethkrkomfort', $ain);
    }

    /**
     * returns the economy temperature for a switch
     *
     * @param $ain
     * @return string
     */
    public function getHKRTEconomy($ain)
    {
        return $this->do_homeauto('gethkrabsenk', $ain);
    }

    /**
     * sets the setpoint temperature for a switch
     *
     * @param $ain
     * @param int|float $degrees temperature in degree celsius
     * @return string
     */
    public function setHKRTSetpoint($ain, $degrees=18)
    {
        $c = round($degrees * 2);
        if ($c < 16) {
            $c = 16;
        }
        if ($c > 56) {
            $c = 56;
        }
        return $this->do_homeauto('sethkrtsoll', $ain, $c);
    }


    /**
     * do http call to box
     *
     * @param string $switchcmd see https://avm.de/fileadmin/user_upload/Global/Service/Schnittstellen/AHA-HTTP-Interface.pdf
     * @param null $ain the id of the actuator
     * @param null $param extra params
     * @return string the box response
     */
    protected function do_homeauto($switchcmd, $ain=null, $param=null)
    {
        $params['sid'] = $this->sid;
        $params['switchcmd'] = $switchcmd;
        if ($ain !== null) {
            $params['ain'] = $ain;
        }
        if ($param !== null) {
            $params['param'] = $param;
        }
        $url = sprintf('http://%s/webservices/homeautoswitch.lua?%s', $this->boxIp, http_build_query($params));

        $rHandle = fopen($url, 'r');
        $res = stream_get_contents($rHandle);

        return $res;
    }

    /**
     * @param $xml
     * @return ResultList
     */
    private function createResultList($xml)
    {
        $xml = simplexml_load_string($xml);

        $rl = new \fb_http\HA\ResultList();
        foreach ($xml as $d) {
            $device = Device::fromXML($d);
            $rl->add($device);
        }

        return $rl;
    }
}
