<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Fax;

use Twilio\TwiML\TwiML;

class Receive extends TwiML
{
    /**
     * Receive constructor.
     *
     * @param array $attributes Optional attributes
     */
    public function __construct($attributes = array())
    {
        parent::__construct('Receive', null, $attributes);
    }

    /**
     * Add Action attribute.
     *
     * @param string $action Receive action URL
     * @return static $this.
     */
    public function setAction($action)
    {
        return $this->setAttribute('action', $action);
    }

    /**
     * Add Method attribute.
     *
     * @param string $method Receive action URL method
     * @return static $this.
     */
    public function setMethod($method)
    {
        return $this->setAttribute('method', $method);
    }
}
