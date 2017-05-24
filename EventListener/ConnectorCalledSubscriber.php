<?php
/*
 * This file is part of the Integrated package.
 *
 * (c) e-Active B.V. <integrated@e-active.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Integrated\Bundle\ChannelBundle\EventListener;

use Exception;

use Integrated\Bundle\ChannelBundle\Event\ConnectorCalledEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Integrated\Bundle\SocialBundle\Social\Twitter\Oauth as Twitter;
use Integrated\Bundle\SocialBundle\Social\Facebook\Oauth as Facebook;

class ConnectorCalledSubscriber implements EventSubscriberInterface
{
    /**
     * @var Twitter $twitter
     */
    private $twitter;

    /**
     * @var Facebook $facebook
     */
    private $facebook;

    public function __construct(Twitter $twitter, Facebook $facebook)
    {
        $this->twitter = $twitter;
        $this->facebook = $facebook;
    }


    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [ConnectorCalledEvent::CONNECTOR => 'onConnectorCalled'];
    }

    /**
     * @param ConnectorCalledEvent $event
     * @return string|void
     * @throws Exception
     */
    public function onConnectorCalled(ConnectorCalledEvent $event)
    {
        $connector = $event->getConnector();
        $connectorName = $event->getConnectorName();

        $socialConnectors = array("twitter", "facebook");

        if (!in_array($connector, $socialConnectors, true)) {
            return null;
        }

        if ($event->getControllerAction() == "newAction") {
            switch ($connector) {
                case "twitter":
                    $event->setResponse($this->twitter->login($connectorName, "admin"));
                    break;
                case "facebook":
                    $event->setResponse($this->facebook->login($connectorName, "admin"));
                    break;
            }
        } elseif ($event->getControllerAction() == "editAction") {
            $options = $event->getOptions();
            switch ($connector) {
                case "twitter":
                    if (empty($options->get("token")) && empty($options->get("token_secret"))) {
                        $callback = $this->twitter->callback($options, $connectorName);
                        if ($callback == false) {
                            $event->setResponse($this->twitter->login($connectorName, "admin"));
                        } else {
                            $token = $callback;
                            $options->set("token", $token["oauth_token"]);
                            $options->set("token_secret", $token["oauth_token_secret"]);

                            $event->setResponse("Twitter token set");
                        }
                    }
                    break;
                case "facebook":
                    if (empty($options->get("user_id")) && empty($options->get("access_token"))) {
                        $token = $this->facebook->callback($options);
                        if ($token == Exception::class) {
                            $event->setResponse($token);
                        } else {
                            $options->set("user_id", $token["user_id"]);
                            $options->set("access_token", $token["access_token"]);
                            $event->setResponse("Facebook token set");
                        }
                    }
                    break;
            }
        }

        return "No social connector";
    }
}
