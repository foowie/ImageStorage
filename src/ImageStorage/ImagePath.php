<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class ImagePath extends \Nette\Object {

	private $baseUrl;
	private $basePath;

	function __construct($basePath, $baseUrl, \Nette\Http\IRequest $request) {
		$this->baseUrl = substr($request->getUrl()->getBaseUrl(), 0, -1) . $baseUrl;
		$this->basePath = realpath($basePath);
		if ($this->basePath === false)
			throw new \Nette\InvalidStateException("Invalid base path '$basePath'!");
	}

	public function getUrl(\Nette\Database\Table\ActiveRow $image) {
		return $this->baseUrl . $image->directory . '/' . $image->name;
	}

	public function getBaseUrl() {
		return $this->baseUrl;
	}

	public function getPath(\Nette\Database\Table\ActiveRow $image, $pathOnly = false) {
		return $this->basePath . '/' . $image->directory . '/' . ($pathOnly ? '' : $image->name);
	}

	public function getBasePath() {
		return $this->basePath . '/';
	}

	/**
	 * @return \Nette\Image
	 */
	public function getImage(\Nette\Database\Table\ActiveRow $image) {
		return \Nette\Image::fromFile($this->getPath($image));
	}


}
