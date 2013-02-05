<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CreateUploadedImageCommand extends \Nette\Object implements \Messaging\ICommand {

	/** @var \Nette\Http\FileUpload */
	protected $upload;

	/** @var int */
	protected $width = null;

	/** @var int */
	protected $height = null;

	/** @var int */
	protected $flags;

	function __construct(\Nette\Http\FileUpload $upload, $width = null, $height = null, $flags = \Nette\Image::FIT) {
		$this->upload = $upload;
		$this->width = $width;
		$this->height = $height;
		$this->flags = $flags;
	}

	/**
	 * @return \Nette\Http\FileUpload
	 */
	public function getUpload() {
		return $this->upload;
	}

	public function getWidth() {
		return $this->width;
	}

	public function getHeight() {
		return $this->height;
	}

	public function getFlags() {
		return $this->flags;
	}

}
