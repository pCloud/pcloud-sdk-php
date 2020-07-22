<?php

namespace pCloud;

class User {

	private $request;
	private $userInfo;

	function __construct(App $app) {
		$this->request = new Request($app);
		$this->userInfo = $this->request->get("userinfo");
	}

	public function getUserInfo() {
		return $this->userInfo;
	}

	public function getUserId() {
		return $this->userInfo->userid;
	}

	public function getUserEmail() {
		return $this->userInfo->email;
	}

	public function getUsedQuota() {
		return $this->userInfo->usedquota;
	}

	public function getQuota() {
		return $this->userInfo->quota;
	}

	public function getPublicLinkQuota() {
		return $this->userInfo->publiclinkquota;
	}
}