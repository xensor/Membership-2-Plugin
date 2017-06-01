<?php
if ($_POST['oscimp_hidden'] == 'Y') {
  //Form data sent
  $dbhost          = $_POST['ban_id'];

  $dbname          = $_POST['staff_id'];

  $dbuser          = $_POST['gym_leader'];

  $dbpwd           = $_POST['maint_url'];

  $prod_img_folder = $_POST['ban_url'];
  $maint_status    = $_POST['maint_status'];
  $banned_page     = $_POST['ban_page'];
  $maint_pages     = $_POST['maint_page'];
  global $wpdb;
  if (isset($dbhost) && isset($dbname) && isset($dbuser) && isset($dbpwd) && isset($prod_img_folder) && isset($maint_status) && isset($maint_pages) && isset($banned_page)) {
    $table_name = $wpdb->prefix . 'maint';
    $wpdb->update( $table_name, array(
        'bid'       => $dbhost,
        'sid'       => $dbname,
        'gid'       => $dbuser,
        'mid'       =>$dbpwd,
        'ban_url'   => $prod_img_folder,
        'maint'     => $maint_status,
        'ban_page'  =>$banned_page,
        'maint_page'=> $maint_pages
      ),array('maint_id'=>1) );

    $maint = $wpdb->get_results( 'SELECT * FROM wprh_maint WHERE maint_id = 1', OBJECT );
    //Normal page display
    $bid     = $maint[0]->bid;
    $sid     = $maint[0]->sid;
    $gid     = $maint[0]->gid;
    $mid     = $maint[0]->mid;
    $ban_url = $maint[0]->ban_url;
    $maints  = $maint[0]->maint;
    ?>
    <div class="updated">
      <p>
        <strong>
          <?php _e('Options saved.' ); ?>
        </strong>
      </p>
    </div>
    <?php
  }
}
else {
  global $wpdb;
  $maint = $wpdb->get_results( 'SELECT * FROM wprh_maint WHERE maint_id = 1', OBJECT );
  //Normal page display
  $bid        = $maint[0]->bid;
  $sid        = $maint[0]->sid;
  $gid        = $maint[0]->gid;
  $mid        = $maint[0]->mid;
  $ban_url    = $maint[0]->ban_url;
  $maints     = $maint[0]->maint;
  $ban_page   = $maint[0]->ban_page;
  $maint_page = $maint[0]->maint_page;
  ?>

  <?php
}
?>
<div class="wrap">
  <?php    echo "<h2>" . __( 'maintenance Options', 'oscimp_trdom' ) . "</h2>"; ?>

  <form name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="oscimp_hidden" value="Y">
    <?php    echo "<h4>" . __( 'Maintenance Settings', 'oscimp_trdom' ) . "</h4>"; ?>
    <p>
      <?php _e("Banned Id: " ); ?><input type="text" name="ban_id" value="<?php echo $bid; ?>" size="20"><?php _e(" ex: 25878" ); ?>
    </p>
    <p>
      <?php _e("default Id: " ); ?><input type="text" name="staff_id" value="<?php echo $sid; ?>" size="20"><?php _e(" ex: 22545" ); ?>
    </p>
    <p>
      <?php _e("Guest Id: " ); ?><input type="text" name="gym_leader" value="<?php echo $gid; ?>" size="20"><?php _e(" ex: 58758" ); ?>
    </p>

    <hr />
    <?php    echo "<h4>" . __( 'Maint Url Settings', 'oscimp_trdom' ) . "</h4>"; ?>
    <p>
      <?php _e("maintenance URL: " ); ?><input type="text" name="maint_url" value="<?php echo $mid; ?>" size="20"><?php _e(" ex: http://www.yourstore.com/" ); ?>
    </p>

    <p>
      <?php _e("ban url: " ); ?><input type="text" name="ban_url" value="<?php echo $ban_url; ?>" size="20"><?php _e(" ex: /ban" ); ?>
    </p>
    <p>
      <?php _e("ban Page: " ); ?><input type="text" name="ban_page" value="<?php echo $ban_page; ?>" size="20"><?php _e(" ex: /ban" ); ?>
    </p>
    <p>
      <?php _e("maintenance page: " ); ?><input type="text" name="maint_page" value="<?php echo $maint_page; ?>" size="20"><?php _e(" ex: /ban" ); ?>
    </p>
    <p>
      <?php _e("Maintenance Status: " ); ?>
      <select name="maint_status">


        <option value='yes' <?php
      if ($maints == 'yes') {
      echo 'selected';
    }
else {

    }?>>
          Yes
        </option>
        <option value='no' <?php
      if ($maints == 'no') {
      echo 'selected';
    }
else {

    }?>>
          No
        </option>
      </select><?php _e(" ex: secretpassword" ); ?>
    </p>

    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Update Options', 'oscimp_trdom' ) ?>" />
    </p>
  </form>
</div>
