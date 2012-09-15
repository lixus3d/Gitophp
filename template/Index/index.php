	<div class="titlehead container12">
		<h1 class="container8">Repositories List</h1>
		<div class="actions grid4">
			<ul>
				<li class="button"><a href="<?=$this->getBaseUrl('action/new-repository')?>">Create new repository</a></li>
			</ul>
		</div>
	</div>
	<div class="clear"></div>

<? foreach($this->repos as $repo) { ?>

	<div class="git-repo">
		<a href="<?=$repo->getUrl()?>">
			<div class="content">
				<h2 class="name"><span><?=$repo->getName()?></span></h2>
				<div class="infos container12">
					<div class="description grid8"><?=$repo->getDescription()?></div>
					<div class="path grid4"><?=$repo->getPath()?></div>
				</div>
			</div>
			<div class="clear"></div>
		</a>
	</div>

<? } ?>