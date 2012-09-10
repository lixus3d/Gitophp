<?php

namespace Controller;

class Index extends \Smally\Controller {

	public function __construct(\Smally\Application $application){
		parent::__construct($application);
		$this->getApplication()->getMeta()->addMetaTag(array('name'=>'robots','content'=>'nofollow, noindex, all'));
	}

	/**
	 * Home , list of repositories
	 * @return null
	 */
	public function indexAction(){
		$list = new \Git\DirList(GIT_PATH_REPOSITORIES);
		$this->getView()->repos = $list->getList();
	}

}
