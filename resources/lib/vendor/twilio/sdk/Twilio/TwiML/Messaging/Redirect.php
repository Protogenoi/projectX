<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Messaging;

use Twilio\TwiML\TwiML;

class Redirect extends TwiML
{
    /**
     * Redirect constructor.
     *
     * @param string $url Redirect URL
     * @param array $attributes Optional attributes
     */
    public function __construct($url, $attributes = array())
    {
        parent::__construct('Redirect', $url, $attributes);
    }

    /**
     * Add Method attribute.
     *
     * @param string $method Redirect URL method
     * @return static $this.
     */
    public function setMethod($method)
    {
        return $this->setAttribute('method', $method);
    }
}
