<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Sync\V1\Service\SyncMap;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
abstract class SyncMapItemOptions
{
    /**
     * @param int $ttl Alias for item_ttl
     * @param int $itemTtl Time-to-live of this item in seconds, defaults to no
     *                     expiration.
     * @param int $collectionTtl Time-to-live of this item's parent Map in seconds,
     *                           defaults to no expiration.
     * @return CreateSyncMapItemOptions Options builder
     */
    public static function create($ttl = Values::NONE, $itemTtl = Values::NONE, $collectionTtl = Values::NONE)
    {
        return new CreateSyncMapItemOptions($ttl, $itemTtl, $collectionTtl);
    }

    /**
     * @param string $order A string; asc or desc. Map Items are ordered
     *                      lexicographically by Item key.
     * @param string $from The Item key offset (including the specified key).
     * @param string $bounds The bounds
     * @return ReadSyncMapItemOptions Options builder
     */
    public static function read($order = Values::NONE, $from = Values::NONE, $bounds = Values::NONE)
    {
        return new ReadSyncMapItemOptions($order, $from, $bounds);
    }

    /**
     * @param array $data Contains an arbitrary JSON object to be stored in this
     *                    Map Item.
     * @param int $ttl Alias for item_ttl
     * @param int $itemTtl Time-to-live of this item in seconds, defaults to no
     *                     expiration.
     * @param int $collectionTtl Time-to-live of this item's parent Map in seconds,
     *                           defaults to no expiration.
     * @return UpdateSyncMapItemOptions Options builder
     */
    public static function update(
        $data = Values::NONE,
        $ttl = Values::NONE,
        $itemTtl = Values::NONE,
        $collectionTtl = Values::NONE
    ) {
        return new UpdateSyncMapItemOptions($data, $ttl, $itemTtl, $collectionTtl);
    }
}

class CreateSyncMapItemOptions extends Options
{
    /**
     * @param int $ttl Alias for item_ttl
     * @param int $itemTtl Time-to-live of this item in seconds, defaults to no
     *                     expiration.
     * @param int $collectionTtl Time-to-live of this item's parent Map in seconds,
     *                           defaults to no expiration.
     */
    public function __construct($ttl = Values::NONE, $itemTtl = Values::NONE, $collectionTtl = Values::NONE)
    {
        $this->options['ttl'] = $ttl;
        $this->options['itemTtl'] = $itemTtl;
        $this->options['collectionTtl'] = $collectionTtl;
    }

    /**
     * Alias for item_ttl. If both are provided, this value is ignored.
     *
     * @param int $ttl Alias for item_ttl
     * @return $this Fluent Builder
     */
    public function setTtl($ttl)
    {
        $this->options['ttl'] = $ttl;
        return $this;
    }

    /**
     * Time-to-live of this item in seconds, defaults to no expiration. In the range [1, 31 536 000 (1 year)], or 0 for infinity. Upon expiry, the map item will be cleaned up at least in a matter of hours, and often within seconds, making this a good tool for garbage management.
     *
     * @param int $itemTtl Time-to-live of this item in seconds, defaults to no
     *                     expiration.
     * @return $this Fluent Builder
     */
    public function setItemTtl($itemTtl)
    {
        $this->options['itemTtl'] = $itemTtl;
        return $this;
    }

    /**
     * Time-to-live of this item's parent Map in seconds, defaults to no expiration. In the range [1, 31 536 000 (1 year)], or 0 for infinity. This parameter can only be used when the map item's data or ttl is updated in the same request.
     *
     * @param int $collectionTtl Time-to-live of this item's parent Map in seconds,
     *                           defaults to no expiration.
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
        return '[Twilio.Sync.V1.CreateSyncMapItemOptions ' . implode(' ', $options) . ']';
    }
}

class ReadSyncMapItemOptions extends Options
{
    /**
     * @param string $order A string; asc or desc. Map Items are ordered
     *                      lexicographically by Item key.
     * @param string $from The Item key offset (including the specified key).
     * @param string $bounds The bounds
     */
    public function __construct($order = Values::NONE, $from = Values::NONE, $bounds = Values::NONE)
    {
        $this->options['order'] = $order;
        $this->options['from'] = $from;
        $this->options['bounds'] = $bounds;
    }

    /**
     * A string; asc or desc. Map Items are [ordered lexicographically](https://en.wikipedia.org/wiki/Lexicographical_order) by Item key.
     *
     * @param string $order A string; asc or desc. Map Items are ordered
     *                      lexicographically by Item key.
     * @return $this Fluent Builder
     */
    public function setOrder($order)
    {
        $this->options['order'] = $order;
        return $this;
    }

    /**
     * The Item key offset (including the specified key). If not present, query is performed from the start or end, depending on the Order query parameter.
     *
     * @param string $from The Item key offset (including the specified key).
     * @return $this Fluent Builder
     */
    public function setFrom($from)
    {
        $this->options['from'] = $from;
        return $this;
    }

    /**
     * The bounds
     *
     * @param string $bounds The bounds
     * @return $this Fluent Builder
     */
    public function setBounds($bounds)
    {
        $this->options['bounds'] = $bounds;
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
        return '[Twilio.Sync.V1.ReadSyncMapItemOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateSyncMapItemOptions extends Options
{
    /**
     * @param array $data Contains an arbitrary JSON object to be stored in this
     *                    Map Item.
     * @param int $ttl Alias for item_ttl
     * @param int $itemTtl Time-to-live of this item in seconds, defaults to no
     *                     expiration.
     * @param int $collectionTtl Time-to-live of this item's parent Map in seconds,
     *                           defaults to no expiration.
     */
    public function __construct(
        $data = Values::NONE,
        $ttl = Values::NONE,
        $itemTtl = Values::NONE,
        $collectionTtl = Values::NONE
    ) {
        $this->options['data'] = $data;
        $this->options['ttl'] = $ttl;
        $this->options['itemTtl'] = $itemTtl;
        $this->options['collectionTtl'] = $collectionTtl;
    }

    /**
     * Contains an arbitrary JSON object to be stored in this Map Item. Serialized to string to respect HTTP form input, up to 16KB.
     *
     * @param array $data Contains an arbitrary JSON object to be stored in this
     *                    Map Item.
     * @return $this Fluent Builder
     */
    public function setData($data)
    {
        $this->options['data'] = $data;
        return $this;
    }

    /**
     * Alias for item_ttl. If both are provided, this value is ignored.
     *
     * @param int $ttl Alias for item_ttl
     * @return $this Fluent Builder
     */
    public function setTtl($ttl)
    {
        $this->options['ttl'] = $ttl;
        return $this;
    }

    /**
     * Time-to-live of this item in seconds, defaults to no expiration. In the range [1, 31 536 000 (1 year)], or 0 for infinity. Upon expiry, the map item will be cleaned up at least in a matter of hours, and often within seconds, making this a good tool for garbage management.
     *
     * @param int $itemTtl Time-to-live of this item in seconds, defaults to no
     *                     expiration.
     * @return $this Fluent Builder
     */
    public function setItemTtl($itemTtl)
    {
        $this->options['itemTtl'] = $itemTtl;
        return $this;
    }

    /**
     * Time-to-live of this item's parent Map in seconds, defaults to no expiration. In the range [1, 31 536 000 (1 year)], or 0 for infinity. This parameter can only be used when the map item's data or ttl is updated in the same request.
     *
     * @param int $collectionTtl Time-to-live of this item's parent Map in seconds,
     *                           defaults to no expiration.
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
        return '[Twilio.Sync.V1.UpdateSyncMapItemOptions ' . implode(' ', $options) . ']';
    }
}
