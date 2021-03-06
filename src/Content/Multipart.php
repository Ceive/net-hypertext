<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 08.01.2016
 * Time: 16:18
 */
namespace Ceive\Net\Hypertext\Content {
	
	use Ceive\Net\Hypertext\ContentInterface;
	use Ceive\Net\Hypertext\Document;
	use Ceive\Net\Hypertext\DocumentInterface;
	use Ceive\Net\Hypertext\Header;
	use Ceive\Net\Hypertext\HeaderRegistryInterface;
	
	use Ceive\Net\Stream\Memory;
	use Ceive\Value\Helper\HelperString;
	
	/**
	 * Class PartitionCollection
	 * @package Ceive\HeaderCover
	 */
	class Multipart implements ContentInterface{

		/** @var string  */
		protected $content_type = 'multipart/mixed';

		/** @var  int|null */
		protected $content_length;

		/** @var  string|null */
		protected $content;

		/** @var  bool  */
		protected $content_parsed = false;


		/** @var  string */
		protected $boundary;

		/** @var  DocumentInterface[] */
		protected $partitions = [];

		/**
		 * Multipart constructor.
		 * @param null $content
		 * @param HeaderRegistryInterface $headers
		 */
		public function __construct($content = null, HeaderRegistryInterface $headers = null){
			if($content!==null){
				$this->parse($content, $headers);
			}
		}

		/**
		 * @param $content
		 * @param HeaderRegistryInterface $headers
		 * @return void
		 * @throws \Exception
		 */
		public function parse($content, HeaderRegistryInterface $headers){

			$contentType = $headers->getHeader('Content-Type');
			$contentType = Header::parseHeaderValue($contentType);

			if(!isset($contentType['params']['boundary'])){
				throw new \Exception('Boundary not found on parse Multipart');
			}

			$this->content_type = $contentType['value'];
			$this->boundary = $contentType['params']['boundary'];

			if(is_string($content)){
				$content = new Memory($content);
			}
			$part = null;
			$boundary = "--{$this->boundary}";
			$string = '';
			while(!$content->isEof()){
				$line = $content->readLine();
				if($line===false){
					break;
				}
				$string.=$line;
				if($part === null){
					$line = trim($line);
					if($line===$boundary){
						$part='';
					}
				}else{
					$trimmedLine = trim($line);
					if($trimmedLine === $boundary){
						$part = new Document($part);
						$this->addPart($part);
						$part = '';
					}elseif($trimmedLine === "--{$this->boundary}--"){
						break;
					}else{
						$part.=$line;
					}
				}
			}
			$this->content = $string;
			$this->content_length = strlen($string);
		}

		/**
		 * @param null $multipartType
		 * @return $this
		 */
		public function setMultipartType($multipartType = null){
			$multipartType = $multipartType!==null?$multipartType:'mixed';
			$this->content_type = "multipart/$multipartType";
			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getContentType(){
			return $this->content_type;
		}

		/**
		 * @return mixed
		 */
		public function getContentLength(){
			$this->__toString();
			return $this->content_length;
		}

		/**
		 * @param $boundary
		 * @param null|false|string $prefix
		 * @return $this
		 * @throws \Exception
		 */
		public function setBoundary($boundary = null, $prefix = null){
			if($boundary!==null){
				if(!$boundary){
					throw new \Exception('Boundary must not be empty string');
				}
				if($prefix===null){
					$prefix = '-----';
				}elseif(is_bool($prefix)){
					$prefix = '';
				}
				$boundary = $prefix.$boundary;
			}
			$this->boundary = $boundary;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getBoundary(){
			if(!$this->boundary){
				$this->boundary = '-----'.base64_encode(uniqid('',true));
			}
			return $this->boundary;
		}



		/**
		 * @param DocumentInterface $document
		 * @return $this
		 */
		public function addPart(DocumentInterface $document){
			$this->partitions[] = $document;
			return $this;
		}

		/**
		 * @param DocumentInterface $document
		 * @return $this
		 */
		public function removePart(DocumentInterface $document){
			$keys = array_keys($this->partitions,$document,true);
			foreach($keys as $key){
				array_splice($this->partitions,$key,1);
			}
			return $this;
		}

		public function getPart($index){

		}

		/**
		 * @param $name
		 * @param bool|false $many
		 * @return DocumentInterface[]|DocumentInterface|null
		 */
		public function getPartByDispositionName($name, $many = false){
			$parts = [];
			foreach($this->partitions as $part){
				$disposition = $part->getHeader('Content-Disposition',null);
				if(isset($disposition)){
					$disposition = Header::parseHeaderValue($disposition);
					if(isset($disposition['params']['name']) && $disposition['params']['name'] === $name){
						if($many){
							$parts[] = $part;
						}else{
							return $part;
						}
					}
				}
			}
			return $many?$parts:null;
		}


		/**
		 * @param $prefix
		 * @return DocumentInterface[]
		 */
		public function getPartsByDispositionNamePrefix($prefix){
			$parts = [];
			foreach($this->partitions as $part){
				$disposition = $part->getHeader('Content-Disposition',null);
				if(isset($disposition)){
					$disposition = Header::parseHeaderValue($disposition);
					if(isset($disposition['params']['name']) && HelperString::startWith($prefix, $disposition['params']['name'])){
						$parts[] = $part;
					}
				}
			}
			return $parts;
		}

		/**
		 * @param HeaderRegistryInterface $headerRegistry
		 * @param Document\WriteProcessor $writer
		 * @return mixed
		 */
		public function beforeHeadersRender(HeaderRegistryInterface $headerRegistry, Document\WriteProcessor $writer){
			$headerRegistry->mergeHeader('Content-Type',[
				'value' => $this->getContentType(),
				'params' => [
					'boundary' => $this->getBoundary(),
				],
			]);
			$contentLength = $this->getContentLength();
			if($contentLength!==null){
				$headerRegistry->setHeader('Content-Length', $contentLength, true);
			}
		}

		/**
		 * @param HeaderRegistryInterface $headerRegistry
		 * @return bool
		 */
		public static function checkHeadersBeforeParse(HeaderRegistryInterface $headerRegistry){
			if($headerRegistry->haveHeader('Content-Type','multipart')){
				return true;
			}
			return false;
		}




		/**
		 * @param $content
		 * @return $this
		 */
		public function setString($content){
			$this->content = $content;
			$this->content_length = strlen($content);
			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getString(){
			return $this->__toString();
		}


		/**
		 * @see getPreparedContent
		 * @return mixed
		 */
		public function __toString(){
			if($this->content === null){
				$boundary = $this->getBoundary();
				$rows = [];
				foreach($this->partitions as $document){
					$rows[] = (string)$document;
				}
				$content = '';
				if($rows){
					$content.= "--{$boundary}\r\n";
					$content.= implode("\r\n--{$boundary}\r\n", $rows);
					$content.= "\r\n--{$boundary}--\r\n";
				}
				$this->content = $content;
				$this->afterRender();
			}
			return $this->content;
		}

		/**
		 *
		 */
		protected function afterRender(){
			$this->content_length = strlen($this->content);
		}


	}
}

