<?php

namespace Omnipay\Juno;

use Omnipay\Juno\Message;

/**
 * Gateway Class
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Juno';
    }

    /**
     * @param array $parameters
     * @return CreateDigitalAccountRequest
     */
    public function createDigitalAccount(array $parameters = array())
    {
        return $this->createRequest(
            Message\CreateDigitalAccountRequest::class,
            $parameters
        );
    }

    /**
     * @param array $parameters
     * @return FindDigitalAccountRequest
     */
    public function findDigitalAccount(array $parameters = array())
    {
        return $this->createRequest(
            Message\FindDigitalAccountRequest::class,
            $parameters
        );
    }

    /**
     * @param array $parameters
     * @return UpdateDigitalAccountRequest
     */
    public function updateDigitalAccount(array $parameters = array())
    {
        return $this->createRequest(
            Message\UpdateDigitalAccountRequest::class,
            $parameters
        );
    }

    /**
     * @param array $parameters
     * @return GetBanksRequest
     */
    public function getBanks(array $parameters = array())
    {
        return $this->createRequest(
            Message\GetBanksRequest::class,
            $parameters
        );
    }

    /**
     * @param array $parameters
     * @return GetCompanyTypesRequest
     */
    public function getCompanyTypes(array $parameters = array())
    {
        return $this->createRequest(
            Message\GetCompanyTypesRequest::class,
            $parameters
        );
    }

    /**
     * @param array $parameters
     * @return GetBusinessAreasRequest
     */
    public function getBusinessAreas(array $parameters = array())
    {
        return $this->createRequest(
            Message\GetBusinessAreasRequest::class,
            $parameters
        );
    }

    /**
     * @param array $parameters
     * @return GetDocumentsRequest
     */
    public function getDocuments(array $parameters = array())
    {
        return $this->createRequest(
            Message\GetDocumentsRequest::class,
            $parameters
        );
    }
    
    /**
     * @param array $parameters
     * @return SendDocumentsRequest
     */
    public function sendDocuments(array $parameters = array())
    {
        return $this->createRequest(
            Message\SendDocumentsRequest::class,
            $parameters
        );
    }
}
