<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CreateImageHandler extends \Nette\Object implements \Messaging\IHandler {

	/** @var \ImageStorage\Repository */
	protected $repository;

	/** @var \ImageStorage\ImagePath */
	protected $imagePath;

	function __construct(\ImageStorage\Repository $repository, \ImageStorage\ImagePath $imagePath) {
		$this->repository = $repository;
		$this->imagePath = $imagePath;
	}

	/**
	 * @param \ImageStorage\CreateImageCommand $message
	 */
	public function handle($message) {
		$imageEntity = $this->repository->create(array(
			'name' => '',
			'directory' => '',
			'width' => $message->getImage()->getWidth(),
			'height' => $message->getImage()->getHeight(),
		));

		$imageEntity = $this->repository->update($imageEntity->id, array(
			'name' => $imageEntity->id . $this->getFileExtension($message->getName(), true),
			'directory' => (int) ($imageEntity->id / 10000),
		));

		$path = $this->imagePath->getPath($imageEntity, true);
		if (!file_exists($path)) {
			if(!@mkdir($path, 0777, true)) {
				throw new \Nette\InvalidStateException('Cant create image directory ' . $path);
			}
		}

		if(!$message->getImage()->save($this->imagePath->getPath($imageEntity))) {
			throw new \Nette\InvalidStateException('Cant save image!');
		}

		return $imageEntity->id;
	}

	protected function getFileExtension($fileName, $withDot = false) {
		$dot = strrpos($fileName, '.');
		return ($dot === false) ? '' : substr($fileName, $withDot ? $dot : $dot + 1);
	}

}
