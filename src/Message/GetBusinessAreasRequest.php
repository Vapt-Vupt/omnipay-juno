<?php

namespace Omnipay\Juno\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class GetBusinessAreasRequest extends AbstractRequest
{

    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return 'data/business-areas';
    }

    public function getData()
    {
        return [];
    }
}