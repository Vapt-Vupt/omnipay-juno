<?php

namespace Omnipay\Juno\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class FindDigitalAccountRequest extends AbstractRequest
{

    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return 'digital-accounts';
    }

    public function getData()
    {
        $this->validate('resourceToken');

        return [];
    }
}