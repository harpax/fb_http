<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 03.10.2017
 * Time: 17:38
 */

namespace fb_http;

class ResultList implements \Iterator
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var int
     */
    private $position;

    public function __construct()
    {
        $this->position = 0;
        $this->data = [];
    }

    /**
     * adds another entity to the result set
     *
     * @param $entity
     */
    public function add($entity)
    {
        $this->data[] = $entity;
    }

    /**
     * removes an entity from result set
     *
     * @param $id
     */
    public function remove($id)
    {
        if (isset($this->data[$id])) {
            unset($this->data[$id]);
            if ($this->position >= $id) {
                $this->position--;
            }
            $this->data = array_values($this->data);
        }
    }

    /**
     * returns to start of ResultList
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * returns current data
     *
     * @return mixed|\App\HA\Entities\Device
     */
    public function current()
    {
        return $this->data[$this->position];
    }

    /**
     * returns current position
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * moves pointer to next
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * checks if current data pos is present
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->data[$this->position]);
    }
}
