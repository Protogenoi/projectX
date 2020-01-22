<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Taskrouter\V1\Workspace;

use Twilio\Options;
use Twilio\Values;

abstract class TaskChannelOptions
{
    /**
     * @param string $friendlyName Toggle the FriendlyName for the TaskChannel
     * @param bool $channelOptimizedRouting If true then prioritize longest idle
     *                                      workers
     * @return UpdateTaskChannelOptions Options builder
     */
    public static function update($friendlyName = Values::NONE, $channelOptimizedRouting = Values::NONE)
    {
        return new UpdateTaskChannelOptions($friendlyName, $channelOptimizedRouting);
    }

    /**
     * @param bool $channelOptimizedRouting If true then prioritize longest idle
     *                                      workers
     * @return CreateTaskChannelOptions Options builder
     */
    public static function create($channelOptimizedRouting = Values::NONE)
    {
        return new CreateTaskChannelOptions($channelOptimizedRouting);
    }
}

class UpdateTaskChannelOptions extends Options
{
    /**
     * @param string $friendlyName Toggle the FriendlyName for the TaskChannel
     * @param bool $channelOptimizedRouting If true then prioritize longest idle
     *                                      workers
     */
    public function __construct($friendlyName = Values::NONE, $channelOptimizedRouting = Values::NONE)
    {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['channelOptimizedRouting'] = $channelOptimizedRouting;
    }

    /**
     * Toggle the FriendlyName for the TaskChannel
     *
     * @param string $friendlyName Toggle the FriendlyName for the TaskChannel
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName)
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * A boolean that if true; mean that the channel will prioritize workers that have been idle
     *
     * @param bool $channelOptimizedRouting If true then prioritize longest idle
     *                                      workers
     * @return $this Fluent Builder
     */
    public function setChannelOptimizedRouting($channelOptimizedRouting)
    {
        $this->options['channelOptimizedRouting'] = $channelOptimizedRouting;
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
        return '[Twilio.Taskrouter.V1.UpdateTaskChannelOptions ' . implode(' ', $options) . ']';
    }
}

class CreateTaskChannelOptions extends Options
{
    /**
     * @param bool $channelOptimizedRouting If true then prioritize longest idle
     *                                      workers
     */
    public function __construct($channelOptimizedRouting = Values::NONE)
    {
        $this->options['channelOptimizedRouting'] = $channelOptimizedRouting;
    }

    /**
     * A boolean that if true; mean that the channel will prioritize workers that have been idle
     *
     * @param bool $channelOptimizedRouting If true then prioritize longest idle
     *                                      workers
     * @return $this Fluent Builder
     */
    public function setChannelOptimizedRouting($channelOptimizedRouting)
    {
        $this->options['channelOptimizedRouting'] = $channelOptimizedRouting;
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
        return '[Twilio.Taskrouter.V1.CreateTaskChannelOptions ' . implode(' ', $options) . ']';
    }
}
