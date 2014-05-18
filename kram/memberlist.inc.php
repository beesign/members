<?php
	//Solving Problem: if position DESC: Reverse Sorting
	$moveuplink = WB_URL.'/modules/'.$mod_dir.'/move_up.php?';
	$movedownlink = WB_URL.'/modules/'.$mod_dir.'/move_down.php?';
		
	if ($sort_by == 'position DESC') {
	//reverse:	
		$moveuplink = WB_URL.'/modules/'.$mod_dir.'/move_down.php?';
		$movedownlink = WB_URL.'/modules/'.$mod_dir.'/move_up.php?';	
	}
	
		
	while($members = $query_members->fetchRow()) {
	$isalias = 0 + (int)$members['m_isalias'];
	$member_id = 0 + (int)$members['member_id'];
	$countmembers++; 
	if ($isalias > 0) $countalias++;
		?>
		<tr class="mrow<?php if($isalias > 0) echo " alias";  if($member_id == $hlmember) echo " hilite"; ?>" onmouseover="this.style.backgroundColor = '#F1F8DD'" onmouseout="this.style.backgroundColor = '#ffffff'">
			<td class="membertd1">			
				<a href="<?php echo $mod_ml.$mod_param.$group_id.$paramdelimiter.'member_id='.$member_id.'"><img src="'.$picurl.'mod'; if ($isalias == 0) {echo 'm';} else {echo 'a';}?>.gif" alt="Modify" /></a>
				<?php if ($use_aliases == 1) { 
					if ($isalias == 0) { echo '<a href="'.$add_ml.$mod_param.$group_id.$paramdelimiter.'addalias='.$member_id.'"><img src="'.$picurl.'addalias.gif" alt="Add Alias" /></a>';} else { echo '<a href="'.$add_ml.$mod_param.$group_id.$paramdelimiter.'addalias='.$isalias.'"><img src="'.$picurl.'isalias.gif" alt="Is Alias" /></a>';} 
				} else { 
					echo '<img src="'.$picurl.'blind.gif"  alt="" />'; 					
				} ?>
			</td>
			<td class="membertd2">
				<a href="<?php echo WB_URL.'/modules/'.$mod_dir.'/modify_member.php?'.$mod_param.$group_id.$paramdelimiter.'member_id='.$member_id; ?>">
					<?php 
					if ($isalias == 0) {  //is NO alias, search for aliases od this member
						echo '<span class="ismember">'.$members['m_name'].'</span>'; 
						$query_alias = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE m_isalias = '$member_id'");
						$has_alias = $query_alias->numRows();
						if($has_alias > 0) {
							$countaliasofmembers += $has_alias;
							echo ' <span class="has_alias">('.$has_alias.'*)</span>';							
						}
					} else { echo '<span class="isalias">'.$members['m_name'].'</span>'; }?>
				</a>
			</td>			
			<td><img src="<?php echo $picurl.'mactive'.$group_active.$members['active'].'.gif" alt ="" title="'.$TEXT['ACTIVE'].': '; if($members['active'] == 1) { echo $TEXT['YES']; } else { echo $TEXT['NO']; } ?>" /></td>
			<?php if ($sort_mem_name > 0) {  
				$t = 'class="score_td"> &nbsp;';
				if ($sort_mem_name == 2) {$t='class="sort_td">'.$members['m_sortt'];}
				if ($sort_mem_name == 3) {$t='class="score_td">'.$members['m_score'];}
				echo '<td colspan="2" '.$t.'</td>';
			} else { ?>
			<td><?php if ($countmembers == 1){ echo '&nbsp;'; } else {
					echo '<a href="'.$moveuplink.$mod_param.$group_id.$paramdelimiter.'member_id='.$member_id.'" title="'.$TEXT['MOVE_UP'].'"><img src="'.THEME_URL.'/images/up_16.png" alt="^" /></a>';
				} ?>
			</td>
			<td><?php if ($countmembers == $countquery_members){ echo '&nbsp;'; } else {
					echo '<a href="'.$movedownlink.$mod_param.$group_id.$paramdelimiter.'member_id='.$member_id.'" title="'.$TEXT['MOVE_DOWN'].'"><img src="'.THEME_URL.'/images/down_16.png" alt="v" /></a>';
				} ?>
			</td>
			<?php } ?>
			<td>
				<a href="#" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL.'/modules/'.$mod_dir.'/ghost_member.php?'.$mod_param.$group_id.$paramdelimiter.'member_id='.$member_id; ?>');" name="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo $picurl.'ghost.gif" alt="'. $TEXT['DELETE']; ?>" title="move to ghosts" />
				</a>
			</td>
		</tr>
		
		
		<?php		
	} ?>