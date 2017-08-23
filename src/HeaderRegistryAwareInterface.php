<?php
/**
 * Created by Kutuzov Alexey Konstantinovich <lexus.1995@mail.ru>.
 * Author: Kutuzov Alexey Konstantinovich <lexus.1995@mail.ru>
 * Project: Ceive
 * IDE: PhpStorm
 * Date: 05.10.2016
 * Time: 12:29
 */
namespace Ceive\Net\Hypertext {

	/**
	 * Interface HeaderRegistryAwareInterface
	 * @package Ceive\Net\Hypertext
	 */
	interface HeaderRegistryAwareInterface{

		/**
		 * @return HeaderRegistryInterface
		 */
		public function getHeaderRegistry();

		/**
		 * @param HeaderRegistryInterface $header_registry
		 * @return mixed
		 */
		public function setHeaderRegistry(HeaderRegistryInterface $header_registry);

	}
}

