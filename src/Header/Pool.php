<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 10.01.2016
 * Time: 0:22
 */
namespace Ceive\Net\Hypertext\Header {
	
	use Ceive\Data\Keword\Keword;
	use Ceive\Net\Hypertext\Header;
	use Ceive\Data\Keword\Factory;
	use Ceive\Data\Keword\Pool as KeyPool;
	use Ceive\Data\Keword\Storage\Dummy;
	use Ceive\Value\Helper\HelperString;
	
	/**
	 * Class Pool
	 * @package Ceive\HeaderCover\Header
	 */
	class Pool extends KeyPool{

		/** @var bool */
		protected $dummy_allowed = true;

		/** @var bool */
		protected $case_insensitive = true;

		/** @var Pool */
		protected static $default_manager = null;

		/**
		 * @return Pool
		 */
		public static function getDefault(){
			if(!self::$default_manager){
				self::$default_manager = new self('RFCHeaderPool',new Dummy());
			}
			return self::$default_manager;
		}

		/**
		 * @param Pool $manager
		 */
		public static function setDefault(Pool $manager){
			self::$default_manager = $manager;
		}

		/**
		 * @param $header
		 * @return string
		 */
		public static function getConcreteClassNameByHeader($header){
			return __NAMESPACE__.'\\Concrete\\'.(HelperString::camelize((string)$header));
		}

		/**
		 * @return Factory
		 */
		public function getFactory(){
			if(!$this->factory){
				$this->factory = new Factory(function($identifier){
					$className = self::getConcreteClassNameByHeader($identifier);
					if(class_exists($className) && is_a($className,__NAMESPACE__,true)){
						return new $className();
					}
					return new Header();
				});
			}
			return parent::getFactory();
		}

		/**
		 * @param $identifier
		 * @return bool
		 */
		public function exists($identifier){
			$className = self::getConcreteClassNameByHeader($identifier);
			return parent::exists($identifier) || class_exists($className);
		}


		/**
		 * @param string $key
		 * @return Keword
		 */
		public function get($key){
			return parent::get(Header::normalize($key));
		}


	}
}

