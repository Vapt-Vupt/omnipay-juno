<?php

namespace Omnipay\Juno\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Juno\Address;
use Omnipay\Juno\BankAccount;

class CreateDigitalAccountRequest extends AbstractRequest
{

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getEndpoint()
    {
        return 'digital-accounts';
    }

    public function getData()
    {
        $this->validate('resourceToken', 'name', 'document', 'email', 'phone', 'businessArea', 'linesOfBusiness', 'address', 'bankAccount');

        $data = [];

        $data['type'] = "PAYMENT";

        $data['name'] = $this->getName();

        $data['document'] = $this->getDocument();

        $data['email'] = $this->getEmail();

        if ($this->getBirthDate())
        {
            $data['birthDate'] = $this->getBirthDate();
        }

        $data['phone'] = $this->getPhone();

        $data['businessArea'] = $this->getBusinessArea();

        $data['linesOfBusiness'] = $this->getLinesOfBusiness();

        if ($this->getCompanyType())
        {
            $data['companyType'] = $this->getCompanyType();
        }

        if ($this->getLegalRepresentative())
        {
            $data['legalRepresentative'] = $this->getLegalRepresentative();
        }

        $address = $this->getAddress() instanceof Address ? $this->getAddress() : new Address($this->getAddress());
        $address->validate();
        $data['address'] = $address->getParameters();

        $bankAccount = $this->getBankAccount() instanceof BankAccount ? $this->getBankAccount() : new BankAccount($this->getBankAccount());
        $bankAccount->validate();
        $data['bankAccount'] = $bankAccount->getParameters();

        if ($this->getEmailOptOut())
        {
            $data['emailOptOut'] = $this->getEmailOptOut();
        }

        if ($this->getAutoApprove())
        {
            $data['autoApprove'] = $this->getAutoApprove();
        }

        if ($this->getAutoTransfer())
        {
            $data['autoTransfer'] = $this->getAutoTransfer();
        }

        return $data;
    }

    public function getName()
    {
        return $this->getParameter('name');
    }

    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    public function getDocument()
    {
        return $this->getParameter('document');
    }

    public function setDocument($value)
    {
        return $this->setParameter('document', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getBirthDate()
    {
        return $this->getParameter('birthDate');
    }

    public function setBirthDate($value)
    {
        return $this->setParameter('birthDate', $value);
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

    public function getLinesOfBusiness()
    {
        return $this->getParameter('linesOfBusiness');
    }

    public function setLinesOfBusiness($value)
    {
        return $this->setParameter('linesOfBusiness', $value);
    }

    public function getCompanyType()
    {
        return $this->getParameter('companyType');
    }

    public function setCompanyType($value)
    {
        return $this->setParameter('companyType', $value);
    }

    public function getLegalRepresentative()
    {
        return $this->getParameter('legalRepresentative');
    }

    public function setLegalRepresentative($value)
    {
        return $this->setParameter('legalRepresentative', $value);
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

    public function getEmailOptOut()
    {
        return $this->getParameter('emailOptOut');
    }

    public function setEmailOptOut($value)
    {
        return $this->setParameter('emailOptOut', $value);
    }

    public function getAutoApprove()
    {
        return $this->getParameter('autoApprove');
    }

    public function setAutoApprove($value)
    {
        return $this->setParameter('autoApprove', $value);
    }

    public function getAutoTransfer()
    {
        return $this->getParameter('autoTransfer');
    }

    public function setAutoTransfer($value)
    {
        return $this->setParameter('autoTransfer', $value);
    }
}