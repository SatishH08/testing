<?php
/*
Plugin Name: External DB Connect.
Plugin URI: www.honeysoft.com/wp/plugins
Description: Used to access data from external database table with WP user credentials and specific access permitions. To get starget: 1) Click the "Activate" link to the left of this description. 2) Go to your <a href="options-general.php?page=external-db-access_by_wp_user/ext_db_aaccess.php">External DB settings</a> page, and save your database details. 3) The database table must have primary key with column name as "id". 4) Plugin will create user to access database with the WP user credentials and selected access permitions. 5) If your WP site already has users then while editing user profile, plugin will create user to external DB. 6) To Create/Update/Delete user in external database call plugin functions in user pages (For more details see readme.txt). 7) You can View/Add/Edit/Delete the data of selected database table by clicking the <a href="admin.php?page=external-db-access_by_wp_user/ext_db_data.php">External DB Data</a> link.
Version: 1.1
Author: Sandip Salunke
Author URI: www.honeysoft.com
Original Author: Sandip Salunke
Original Author URI: www.honeysoft.com

    Copyright 2014  Sandip Salunke  (email : sandy.salunke89@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it  under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    HoneySoft Technologies, 21-A, Nagare Nagar, Sakri, Maharashtra, 424304 India
*/


include_once dirname( __FILE__ ) . '/widget.php';
include('ext_db_data.php');

function ext_db_access_activate() {
	add_option('ext_db_type',"MySQL","External database type");
	add_option('ext_db_mdb2_path',"","Path to MDB2 (if non-standard)");
	add_option('ext_host',"","External database hostname");
	add_option('ext_db_port',"","Database port (if non-standard)");
	add_option('ext_db',"","External database name");
	add_option('ext_table',"","table name");
	add_option('ext_access_type',"ALL","type of access permitions");
	add_option('ext_db_user',"","External database username");
	add_option('ext_db_pw',"","External database password");
	add_option('ext_db_other_enc',"");
	add_option('ext_db_site_url','');
}

function ext_db_access_init(){
	register_setting('ext_db_access','ext_db_type');
	register_setting('ext_db_access','ext_db_mdb2_path');
	register_setting('ext_db_access','ext_host');
	register_setting('ext_db_access','ext_db_port');
	register_setting('ext_db_access','ext_db');
	register_setting('ext_db_access','ext_table');
	register_setting('ext_db_access','ext_access_type');
	register_setting('ext_db_access','ext_db_user');
	register_setting('ext_db_access','ext_db_pw');
	register_setting('ext_db_access','ext_db_other_enc');
	register_setting('ext_db_access','ext_db_error_msg');
	register_setting('ext_db_access','ext_db_site_url');
}

//page for config menu
function ext_db_access_add_menu() {
	add_options_page("External DB settings", "External DB settings", 'manage_options', __FILE__, "ext_db_access_display_options");
}

