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
		$text .= 'if(!array_key_exists("width",$macroImageArgs))$macroImageArgs["width"]=%node.word->width;';
		$text .= 'if(!array_key_exists("height",$macroImageArgs))$macroImageArgs["height"]=%node.word->height;';
		$text .= 'echo("<img");';
		$text .= 'foreach($macroImageArgs as $macroImageKey=>$macroImageValue)if($macroImageValue!==null)echo(" $macroImageKey=\'$macroImageValue\'");';
		$text .= 'echo(">");'; // xhtml?
		return $writer->write($text);
	}

}