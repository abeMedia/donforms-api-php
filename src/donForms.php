<?php

namespace donForms;

class donForms
{
    protected $secret;
    protected $api_endpoint = 'https://forms.donlabs.com/';

	/**
	 * @var    Http  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $client;
    
    /*
     * Create a new instance
     * @param string $secret Your donForms API secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
        $this->client = new HttpClient();
        $this->client->addHeader('Authorization: Secret ' . $secret);
    }    

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  Object  donForms API object (users, forms or data).
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException If $name is not a valid sub class.
	 */
	public function __get($name)
	{
		$class = 'donForms\\donForms\\' . ucfirst($name);

		if (class_exists($class))
		{
			if (false == isset($this->$name))
			{
				$this->$name = new $class($this->secret);
			}

			return $this->$name;
		}

		throw new \InvalidArgumentException(sprintf('Argument %s produced an invalid class name: %s', $name, $class));
	}
}