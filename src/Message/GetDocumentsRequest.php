<?php

namespace Omnipay\Juno\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class GetDocumentsRequest extends AbstractRequest
{

    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return 'documents';
    }

    public function getData()
    {
        $this->validate('resourceToken');

        $data = [];
    }
}