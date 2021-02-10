<?php

class upload {
	private $error = '';
	private $path = '';
	private $extension = '';

	//upload file function
	public function upload_file($field, $dir, $filename, array $allowed_extension, $max_size) {
		$this->error = "";

		$path = $dir . basename($_FILES[$field]["name"]);
		$filetype = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		$invalid = TRUE;

		for ($i = 0; $i < count($allowed_extension); $i++) {
			if ($filetype == $allowed_extension[$i]) {
				$invalid = FALSE;
				$this->extension = $filetype;
			}
		}

		if (
			!getimagesize($_FILES[$field]["tmp_name"]) ||
			$_FILES[$field]["size"] > $max_size ||
			$invalid
		) {
			$this->error = "Invalid file!";
			return FALSE;
		}

		$this->mkdir($dir);
		$this->path = $dir . $filename . "." . $filetype;

		if (!move_uploaded_file($_FILES[$field]["tmp_name"], $this->path)) {
			$this->error = "Error while trying to upload file!";
			return FALSE;
		}

		return TRUE;
	}

	//make directory function
	private function mkdir($dir) {
		$dir_array = explode("/", $dir);

		for ($i = 0; $i < count($dir_array); $i++) {
			$dir_array[$i] .= "/";

			if (!is_dir($dir_array[$i])) {
				mkdir($dir_array[$i]);
			}

			if ($i < count($dir_array) - 1) {
				$dir_array[$i + 1] = $dir_array[$i] . $dir_array[$i + 1];
			}
		}
	}

	//getter
	public function getError() {
		return $this->error;
	}

	public function getImagePath() {
		return $this->path;
	}

	public function getExtension() {
		return $this->extension;
	}
}
