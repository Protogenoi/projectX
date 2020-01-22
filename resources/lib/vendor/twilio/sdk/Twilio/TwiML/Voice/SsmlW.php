<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Voice;

use Twilio\TwiML\TwiML;

class SsmlW extends TwiML
{
    /**
     * SsmlW constructor.
     *
     * @param string $words Words to speak
     * @param array $attributes Optional attributes
     */
    public function __construct($words, $attributes = array())
    {
        parent::__construct('w', $words, $attributes);
    }

    /**
     * Add Role attribute.
     *
     * @param string $role Customize the pronunciation of words by specifying the
     *                     word’s part of speech or alternate meaning
     * @return static $this.
     */
    public function setRole($role)
    {
        return $this->setAttribute('role', $role);
    }
}
