<?php
/**
 * Created by Kutuzov Alexey Konstantinovich <lexus.1995@mail.ru>.
 * Author: Kutuzov Alexey Konstantinovich <lexus.1995@mail.ru>
 * Project: Ceive
 * IDE: PhpStorm
 * Date: 05.10.2016
 * Time: 19:11
 */
namespace Ceive\Net\Hypertext\Document {
	
	use Ceive\Net\Hypertext\DocumentInterface;
	use Ceive\Net\Stream\StreamInteractionInterface;
	use Ceive\Util\Buffer\BufferInterface;
	
	/**
	 * Interface ProcessorInterface
	 * @package Ceive\Util\Communication\Hypertext\Document
	 */
	interface ProcessorInterface{

		/**
		 * @param DocumentInterface $document
		 * @return $this
		 */
		public function setDocument(DocumentInterface $document);

		/**
		 * @return DocumentInterface
		 */
		public function getDocument();


		/**
		 * @param bool|true $auto_close
		 * @return $this
		 */
		public function setSourceAutoClose($auto_close = true);

		/**
		 * @return boolean
		 */
		public function isSourceAutoClose();



		/**
		 * @param bool|true $auto_close
		 * @return $this
		 */
		public function setSourceAutoConnect($auto_close = true);

		/**
		 * @return boolean
		 */
		public function isSourceAutoConnect();

		/**
		 * @return bool
		 */
		public function isSourceStreamInteraction();


		/**
		 * @param array $config
		 * @param bool|false $merge
		 * @return mixed
		 */
		public function setConfig(array $config, $merge = false);


		/**
		 * @return bool
		 */
		public function isCompleted();

		/**
		 * @param $source
		 * @return mixed
		 */
		public function process($source);

		/**
		 * @return StreamInteractionInterface|string|null
		 */
		public function getSource();

		/**
		 *
		 */
		public function setBufferToString();

		/**
		 * @param BufferInterface $buffer
		 */
		public function setBuffer(BufferInterface $buffer = null);

		/**
		 * @return string|BufferInterface|null
		 */
		public function getBuffer();

		/**
		 * @return string|null
		 */
		public function getBuffered();

	}
}

