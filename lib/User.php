<?php

namespace pCloud\Sdk;

use stdClass;

/**
 * User class
 *
 * @package pCloud\Sdk
 */
class User
{

    /** @var Request $request Holds the Request class. */
    private Request $request;

    /** @var stdClass $userInfo User info object. */
    private stdClass $userInfo;

    /**
     * Class constructor.
     *
     * @param App $app The App class.
     * @throws Exception
     */
    function __construct(App $app)
    {
        $this->request = new Request($app);
        $this->userInfo = $this->request->get("userinfo");
    }

    /**
     * Get the full user info.
     *
     * @return stdClass
     * @noinspection PhpUnused
     */
    public function getUserInfo(): stdClass
    {
        return $this->userInfo;
    }

    /**
     * Get the user ID.
     *
     * @return int
     * @noinspection PhpUnused
     */
    public function getUserId(): int
    {
        return intval($this->userInfo->userid);
    }

    /**
     * Get the user email address.
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function getUserEmail(): string
    {
        return strval($this->userInfo->email);
    }

    /**
     * Get the used quota.
     *
     * @return int
     * @noinspection PhpUnused
     */
    public function getUsedQuota(): int
    {
        return intval($this->userInfo->usedquota);
    }

    /**
     * Get quota.
     *
     * @return int
     * @noinspection PhpUnused
     */
    public function getQuota(): int
    {
        return intval($this->userInfo->quota);
    }

    /**
     * Get user's public links quota.
     *
     * @return int
     * @noinspection PhpUnused
     */
    public function getPublicLinkQuota(): int
    {
        return intval($this->userInfo->publiclinkquota);
    }
}