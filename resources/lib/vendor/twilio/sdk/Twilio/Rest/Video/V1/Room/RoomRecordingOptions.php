<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Video\V1\Room;

use Twilio\Options;
use Twilio\Values;

abstract class RoomRecordingOptions
{
    /**
     * @param string $status The status
     * @param string $sourceSid The source_sid
     * @param \DateTime $dateCreatedAfter The date_created_after
     * @param \DateTime $dateCreatedBefore The date_created_before
     * @return ReadRoomRecordingOptions Options builder
     */
    public static function read(
        $status = Values::NONE,
        $sourceSid = Values::NONE,
        $dateCreatedAfter = Values::NONE,
        $dateCreatedBefore = Values::NONE
    ) {
        return new ReadRoomRecordingOptions($status, $sourceSid, $dateCreatedAfter, $dateCreatedBefore);
    }
}

class ReadRoomRecordingOptions extends Options
{
    /**
     * @param string $status The status
     * @param string $sourceSid The source_sid
     * @param \DateTime $dateCreatedAfter The date_created_after
     * @param \DateTime $dateCreatedBefore The date_created_before
     */
    public function __construct(
        $status = Values::NONE,
        $sourceSid = Values::NONE,
        $dateCreatedAfter = Values::NONE,
        $dateCreatedBefore = Values::NONE
    ) {
        $this->options['status'] = $status;
        $this->options['sourceSid'] = $sourceSid;
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
    }

    /**
     * The status
     *
     * @param string $status The status
     * @return $this Fluent Builder
     */
    public function setStatus($status)
    {
        $this->options['status'] = $status;
        return $this;
    }

    /**
     * The source_sid
     *
     * @param string $sourceSid The source_sid
     * @return $this Fluent Builder
     */
    public function setSourceSid($sourceSid)
    {
        $this->options['sourceSid'] = $sourceSid;
        return $this;
    }

    /**
     * The date_created_after
     *
     * @param \DateTime $dateCreatedAfter The date_created_after
     * @return $this Fluent Builder
     */
    public function setDateCreatedAfter($dateCreatedAfter)
    {
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
        return $this;
    }

    /**
     * The date_created_before
     *
     * @param \DateTime $dateCreatedBefore The date_created_before
     * @return $this Fluent Builder
     */
    public function setDateCreatedBefore($dateCreatedBefore)
    {
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Video.V1.ReadRoomRecordingOptions ' . implode(' ', $options) . ']';
    }
}
