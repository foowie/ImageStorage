<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class ImageExtension extends \Nette\Config\CompilerExtension {

	public $defaults = array(
		'path' => 'images',
		'table' => 'image',
		'handlerTag' => 'handler',
		'validatorTag' => 'validator',
	);

	public function loadConfiguration() {

		$builder = $this->getContainerBuilder();

		$config = $this->getConfig($this->defaults);

		$builder->addDefinition($this->prefix('repository'))->setClass('ImageStorage\Repository')->setArguments(array('@\Nette\Database\Connection', $config['table']));

		$builder->addDefinition($this->prefix('imagePath'))->setClass('ImageStorage\ImagePath', array($this->getContainerBuilder()->parameters['wwwDir'] . '/' . $config['path'], '/'.trim($config['path'], '/').'/'));

		$builder->addDefinition($this->prefix('createImageValidator'))->setClass('ImageStorage\CreateImageValidator')->addTag($config['validatorTag']);
		$builder->addDefinition($this->prefix('createUploadedImageValidator'))->setClass('ImageStorage\CreateUploadedImageValidator')->addTag($config['validatorTag']);

		$builder->addDefinition($this->prefix('createImageHandler'))->setClass('ImageStorage\CreateImageHandler')->addTag($config['handlerTag']);
		$builder->addDefinition($this->prefix('createUploadedImageHandler'))->setClass('ImageStorage\CreateUploadedImageHandler')->addTag($config['handlerTag']);
		$builder->addDefinition($this->prefix('deleteImageHandler'))->setClass('ImageStorage\DeleteImageHandler')->addTag($config['handlerTag']);


		$builder->getDefinition('nette.latte')->addSetup('ImageStorage\ImageMacros::install($service->getCompiler(?))', array(null));

	}

}
