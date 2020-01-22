<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Values;
use Twilio\Version;

class AuthorizedConnectAppContext extends InstanceContext
{
    /**
     * Initialize the AuthorizedConnectAppContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $accountSid The SID of the Account that created the resource
     *                           to fetch
     * @param string $connectAppSid The SID of the Connect App to fetch
     * @return \Twilio\Rest\Api\V2010\Account\AuthorizedConnectAppContext
     */
    public function __construct(Version $version, $accountSid, $connectAppSid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('accountSid' => $accountSid, 'connectAppSid' => $connectAppSid,);

        $this->uri = '/Accounts/' . rawurlencode($accountSid) . '/AuthorizedConnectApps/' . rawurlencode($connectAppSid) . '.json';
    }

    /**
     * Fetch a AuthorizedConnectAppInstance
     *
     * @return AuthorizedConnectAppInstance Fetched AuthorizedConnectAppInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch()
    {
        $params = Values::of(array());

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new AuthorizedConnectAppInstance(
            $this->version,
            $payload,
            $this->solution['accountSid'],
            $this->solution['connectAppSid']
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        $context = array();
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Api.V2010.AuthorizedConnectAppContext ' . implode(' ', $context) . ']';
    }
}
