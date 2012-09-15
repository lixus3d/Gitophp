<?php

namespace Form;

class BranchForm extends \Smally\Form {

	public function init(){

		$this
			->setMethod(\Smally\Form::METHOD_POST)
			->addField('text','branchName','Branch name')
			->addField('submit','submitter','','Create')
			;

	}


}