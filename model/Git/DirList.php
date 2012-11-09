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

	/**
	 * Check if a particular reponame exists
	 * @param  string $repoName the reponame to test without the ".git"
	 * @return boolean
	 */
	public function exists($repoName){
		return is_dir($this->getPAth($repoName.'.git'));
	}

	/**
	 * Create a new repository with name $repoName
	 * @param  string $repoName The name of the new repository
	 * @return boolean Does it work or not ?
	 */
	public function createRepository($repoName){

		if(mkdir($this->getPath().$repoName.'.git')){
			chgrp($this->getPath().$repoName.'.git','www-data');
			chmod($this->getPath().$repoName.'.git',0770);
			$exec = Exec::getInstance();
			$cmd = '--git-dir="'.$this->getPath().$repoName.'.git" --bare init';
			$return = $exec->run($cmd);
			foreach($return as $line){
				if(strpos($line,'Initialized') === 0){
					// We must configure the repository to accept http updates
					file_put_contents($this->getPath().$repoName.'.git'.DIRECTORY_SEPARATOR.'config',"[http]\n\treceivepack = true",FILE_APPEND);
					return true;
				}
			}
		}
		return false;
	}

}