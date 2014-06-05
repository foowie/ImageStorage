<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class ImageMacros extends \Nette\Latte\Macros\MacroSet {

	public static function install(\Nette\Latte\Compiler $compiler) {
		$me = new static($compiler);
		$me->addMacro('imageUrl', array('ImageStorage\ImageMacros', 'macroImageUrl'));
		$me->addMacro('imagePath', array('ImageStorage\ImageMacros', 'macroImagePath'));
		$me->addMacro('image', array('ImageStorage\ImageMacros', 'macroImage'));
	}

	public static function macroImageUrl(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
		return $writer->write('echo($presenter->context->getByType(\'ImageStorage\ImagePath\')->getUrl(%node.word));');
	}

	public static function macroImagePath(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
		return $writer->write('echo($presenter->context->getByType(\'ImageStorage\ImagePath\')->getPath(%node.word));');
	}

	public static function macroImage(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
		$text = '$macroImageArgs=%node.array;';
		$text .= '$macroImageArgs["src"]=$presenter->context->getByType("ImageStorage\ImagePath")->getUrl(%node.word);';
		$text .= '$macroImageArgs=\ImageStorage\ImageMacros::fixSize($macroImageArgs,%node.word);';
		$text .= 'echo("<img");';
		$text .= 'foreach($macroImageArgs as $macroImageKey=>$macroImageValue)if($macroImageValue!==null)echo(" $macroImageKey=\'$macroImageValue\'");';
		$text .= 'echo(">");'; // xhtml?
		return $writer->write($text);
	}

	public static function fixSize(array $params, $image) {
		if (array_key_exists('size', $params) && in_array($params['size'], array('fit', 'fill'))) {
			if (!isset($params['width']) || !isset($params['height'])) {
				throw new \InvalidStateException('Missing width/height!');
			}
			if (($params["width"] / $image->width < $params["height"] / $image->height) XOR ($params['size'] == 'fill')) {
				$pom = $params["width"] / $image->width;
			} else {
				$pom = $params["height"] / $image->height;
			}
			$params["width"] = round($image->width * $pom);
			$params["height"] = round($image->height * $pom);
		} else {
			if (!array_key_exists('width', $params) && !array_key_exists('height', $params)) {
				$params["width"] = $image->width;
				$params["height"] = $image->height;
			}
		}
		return $params;
	}

}