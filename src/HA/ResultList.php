<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 06.10.2017
 * Time: 18:32
 */

namespace fb_http\HA;



class ResultList extends \fb_http\ResultList
{

    /**
     * returns current data
     *
     * @return \fb_http\HA\Entities\Device
     */
    public function current()
    {
        return $this->data[$this->position];
    }
}
