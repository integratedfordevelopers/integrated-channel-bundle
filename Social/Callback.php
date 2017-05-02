<?php
/*
 * This file is part of the Integrated package.
 *
 * (c) e-Active B.V. <integrated@e-active.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Integrated\Bundle\ChannelBundle\Social;

use Integrated\Bundle\SocialBundle\Social\Twitter\Oauth as Twitter;
use Integrated\Bundle\SocialBundle\Social\Facebook\Oauth as Facebook;

class Callback
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

    public function twitter($connectorName)
    {
        $twitter_callback = $this->twitter->callback();

        if ($twitter_callback !== false) {
            return $twitter_callback;
        } else {
            return $this->twitter->login($connectorName, "admin");
        }
    }

    public function facebook()
    {
        $facebook_callback = $this->facebook->callback();

        return $facebook_callback;
    }
}