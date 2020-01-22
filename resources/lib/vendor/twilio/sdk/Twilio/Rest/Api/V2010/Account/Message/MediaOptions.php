<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account\Message;

use Twilio\Options;
use Twilio\Values;

abstract class MediaOptions
{
    /**
     * @param string $dateCreatedBefore The `YYYY-MM-DD` value of the resources to
     *                                  read
     * @param string $dateCreated The `YYYY-MM-DD` value of the resources to read
     * @param string $dateCreatedAfter The `YYYY-MM-DD` value of the resources to
     *                                 read
     * @return ReadMediaOptions Options builder
     */
    public static function read(
        $dateCreatedBefore = Values::NONE,
        $dateCreated = Values::NONE,
        $dateCreatedAfter = Values::NONE
    ) {
        return new ReadMediaOptions($dateCreatedBefore, $dateCreated, $dateCreatedAfter);
    }
}

class ReadMediaOptions extends Options
{
    /**
     * @param string $dateCreatedBefore The `YYYY-MM-DD` value of the resources to
     *                                  read
     * @param string $dateCreated The `YYYY-MM-DD` value of the resources to read
     * @param string $dateCreatedAfter The `YYYY-MM-DD` value of the resources to
     *                                 read
     */
    public function __construct(
        $dateCreatedBefore = Values::NONE,
        $dateCreated = Values::NONE,
        $dateCreatedAfter = Values::NONE
    ) {
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
        $this->options['dateCreated'] = $dateCreated;
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
    }

    /**
     * The `date_created` value, specified as `YYYY-MM-DD`, of the resources to read. You can also specify inequality, such as `DateCreated<=YYYY-MM-DD` for media generated at or before midnight on a date, and `DateCreated>=YYYY-MM-DD` for media generated at or after midnight on a date.
     *
     * @param string $dateCreatedBefore The `YYYY-MM-DD` value of the resources to
     *                                  read
     * @return $this Fluent Builder
     */
    public function setDateCreatedBefore($dateCreatedBefore)
    {
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
        return $this;
    }

    /**
     * The `date_created` value, specified as `YYYY-MM-DD`, of the resources to read. You can also specify inequality, such as `DateCreated<=YYYY-MM-DD` for media generated at or before midnight on a date, and `DateCreated>=YYYY-MM-DD` for media generated at or after midnight on a date.
     *
     * @param string $dateCreated The `YYYY-MM-DD` value of the resources to read
     * @return $this Fluent Builder
     */
    public function setDateCreated($dateCreated)
    {
        $this->options['dateCreated'] = $dateCreated;
        return $this;
    }

    /**
     * The `date_created` value, specified as `YYYY-MM-DD`, of the resources to read. You can also specify inequality, such as `DateCreated<=YYYY-MM-DD` for media generated at or before midnight on a date, and `DateCreated>=YYYY-MM-DD` for media generated at or after midnight on a date.
     *
     * @param string $dateCreatedAfter The `YYYY-MM-DD` value of the resources to
     *                                 read
     * @return $this Fluent Builder
     */
    public function setDateCreatedAfter($dateCreatedAfter)
    {
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
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
        return '[Twilio.Api.V2010.ReadMediaOptions ' . implode(' ', $options) . ']';
    }
}