//actual configuration screen
function ext_db_access_display_options() { 
    $db_types[] = "MySQL";
    $db_types[] = "MSSQL";
    $db_types[] = "PgSQL";
?>
	<div class="wrap">
	<h2><?php _e( 'External Database Access Settings' ); ?></h2>        
	<form method="post" action="options.php">
	<?php settings_fields('ext_db_access'); ?>
        <h3><?php _e( 'External Database Settings' ); ?></h3>
          <strong><?php _e( 'Make sure your WP admin account exists in the external db prior to saving these settings.'); ?></strong>
        <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php _e( 'Database type' ); ?></th>
                <td><select name="ext_db_type" >
                <?php 
                    foreach ($db_types as $key=>$value) { //print out radio buttons
                        if ($value == get_option('ext_db_type'))
                            echo '<option value="'.$value.'" selected="selected">'.$value.'<br/>';
                        else echo '<option value="'.$value.'">'.$value.'<br/>';
                    }                
                ?>
                </select> 
				</td>
				<td>
					<span class="description"><?php _e( 'If not MySQL, requires' ); ?> <a href="http://pear.php.net/package/MDB2/" target="new"><?php _e( 'PEAR MDB2 package' ); ?></a> <?php _e( 'and relevant database driver package installation.' ); ?></span>
				</td>
        </tr>        
        <tr valign="top">
            <th scope="row"><label><?php _e( 'Path to MDB2.php' ); ?></label></th>
				<td><input type="text" name="ext_db_mdb2_path" value="<?php echo get_option('ext_db_mdb2_path'); ?>" /> </td>
				<td><span class="description"><?php _e( 'Only when using non-MySQL database and in case this isn\'t in some sort of include path in your PHP configuration.  No trailing slash! e.g., /home/username/php' ); ?></span></td>
        </tr>
        <tr valign="top">
            <th scope="row"><label><?php _e( 'Host' ); ?></label></th>
				<td><input type="text" name="ext_host" value="<?php echo get_option('ext_host'); ?>" /> </td>
				<td><span class="description"><strong style="color:red;"><?php _e( 'required' ); ?></strong>; <?php _e( '(often localhost)' ); ?></span> </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label><?php _e( 'Port' ); ?></label></th>
				<td><input type="text" name="ext_db_port" value="<?php echo get_option('ext_db_port'); ?>" /> </td>
				<td><span class="description"><?php _e( 'Only set this if you have a non-standard port for connecting.' ); ?></span></td>
        </tr>        
        <tr valign="top">
            <th scope="row"><label><?php _e( 'Username' ); ?></label></th>
				<td><input type="text" name="ext_db_user" value="<?php echo get_option('ext_db_user'); ?>" /></td>
				<td><span class="description"><strong style="color:red;"><?php _e( 'required' ); ?></strong>; <?php _e( '(recommend select privileges only)' ); ?></span></td>
        </tr>
        <tr valign="top">
            <th scope="row"><label><?php _e( 'Password' ); ?></label></th>
				<td><input type="password" name="ext_db_pw" value="<?php echo get_option('ext_db_pw'); ?>" /></td>
				<td><span class="description"></span></td>
        </tr>
        <tr valign="top">
					<th scope="row"><label><?php _e( 'Database Name' ); ?></label></th>
					<td><input type="text" name="ext_db" value="<?php echo get_option('ext_db'); ?>" /></td>
					<td><span class="description"><strong style="color:red;"><?php _e( 'required' ); ?></strong></span></td>
        </tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Table Name' ); ?></label></th>
					<td><input type="text" name="ext_table" value="<?php echo get_option('ext_table'); ?>" /></td>
					<td><span class="description"><strong style="color:red;"><?php _e( 'required' ); ?></strong></span></td>
        </tr>
				<?php
				$access_types = array('ALL', 'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'FILE');
				?>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Access Permitions' ); ?></label></th>
					<td>
						<select name="ext_access_type">
							<?php 
								foreach ($access_types as $key=>$value) { //print out radio buttons
									if ($value == get_option('ext_access_type'))
										echo '<option value="'.$value.'" selected="selected">'.$value.'<br/>';
									else echo '<option value="'.$value.'">'.$value.'<br/>';
								}                
							?>
						</select> 
					</td>
					<td><span class="description"><strong style="color:red;"><?php _e( 'required' ); ?></strong></span></td>
        </tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'External Site URL' ); ?></label></th>
					<td><input type="text" name="ext_db_site_url" value="<?php echo get_option('ext_db_site_url'); ?>" /></td>
					<td><span class="description"></span></td>
				</tr>
			</table>
	<p class="submit">
	<input type="submit" class="button button-primary" name="Submit" value="Save changes" />
	</p>
	</form>
	</div>
<?php
}

