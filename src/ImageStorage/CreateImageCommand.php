<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CreateImageCommand extends \Nette\Object implements \Messaging\ICommand {

	/** @var \Nette\Image */
	protected $image;

	/** @var string */
	protected $name;

	function __construct(\Nette\Image $image, $name) {
		$this->image = $image;
		$this->name = $name;
	}

	/**
	 * @return \Nette\Image
	 */
	public function getImage() {
		return $this->image;
	}

	public function getName() {
		return $this->name;
	}

}
