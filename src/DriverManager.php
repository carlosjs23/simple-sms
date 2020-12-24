<?php

namespace SimpleSoftwareIO\SMS;

use GuzzleHttp\Client;
use Illuminate\Support\Manager;
use SimpleSoftwareIO\SMS\Drivers\LabsMobileSMS;
use SimpleSoftwareIO\SMS\Drivers\LogSMS;

class DriverManager extends Manager
{
    /**
     * Get the default sms driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->container['config']['sms.driver'];
    }

    /**
     * Set the default sms driver name.
     *
     * @param string $name
     */
    public function setDefaultDriver(string $name)
    {
        $this->container['config']['sms.driver'] = $name;
    }

    /**
     * Create an instance of the Log driver.
     *
     * @return LogSMS
     */
    protected function createLogDriver()
    {
        $provider = new LogSMS($this->container['log']);

        return $provider;
    }

    /**
     * Create an instance of the LabsMobile driver.
     *
     * @return LabsMobileSMS
     */
    protected function createLabsMobileDriver()
    {
        $config = $this->container['config']->get('sms.labsmobile', []);

        $provider = new LabsMobileSMS(new Client());

        $auth = [
            'username' => $config['username'],
            'password' => $config['password'],
        ];

        $provider->buildBody($auth);

        return $provider;
    }
}
