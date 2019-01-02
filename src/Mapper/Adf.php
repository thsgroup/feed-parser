<?php

namespace Thsgroup\FeedParser\Mapper;

class Adf
{
    private $network;
    private $property;
    private $branch;
    private $agent_ref;

    /**
     * @return mixed
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param mixed $network
     */
    public function setNetwork($network)
    {
        $this->network = $network;
    }

    /**
     * @return mixed
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param mixed $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * @return mixed
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param mixed $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    /**
     * @return mixed
     */
    public function getAgentRef()
    {
        return $this->agent_ref;
    }

    /**
     * @param mixed $agent_ref
     */
    public function setAgentRef($agent_ref)
    {
        $this->agent_ref = $agent_ref;
    }
}
