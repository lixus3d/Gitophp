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
					foreach($this->subItems as $item){
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