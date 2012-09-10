<?php

namespace Git;

/**
 * List of repositories
 */
class DirList {

	protected $_path = null;

	/**
	 * Can be initialized with the base path of the repositories
	 * @param string $path Path to the repositories
	 */
	public function __construct($path=null){
		$this->setPath($path);
	}

	/**
	 * Define the base path of repositories
	 * @param string $path Path to the repositories
	 * @return \Git\DirList
	 */
	public function setPath($path){
		if(is_dir($path)){
			$this->_path = $path;
		}else throw new Exception('List path is not a valid directory');
		return $this;
	}

	/**
	 * Get the base $path, and prefix if $subPath if given
	 * @param  string $subPath An optionnal suffix to add
	 * @return string
	 */
	public function getPath($subPath=''){
		return $this->_path.$subPath;
	}

	/**
	 * Get the repositories in the directory, actually repositories have to name xxxxxxx.git to work
	 * @return array
	 */
	public function getList(){
		$list = array();
		$folders = glob($this->getPath('*.git'), (GLOB_ONLYDIR));
		foreach($folders as $repoPath){
			$list[] = new Repository($repoPath);
		}
		return $list;
	}

}