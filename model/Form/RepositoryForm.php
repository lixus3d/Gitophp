<?php

namespace Form;

class RepositoryForm extends \Smally\Form {

	public function init(){

		$this
			->setMethod(\Smally\Form::METHOD_POST)
			->addField('text','repoName','Repository name')
			->addField('textarea','repoDesc','Repository description')
			->addField('submit','submitter','','Create')
			;

	}


}