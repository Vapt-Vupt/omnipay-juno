<?php

namespace Omnipay\Juno\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Juno\Address;
use Omnipay\Juno\BankAccount;

class UpdateDigitalAccountRequest extends AbstractRequest
{

    public function getHttpMethod()
    {
        return 'PATCH';
    }

    public function getEndpoint()
    {
        return 'digital-accounts';
    }

    public function getData()
    {
        $this->validate('resourceToken');

        $data = [];

        if ($this->getCompanyType())
        {
            $data['companyType'] = $this->getCompanyType();
        }

        if ($this->getName())
        {
            $data['name'] = $this->getName();
        }

        if ($this->getBirthDate())
        {
            $data['birthDate'] = $this->getBirthDate();
        }

        if ($this->getLinesOfBusiness())
        {
            $data['linesOfBusiness'] = $this->getLinesOfBusiness();
        }

        if ($this->getEmail())
        {
            $data['email'] = $this->getEmail();
        }

        if ($this->getPhone())
        {
            $data['phone'] = $this->getPhone();
        }

        if ($this->getBusinessArea())
        {
            $data['businessArea'] = $this->getBusinessArea();
        }

        if ($this->getTradingName())
        {
            $data['tradingName'] = $this->getTradingName();
        }

        if ($this->getAddress())
        {
            $address = $this->getAddress() instanceof Address ? $this->getAddress() : new Address($this->getAddress());
            $address->validate();
            $data['address'] = $address->getParameters();
        }

        if ($this->getBankAccount())
        {
            $bankAccount = $this->getBankAccount() instanceof BankAccount ? $this->getBankAccount() : new BankAccount($this->getBankAccount());
            $bankAccount->validate();
            $data['bankAccount'] = $bankAccount->getParameters();
        }

        if ($this->getLegalRepresentative())
        {
            $data['legalRepresentative'] = $this->getLegalRepresentative();
        }

        return $data;
    }

    public function getCompanyType()
    {
        return $this->getParameter('companyType');
    }

    public function setCompanyType($value)
    {
        return $this->setParameter('companyType', $value);
    }

    public function getName()
    {
        return $this->getParameter('name');
    }

    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    public function getBirthDate()
    {
        return $this->getParameter('birthDate');
    }

    public function setBirthDate($value)
    {
        return $this->setParameter('birthDate', $value);
    }

    public function getLinesOfBusiness()
    {
        return $this->getParameter('linesOfBusiness');
    }

    public function setLinesOfBusiness($value)
    {
        return $this->setParameter('linesOfBusiness', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    public function setPhone($value)
    {
        return $this->setParameter('phone', $value);
    }

    public function getBusinessArea()
    {
        return $this->getParameter('businessArea');
    }

    public function setBusinessArea($value)
    {
        return $this->setParameter('businessArea', $value);
    }

    public function getTradingName()
    {
        return $this->getParameter('tradingName');
    }

    public function setTradingName($value)
    {
        return $this->setParameter('tradingName', $value);
    }

    public function getAddress()
    {
        return $this->getParameter('address');
    }

    public function setAddress($value)
    {
        return $this->setParameter('address', $value);
    }

    public function getBankAccount()
    {
        return $this->getParameter('bankAccount');
    }

    public function setBankAccount($value)
    {
        return $this->setParameter('bankAccount', $value);
    }

    public function getLegalRepresentative()
    {
        return $this->getParameter('legalRepresentative');
    }

    public function setLegalRepresentative($value)
    {
        return $this->setParameter('legalRepresentative', $value);
    }
}