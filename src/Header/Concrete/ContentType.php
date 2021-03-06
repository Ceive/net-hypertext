<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 11.01.2016
 * Time: 1:25
 */
namespace Ceive\Util\Communication\Hypertext\Header\Concrete {
	
	use Ceive\Net\Hypertext\Content\Multipart;
	use Ceive\Net\Hypertext\Header;
	use Ceive\Net\Hypertext\Header\Value;
	use Ceive\Net\Hypertext\HeaderRegistryInterface;
	
	/**
	 * Class ContentType
	 * @package Ceive\Util\Communication\Hypertext\Header\Concrete
	 */
	class ContentType extends Header{

		/** @var int  */
		protected $priority_encode = 1001;

		/** @var int  */
		protected $priority_decode = 1002;


		/**
		 * @param Value[] $values
		 * @param $contents
		 * @param HeaderRegistryInterface $headers
		 * @return null|string
		 * @throws \Exception
		 */
		public function decodeContents(array $values, $contents, HeaderRegistryInterface $headers){
			$value = array_pop($values);
			$value = Header::parseHeaderValue($value);
			$modified = false;
			if(isset($value['params']['charset'])){
				if(strcasecmp($value['params']['charset'], 'utf-8')!==0){
					$modified = true;
					$contents = mb_convert_encoding($contents, 'utf-8', $value['params']['charset']);
				}
			}

			if(stripos($value['value'],'multipart')!==false){
				$modified = true;
				$contents = new Multipart($contents,$headers);
			}
			return $modified?$contents:null;
		}



		/**
		 * @param Value[] $values
		 * @param $contents
		 * @param HeaderRegistryInterface $headers
		 * @return null|string
		 * @throws \Exception
		 */
		public function encodeContents(array $values, $contents, HeaderRegistryInterface $headers){
			$value = array_pop($values);
			$value = Header::parseHeaderValue($value);
			$modified = false;
			if(isset($value['params']['charset'])){
				if(strcasecmp($value['params']['charset'], 'utf-8')!==0){
					$modified = true;
					$contents = mb_convert_encoding($contents, $value['params']['charset'], 'utf-8');
				}
			}
			return $modified?$contents:null;
		}


	}
}

