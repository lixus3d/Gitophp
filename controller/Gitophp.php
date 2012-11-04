<?php

namespace Controller;

class Gitophp extends \Smally\Controller {

	protected $_formPrefix = 'repository';

	public function __construct(\Smally\Application $application){
		parent::__construct($application);

		$this->getApplication()->getMeta()->addMetaTag(array('name'=>'robots','content'=>'nofollow, noindex, all'));

		$this->gitList = new \Git\DirList(GIT_PATH_REPOSITORIES);
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

			if($itemType && $itemPath){ // We have a type and a path
				$item = new \Git\Item($this->getView()->repo);
				$item
					->setType($itemType)
					->setPath($itemPath)
					;
			}else{ // If no path and no item have been defined
				$item = new \Git\Item($this->getView()->repo);
				$item
					->setType(\Git\Item::TYPE_TREE)
					->setPath('')
					;
			}
			$this->getView()->item = $item;

			// Get sub items if item is a tree
			if($item->getType() == \Git\Item::TYPE_TREE) $this->getView()->subItems = $item->getContent();

			// Generate paths elements
			$this->getView()->pathElements = $repo->getPathElements($item->getPath());

		}catch( \Git\Exception $e ){
			$this->getRooter()->redirect('');
		}
	}

	/**
	 * Action for create a new repository
	 * @return null
	 */
	public function newRepositoryAction(){

			$repositoryForm = new \Form\RepositoryForm();
			$repositoryForm->setNamePrefix($this->_formPrefix);

			$errors = array();

			if($inputs = $this->getContext()->{$this->_formPrefix}->toArray()){
				$repositoryForm->autoPopulateValue($this->getContext());
				if(isset($inputs['repoName'])&&$inputs['repoName']){
					$repoName = $inputs['repoName'];
					$repoName = trim($repoName);
					$repoDesc = $inputs['repoDesc'];
					$repoDesc = trim($repoDesc);
					if(!$this->gitList->exists($repoName)){
						if($this->gitList->createRepository($repoName)){

							$repo = new \Git\Repository(GIT_PATH_REPOSITORIES.$repoName.'.git');

							if($repoDesc!='') $repo->setDescription($repoDesc);

							$this->getRooter()->redirect($repo->getUrl());

						}else $errors['repoName'] = 'Fail to create the repository "'.$repoName.'" !';
					}else $errors['repoName'] = 'Repository already exists';
				}else $errors['repoName'] = 'You must type a repository name.';
			}

			$repositoryForm->populateError($errors);

			$this->getView()->repositoryForm = $repositoryForm;
	}

	/**
	 * Action for create a new branch
	 * @return null
	 */
	public function newBranchAction(){
		$repoBasePath = $this->getContext()->repo;

		try{
			if(!$repoBasePath) throw new Exception('Invalid repository name');
			$repo = new \Git\Repository(GIT_PATH_REPOSITORIES.$repoBasePath);

			$branchForm = new \Form\BranchForm();
			$branchForm->setNamePrefix($this->_formPrefix);

			$errors = array();

			if($inputs = $this->getContext()->{$this->_formPrefix}->toArray()){
				$branchForm->autoPopulateValue($this->getContext());
				if(isset($inputs['branchName'])&&$inputs['branchName']){
					$branchName = $inputs['branchName'];
					$branchName = $repo->validBranchName($branchName);
					if($branchName == $inputs['branchName']){
						if(!in_array($branchName,$repo->getBranches())){
							if($repo->createBranch($branchName)){
								$this->getRooter()->redirect($repo->getUrl('home').'/tree/'.$branchName);
							}else $errors['branchName'] = 'Fail to create the branch "'.$branchName.'" in repository "'.$repo->getName().'" !';
						}else $errors['branchName'] = 'This branch already exist.';
					}else{
					 	$errors['branchName'] = 'The name was not valid, a corrected one is suggested.';
					 	$branchForm->populateValue(array('branchName'=>$branchName));
					}
				}else $errors['branchName'] = 'You must type a branch name.';
			}

			$branchForm->populateError($errors);

			$this->getView()->repo = $repo;
			$this->getView()->branchForm = $branchForm;

		}catch( \Git\Exception $e ){
			$this->getRooter()->redirect('');
		}
	}

	public function editRepositoryAction(){
		$repoBasePath = $this->getContext()->repo;

		try{
			if(!$repoBasePath) throw new Exception('Invalid repository name');
			$repo = new \Git\Repository(GIT_PATH_REPOSITORIES.$repoBasePath);

			$repositoryForm = new \Form\RepositoryFormEdit();
			$repositoryForm->setNamePrefix($this->_formPrefix);

			if($this->getContext()->{$this->_formPrefix}->toArray()){
				$repositoryForm->autoPopulateValue($this->getContext());
			}else{
				$repositoryForm->populateValue(array('repoDesc'=>$repo->getDescription()));
			}

			$errors = array();

			if($inputs = $this->getContext()->{$this->_formPrefix}->toArray()){
				if(isset($inputs['repoDesc'])&&$inputs['repoDesc']){
					$repoDesc = $inputs['repoDesc'];
					$repoDesc = trim($repoDesc);
					if($repo->setDescription($repoDesc,true)){
						$this->getRooter()->redirect($repo->getUrl());
					}else $errors['repoDesc'] = 'Fail to write the description to the repository "'.$repoName.'" !';
				}else $errors['repoDesc'] = 'You must define a description';
			}

			$repositoryForm->populateError($errors);

			$this->getView()->repo = $repo;
			$this->getView()->repositoryForm = $repositoryForm;

		}catch( \Git\Exception $e ){
			$this->getRooter()->redirect('');
		}
	}

}
