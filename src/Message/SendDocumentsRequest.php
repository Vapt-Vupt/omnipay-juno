<?php

namespace Omnipay\Juno\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class SendDocumentsRequest extends AbstractRequest
{

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getEndpoint()
    {
        return "documents/{$this->getId()}/files";
    }

    public function getContentType()
    {
        return 'multipart/form-data';
    }

    public function getData()
    {
        $this->validate('resourceToken');

        $data = [];
        
        $data['files'] = $this->getFiles();

        return $data;
    }

    public function getId()
    {
        return $this->getParameter('id');
    }

    public function setId($value)
    {
        return $this->setParameter('id', $value);
    }

    public function getFiles()
    {
        return $this->getParameter('files');
    }

    public function setFiles($value)
    {
        return $this->setParameter('files', $value);
    }
}
