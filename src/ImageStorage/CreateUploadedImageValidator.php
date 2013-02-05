<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CreateUploadedImageValidator extends \Nette\Object implements \Messaging\IValidator {

	/**
	 * @param \ImageStorage\CreateUploadedImageCommand $message
	 */
	public function validate($message) {
		$upload = $message->getUpload();
		if ($upload == null) {
			throw new \Messaging\ValidationException('Musíte přiložit obrázek');
		}

		if (!$upload->isOk()) {
			switch ($upload->getError()) {
				case UPLOAD_ERR_INI_SIZE: throw new \Messaging\ValidationException('Přiložený obrázek je příliš veliký!');
				case UPLOAD_ERR_PARTIAL: throw new \Messaging\ValidationException('Obrázek nebyl korektně nahrán!');
				case UPLOAD_ERR_NO_FILE: throw new \Messaging\ValidationException('Musíte přiložit obrázek!');
				default:
					\Nette\Diagnostics\Debugger::log('Error on file upload: ' . $upload->getError());
					throw new \Messaging\ValidationException('Nastala chyba při ukládání obrázku, zkuste to prosím později.');
			}
		}

		if (!$upload->isImage()) {
			throw new \Messaging\ValidationException('Přiložený soubor není podporovaný typ obrázku');
		}
	}

}
