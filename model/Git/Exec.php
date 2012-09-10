<?php

namespace Git;

/**
 * Execute git command and return the result
 */
class Exec {

	static protected $_singleton = null;

	protected $_cmdPath = null;

	/**
	 * Must be construct with the path to git binary
	 * @param string $cmdPath Path to the binary, actually just "git" work well if correctly configured
	 */
	public function __construct($cmdPath){
		$this->setCmdPath($cmdPath);
		self::$_singleton = $this;
	}

	/**
	 * Singleton logic
	 * @return \Git\Exec
	 */
	public function getInstance(){
		if(!self::$_singleton instanceof Exec){
			new Exec(GIT_PATH_CMD);
		}
		return self::$_singleton;
	}

	/**
	 * Define the binary path
	 * @param string $cmdPath the binary path
	 */
	public function setCmdPath($cmdPath){
		$this->_cmdPath = $cmdPath;
	}

	/**
	 * Concat the given $cmd with the binary path for execution
	 * @param  string $cmd Git command
	 * @return string
	 */
	public function getCmd($cmd){
		return $this->_cmdPath.' '.$cmd;
	}

	/**
	 * Run the given $cmd and return the result
	 * @param  string $cmd the command you want to exec
	 * @return array One line for each command line result
	 */
	public function run($cmd){
		$cmd = $this->getCmd($cmd);
		//echo BR.$cmd.BR; // not very clean ... but usefull for now
		exec($cmd,$return,$state);
		return $return;
	}
}