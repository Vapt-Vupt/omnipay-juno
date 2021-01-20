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

        /**
         * multipart/form-data processing
         */
        if (strcasecmp($headers['Content-Type'], 'multipart/form-data') === 0) {
            $body = $this->buildMultipartFormData($data['files'], $headers['Content-Type']);
        }

        /**
         * application/json processing
         */
        if (strcasecmp($headers['Content-Type'], 'application/json;charset=UTF-8') === 0) {
            $body = $data ? json_encode($data) : null;
        }

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

    /**
    * Build multipart/form-data
    *
    * @param array @$data Data
    * @param array @$files 1-D array of files where key is field name and value if file contents
    * @param string &$contentType Retun variable for content type
    *
    * @return string Encoded data
    */
    private function buildMultipartFormData(array $files, &$contentType)
    {
       $eol = "\r\n";
       
       // Build test string
       $testStr = '';
       
       // Get file content type and content.  Add to test string
       $finfo = finfo_open(FILEINFO_MIME_TYPE);
       $fileContent = array();
       $fileContentTypes = array();
       $fileFullName = array();
       foreach ($files as $key=>$filename)
       {
           $fileContent[$key] = file_get_contents($filename);
           $fileContentTypes[$key] = finfo_file($finfo, $filename);
           $fileFullName[$key] = basename($filename) . '.' . explode('/', $fileContentTypes[$key])[1];
           $testStr .= $key . $eol . $fileContentTypes[$key] . $eol. $fileContent[$key] . $eol;
       }
       finfo_close($finfo);
       
       // Find a boundary not present in the test string
       $boundaryLen = 6;
       $alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
       do {
           $boundary = '--';
           for ($i = 0; $i < $boundaryLen; $i++)
           {
               $c = rand(0, 61);
               $boundary .= $alpha[$c];
           }
           
           // Check test string
           if (strpos($testStr, $boundary) !== false)
           {
               $boundary = null;
               $boundaryLen++;
           }
       } while ($boundary === null);
       unset($testStr);
       
       // Set content type
       $contentType = 'multipart/form-data;charset=UTF-8;boundary='.$boundary;
       
       // Build data
       $rtn = '';

       // Add files
       foreach ($files as $key=>$filename)
       {
           $rtn .= '--' . $boundary . $eol . 'Content-Disposition: form-data; name="files"; filename="'. $fileFullName[$key] . '"' . $eol
               . 'Content-Type: ' . $fileContentTypes[$key] . $eol
               . 'Content-Transfer-Encoding: binary' . $eol . $eol
               . $fileContent[$key] . $eol;
       }
       
       return $rtn . '--' . $boundary . '--' . $eol;
   }
}