//sort-of wraexter for all DB interactions
function db_functions($process,$resource,$query) {
	//Get details of current user
	global $current_user;
	
	//first figure out the DB type and connect...
	$driver = get_option('ext_db_type');
	
	if ($driver == "MySQL") {	//use built-in PHP mysql connection
		switch($process) {
			case "connect" :
					$port = get_option('ext_db_port');                
					if (!empty($port)) $port = ":".get_option('ext_db_port');
					
					$resource =  mysqli_connect(get_option('ext_host').$port,
																			get_option('ext_db_user'),
																			get_option('ext_db_pw'),
																			get_option('ext_db')
																			) or die("Error".mysqli_error($resource));
					return $resource;
					break;
			case "query":
					if(get_option('ext_table')){
						$result = mysqli_query($resource, $query) or die(mysqli_error($resource) . '<br/><br/>'.BACK_BUTTON);
						return $result;
					} else {
						echo '<script>window.location="options-general.php?page=external-database-access-using-wp-user%2Fext_db_aaccess.php";</script>';
					}
					break;            
			case "numrows":
					return mysqli_num_rows($resource);
					break;
			case "fetch":
					return mysqli_fetch_assoc($resource);            
					break;
			case "close":
					mysqli_close($resource);            
					break;
		}
	}
	else {  //Use MDB2   
		$mdbpath = get_option('ext_db_mdb2_path')."/MDB2.php";        
		require_once($mdbpath);
		switch($process) {
			case "connect" :                
					$port = get_option('ext_db_port');
					if (!empty($port))   $port = ":".get_option('ext_db_port');                
					$url = strtolower($driver)."://".$current_user->user_login.":".$current_user->user_pass."@".get_option('ext_host').$port."/".get_option('ext_db');                
					$resource =& MDB2::connect($url);
					if(PEAR::isError($resource)) die("Error while connecting : " . $resource->getMessage());
					return $resource;        
					break;
			case "query":
					if(get_option('ext_table')){
						$result = $resource->query($query);
						if(PEAR::isError($result)) die('Failed to issue query, error message : ' . $result->getMessage());
						return $result;
					} else {
						page_redirect('options-general.php?page=external-database-access-using-wp-user%2Fext_db_aaccess.php');
					}
					break;            
			case "numrows":
					return $resource->numRows();
					break;
			case "fetch":
					return $resource->fetchRow(MDB2_FETCHMODE_ASSOC);                
					break;
			case "close":
					$resource->disconnect();                
					break;
		}
	}
}

