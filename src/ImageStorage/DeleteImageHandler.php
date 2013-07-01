<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class DeleteImageHandler extends \Nette\Object implements \Messaging\IHandler {

	/** @var \ImageStorage\Repository */
	protected $repository;

	/** @var \ImageStorage\ImagePath */
	protected $imagePath;

	function __construct(\ImageStorage\Repository $repository, \ImageStorage\ImagePath $imagePath) {
		$this->repository = $repository;
		$this->imagePath = $imagePath;
	}

	/**
	 * @param \ImageStorage\DeleteImageCommand $message
	 */
	public function handle($message) {
		if($message->getId() === null || $message->getId() === false) {
			return;
		}

		$id = ($message->getId() instanceof \Nette\Database\Table\ActiveRow) ? $message->getId()->id : $message->getId();

		$imageEntity = $this->repository->find($id);

		if($imageEntity !== false) {
			try {
				$path = $this->imagePath->getPath($imageEntity);
				if (file_exists($path)) {
					if (!@unlink($path)) {
						\Nette\Diagnostics\Debugger::log("Cant delete file $path", \Nette\Diagnostics\Debugger::WARNING);
					}
				}
			} catch(\Nette\InvalidStateException $e) {
			}

			$this->repository->delete($id);
		}
	}

}
