<?php
/**
 * Created by Kutuzov Alexey Konstantinovich <lexus.1995@mail.ru>.
 * Author: Kutuzov Alexey Konstantinovich <lexus.1995@mail.ru>
 * Project: Ceive
 * IDE: PhpStorm
 * Date: 30.06.2016
 * Time: 13:10
 */
namespace Ceive\Net\Hypertext {

	/**
	 * Interface HeaderInterface
	 * @package Ceive\Net\Hypertext
	 */
	interface HeaderInterface{



		/**
		 * @return mixed
		 */
		public function getName();

		/**
		 * @param $name
		 * @return mixed
		 */
		public function setName($name);


	}
}

