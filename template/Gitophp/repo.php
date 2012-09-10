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
			<li class="button"><a href="<?=$this->getBaseUrl('Gitophp/new-branch')?>"><span><span class="desktop">Create</span> new branch</span></a></li>
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
			<li><a href="<?=$this->repo->getUrl()?>"><?=$this->repo->getName()?></a></li>
			<?
				$paths = explode('/',$this->item->getPath());
				$sUrl = $this->repo->getActualBranch();
				foreach($paths as $k => $path){
					if(!$path) continue;
					$sUrl .= '/'.$path;
					if($k != (count($paths)-1) ){
						?><li><a href="<?=$this->repo->getUrl('home').'/tree/'.$sUrl?>"><?=$path?></a></li><?
					}else{
						/*?><li class="omega"><a href="<?=$this->repo->getUrl('home').'/'.$this->item->getType().'/'.$sUrl?>"><?=$path?></a></li><?*/
						?><li class="omega"><?=$path?></li><?
					}
				}
			?>
		</ul>
	</div>

	<div class="content">
	<? if( $this->item->getType() == \Git\Item::TYPE_TREE ) { ?>
		<?
			if($items = $this->item->getContent()){
				?>
				<table class="files">
					<thead>
						<tr>
							<th class="ico">&nbsp;</th>
							<th class="name">name</th>
							<th class="age">age</th>
							<th class="message">message</th>
						</tr>
					</thead>
					<tbody>
					<?
					foreach($items as $item){
						?>
						<tr>
							<td class="ico"><span class="icon icon-<?=$item->getType()?>"></span></td>
							<td><a href="<?=$item->getUrl()?>"><?=$item->getName()?></a></td>
							<td></td>
							<td></td>
						</tr>
						<?
					}
					?>
					</tbody>
				</table>
				<?
			}
		?>
	<? } else { ?>
		<pre><code><?=$this->item->getContent()?></code></pre>
	<? } ?>
	</div>

</div>