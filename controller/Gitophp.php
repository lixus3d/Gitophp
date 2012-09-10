<?php

namespace Controller;

class Gitophp extends \Smally\Controller {

	public function __construct(\Smally\Application $application){
		parent::__construct($application);
		$this->getApplication()->getMeta()->addMetaTag(array('name'=>'robots','content'=>'nofollow, noindex, all'));
	}

	/**
	 * Home , list of repositories
	 * @return null
	 */
	public function indexAction(){
		$this->getRooter()->redirect('');
	}

	/**
	 * Common controller for all git repository stuff
	 * @return null
	 */
	public function repoAction(){
		$repoBasePath = $this->getContext()->repo;

		try{
			$repo = new \Git\Repository(GIT_PATH_REPOSITORIES.$repoBasePath);
			$this->getView()->repo = $repo;

			if($branch = $this->getContext()->branch){
				$repo->setActualBranch($branch);
			}

			$itemType = $this->getContext()->itemType;
			$itemPath = $this->getContext()->itemPath;

			if($itemType && $itemPath){
				$item = new \Git\Item($this->getView()->repo);
				$item
					->setType($itemType)
					->setPath($itemPath)
					;
			}else{
				$item = new \Git\Item($this->getView()->repo);
				$item
					->setType(\Git\Item::TYPE_TREE)
					->setPath('')
					;
			}
			$this->getView()->item = $item;


		}catch( \Git\Exception $e ){
			$this->getRooter()->redirect('');
		}
	}

	public function newRepositoryAction(){

	}

	public function newBranchAction(){

	}

}
