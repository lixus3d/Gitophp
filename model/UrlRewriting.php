<?php

class UrlRewriting extends \Smally\AbstractUrlRewriting {

	public function __construct(){

		// For the repo home
		$this->addRule('#(.*\.git)$#', array('path'=>'Gitophp/repo','matches'=>array('match','repo')));

		// For the repo action
		$this->addRule('#(.*\.git)/action/new-branch$#', array('path'=>'Gitophp/newBranch','matches'=>array('match','repo')));

		// Match sub element of a repo
		$this->addRule('#(.*\.git)/(tree|blob)/([^/]+)(/(.*))?#', array('path'=>'Gitophp/repo','matches'=>array('match','repo','itemType','branch','path','itemPath')));

		// For global actions
		$this->addRule('action/new-repository', 'Gitophp/newRepository');
	}

}