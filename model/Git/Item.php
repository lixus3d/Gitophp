<?php

namespace Git;

/**
 * Item class, use for blob and tree type
 */
class Item {

	const TYPE_BLOB = 'blob';
	const TYPE_TREE = 'tree';

	protected $_name = '';
	protected $_type = null;
	protected $_hash = null;
	protected $_path = null;

	protected $_content = null;

	protected $_repository = null;

	/**
	 * Must always be construct with a reference to is repository
	 * @param \Git\Repository $repository The reference to the repository
	 */
	public function __construct(\Git\Repository $repository){
		$this->_repository = $repository;
	}

	/**
	 * Return the name of an item when converted to string
	 * @return string
	 */
	public function __toString(){
		return $this->getName();
	}

	/**
	 * Make a \Git\Item object from an ls-tree line
	 * @static
	 * @param  string          $lsTreeLine The ls-tree line you want to convert
	 * @param  \Git\Repository $repository The reference to the repository
	 * @return \Git\Item
	 */
	static public function make($lsTreeLine, \Git\Repository $repository){
		if(preg_match('#^([0-9]+)\s([a-z]+)\s(.+)\s(.+)$#i', $lsTreeLine,$matches)){
			$item = new self($repository);
			$item->setType($matches[2]);
			$item->setHash($matches[3]);
			$item->setName($matches[4]);
			return $item;
		}else var_dump($lsTreeLine); // Ok not very clean, but usefull for now
		return null;
	}

	/**
	 * Define the item name
	 * @param string $name
	 */
	public function setName($name){
		$this->_name = $name;
		return $this;
	}

	/**
	 * Define the item hash
	 * @param string $hash
	 */
	public function setHash($hash){
		$this->_hash = $hash;
		return $this;
	}

	/**
	 * Define the item type, use class constant instead of direct string plz
	 * @param string $type
	 */
	public function setType($type){
		if(in_array($type,array(self::TYPE_BLOB,self::TYPE_TREE))){
			$this->_type = $type;
		}else throw new Exception('invalid Item type : '.$type);
		return $this;
	}

	/**
	 * Define the item path
	 * @param string $path
	 */
	public function setPath($path){
		$this->_path = $path;
		return $this;
	}

	/**
	 * Get the back referenced Repository object
	 * @return \Git\Repository
	 */
	public function getRepository(){
		return $this->_repository;
	}

	/**
	 * Get the name of the item
	 * @return string
	 */
	public function getName(){
		return $this->_name;
	}

	/**
	 * Get the type of the item
	 * @return string
	 */
	public function getType(){
		return $this->_type;
	}

	/**
	 * Get the hash of the item
	 * @return string
	 */
	public function getHash(){
		return $this->_hash;
	}

	/**
	 * Get the path of the item
	 * @return string
	 */
	public function getPath(){
		return $this->_path;
	}

	/**
	 * Get the object name use in git commands
	 * @return string If the item has a hash it return it, else it returns the branch and the path
	 */
	public function getObjectName(){
		if($this->getHash()) return $this->getHash;
		else return $this->getRepository()->getActualBranch().':'.$this->getPath();
	}

	/**
	 * Get the item content, return depend of the item type
	 * @return mixed
	 */
	public function getContent(){

		if(!isset($this->_content)){

			$exec = Exec::getInstance();

			switch($this->getType()){
				case self::TYPE_BLOB: // Return the actual file content from a blob
					$return = $exec->run('--git-dir="'.$this->getRepository()->getPath().'" show "'.$this->getObjectName().'"');
					$return = implode(NN,$return);
					$this->_content = $return;
				break;

				case self::TYPE_TREE: // Return an array of contained items from a tree

					$blobs = array();
					$trees = array();

					$return = $exec->run('--git-dir="'.$this->getRepository()->getPath().'" ls-tree "'.$this->getObjectName().'"');

					foreach($return as $line){
						if($itemObject = Item::make($line,$this->getRepository())){
							$itemObject->setPath(($this->getPath()?$this->getPath().'/':'').$itemObject->getName());
							switch($itemObject->getType()){
								case \Git\Item::TYPE_BLOB:
									$blobs[$itemObject->getName()] = $itemObject;
									break;
								case \Git\Item::TYPE_TREE:
									$trees[$itemObject->getName()] = $itemObject;
									break;
							}
						}
					}
					ksort($trees);
					ksort($blobs);
					$this->_content = array_merge($trees,$blobs);
				break;
			}

		}

		return $this->_content;
	}

	/**
	 * Get item urls
	 * @param  string $type The url you want to get
	 * @return string
	 */
	public function getUrl($type=null){
		$url = \Smally\Application::getInstance()->getBaseUrl();
		switch ($type) {
			default:
			case 'home':
				$url = $this->getRepository()->getUrl('home') .'/'. $this->getType() .'/'. $this->getRepository()->getActualBranch() .'/'. $this->getPath();
				break;
		}
		return $url;
	}
}