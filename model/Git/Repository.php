<?php

namespace Git;

/**
 * Repository class
 */
class Repository {

	protected $_path = null;
	protected $_name = null;
	protected $_description = null;

	protected $_branches = null;
	protected $_content = null;

	protected $_actualBranch = 'master';

	/**
	 * Construct a repository for the given path
	 * @param string $path The repository path
	 * @param string $name Optionnal name, get from the path if not given
	 */
	public function __construct($path,$name=null){
		$this->setPath($path);
	}

	/**
	 * Define the repository path
	 * @param string $path
	 */
	public function setPath($path){
		if(is_dir($path)){
			$this->_path = $path;
		}else throw new Exception('Repository path is not a valid directory');
		if(!$this->getName()){
			$this->setName(basename($path));
		}
		return $this;
	}

	/**
	 * Define the repository name
	 * @param string $name
	 */
	public function setName($name){
		$this->_name = $name;
		return $this;
	}

	/**
	 * Set the actual branch of the repository to $branch
	 * @param string $branch name of the branch
	 * TODO : test if the branch is a valid branch
	 */
	public function setActualBranch($branch){
		$this->_actualBranch = $branch;
		return $this;
	}

	public function setDescription($description,$breakChain=false){
		$descPath = $this->getDescriptionPath();
		$return = file_put_contents($descPath, $description);
		if($breakChain) return $return;
		return $this;
	}

	/**
	 * Get the repository path
	 * @return string
	 */
	public function getPath(){
		return $this->_path;
	}

	/**
	 * Get the repository name
	 * @return string
	 */
	public function getName(){
		return $this->_name;
	}

	/**
	 * Get the repository description
	 * @return string
	 */
	public function getDescription(){
		if(is_null($this->_description)){
			$descPath = $this->getDescriptionPath();
			if(file_exists($descPath)){
				$this->_description = file_get_contents($descPath);
			}else $this->_description = '';
		}
		return $this->_description;
	}

	public function getDescriptionPath(){
		return $this->getPath().DIRECTORY_SEPARATOR.'description';
	}

	/**
	 * Get the actual branch the repository is set on ( Set in the object context, not the real repository branch )
	 * @return string
	 */
	public function getActualBranch(){
		return $this->_actualBranch;
	}

	/**
	 * Get repository urls
	 * @param  string $type The type of url you want
	 * @return string
	 */
	public function getUrl($type=null){
		$url = \Smally\Application::getInstance()->getBaseUrl();
		switch ($type) {
			default:
			case 'currentBranch':
				$url .= $this->getName().'/tree/'.$this->getActualBranch();
				break;
			case 'home':
				$url .= $this->getName();
				break;
			case 'forBranch':
				$url .= $this->getName().'/tree/';
				break;
		}
		return $url;
	}

	/**
	 * Get the available branches of the repository
	 * @return array
	 */
	public function getBranches(){
		if(!isset($this->_branches)){
			$branches = array();
			$exec = Exec::getInstance();
			$return = $exec->run('--git-dir="'.$this->getPath().'" branch -a');
			foreach($return as $line){
				$branches[] = trim($line,' *');
			}
			$this->_branches = $branches;
		}
		return $this->_branches;
	}

	/**
	 * Cut a path in sub elements and return them
	 * @param string $path the path to cut in parts
	 * @return string
	 */
	public function getPathElements($path){
		$return = array();
		$return[] = array('name'=>$this->getName(), 'url'=>$this->getUrl());


		$paths = explode('/',$path);
		$sUrl = $this->getActualBranch();
		foreach($paths as $k => $sPath){
			if(!$sPath) continue;
			$sUrl .= '/'.$sPath;
			$return[] = array('name'=>$sPath,'url'=>$this->getUrl('home').'/tree/'.$sUrl);
		}
		return $return;
	}

	/**
	 * BRANCH MANIPULATIONS
	 */

	/**
	 * Validate a branchName by returning a valid version
	 * @param  string $branchName The branch name wanted
	 * @return string
	 */
	public function validBranchName($branchName){
		if($branchName){
			$branchName = preg_replace('#[^-a-zA-Z0-9]#', '', $branchName);
			$branchName = trim($branchName,' -');
			return $branchName;
		}
		return false;
	}

	/**
	 * Create a new branch in the repository
	 * @return \Git\Repository
	 */
	public function createBranch($branchName){
		$exec = Exec::getInstance();
		$cmd = '--git-dir="'.$this->getPath().'" branch "'.$branchName.'"';
		if($return = $exec->run($cmd)){
			print_r($cmd);
			print_r($return);
			return false;
		}
		return true;
	}

}