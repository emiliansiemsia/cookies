<?php

namespace Mill\DemoBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Cookie;


class CookiesListener implements EventSubscriberInterface {

	/* storage for encrypted cookies names */
	private $cookies;

	/* openssl_encrypt / decrypt vars */
	private $encrypter;
	private $password;
	private $iv;

	public static function getSubscribedEvents() {
		return [
			KernelEvents::CONTROLLER => 'onKernelController',
		];
	}

	public function __construct($cookies, $encrypter, $password, $iv) {
		$this->cookies = $cookies;
		$this->encrypter = $encrypter;
		$this->password = $password;
		$this->iv = $iv;
	}

	public function onKernelResponse(FilterResponseEvent $event) {

		$response = $event->getResponse();
		$request = $event->getRequest();

		foreach ($this->cookies as $cookie_name) {
			if ($request->cookies->has($cookie_name)) {
				$cookie = $request->cookies->get($cookie_name);
				$response->headers->removeCookie($cookie_name);
				$response->headers->setCookie(new Cookie(
						$cookie_name,
						openssl_encrypt($cookie, 
								$this->encrypter, 
								$this->password, 0, 
								$this->iv)));
			}
		}
	}

	public function onKernelRequest(GetResponseEvent $event) { 
		$request = $event->getRequest();

		foreach ($this->cookies as $cookie_name) {
			if ($request->cookies->has($cookie_name)) {
				$request->cookies->set($cookie_name, 
					openssl_decrypt($request->cookies->get($cookie_name), 
							$this->encrypter, 
							$this->password, 0, 
							$this->iv));
			}
		}

	}
}
