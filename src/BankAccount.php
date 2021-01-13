<?php

namespace Omnipay\Juno;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ParametersTrait;
use Omnipay\Common\Helper;
use Symfony\Component\HttpFoundation\ParameterBag;

class BankAccount
{
    use ParametersTrait;

    const AccountComplementNumberEnum = [
        "001", "002", "003", "006", "007", "013", "022", "023", "028", "043", "031",
    ];

    const AccountTypeEnum = [
        "CHECKING", "SAVINGS",
    ];

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
            'bankNumber' => 'Bank number',
            'agencyNumber' => 'Agency number',
            'accountNumber' => 'Account number',
            'accountType' => 'Account type',
            'accountHolder' => 'Account holder',
        );

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getParameter($key)) {
                throw new InvalidRequestException("The $val is required");
            }
        }

        if (!is_null($this->getAccountComplementNumber()) && !in_array($this->getAccountComplementNumber(), self::AccountComplementNumberEnum)) {
            throw new InvalidRequestException('Account complement number must be one of these:' + (string)self::AccountComplementNumberEnum);
        }

        if (!in_array($this->getAccountType(), self::AccountTypeEnum)) {
            throw new InvalidRequestException('Account type must be one of these:' + (string)self::AccountTypeEnum);
        }

        if (!is_array($this->getParameter('accountHolder')))
        {
            throw new InvalidRequestException('Account holder must be an array with name and document');
        }

        $requiredParameters = array(
            'name' => 'Name',
            'document' => 'Document',
        );

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getAccountHolder()[$key]) {
                throw new InvalidRequestException("The accountHolder.$val is required");
            }
        }

        if (!preg_match('/^(\d{11})$/i', $this->getAccountHolder()['document']))
        {
            throw new InvalidRequestException('accountHolder.document must have 11 digits');
        }
    }

    public function getBankNumber()
    {
        return $this->getParameter('bankNumber');
    }

    public function setBankNumber($value)
    {
        return $this->setParameter('bankNumber', $value);
    }

    public function getAgencyNumber()
    {
        return $this->getParameter('agencyNumber');
    }

    public function setAgencyNumber($value)
    {
        return $this->setParameter('agencyNumber', $value);
    }

    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    public function setAccountNumber($value)
    {
        return $this->setParameter('accountNumber', $value);
    }

    public function getAccountComplementNumber()
    {
        return $this->getParameter('accountComplementNumber');
    }

    public function setAccountComplementNumber($value)
    {
        return $this->setParameter('accountComplementNumber', $value);
    }

    public function getAccountType()
    {
        return $this->getParameter('accountType');
    }

    public function setAccountType($value)
    {
        return $this->setParameter('accountType', $value);
    }

    public function getAccountHolder()
    {
        return $this->getParameter('accountHolder');
    }

    public function setAccountHolder($value)
    {
        return $this->setParameter('accountHolder', $value);
    }

}