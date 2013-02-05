<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CreateImageValidator extends \Nette\Object implements \Messaging\IValidator {

	/**
	 * @param \ImageStorage\CreateImageCommand $message
	 */
	public function validate($message) {
		if ($message->getName() == '') {
			throw new \Messaging\ValidationException('Neplatný název obrázku!');
		}
	}

}
