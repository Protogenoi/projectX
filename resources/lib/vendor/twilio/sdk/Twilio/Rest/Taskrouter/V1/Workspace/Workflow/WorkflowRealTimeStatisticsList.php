<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Taskrouter\V1\Workspace\Workflow;

use Twilio\ListResource;
use Twilio\Version;

class WorkflowRealTimeStatisticsList extends ListResource
{
    /**
     * Construct the WorkflowRealTimeStatisticsList
     *
     * @param Version $version Version that contains the resource
     * @param string $workspaceSid The workspace_sid
     * @param string $workflowSid The workflow_sid
     * @return \Twilio\Rest\Taskrouter\V1\Workspace\Workflow\WorkflowRealTimeStatisticsList
     */
    public function __construct(Version $version, $workspaceSid, $workflowSid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('workspaceSid' => $workspaceSid, 'workflowSid' => $workflowSid,);
    }

    /**
     * Constructs a WorkflowRealTimeStatisticsContext
     *
     * @return \Twilio\Rest\Taskrouter\V1\Workspace\Workflow\WorkflowRealTimeStatisticsContext
     */
    public function getContext()
    {
        return new WorkflowRealTimeStatisticsContext(
            $this->version,
            $this->solution['workspaceSid'],
            $this->solution['workflowSid']
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        return '[Twilio.Taskrouter.V1.WorkflowRealTimeStatisticsList]';
    }
}
