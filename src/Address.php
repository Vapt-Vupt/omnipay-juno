<?php

namespace Omnipay\Juno;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ParametersTrait;
use Omnipay\Common\Helper;
use Symfony\Component\HttpFoundation\ParameterBag;

class Address
{
    use ParametersTrait;

    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    public function initialize(array $parameters = null)
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    public function validate()
    {
        $requiredParameters = array(
            'street' => 'street',
            'city' => 'city',
            'state' => 'state',
            'postCode' => 'post code'
        );

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getParameter($key)) {
                throw new InvalidRequestException("The $val is required");
            }
        }

        if (!is_null($this->getState()) && !preg_match('/^[a-zA-Z]{2}$/i', $this->getState())) {
            throw new InvalidRequestException('State must have 2 chars');
        }

        if (!is_null($this->getPostCode()) && !preg_match('/^(\d{8})$/i', $this->getPostCode())) {
            throw new InvalidRequestException('Post code must have 8 digits');
        }
    }

    public function getStreet()
    {
        return $this->getParameter('street');
    }

    public function setStreet($value)
    {
        return $this->setParameter('street', $value);
    }

    public function getNumber()
    {
        return $this->getParameter('number');
    }

    public function setNumber($value)
    {
        return $this->setParameter('number', $value ? $value : 'N/A');
    }

    public function getComplement()
    {
        return $this->getParameter('complement');
    }

    public function setComplement($value)
    {
        return $this->setParameter('complement', $value);
    }

    public function getNeighborhood()
    {
        return $this->getParameter('neighborhood');
    }

    public function setNeighborhood($value)
    {
        return $this->setParameter('neighborhood', $value);
    }

    public function getCity()
    {
        return $this->getParameter('city');
    }

    public function setCity($value)
    {
        return $this->setParameter('city', $value);
    }

    public function getState()
    {
        return $this->getParameter('state');
    }

    public function setState($value)
    {
        return $this->setParameter('state', $value);
    }

    public function getPostCode()
    {
        return $this->getParameter('postCode');
    }

    public function setPostCode($value)
    {
        return $this->setParameter('postCode', $value);
    }
}