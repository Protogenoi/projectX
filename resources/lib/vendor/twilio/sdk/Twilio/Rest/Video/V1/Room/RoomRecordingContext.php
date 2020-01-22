<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Video\V1\Room;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Values;
use Twilio\Version;

class RoomRecordingContext extends InstanceContext
{
    /**
     * Initialize the RoomRecordingContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $roomSid The room_sid
     * @param string $sid The sid
     * @return \Twilio\Rest\Video\V1\Room\RoomRecordingContext
     */
    public function __construct(Version $version, $roomSid, $sid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('roomSid' => $roomSid, 'sid' => $sid,);

        $this->uri = '/Rooms/' . rawurlencode($roomSid) . '/Recordings/' . rawurlencode($sid) . '';
    }

    /**
     * Fetch a RoomRecordingInstance
     *
     * @return RoomRecordingInstance Fetched RoomRecordingInstance
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

        return new RoomRecordingInstance(
            $this->version,
            $payload,
            $this->solution['roomSid'],
            $this->solution['sid']
        );
    }

    /**
     * Deletes the RoomRecordingInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete()
    {
        return $this->version->delete('delete', $this->uri);
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
        return '[Twilio.Video.V1.RoomRecordingContext ' . implode(' ', $context) . ']';
    }
}
