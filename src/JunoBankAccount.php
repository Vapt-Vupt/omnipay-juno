<?php

namespace Omnipay\Juno;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ParametersTrait;
use Omnipay\Common\Helper;
use Symfony\Component\HttpFoundation\ParameterBag;

class JunoBankAccount
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
            'accountNumber' => 'Account number',
        );

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getParameter($key)) {
                throw new InvalidRequestException("The $val is required");
            }
        }
    }

    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }
}
