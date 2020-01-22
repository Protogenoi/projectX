<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\DeployedDevices\Fleet;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
class KeyList extends ListResource
{
    /**
     * Construct the KeyList
     *
     * @param Version $version Version that contains the resource
     * @param string $fleetSid The unique identifier of the Fleet.
     * @return \Twilio\Rest\Preview\DeployedDevices\Fleet\KeyList
     */
    public function __construct(Version $version, $fleetSid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('fleetSid' => $fleetSid,);

        $this->uri = '/Fleets/' . rawurlencode($fleetSid) . '/Keys';
    }

    /**
     * Create a new KeyInstance
     *
     * @param array|Options $options Optional Arguments
     * @return KeyInstance Newly created KeyInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create($options = array())
    {
        $options = new Values($options);

        $data = Values::of(array(
            'FriendlyName' => $options['friendlyName'],
            'DeviceSid' => $options['deviceSid'],
        ));

        $payload = $this->version->create(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new KeyInstance($this->version, $payload, $this->solution['fleetSid']);
    }

    /**
     * Streams KeyInstance records from the API as a generator stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     *
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. stream()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, stream()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return \Twilio\Stream stream of results
     */
    public function stream($options = array(), $limit = null, $pageSize = null)
    {
        $limits = $this->version->readLimits($limit, $pageSize);

        $page = $this->page($options, $limits['pageSize']);

        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }

    /**
     * Reads KeyInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     *
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return KeyInstance[] Array of results
     */
    public function read($options = array(), $limit = null, $pageSize = null)
    {
        return iterator_to_array($this->stream($options, $limit, $pageSize), false);
    }

    /**
     * Retrieve a single page of KeyInstance records from the API.
     * Request is executed immediately
     *
     * @param array|Options $options Optional Arguments
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return \Twilio\Page Page of KeyInstance
     */
    public function page(
        $options = array(),
        $pageSize = Values::NONE,
        $pageToken = Values::NONE,
        $pageNumber = Values::NONE
    ) {
        $options = new Values($options);
        $params = Values::of(array(
            'DeviceSid' => $options['deviceSid'],
            'PageToken' => $pageToken,
            'Page' => $pageNumber,
            'PageSize' => $pageSize,
        ));

        $response = $this->version->page(
            'GET',
            $this->uri,
            $params
        );

        return new KeyPage($this->version, $response, $this->solution);
    }

    /**
     * Retrieve a specific page of KeyInstance records from the API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return \Twilio\Page Page of KeyInstance
     */
    public function getPage($targetUrl)
    {
        $response = $this->version->getDomain()->getClient()->request(
            'GET',
            $targetUrl
        );

        return new KeyPage($this->version, $response, $this->solution);
    }

    /**
     * Constructs a KeyContext
     *
     * @param string $sid A string that uniquely identifies the Key.
     * @return \Twilio\Rest\Preview\DeployedDevices\Fleet\KeyContext
     */
    public function getContext($sid)
    {
        return new KeyContext($this->version, $this->solution['fleetSid'], $sid);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        return '[Twilio.Preview.DeployedDevices.KeyList]';
    }
}
