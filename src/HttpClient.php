<?php

namespace donForms;

/**
 * A simple Curl wrapper providing RESTful HTTP requests
 *
 */
class HttpClient
{
    private $handler;
    private $connectionRetryAttempts;
    private $connectionRetryPause;
    private $verify_ssl;
    private $header;
    
    /**
     * Constructor
     *
     * @param type $retryAttempts
     * @param type $retryPause
     */
    public function __construct($retryAttempts = 1, $retryPause = 1, $verify_ssl = false)
    {
        $this->connectionRetryAttempts = $retryAttempts;
        $this->connectionRetryPause = $retryPause;
        $this->verify_ssl = $verify_ssl;
    }
    
    public function __call($method, $args) 
    {
        $url = $args[0];
        $params = (isset($args[1]) ? $args[1] : false);
        
        if (!in_array($method, array('get', 'post', 'put', 'patch', 'delete'))) {
            throw new \Exception("Invalid method: $method");
        }
        
        if (gettype($this->handler) != 'resource') {
            if (in_array('curl', get_loaded_extensions())) {
                $this->handler = curl_init();
                curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
                curl_setopt($this->handler, CURLOPT_USERAGENT, "donForms/1.0");
                curl_setopt($this->handler, CURLOPT_AUTOREFERER, true);
                curl_setopt($this->handler, CURLOPT_COOKIE, "");
                curl_setopt($this->handler, CURLOPT_COOKIEFILE, tempnam("/tmp", "cookie"));
                curl_setopt($this->handler, CURLOPT_SSLVERSION , 3);
            } else {
                throw new \Exception("Missing CURL extension. Check your PHP configuration.");
            }
        }
        
        curl_setopt($this->handler, CURLOPT_URL, $url);
        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $this->header);
        
        if($params) {
            curl_setopt($this->handler, CURLOPT_POSTFIELDS, $params);
        }
        
        switch ($method) {
            case 'get':
                break;
            case 'post':
                curl_setopt($this->handler, CURLOPT_POST, true);
                break;
            case 'patch':
            case 'put':
            case 'delete':
                curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                break;
        }
        return json_decode($this->execute()->content);
    }
    
    /**
     * Sets the number of retries for connection failures
     *
     * @param int $attemtps
     */
    public function setConnectionRetryAttempts($attemtps)
    {
        if (!ctype_digit($attemtps)) {
            throw new \Exception("Invalid parameter, setConnectionRetryAttempts expects an integer ");
        }
        $this->setConnectionRetryAttempts($attemtps);
        return $this;
    }
    
    /**
     * Sets the pause time in seconds between connection attempts
     *
     * @param int $seconds
     */
    public function setConnectionRetryPause($seconds)
    {
        if (!ctype_digit($seconds)) {
            throw new \Exception("Invalid parameter, setConnectionRetryPause expects an integer ");
        }
        $this->setConnectionRetryPause($seconds);
        return $this;
    }
    
    /**
     * Add a new header
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function addHeader($header)
    {
        $this->header[] = $header;
    }
    
    /**
     * Closes the connection and returns the response as an stdClass
     *
     * @return \stdClass
     * @throws \Exception
     */
    private function execute($attempt = 1)
    {
        $result = curl_exec($this->handler);
        if (($result === false || curl_getinfo($this->handler, CURLINFO_HTTP_CODE) != 200)) {
            if ($attempt <= $this->connectionRetryAttempts) {
                sleep($this->connectionRetryPause);
                $this->execute($attempt+1);
            } else {
                throw new \Exception(sprintf(
                    "Error %s: %s %s",
                    curl_errno($this->handler),
                    curl_error($this->handler),
                    $result
                ));
            }
        }
        $response = new \stdClass();
        $response->code = curl_getinfo($this->handler, CURLINFO_HTTP_CODE);
        $response->info = (object) curl_getinfo($this->handler);
        $response->content = $result;
        curl_close($this->handler);
        return $response;
    }
}