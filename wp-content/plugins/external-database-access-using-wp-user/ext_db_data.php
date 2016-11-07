<?php
error_reporting(0);

include_once dirname( __FILE__ ) . '/widget.php';
$plugin_url = get_admin_url() . 'admin.php?page=external-database-access-using-wp-user/ext_db_data.php';
define('PLUGIN_URL', $plugin_url);
define('BACK_BUTTON', '<form action="" method="post"><input class="button action" type="submit" name="cancel" value="Back">');

function ext_db_data_init(){

}
function ext_db_data_activate() {

}

//Function to add menu item
//Parameters: 
//Return:
function ext_db_data_add_menu() {
	add_menu_page("External DB data", "External DB data", 'manage_options', __FILE__, "ext_db_data_display_options",plugins_url('/images/scc-sc.png', __FILE__));
}

//Function to shoe records from selected table of external database
//Parameters: 
//Return:
function ext_db_data_display_options()
{
	//Get User data from external DB
	$action = $_REQUEST['action'];
	switch($action)
	{
		case 'Add New':
						extAddForm($_POST['fields']);
		break;
		
		case 'Add':
						extAddNew($_POST);
		break;
		
		case 'Edit': 
						$data = extGetData($_POST['id']);
						extEditForm($data);
		break;
		
		case 'Update':
						extUpdate($_POST);
		break;
		
		case 'Delete': 
						extDeleteForm($_POST['id']);
		break;
		
		case 'Confirm':
						extDelete($_POST);
		break;
				
		default:
						extReadData();	//Defined in ext_db_acces.php
		break;
	}
}
add_action('admin_init', 'ext_db_data_init' );
add_action('admin_menu', 'ext_db_data_add_menu');
register_activation_hook( __FILE__, 'ext_db_data_activate' );

//Function to show add new record form
//Parameters: posted data (fields of table)
//Return:
function extAddForm($fields)
{
	?>
	<div class="wrap">
	<h2><?php _e( 'Add Record To External Database' ); ?></h2>	
	<form method="post" action="">
		<table class="form-table">
			<tbody>
			<?php
				foreach($fields as $key => $field)
				{
					?>
					<tr valign="top">
						<th scope="row"><label for="blogdescription"><?php echo ucwords(str_replace('_', ' ', $field)); ?></label></th>
						<td>
							<input type="<?php if(strpos($field,'password') !== false) { echo 'password';} else{ echo 'text';} ?>" class="regular-text" value="" name="<?php echo $field; ?>" />
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<p class="submit">
		<input type="submit" value="Add" class="button button-primary" name="action" />&nbsp;&nbsp;
		<input type="submit" value="Cancel" class="button action" name="cancel" /></p>
	</form>
	<?php
}

//Function to show edit record form with saved data
//Parameters: saved data
//Return:
function extEditForm($data)
{
	?>
	<div class="wrap">
	<h2><?php _e( 'Edit Record from External Database' ); ?></h2>	
	<?php
	if(isset($data))
	{
	?>	
	<form method="post" action="">
	<?php
		foreach($data as $fields)
		{
		?>
			<p class="submit"></p>
			<table class="form-table">
				<tbody>
			<?php
			foreach($fields as $key => $value)
			{
				if($key == 'id')
				{
					?>
					<input type="hidden" class="regular-text" value="<?php echo $value; ?>" name="<?php echo $key; ?>[]">
					<?php
				}
				else
				{
				?>
					<tr valign="top">
						<th scope="row"><label for="blogdescription"><?php echo ucwords(str_replace('_', ' ', $key)); ?></label></th>
						<td>
							<input type="<?php if(strpos($key,'password') !== false) { echo 'password';} else{ echo 'text';} ?>" class="regular-text" value="<?php echo $value; ?>" name="<?php echo $key; ?>[]">
						</td>
					</tr>
				<?php
				}
			}
			?>
				</tbody>
			</table>
			<p class="submit"></p>
			<?php
		}
		?>
		<p class="submit">
		<input type="submit" value="Update" class="button button-primary" name="action">&nbsp;&nbsp;
		<input type="submit" value="Cancel" class="button action" name="cancel"></p>
	</form>
	<?php
	}
	else
	{
	?>
		<p>Please select record to edit.</p>
		<?php echo BACK_BUTTON; ?>
	<?php
	}
}

//Function to show delete record confirmation form
//Parameters: record Id
//Return:
function extDeleteForm($id)
{
	?>
	<div class="wrap">
	<h2><?php _e( 'Delete Record from External Database' ); ?></h2>	
	<?php
	if($id)
	{
	?>	
	<p>Are you sure you wish to delete this record?</p>
	<form method="post" action="">
		<?php
		foreach($id as $value)
		{
		?>
			<input type="hidden" name="id[]" value="<?php echo $value; ?>">
		<?php
		}
		?>
		<input type="submit" value="Confirm" class="button button-primary" name="action">&nbsp;&nbsp;
		<input type="submit" value="Cancel" class="button action" name="cancel"></p>
	</form>
	<?php
	}
	else
	{
	?>
		<p>Please select record to be deleted.</p>
		<?php echo BACK_BUTTON; ?>
	<?php
	}
}
?>