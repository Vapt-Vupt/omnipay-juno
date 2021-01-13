<?php

/**
 * Stripe Abstract Request.
 */

namespace Omnipay\Juno\Message;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Omnipay\Common\Exception\InvalidRequestException;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveBaseUrl = 'https://api.juno.com.br';
    protected $testBaseUrl = 'https://sandbox.boletobancario.com/api-integration';

    protected $authLiveEndpoint = 'https://api.juno.com.br/authorization-server/oauth/token';
    protected $authTestEndpoint = 'https://sandbox.boletobancario.com/authorization-server/oauth/token';
    protected $authContentType = 'application/x-www-form-urlencoded';
    protected $authAccept = 'application/json;charset=UTF-8';

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getClientSecret()
    {
        return $this->getParameter('clientSecret');
    }

    public function setClientSecret($value)
    {
        return $this->setParameter('clientSecret', $value);
    }

    public function getJunoVersion()
    {
        return $this->getParameter('junoVersion');
    }

    public function setJunoVersion($value)
    {
        return $this->setParameter('junoVersion', $value);
    }

    public function getResourceToken()
    {
        return $this->getParameter('resourceToken');
    }

    public function setResourceToken($value)
    {
        return $this->setParameter('resourceToken', $value);
    }

    public function getContentType()
    {
        return 'application/json;charset=UTF-8';
    }

    public function getAccept()
    {
        return 'application/json;charset=UTF-8';
    }

    public function getBaseUrl()
    {
        if ($this->getTestMode())
        {
            return $this->testBaseUrl;
        }
        return $this->liveBaseUrl;
    }

    private function getAuthEndpoint()
    {
        if ($this->getTestMode())
        {
            return $this->authTestEndpoint;
        }
        return $this->authLiveEndpoint;
    }

    private function getBasicAuthorization()
    {
        return 'Basic ' . base64_encode($this->getClientId() . ':' . $this->getClientSecret());
    }

    private function getAuthorization()
    {
        $cache = new FilesystemAdapter();

        $basicAuthorization = $this->getBasicAuthorization();

        $junoBearerToken = $cache->get($basicAuthorization, function (ItemInterface $item) use ($basicAuthorization) {
            $url = $this->getAuthEndpoint();

            $headers = [
                'Authorization' => $basicAuthorization,
                'Content-Type' => $this->authContentType,
                'Accept' => $this->authAccept,
            ];
    
            $data = ['grant_type' => 'client_credentials'];
            $body = http_build_query($data, '', '&');
    
            $httpResponse = $this->httpClient->request(
                'POST',
                $url,
                $headers,
                $body,
            );
    
            $responseBody = $httpResponse->getBody()->getContents();

            $data = json_decode($responseBody, true);
    
            $item->expiresAfter($data['expires_in']);
        
            return $data['access_token'];
        });

        return 'Bearer ' . $junoBearerToken;
    }

    abstract public function getHttpMethod();

    abstract public function getEndpoint();

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headers = array();

        if ($this->getJunoVersion()) {
            $headers['X-Api-Version'] = $this->getJunoVersion();
        }

        if ($this->getResourceToken()) {
            $headers['X-Resource-Token'] = $this->getResourceToken();
        }

        return $headers;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $url = $this->getBaseUrl() . '/' . $this->getEndpoint();

        $headers = array_merge(
            $this->getHeaders(),
            array('Authorization' => $this->getAuthorization()),
            array('Content-Type' => $this->getContentType()),
            array('Accept' => $this->getAccept()),
        );

        $body = $data ? json_encode($data) : null;

        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $url,
            $headers,
            $body
        );

        return $this->createResponse($httpResponse->getBody()->getContents(), $httpResponse->getHeaders());
    }

    protected function createResponse($data, $headers = [])
    {
        return $this->response = new Response($this, $data, $headers);
    }
}