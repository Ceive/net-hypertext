<?php
/**
 * Created by Kutuzov Alexey Konstantinovich <lexus.1995@mail.ru>.
 * Author: Kutuzov Alexey Konstantinovich <lexus.1995@mail.ru>
 * Project: Ceive
 * IDE: PhpStorm
 * Date: 05.10.2016
 * Time: 0:35
 */
namespace Ceive\Net\Hypertext {
	
	use Ceive\Net\Hypertext\Document\Processor;
	use Ceive\Net\Hypertext\Document\ReadProcessor;
	use Ceive\Net\Hypertext\Document\WriteProcessor;
	
	
	/**
	 * Interface DocumentInterface
	 * @package Ceive\Net\Hypertext
	 */
	interface DocumentInterface extends HeaderRegistryInterface, ContentAwareInterface{

		/**
		 * @param string $contents
		 * @return string|null
		 */
		public function encodeContents($contents);

		/**
		 * @param string $contents
		 * @return string|null
		 */
		public function decodeContents($contents);

		/**
		 * @param Processor $processor
		 * @return mixed
		 */
		public function beforeProcessStart(Processor $processor);

		/**
		 * @param WriteProcessor $writer
		 * @return void
		 */
		public function beforeWrite(WriteProcessor $writer);

		/**
		 * @param WriteProcessor $writer
		 * @return mixed
		 */
		public function onHeadersWrite(WriteProcessor $writer);

		/**
		 * @param WriteProcessor $writer
		 * @return mixed
		 */
		public function afterWrite(WriteProcessor $writer);

		/**
		 * @param WriteProcessor $writer
		 * @return void
		 */
		public function continueWrite(WriteProcessor $writer);



		/**
		 * @param ReadProcessor $reader
		 * @return void
		 */
		public function beforeRead(ReadProcessor $reader);

		/**
		 * @param $data
		 * @param $readingIndex
		 * @return bool
		 */
		public function beforeHeaderRead($data, $readingIndex);

		/**
		 * @param ReadProcessor $reader
		 * @return void
		 */
		public function onHeadersRead(ReadProcessor $reader);

		/**
		 * @param ReadProcessor $reader
		 * @return void
		 */
		public function afterRead(ReadProcessor $reader);

		/**
		 * @param ReadProcessor $writer
		 * @return mixed
		 */
		public function continueRead(ReadProcessor $writer);


		/**
		 * @return string
		 */
		public function __toString();



	}
}

