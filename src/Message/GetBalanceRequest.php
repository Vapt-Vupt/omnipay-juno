<?php

namespace Omnipay\Juno\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class GetBalanceRequest extends AbstractRequest
{

    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return 'balance';
    }

    public function getData()
    {
        $this->validate('resourceToken');

        return [];
    }
}
