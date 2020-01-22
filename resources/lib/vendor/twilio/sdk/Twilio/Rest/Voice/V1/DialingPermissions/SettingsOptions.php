<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Voice\V1\DialingPermissions;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
abstract class SettingsOptions
{
    /**
     * @param bool $dialingPermissionsInheritance `true` for this sub-account to
     *                                            inherit voice dialing permissions
     *                                            from the Master Project;
     *                                            otherwise `false`
     * @return UpdateSettingsOptions Options builder
     */
    public static function update($dialingPermissionsInheritance = Values::NONE)
    {
        return new UpdateSettingsOptions($dialingPermissionsInheritance);
    }
}

class UpdateSettingsOptions extends Options
{
    /**
     * @param bool $dialingPermissionsInheritance `true` for this sub-account to
     *                                            inherit voice dialing permissions
     *                                            from the Master Project;
     *                                            otherwise `false`
     */
    public function __construct($dialingPermissionsInheritance = Values::NONE)
    {
        $this->options['dialingPermissionsInheritance'] = $dialingPermissionsInheritance;
    }

    /**
     * `true` for this sub-account to inherit voice dialing permissions from the Master Project; otherwise `false`.
     *
     * @param bool $dialingPermissionsInheritance `true` for this sub-account to
     *                                            inherit voice dialing permissions
     *                                            from the Master Project;
     *                                            otherwise `false`
     * @return $this Fluent Builder
     */
    public function setDialingPermissionsInheritance($dialingPermissionsInheritance)
    {
        $this->options['dialingPermissionsInheritance'] = $dialingPermissionsInheritance;
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
        return '[Twilio.Voice.V1.UpdateSettingsOptions ' . implode(' ', $options) . ']';
    }
}
