<?php

/**
 * This is a CSV reader used for importing
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CSVReader implements Iterator {

	private $_file;
	private $_fp;
	private $_headers;
	private $_current;
	private $_counter;

	public function __construct($file) {
		$this->_file = $file;
		$this->rewind();
	}

	public function current() {
		return $this->_current;
	}

	public function key() {
		return $this->_counter;
	}

	public function next() {
		$data =  fgetcsv($this->_fp);
		if($data === false) {
			$this->_current = null;
		}
		else {
			$this->_current = array_combine($this->_headers, $data);
		}
		$this->_counter++;
	}

	public function rewind() {
		if(is_resource($this->_fp)) fclose($this->_fp);
		$this->_fp = fopen($this->_file, 'r');
		$this->_headers = fgetcsv($this->_fp);
		$this->_counter = 0;
		$this->next();
	}

	public function valid() {
		return $this->_current != null;
	}
}