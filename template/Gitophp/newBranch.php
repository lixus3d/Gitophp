	<div class="titlehead container12">
		<h1 class="container8">Create new branch in repository "<?=$this->repo->getName()?>"</h1>
		<div class="actions grid4">
			<!--<ul>
				<li class="button"><a href="<?=$this->getBaseUrl('Gitophp/new-repository')?>">Create new repository</a></li>
			</ul>-->
		</div>
	</div>
	<div class="clear"></div>

	<div class="form git-form">
		<? echo $this->branchForm->render() ?>
	</div>