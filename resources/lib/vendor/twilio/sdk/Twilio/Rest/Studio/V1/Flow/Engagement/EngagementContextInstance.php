<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Studio\V1\Flow\Engagement;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Values;
use Twilio\Version;

/**
 * @property string $accountSid
 * @property array $context
 * @property string $engagementSid
 * @property string $flowSid
 * @property string $url
 */
class EngagementContextInstance extends InstanceResource
{
    /**
     * Initialize the EngagementContextInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $flowSid Flow Sid.
     * @param string $engagementSid Engagement Sid.
     * @return \Twilio\Rest\Studio\V1\Flow\Engagement\EngagementContextInstance
     */
    public function __construct(Version $version, array $payload, $flowSid, $engagementSid)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'context' => Values::array_get($payload, 'context'),
            'engagementSid' => Values::array_get($payload, 'engagement_sid'),
            'flowSid' => Values::array_get($payload, 'flow_sid'),
            'url' => Values::array_get($payload, 'url'),
        );

        $this->solution = array('flowSid' => $flowSid, 'engagementSid' => $engagementSid,);
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return \Twilio\Rest\Studio\V1\Flow\Engagement\EngagementContextContext Context for this EngagementContextInstance
     */
    protected function proxy()
    {
        if (!$this->context) {
            $this->context = new EngagementContextContext(
                $this->version,
                $this->solution['flowSid'],
                $this->solution['engagementSid']
            );
        }

        return $this->context;
    }

    /**
     * Fetch a EngagementContextInstance
     *
     * @return EngagementContextInstance Fetched EngagementContextInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch()
    {
        return $this->proxy()->fetch();
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
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
        return '[Twilio.Studio.V1.EngagementContextInstance ' . implode(' ', $context) . ']';
    }
}
