<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
if(current_user_can('manage_options')):

$ripath = plugins_url( '', __FILE__);
$err = '';
?>
<script type="text/javascript">
	function remove_space(el){
		var val = el.value;
		el.value = val.replace(/\s+/g, '-').toLowerCase();;
	}
</script>
<div class="wrap ripladmin">
<form action="" method="post">
	<input type="hidden" name="_cuptaxnfanonce" value="<?php echo wp_create_nonce( 'cuptaxnfn-nonce' ); ?>" />
  <p><b>Plural Name : </b> <input type="text" name="lab" /></p>
  <p class="sc">
  	<b>Singilar Name : </b> <input type="text" name="ph" />
  </p>
  <p class="mc" id="mc">
  	<b>Slug : </b> <input type="text" name="ftype" placeholder="" onblur="remove_space(this)" />
  </p>
  <p><b>Post Type : </b> <?php
	$args = array(   'public'   => true,   '_builtin' => false );
	echo '<span><input type="checkbox" name="ritaxpt[]" value="post" />Post</span>';
    foreach ( get_post_types( $args, 'objects' ) as $post_type ) { //echo '<pre>'; print_r($post_type);
	   echo '<span><input type="checkbox" name="ritaxpt[]" value="' . $post_type->name . '" />' . $post_type->label . '</span>';
	}
  ?></p>
  <p><input type="submit" value="Create Taxonomy" /></p>
</form>
<?php
//postList type='post' cat='23' tag='24' ordby='date' ord='asc' count='10' offset='0' temp='t1' hide='date,author' exrpt='50';

if(isset($_REQUEST['cuptaxnslug']) && $_REQUEST['cuptaxnslug']!=''){
	if(get_option( 'cuptaxnf_custom_post_opt' )){
		$cuptaxnf_custom_posts_disp = unserialize(get_option( 'cuptaxnf_custom_post_opt' ));
		if(sizeof($cuptaxnf_custom_posts_disp)>0){
			foreach($cuptaxnf_custom_posts_disp as $cuptaxnf_custom_post_disp){
				if($cuptaxnf_custom_post_disp)
				foreach($cuptaxnf_custom_post_disp as $slug=>$field){
					if($field['type']!=$_REQUEST['cuptaxnslug']){ 
						$newfiels = array();
						$newfiels[] = array('label'=>$field['label'],
											 'type'=>$field['type'],
											 'ph'=>$field['ph'],
											 'ritaxpt'=>$field['ritaxpt']
											 );
					}
				}
			}
		}
		
		$cuptaxnf_custom_post = array(); //unserialize(get_option( 'cuptaxnf_custom_post_opt' ));
		$cuptaxnf_custom_post[] = $newfiels;
		update_option( 'cuptaxnf_custom_post_opt', serialize($cuptaxnf_custom_post) );
	}
}

if(isset($_POST['_cuptaxnfanonce']) && wp_verify_nonce( $_POST['_cuptaxnfanonce'], 'cuptaxnfn-nonce' )){
	$f = 0;
	if(get_option( 'cuptaxnf_custom_post_opt' )){
		$cuptaxnf_custom_posts_disp = unserialize(get_option( 'cuptaxnf_custom_post_opt' ));
		foreach($cuptaxnf_custom_posts_disp as $cuptaxnf_custom_post_disp){
			if($cuptaxnf_custom_post_disp)
			foreach($cuptaxnf_custom_post_disp as $slug=>$field){
				if($field['type']==$_POST['ftype']){ $f=1; $err = 'Please use a different slug'; break; }
			}
		}
	}
	if(isset($_POST['ftype']) && $f==0){ $h = '';
		
		if( strlen($_POST['ftype']) < 30 ){ $ftype =  sanitize_text_field($_POST['ftype']); }
		if( strlen($_POST['lab']) < 30 ){ $lab =  sanitize_text_field($_POST['lab']); }
		if( strlen($_POST['ph']) < 30 ){ $ph =  sanitize_text_field($_POST['ph']); }
		if($_POST['ritaxpt']){ $ritaxpt = implode(', ', $_POST['ritaxpt']); }
		
		$newfiels = array(); 
		$newfiels[] = array('label'=>$lab,
								 'type'=>$ftype,
								 'ph'=>$ph,
								 'ritaxpt'=>$ritaxpt
								 );
		$cuptaxnf_custom_post = unserialize(get_option( 'cuptaxnf_custom_post_opt' ));
		$cuptaxnf_custom_post[] = $newfiels;
		
		update_option( 'cuptaxnf_custom_post_opt', serialize($cuptaxnf_custom_post) );
	}
}
echo '<p class="error">'.$err.'</p>';
if(get_option( 'cuptaxnf_custom_post_opt' )){
	$cuptaxnf_custom_posts_disp = unserialize(get_option( 'cuptaxnf_custom_post_opt' ));
}
else{ if(add_option( 'cuptaxnf_custom_post_opt' )){  } }

if($cuptaxnf_custom_posts_disp && sizeof($cuptaxnf_custom_posts_disp)>0){
	/*echo '<pre>';
	print_r($cuptaxnf_custom_posts_disp);*/
	
	echo '<table class="wp-list-table widefat fixed striped pages">';
	echo '<thead><tr> <td>Plural Name</td> <td>Singular Name</td> <td>Slug</td> <td>Post Type</td> <td>Delete</td>  </tr></thead><tbody>';
	foreach($cuptaxnf_custom_posts_disp as $cuptaxnf_custom_post_disp){
		if($cuptaxnf_custom_post_disp)
		foreach($cuptaxnf_custom_post_disp as $slug=>$field){
			$ritaxpt = explode(', ', $field['ritaxpt']); $ric = 0;
			
			echo '<tr>
			<td>'.$field['label'].'</td>
			<td>'.$field['ph'].'</td>
			<td>'.$field['type']; 
			echo '</td><td>'; 
			foreach($ritaxpt as $ritaxpt1){ 
			$ric++;
			if($ric>1){ echo ', '; }
				$args = array(   'public'   => true,   '_builtin' => false, 'name' => $ritaxpt1 ); 
				//echo '<pre>'; 
				$rictaxpty = get_post_types( $args, 'objects' ); 
				foreach($rictaxpty as $rictaxpty1)
				echo $rictaxpty1->label; 
			}
			echo '</td><td><a href="admin.php?page=custom-post-taxonomy%2Fmain.php&cuptaxnslug='.$field['type'].'">Delete</a></td>
			</tr>';
		}
	}
	echo '</tbody></table>';
}


endif;
?>
</div>