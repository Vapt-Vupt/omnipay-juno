<?php

namespace Omnipay\Juno\Message;

use Omnipay\Juno\BankAccount;
use Omnipay\Common\Exception\InvalidRequestException;

class RequestTransferRequest extends AbstractRequest
{

    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return 'transfers';
    }

    public function getData()
    {

        $this->validate('resourceToken', 'type');

        $data = [];
        $data['type'] = $this->getType();
        if ($data['type'] == "P2P") {
            $this->validate('resourceToken', 'type', 'document', 'amount', 'bankAccount');
            $data['name'] = $this->getName();

            $data['document'] = $this->getDocument();

            $data['amount'] = $this->getAmount();

            $data['bankAccount'] = $this->getBankAccount();
        } else if ($data['type'] == "DEFAULT_BANK_ACCOUNT") {
            $this->validate('resourceToken', 'type', 'amount');
            $data['amount'] = $this->getAmount();
        } else if ($data['type'] == "BANK_ACCOUNT") {
            $this->validate('resourceToken', 'type', 'document', 'amount', 'bankAccount');
            $data['name'] = $this->getName();

            $data['document'] = $this->getDocument();

            $data['amount'] = $this->getAmount();

            $bankAccount = $this->getBankAccount() instanceof BankAccount ? $this->getBankAccount() : new BankAccount($this->getBankAccount());
            $bankAccount->validate();
            $data['bankAccount'] = $bankAccount->getParameters();
        }


        return $data;
    }

    public function getType()
    {
        return $this->getParameter('type');
    }
    public function getName()
    {
        return $this->getParameter('name');
    }
    public function getDocument()
    {
        return $this->getParameter('document');
    }
    public function getAmount()
    {
        return $this->getParameter('amount');
    }
    public function getBankAccount()
    {
        return $this->getParameter('bankAccount');
    }
}
