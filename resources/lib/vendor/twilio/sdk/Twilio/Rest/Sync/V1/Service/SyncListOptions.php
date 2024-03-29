<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Sync\V1\Service;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
abstract class SyncListOptions
{
    /**
     * @param string $uniqueName Human-readable name for this list
     * @param int $ttl Alias for collection_ttl
     * @param int $collectionTtl Time-to-live of this List in seconds, defaults to
     *                           no expiration.
     * @return CreateSyncListOptions Options builder
     */
    public static function create($uniqueName = Values::NONE, $ttl = Values::NONE, $collectionTtl = Values::NONE)
    {
        return new CreateSyncListOptions($uniqueName, $ttl, $collectionTtl);
    }

    /**
     * @param int $ttl Alias for collection_ttl
     * @param int $collectionTtl Time-to-live of this List in seconds, defaults to
     *                           no expiration.
     * @return UpdateSyncListOptions Options builder
     */
    public static function update($ttl = Values::NONE, $collectionTtl = Values::NONE)
    {
        return new UpdateSyncListOptions($ttl, $collectionTtl);
    }
}

class CreateSyncListOptions extends Options
{
    /**
     * @param string $uniqueName Human-readable name for this list
     * @param int $ttl Alias for collection_ttl
     * @param int $collectionTtl Time-to-live of this List in seconds, defaults to
     *                           no expiration.
     */
    public function __construct($uniqueName = Values::NONE, $ttl = Values::NONE, $collectionTtl = Values::NONE)
    {
        $this->options['uniqueName'] = $uniqueName;
        $this->options['ttl'] = $ttl;
        $this->options['collectionTtl'] = $collectionTtl;
    }

    /**
     * Human-readable name for this list
     *
     * @param string $uniqueName Human-readable name for this list
     * @return $this Fluent Builder
     */
    public function setUniqueName($uniqueName)
    {
        $this->options['uniqueName'] = $uniqueName;
        return $this;
    }

    /**
     * Alias for collection_ttl. If both are provided, this value is ignored.
     *
     * @param int $ttl Alias for collection_ttl
     * @return $this Fluent Builder
     */
    public function setTtl($ttl)
    {
        $this->options['ttl'] = $ttl;
        return $this;
    }

    /**
     * Time-to-live of this List in seconds, defaults to no expiration. In the range [1, 31 536 000 (1 year)], or 0 for infinity.
     *
     * @param int $collectionTtl Time-to-live of this List in seconds, defaults to
     *                           no expiration.
     * @return $this Fluent Builder
     */
    public function setCollectionTtl($collectionTtl)
    {
        $this->options['collectionTtl'] = $collectionTtl;
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
        return '[Twilio.Sync.V1.CreateSyncListOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateSyncListOptions extends Options
{
    /**
     * @param int $ttl Alias for collection_ttl
     * @param int $collectionTtl Time-to-live of this List in seconds, defaults to
     *                           no expiration.
     */
    public function __construct($ttl = Values::NONE, $collectionTtl = Values::NONE)
    {
        $this->options['ttl'] = $ttl;
        $this->options['collectionTtl'] = $collectionTtl;
    }

    /**
     * Alias for collection_ttl. If both are provided, this value is ignored.
     *
     * @param int $ttl Alias for collection_ttl
     * @return $this Fluent Builder
     */
    public function setTtl($ttl)
    {
        $this->options['ttl'] = $ttl;
        return $this;
    }

    /**
     * Time-to-live of this List in seconds, defaults to no expiration. In the range [1, 31 536 000 (1 year)], or 0 for infinity.
     *
     * @param int $collectionTtl Time-to-live of this List in seconds, defaults to
     *                           no expiration.
     * @return $this Fluent Builder
     */
    public function setCollectionTtl($collectionTtl)
    {
        $this->options['collectionTtl'] = $collectionTtl;
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
        return '[Twilio.Sync.V1.UpdateSyncListOptions ' . implode(' ', $options) . ']';
    }
}
