<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 03.10.2017
 * Time: 19:30
 */

namespace fb_http;

use fb_http\Exceptions\BoxNotFoundException;
use fb_http\Exceptions\InvalidCredentialsException;

abstract class FBHttp
{
    /**
     * @var string
     */
    protected $boxIp;
    /**
     * @var string
     */
    protected $sid;

    /**
     * gets the sid from a box
     *
     * @param string $password
     * @param string $username
     * @throws BoxNotFoundException
     * @throws InvalidCredentialsException
     */
    protected function getSid($password, $username = "")
    {
        try {
            $xml = simplexml_load_string(file_get_contents(sprintf('http://%s/login_sid.lua', $this->boxIp)));
            $challenge = $xml->Challenge;
        } catch (\Exception $e) {
            throw new BoxNotFoundException();
        }
        $challenge_str = sprintf("%s-%s", $challenge, $password);
        $md5_str = md5(iconv("UTF-8", "UTF-16LE", $challenge_str));
        $xml = simplexml_load_string(file_get_contents(sprintf('http://%s/login_sid.lua?user=%s&response=%s', $this->boxIp, $username, $challenge . '-' . $md5_str)));
        $sid = (string)$xml->SID;
        if ($sid == '0000000000000000') {
            throw new InvalidCredentialsException();
        }
        $this->sid = $sid;
    }
}
