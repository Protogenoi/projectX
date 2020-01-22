<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Sync\V1;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Options;
use Twilio\Rest\Sync\V1\Service\DocumentList;
use Twilio\Rest\Sync\V1\Service\SyncListList;
use Twilio\Rest\Sync\V1\Service\SyncMapList;
use Twilio\Rest\Sync\V1\Service\SyncStreamList;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 *
 * @property \Twilio\Rest\Sync\V1\Service\DocumentList $documents
 * @property \Twilio\Rest\Sync\V1\Service\SyncListList $syncLists
 * @property \Twilio\Rest\Sync\V1\Service\SyncMapList $syncMaps
 * @property \Twilio\Rest\Sync\V1\Service\SyncStreamList $syncStreams
 * @method \Twilio\Rest\Sync\V1\Service\DocumentContext documents(string $sid)
 * @method \Twilio\Rest\Sync\V1\Service\SyncListContext syncLists(string $sid)
 * @method \Twilio\Rest\Sync\V1\Service\SyncMapContext syncMaps(string $sid)
 * @method \Twilio\Rest\Sync\V1\Service\SyncStreamContext syncStreams(string $sid)
 */
class ServiceContext extends InstanceContext
{
    protected $_documents = null;
    protected $_syncLists = null;
    protected $_syncMaps = null;
    protected $_syncStreams = null;

    /**
     * Initialize the ServiceContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $sid A unique identifier for this service instance.
     * @return \Twilio\Rest\Sync\V1\ServiceContext
     */
    public function __construct(Version $version, $sid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('sid' => $sid,);

        $this->uri = '/Services/' . rawurlencode($sid) . '';
    }

    /**
     * Fetch a ServiceInstance
     *
     * @return ServiceInstance Fetched ServiceInstance
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

        return new ServiceInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Deletes the ServiceInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete()
    {
        return $this->version->delete('delete', $this->uri);
    }

    /**
     * Update the ServiceInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ServiceInstance Updated ServiceInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($options = array())
    {
        $options = new Values($options);

        $data = Values::of(array(
            'WebhookUrl' => $options['webhookUrl'],
            'FriendlyName' => $options['friendlyName'],
            'ReachabilityWebhooksEnabled' => Serialize::booleanToString($options['reachabilityWebhooksEnabled']),
            'AclEnabled' => Serialize::booleanToString($options['aclEnabled']),
            'ReachabilityDebouncingEnabled' => Serialize::booleanToString($options['reachabilityDebouncingEnabled']),
            'ReachabilityDebouncingWindow' => $options['reachabilityDebouncingWindow'],
        ));

        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new ServiceInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Access the documents
     *
     * @return \Twilio\Rest\Sync\V1\Service\DocumentList
     */
    protected function getDocuments()
    {
        if (!$this->_documents) {
            $this->_documents = new DocumentList($this->version, $this->solution['sid']);
        }

        return $this->_documents;
    }

    /**
     * Access the syncLists
     *
     * @return \Twilio\Rest\Sync\V1\Service\SyncListList
     */
    protected function getSyncLists()
    {
        if (!$this->_syncLists) {
            $this->_syncLists = new SyncListList($this->version, $this->solution['sid']);
        }

        return $this->_syncLists;
    }

    /**
     * Access the syncMaps
     *
     * @return \Twilio\Rest\Sync\V1\Service\SyncMapList
     */
    protected function getSyncMaps()
    {
        if (!$this->_syncMaps) {
            $this->_syncMaps = new SyncMapList($this->version, $this->solution['sid']);
        }

        return $this->_syncMaps;
    }

    /**
     * Access the syncStreams
     *
     * @return \Twilio\Rest\Sync\V1\Service\SyncStreamList
     */
    protected function getSyncStreams()
    {
        if (!$this->_syncStreams) {
            $this->_syncStreams = new SyncStreamList($this->version, $this->solution['sid']);
        }

        return $this->_syncStreams;
    }

    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     * @return \Twilio\ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get($name)
    {
        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return \Twilio\InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call($name, $arguments)
    {
        $property = $this->$name;
        if (method_exists($property, 'getContext')) {
            return call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
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
        return '[Twilio.Sync.V1.ServiceContext ' . implode(' ', $context) . ']';
    }
}
