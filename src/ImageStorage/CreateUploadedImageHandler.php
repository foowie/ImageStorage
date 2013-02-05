<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CreateUploadedImageHandler extends \Nette\Object implements \Messaging\IHandler {

	/** @var \Messaging\IMessageBus */
	protected $bus;

	function __construct(\Messaging\IMessageBus $bus) {
		$this->bus = $bus;
	}

	/**
	 * @param \ImageStorage\CreateUploadedImageCommand $message
	 */
	public function handle($message) {
		$image = $message->getUpload()->toImage();
		if($message->getWidth() !== null || $message->getHeight() !== null) {
			$image = $image->resize($message->getWidth(), $message->getHeight(), $message->getFlags());
		}
		$command = new CreateImageCommand($image, $message->getUpload()->getName());
		return $this->bus->send($command);
	}

}
