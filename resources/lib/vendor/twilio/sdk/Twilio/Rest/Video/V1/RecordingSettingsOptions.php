<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Video\V1;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
abstract class RecordingSettingsOptions
{
    /**
     * @param string $awsCredentialsSid SID of the Stored Credential resource CRxx
     * @param string $encryptionKeySid SID of the Public Key resource CRxx
     * @param string $awsS3Url Identity of the external location where the
     *                         recordings should be stored. We only support
     *                         DNS-compliant URLs like
     *                         http://<my-bucket>.s3-<aws-region>.amazonaws.com/recordings, where recordings is the path where you want recordings to be stored.
     * @param bool $awsStorageEnabled true|false When set to true, all Recordings
     *                                will be written to the AwsS3Url specified
     *                                above. When set to false, all Recordings will
     *                                be stored in Twilio's cloud.
     * @param bool $encryptionEnabled true|false When set to true, all Recordings
     *                                will be stored encrypted.
     * @return CreateRecordingSettingsOptions Options builder
     */
    public static function create(
        $awsCredentialsSid = Values::NONE,
        $encryptionKeySid = Values::NONE,
        $awsS3Url = Values::NONE,
        $awsStorageEnabled = Values::NONE,
        $encryptionEnabled = Values::NONE
    ) {
        return new CreateRecordingSettingsOptions($awsCredentialsSid, $encryptionKeySid, $awsS3Url, $awsStorageEnabled,
            $encryptionEnabled);
    }
}

class CreateRecordingSettingsOptions extends Options
{
    /**
     * @param string $awsCredentialsSid SID of the Stored Credential resource CRxx
     * @param string $encryptionKeySid SID of the Public Key resource CRxx
     * @param string $awsS3Url Identity of the external location where the
     *                         recordings should be stored. We only support
     *                         DNS-compliant URLs like
     *                         http://<my-bucket>.s3-<aws-region>.amazonaws.com/recordings, where recordings is the path where you want recordings to be stored.
     * @param bool $awsStorageEnabled true|false When set to true, all Recordings
     *                                will be written to the AwsS3Url specified
     *                                above. When set to false, all Recordings will
     *                                be stored in Twilio's cloud.
     * @param bool $encryptionEnabled true|false When set to true, all Recordings
     *                                will be stored encrypted.
     */
    public function __construct(
        $awsCredentialsSid = Values::NONE,
        $encryptionKeySid = Values::NONE,
        $awsS3Url = Values::NONE,
        $awsStorageEnabled = Values::NONE,
        $encryptionEnabled = Values::NONE
    ) {
        $this->options['awsCredentialsSid'] = $awsCredentialsSid;
        $this->options['encryptionKeySid'] = $encryptionKeySid;
        $this->options['awsS3Url'] = $awsS3Url;
        $this->options['awsStorageEnabled'] = $awsStorageEnabled;
        $this->options['encryptionEnabled'] = $encryptionEnabled;
    }

    /**
     * SID of the Stored Credential resource `CRxx`
     *
     * @param string $awsCredentialsSid SID of the Stored Credential resource CRxx
     * @return $this Fluent Builder
     */
    public function setAwsCredentialsSid($awsCredentialsSid)
    {
        $this->options['awsCredentialsSid'] = $awsCredentialsSid;
        return $this;
    }

    /**
     * SID of the Public Key resource `CRxx`
     *
     * @param string $encryptionKeySid SID of the Public Key resource CRxx
     * @return $this Fluent Builder
     */
    public function setEncryptionKeySid($encryptionKeySid)
    {
        $this->options['encryptionKeySid'] = $encryptionKeySid;
        return $this;
    }

    /**
     * Identity of the external location where the recordings should be stored. We only support DNS-compliant URLs like `http://<my-bucket>.s3-<aws-region>.amazonaws.com/recordings`, where `recordings` is the path where you want recordings to be stored.
     *
     * @param string $awsS3Url Identity of the external location where the
     *                         recordings should be stored. We only support
     *                         DNS-compliant URLs like
     *                         http://<my-bucket>.s3-<aws-region>.amazonaws.com/recordings, where recordings is the path where you want recordings to be stored.
     * @return $this Fluent Builder
     */
    public function setAwsS3Url($awsS3Url)
    {
        $this->options['awsS3Url'] = $awsS3Url;
        return $this;
    }

    /**
     * `true|false` When set to `true`, all Recordings will be written to the `AwsS3Url` specified above. When set to `false`, all Recordings will be stored in Twilio's cloud.
     *
     * @param bool $awsStorageEnabled true|false When set to true, all Recordings
     *                                will be written to the AwsS3Url specified
     *                                above. When set to false, all Recordings will
     *                                be stored in Twilio's cloud.
     * @return $this Fluent Builder
     */
    public function setAwsStorageEnabled($awsStorageEnabled)
    {
        $this->options['awsStorageEnabled'] = $awsStorageEnabled;
        return $this;
    }

    /**
     * `true|false` When set to `true`, all Recordings will be stored encrypted. Dafault value is `false`
     *
     * @param bool $encryptionEnabled true|false When set to true, all Recordings
     *                                will be stored encrypted.
     * @return $this Fluent Builder
     */
    public function setEncryptionEnabled($encryptionEnabled)
    {
        $this->options['encryptionEnabled'] = $encryptionEnabled;
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
        return '[Twilio.Video.V1.CreateRecordingSettingsOptions ' . implode(' ', $options) . ']';
    }
}