//Function to read data from external database
//Parameters: data
//Return:
function extReadData()
{
	$table_name = get_option('ext_table');
	//Make connection to Database
	$resource = db_functions("connect","","");
	
	//Get table column names
	$columns_query = "SHOW COLUMNS FROM `".$table_name."`";
	$columns_qry = db_functions("query",$resource,$columns_query);
	if (db_functions("numrows",$columns_qry,"") > 0) {
		while ($row = db_functions("fetch",$columns_qry,"")) {
				$columns[] = $row[Field];
		}
	}
	
	//Get table data
	$order = 'ORDER BY id ASC';
	if($_REQUEST['orderBy'] && $_REQUEST['order'])
		$order = 'ORDER BY '.$_REQUEST['orderBy']. ' '.$_REQUEST['order'];
	else if($_REQUEST['order'])
		$order = 'ORDER BY id '.$_REQUEST['order'];
	
	//PAGINATION
	$recordsPerPage = 10;
	$page = ($_REQUEST['pg']) ? $_REQUEST['pg'] : 0;
	$prevPage = $page-1;
	$nextPage = $page+1;
	$offset = $page*$recordsPerPage;
	$isPrevious = ($page) ? true : false;
	$isNext = isNextPageAvailable($table_name, $resource, $whereClause, $offset+$recordsPerPage, $recordsPerPage);
	
	//$query = "SELECT * FROM `" . $table_name . "` ".$order.";";
	$query = "SELECT * FROM `$table_name` $whereClause $order LIMIT $offset, $recordsPerPage;";
	$result_qry = db_functions("query",$resource,$query);
	while($row = db_functions("fetch",$result_qry,""))
	{
		$rows[] = $row;
	}
	?>
	<style type="text/css">
	.align-check-column{padding: 15px 6px 10px !important}
	.pg-inactive, .pg-inactive:hover{cursor: not-allowed;color: #999;}
	</style>
	<div class="wrap">
	<h2><?php _e( 'Records of External Database' ); ?></h2>	
	<form method="post" action="">
	<div class="tablenav top">
		<?php if(get_option('ext_access_type') == 'ALL') { ?>
		<input type="submit" value="Add New" class="button action" name="action">
		<input type="submit" value="Edit" class="button action" name="action">
		<input type="submit" value="Delete" class="button action" name="action">
		<?php } else if(get_option('ext_access_type') == 'INSERT') { ?>
		<input type="submit" value="Add New" class="button action" name="action">
		<?php } else if(get_option('ext_access_type') == 'UPDATE') { ?>
		<input type="submit" value="Edit" class="button action" name="action">
		<?php } else if(get_option('ext_access_type') == 'DELETE') { ?>
		<input type="submit" value="Delete" class="button action" name="action">
		<?php } ?>
		<!-- <a class="button action" href="<?php echo PLUGIN_URL; echo ($_REQUEST['order'] == 'DESC')? '&order=ASC' : '&order=DESC'; ?>"><?php echo ($_REQUEST['order'] == 'DESC')? 'ASC' : 'DESC'; ?></a> -->
	</div>
	<table cellspacing="0" class="wp-list-table widefat fixed users">
		<thead>
			<tr>
				<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" id="cb-select-all-1"></th>
				<?php
				if(!empty($columns))
				{
					foreach($columns as $column)
					{	
						if($column != 'id')
						{
							if($_REQUEST['order'] == 'ASC' && $column == $_REQUEST['orderBy'])
								$order = 'DESC';
							else
								$order = 'ASC';
						?>
						<th style="" class="manage-column sortable desc" scope="col">
							<a href="<?php echo getOrderUrl() . '&orderBy='.$column.'&order='.$order; echo ($_REQUEST['pg']) ? '&pg='.$_REQUEST['pg'] : ''; ?>"><span><?php echo ucwords(str_replace('_', ' ', $column)); ?></span><span class="sorting-indicator"></span></a>
							<input type="hidden" name="fields[]" value="<?php echo $column; ?>" />
						</th>
						<?php
						}
					}
				}
				?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" id="cb-select-all-1"></th>
				<?php
				if(!empty($columns))
				{
					foreach($columns as $column)
					{
						if($column != 'id')
						{
							if($_REQUEST['order'] == 'ASC' && $column == $_REQUEST['orderBy'])
								$order = 'DESC';
							else
								$order = 'ASC';
						?>
						<th style="" class="manage-column sortable desc" scope="col">
							<a href="<?php echo getOrderUrl() . '&orderBy='.$column.'&order='.$order; echo ($_REQUEST['pg']) ? '&pg='.$_REQUEST['pg'] : ''; ?>"><span><?php echo ucwords(str_replace('_', ' ', $column)); ?></span><span class="sorting-indicator"></span></a>
						</th>
						<?php
						}
					}
				}
				?>
			</tr>
		</tfoot>
		<tbody data-wp-lists="list:user" id="the-list">
			<?php 
			if(!empty($rows))
			{
				foreach($rows as $row)
				{
				?>
					<tr class="alternate" >
						<th class="check-column" scope="row"><input type="checkbox" value="<?php echo $row['id']; ?>" name="id[]"></th>
						<?php
						foreach($row as $key => $data)
						{
							if($key != 'id')
							{
							?>
								<td class="table_row"><?php if(strpos($key,'password') !== false){echo preg_replace("|.|","*",$data);} else{echo $data;} ?></td>
							<?php
							}
						}
						?>
					</tr>
				<?php
				}
			}
			else {
				echo '<tr class="alternate" ><td colspan="'.count($columns).'">Sorry, no records found</td></td>';
			}
			?>
		</tbody>
	</table>
	
	<table cellspacing="0" class="wp-list-table widefat fixed users">
		<tr class="alternate" >
			<td style="text-align:left;">				
				<a href="<?php echo ($isPrevious) ? getOrderUrl() . getPaginateUrl() . "&pg=$prevPage" : 'javascript:void(0);'; ?>" class="<?php echo ($isPrevious) ? '' : 'pg-inactive'; ?>"><< Previous</a>
			</td>
			<td style="text-align:right;">				
				<a href="<?php echo ($isNext) ? getOrderUrl() . getPaginateUrl() . "&pg=$nextPage" : 'javascript:void(0);'; ?>" class="<?php echo ($isNext) ? '' : 'pg-inactive'; ?>">Next >></a>
			</td>
		</tr>
	</table>
	
	<div class="tablenav top">
		<?php if(get_option('ext_access_type') == 'ALL') { ?>
		<input type="submit" value="Add New" class="button action" name="action">
		<input type="submit" value="Edit" class="button action" name="action">
		<input type="submit" value="Delete" class="button action" name="action">
		<?php } else if(get_option('ext_access_type') == 'INSERT') { ?>
		<input type="submit" value="Add New" class="button action" name="action">
		<?php } else if(get_option('ext_access_type') == 'UPDATE') { ?>
		<input type="submit" value="Edit" class="button action" name="action">
		<?php } else if(get_option('ext_access_type') == 'DELETE') { ?>
		<input type="submit" value="Delete" class="button action" name="action">
		<?php } ?>
		<!-- <a class="button action" href="<?php echo PLUGIN_URL; echo ($_REQUEST['order'] == 'DESC')? '&order=ASC' : '&order=DESC'; ?>"><?php echo ($_REQUEST['order'] == 'DESC')? 'ASC' : 'DESC'; ?></a> -->
	</div>
	</form>
	<?php
	
	//Close Database connection after user
	db_functions('close',$resource,'');
}

//Function to insert record in selected table in external database 
//Parameters: posted data
//Return:
function extAddNew($post)
{
	if(!empty($post))
	{
		array_pop($post);
		//Make connection to Database
		$resource = db_functions("connect","","");
		$query = "INSERT INTO `" . get_option('ext_table') . "` (`".implode(array_keys($post), "`,`")."`) VALUES ('".implode($post, "','")."');";
		$result_qry = db_functions("query",$resource,$query);
		db_functions('close',$resource,'');
	}
	//wp_redirect(PLUGIN_URL, 301); exit;
	page_redirect($_SERVER['REQUEST_URI']);
}

//Function to show records details from selected table in external database 
//Parameters: record id
//Return: record details
function extGetData($ids)
{
	if(!empty($ids))
	{
		$data = array();
		foreach($ids as $id)
		{
			$query = "SELECT * FROM " . get_option('ext_table') . " WHERE id=".$id.";";
			$resource = db_functions("connect","","");
			$result_qry = db_functions("query",$resource,$query);
			$data[] = db_functions("fetch",$result_qry,"");
		}
		return $data;
	}
}

//Function to update record in selected table in external database 
//Parameters: posted data
//Return:
function extUpdate($data)
{
	array_pop($data);
	foreach($data as $key => $values)
	{
		$column_names[] = $key;	
	}
	
	for($i=0; $i<count($data[$column_names[0]]); $i++)
	{
		$query = "UPDATE `" . get_option('ext_table') . "` SET ";
		foreach($column_names as $value)
		{
			if($value == 'id')
				$qry_where = " WHERE `".$value."`='".$data[$value][$i]."'";
			else
				$query .= "`".$value."`='".$data[$value][$i]."', ";			
		}
		$query = trim($query);
		$query = trim($query, ',');
		$query = $query . $qry_where;
		//Make connection to Database
		$resource = db_functions("connect","","");
		$result_qry = db_functions("query",$resource,$query);
		db_functions('close',$resource,'');
	}
	//wp_redirect(PLUGIN_URL, 301);
	page_redirect($_SERVER['REQUEST_URI']);
}

//Function to delete record from selected table in external database 
//Parameters: record id
//Return:
function extDelete($id)
{
	if(!empty($id))
	{
		array_pop($id);
		$query = "DELETE FROM `" . get_option('ext_table') . "` WHERE `id` IN(".implode($id['id'], ',').");";
		//Make connection to Database
		$resource = db_functions("connect","","");
		$result_qry = db_functions("query",$resource,$query);
		db_functions('close',$resource,'');
	}
	//wp_redirect(PLUGIN_URL, 301); exit;
	page_redirect($_SERVER['REQUEST_URI']);
}

//Function to create user in external database
//Parameters: userName, password
//Return:
function extDbCreateUser($id)
{
	$userDetails = get_userdata($id);
	if($userDetails)
	{
		$user 		= get_option('ext_db_user');
		$pwd 		= get_option('ext_db_pw');
		$db 		= get_option('ext_db');
		$host 		= get_option('ext_host');
		$accessType = get_option('ext_access_type');

		if($user && $db && $host)
		{
			$newdb = new wpdb($user, $pwd, $db, $host);
			$newdb->show_errors();
			$query = "GRANT " . $accessType . " ON * . * TO '" . $userDetails->user_login . "'@'localhost' IDENTIFIED BY '" . $userDetails->user_pass . "';";
			$newdb->query($query);
			unset($newdb);
		}
	}
}

//Function to update user from external database
//Parameters: userId (UserName)
//Return:
function extDbUpdateUser($id)
{
	$userDetails = get_userdata($id);
	if($userDetails)
	{
		$user = get_option('ext_db_user');
		$pwd = get_option('ext_db_pw');
		$db = get_option('ext_db');
		$host = get_option('ext_host');
		$accessType = get_option('ext_access_type');
		
		if($user && $db && $host)
		{
			$newdb = new wpdb($user, $pwd, $db, $host);
			$newdb->show_errors();
			
			//Check if user exist
			$query = "SELECT * FROM mysql.user WHERE `user`.`User` = '".$userDetails->user_login."';";
			$user = $newdb->query($query);
			
			if($user == 1) //if exist: Update details
			{
				$query = "UPDATE `mysql`.`user` SET `password` = PASSWORD('" . $userDetails->user_pass . "') WHERE `user`.`Host` = 'localhost' AND `user`.`User` = '" . $userDetails->user_login . "';";
				$newdb->query($query);
				$newdb->query('FLUSH PRIVILEGES;');
			}
			else	//not exist create new
			{
				$query = "GRANT " . $accessType . " ON * . * TO '" . $userDetails->user_login . "'@'localhost' IDENTIFIED BY '" . $userDetails->user_pass . "';";
				$newdb->query($query);
			}
			unset($newdb);
		}
	}
}

//Function to delete user from external database
//Parameters: userId (UserName)
//Return:
function extDbDeleteUser($id)
{
	$userDetails = get_userdata($id);
	if($userDetails)
	{
		$user 	= get_option('ext_db_user');
		$pwd 	= get_option('ext_db_pw');
		$db 	= get_option('ext_db');
		$host 	= get_option('ext_host');
		
		if($user && $db && $host)
		{
			$newdb = new wpdb($user, $pwd, $db, $host);
			$newdb->show_errors();
			$query = "DROP USER '" . $userDetails->user_login . "'@'localhost';";
			$newdb->query($query);
			unset($newdb);
		}
	}
}

//Function to redirect the page to given URL
//Parameters: $urlToRedirect
//Return:
function page_redirect($urlToRedirect){
	echo '<script>window.location="'.$urlToRedirect.'";</script>'; die;
}

//Function to get seperate URL's for front and admin panel for order by operations
//Parameters: 
//Return: URL
function getOrderUrl(){
	if(strpos($_SERVER['PHP_SELF'],'admin') !== false)
		return PLUGIN_URL;
	else
		return $_SERVER['PHP_SELF'].'?';
}


//Function to get respective url for pagination
//Parameters: 
//Return: URL
function getPaginateUrl(){
	$returnParams = '';
	$returnParams .= ($_REQUEST['orderBy']) ? '&orderBy='.$_REQUEST['orderBy'] : '';
	$returnParams .= ($_REQUEST['order']) ? '&order='.$_REQUEST['order'] : '';
	return $returnParams;
}

//Function to know if next page is available
//Parameters: Database table name, Database instance, where clause, limit offset
//Return: boolean
function isNextPageAvailable($table_name, $resource, $whereClause, $offset, $recordsPerPage){
	$query = "SELECT * FROM `$table_name` $whereClause $order LIMIT $offset, $recordsPerPage;";
	$result_qry = db_functions("query",$resource,$query);
	$rowCount = db_functions("numrows",$result_qry,"");
	return ($rowCount) ? true : false;
}

add_action('admin_init', 'ext_db_access_init' );
add_action('admin_menu', 'ext_db_access_add_menu');
register_activation_hook( __FILE__, 'ext_db_access_activate' );

?>