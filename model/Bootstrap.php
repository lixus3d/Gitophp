<?php

class Bootstrap extends \Smally\AbstractBootstrap {

	public function x(){

		$this->getApplication()
			// set CSS
			->setCss('css/reset.css')
			->setCss('css/grid.less')
			->setCss('css/style.less')
			->setCss('http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,600,400')
			->setCss('js/styles/github.css')
			// set JS
			->setJs('js/jquery.js')
			->setJs('js/jqueryEasing.js')
			->setJs('js/highlight.pack.js')
			->setJs('js/global.js')
			;

		$this->getApplication()->getMeta()
			// Default METAS
			->addMeta('title','Gitophp',true)
			->addMeta('keywords','developpement, dev, git, php, smally',true)
			->addMeta('description','Simple online git repository interface',true)
			// Viewport meta for mobile and tablet devices
			->addMetaTag(array('name'=>'viewport','content'=>'width=device-width, initial-scale=1.0'))
			;

		// Define some constant for GITOPHP
		define('GIT_PATH_CMD',$this->getApplication()->getConfig()->git->paths->cmd);
		define('GIT_PATH_REPOSITORIES',$this->getApplication()->getConfig()->git->paths->repositories);
	}

}