<?php

namespace Form;

class RepositoryFormEdit extends \Smally\Form {

	public function init(){

		$this
			->setMethod(\Smally\Form::METHOD_POST)
			->addField('textarea','repoDesc','Repository description')
			->addField('submit','submitter','','Edit')
			;

	}


}