<?php

/*
 * This file is part of the Integrated package.
 *
 * (c) e-Active B.V. <integrated@e-active.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Integrated\Bundle\ChannelBundle\Event;

use Integrated\Common\Channel\Connector\Config\OptionsInterface;
use Symfony\Component\EventDispatcher\Event;

class ConnectorCalledEvent extends Event
{
    /**
     * @var string
     */
    const CONNECTOR = 'integrated_channel.connector';

    /**
     * @var string
     */
    private $connector;

    /**
     * @var string
     */
    private $connectorName;

    /**
     * @var mixed
     */
    private $response;

    /**
     * @var OptionsInterface
     */
    private $options;

    /**
     * @var string
     */
    private $controllerAction;

    /**
     * @param string $connector
     * @param string $connectorName
     * @param OptionsInterface $options
     * @param string $controllerAction
     */
    public function __construct($connector, $connectorName, OptionsInterface $options, $controllerAction)
    {
        $this->connector = $connector;
        $this->connectorName = $connectorName;
        $this->options = $options;
        $this->controllerAction = $controllerAction;
    }

    /**
     * @return string
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * @return string
     */
    public function getConnectorName()
    {
        return $this->connectorName;
    }

    /**
     * @param $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getControllerAction()
    {
        return $this->controllerAction;
    }
}
