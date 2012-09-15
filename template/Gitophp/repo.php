<div class="titlehead container12">
	<h1 class="container6"><?=$this->repo->getName()?></h1>

	<div class="actions container6">
		<div class="git-branches grid4">
			Branches :
			<?
				if($branches = $this->repo->getBranches()){
					?><ul><?
					foreach($branches as $branch){
						?>
						<li class="button"><a href="<?=$this->repo->getUrl('forBranch').$branch?>"><?=$branch?></a></li>
						<?
					}
					?></ul><?
				}
			?>
		</div>
		<ul class="grid2">
			<li class="button"><a href="<?=$this->repo->getUrl('home').'/action/new-branch'?>"><span><span class="desktop">Create</span> new branch</span></a></li>
		</ul>
	</div>
</div>
<div class="clear"></div>

<div class="git-description grid12">
<?=$this->repo->getDescription()?>
</div>
<div class="clear"></div>

<div class="git-file-list">

	<div class="git-breadcrumb">
		<span class="branch"><?=$this->repo->getActualBranch()?> :</span>
		<ul>
			<?
				if($this->pathElements){
					foreach($this->pathElements as $k => $path){
						if($k==0 || $k != (count($this->pathElements)-1) ){
							?><li><a href="<?=$path['url']?>"><?=$path['name']?></a></li><?
						}else{
							?><li class="omega"><?=$path['name']?></li><?
						}
					}
				}
			?>
		</ul>
	</div>

	<div class="content">
	<?	if( isset($this->subItems) ) {
			echo $this->render('Gitophp/itemList');
		} else { ?>
			<pre><code><?=$this->item->getContent()?></code></pre>
	<? 	}	?>
	</div>

</div>