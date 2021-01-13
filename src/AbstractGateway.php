<?php

namespace Omnipay\Juno;

/**
 * AbstractGateway Class
 */
abstract class AbstractGateway extends \Omnipay\Common\AbstractGateway
{
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getClientSecret()
    {
        return $this->getParameter('clientSecret');
    }

    public function setClientSecret($value)
    {
        return $this->setParameter('clientSecret', $value);
    }

    public function getJunoVersion()
    {
        return $this->getParameter('junoVersion');
    }

    public function setJunoVersion($value)
    {
        return $this->setParameter('junoVersion', $value);
    }

    public function getResourceToken()
    {
        return $this->getParameter('resourceToken');
    }

    public function setResourceToken($value)
    {
        return $this->setParameter('resourceToken', $value);
    }

    public function setProduction(array $value)
    {
        if (!$this->getTestMode())
        {
            $this->setClientId($value['clientId']);
            $this->setClientSecret($value['clientSecret']);
            $this->setResourceToken($value['resourceToken']);
        }
    }

    public function setSandbox(array $value)
    {
        if ($this->getTestMode())
        {
            $this->setClientId($value['clientId']);
            $this->setClientSecret($value['clientSecret']);
            $this->setResourceToken($value['resourceToken']);
        }
    }
}