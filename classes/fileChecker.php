<?php
require_once('config/config.php');

class FileChecker {

	private $files;
	private $finalFiles = array();

	function __construct($files) {
		$this->files = $files['bikeimages'];
	}

	// check if there is a file appended, if it is the right size
	// and the right extension. if not == return false
	function checkFileErrors() {
		$errors = array();

		if (isset($this->files) && !empty($this->files)) {
			foreach ($this->files['tmp_name'] as $fileID => $tempfile) {

				$original = $this->files['name'][$fileID];
				$mimetype = $this->files['type'][$fileID];
				$filesize = $this->files['size'][$fileID];
				$error = $this->files['error'][$fileID];

				if ($error === UPLOAD_ERR_OK) { // everything went fine, a file was uploaded
					// now check if the file is a jpg and the file size is fine
					if ($mimetype != MIME_TYPE) {
						$errors[] = 'The Image ' . $original . ' was no jpg-file. Please upload jpg-images only.';
					}
					if ($filesize > MAX_UPLOAD_SIZE) {
						$errors[] = 'Your image ' . $original . ' was to large. Maximum file size allowed: 1MB. ';
					}
					// if there are _NO_ errors this far, try moving it to the destination folder
					// and give it a random name
					if (empty($errors)) {
						if (is_uploaded_file($tempfile)) {
							// create a new filename with sha1
							$savedFileName = sha1($tempfile . $filesize) . '.jpg';
							$moved = @move_uploaded_file($tempfile, UPLOAD_FOLDER . $savedFileName);
							if ($moved === false) { // if this still does not work, life sucks throw error
								$errors[] = 'An error occured uploading the file ' . $original . '. Sorry.';
							} else { // return the new filename if everything went fine
								$this->finalFiles[] = $savedFileName;
							}
						} else {
							$errors[] = 'Your image ' . $original . 'was corrupted. Please try again.';
						}
					}
				} else {
					// there was not even a file. fine
					if ($error === UPLOAD_ERR_NO_FILE) {
						// $errors[] = 'There where no files.';
					} else {
						$errors[] = 'The server rejected your file, probably to big.';
					}
				}
			}

			// now we should have an error-array and or a finalFiles array
			if (empty($errors)) {
				return $this->finalFiles;
			} else {
        throw new Exception(implode("\n", $errors));
			}
	  }

	}

}


?>
