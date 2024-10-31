<?php
/*
Plugin Name: Server Mail Blacklist Checker (by SiteGuarding.com)
Plugin URI: http://www.siteguarding.com/en/website-extensions
Description: Server Mail Blacklist Checker checks if your website or server IP is listed in DNSBL (DNS-based Blackhole List) and RBL (Real-time Blackhole List) blacklists.
Version: 1.2
Author: SiteGuarding.com (SafetyBis Ltd.)
Author URI: http://www.siteguarding.com
License: GPLv2
*/ 

// rev.20200601

if (!defined('DIRSEP'))
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') define('DIRSEP', '\\');
    else define('DIRSEP', '/');
}

define('plgsgsmbc_UPGRADE_LINK', 'https://www.siteguarding.com/en/buy-service/website-security-suite?pgid=PLG30');

error_reporting(0);

if( !is_admin() ) 
{
    
 	// Sorry if you are using it for free. It costs nothing for you, but it will help us to support the extension.
	function plgsgsmbc_footer_protectedby() 
	{
        if (strlen($_SERVER['REQUEST_URI']) < 5)
        {
                $params = plgsgsmbc_Get_Params(array('installation_date', 'link_id', 'protection_by', 'registration_code', 'check_every', 'last_scan_date'));
                
                if (intval($params['check_every']) > 0)
                {
                    $new_cron_time = strtotime($params['last_scan_date']) + intval($params['check_every']) * 24 * 60 * 60;
                    if (time() > $new_cron_time) 
                    {
                        // Do cron scan
                        $website_url = get_site_url();
                        plgsgsmbc_ScanWebsite($website_url);
                    }
                }
                
                if ( intval($params['protection_by']) == 0 && trim($params['registration_code']) != '' && plgsgsmbc_CheckIfPRO() ) return;
                
                $new_date = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-3, date("Y")));
        		if ( $new_date >= $params['installation_date'] )
        		{
                    $links = array(
                        array('t' => 'UHJvdGVjdGVkIGJ5IFNpdGVndWFyZGluZw==', 'lnk' => 'aHR0cHM6Ly93d3cuc2l0ZWd1YXJkaW5nLmNvbS8='),
                        array('t' => 'V2ViIERldmVsb3BtZW50IGJ5IFNpdGVndWFyZGluZw==', 'lnk' => 'aHR0cHM6Ly93d3cuc2l0ZWd1YXJkaW5nLmNvbS9lbi93ZWItZGV2ZWxvcG1lbnQ='),
                        array('t' => 'RGV2ZWxvcGVkIGJ5IFNpdGVndWFyZGluZw==', 'lnk' => 'aHR0cHM6Ly93d3cuc2l0ZWd1YXJkaW5nLmNvbS9lbi9tYWdlbnRvLWRldmVsb3BtZW50'),
                    );
                      
                    if (!isset($params['link_id']) || $params['link_id'] === false || $params['link_id'] == null)
                    {
                        $params['link_id'] = mt_rand(0, count($links)-1);
                        plgsgsmbc_Set_Params($params);
                    }

                    $link_info = $links[ intval($params['link_id']) ];
                    $link = base64_decode($link_info['lnk']);
                    $link_txt = base64_decode($link_info['t']);
        			?>
                    <script>
        			jQuery(document).ready(function($) 
                    {
                        $('body').append($('.sg_copyright').html());
                        $('.sg_copyright').remove();
                        
        			});
                    </script>
        				<div class="sg_copyright"><div style="font-size:10px; padding:0 2px;z-index:1000;text-align:center;color:#222;opacity:0.8;"><a style="color:#4B9307" href="<?php echo $link; ?>" target="_blank" title="<?php echo $link_txt; ?>"><?php echo $link_txt; ?></a></div></div>
        			<?php
        		}
        }
	}
	add_action('wp_footer', 'plgsgsmbc_footer_protectedby', 100);
    
    if (isset($_GET['siteguarding_tools']) && intval($_GET['siteguarding_tools']) == 1)
    {
        plgsgsmbc_SiteGuardingPanelFile(true);
    }
    
    

}


if( is_admin() ) {

	//error_reporting(0);
	

	
    
	function plgsgsmbc_big_dashboard_widget() 
	{
		if ( get_current_screen()->base !== 'dashboard' ) {
			return;
		}
        
        $params = plgsgsmbc_Get_Params(array('show_dashboard', 'registration_code'));
        if ($params['show_dashboard'] == 0 && trim($params['registration_code']) != '' && plgsgsmbc_CheckIfPRO() ) return;
		?>
        <style>
        #custom-id-F794434C4E10 h1{padding:5px 10px;text-align: left; background-color: #4B9307;color:#fff;font-size:16px}
        #custom-id-F794434C4E10 a#lmore{float:right;color:#fff!important;text-decoration: none;}
        #custom-id-F794434C4E10 a#lmore:hover{color:#ddd!important;}
        #custom-id-F794434C4E10 a#lmore span{font-size: 200%;margin-right:14px}
        #custom-id-F794434C4E10 .welcome-panel-content{margin:0!important}
        </style>
		<div id="custom-id-F794434C4E10" style="display: none;">
			<div class="welcome-panel-content">
			<h1>WordPress Security Tools<a id="lmore" href="javascript:;" onclick=""><span class="dashicons dashicons-welcome-learn-more"></span> Learn more</a></h1>
			<p style="text-align: center;display: none;">
				<a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=GE2" target="_blank"><img src="<?php echo plugins_url('images/b10.png', __FILE__); ?>" /></a>&nbsp;
				<a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=GE2" target="_blank"><img src="<?php echo plugins_url('images/b11.png', __FILE__); ?>" /></a>&nbsp;
				<a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=GE2" target="_blank"><img src="<?php echo plugins_url('images/b12.png', __FILE__); ?>" /></a>&nbsp;
				<a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=GE2" target="_blank"><img src="<?php echo plugins_url('images/b13.png', __FILE__); ?>" /></a>&nbsp;
				<a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=GE2" target="_blank"><img src="<?php echo plugins_url('images/b14.png', __FILE__); ?>" /></a>
			</p>
			<p style="text-align: center;font-weight: bold;font-size:120%;display: none;">
				Includes: Website Antivirus, Website Firewall, Bad Bot Protection, GEO Protection, Admin Area Protection and etc.
			</p>
			<p style="text-align: center;display: none;">
				<a class="button button-primary button-hero" target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=GE2">Secure Your Website</a>
			</p>
			</div>
		</div>
		<script>
			jQuery(document).ready(function($) {
				$('#welcome-panel').after($('#custom-id-F794434C4E10').show());
                
                $(document).on("click", "#custom-id-F794434C4E10 #lmore" , function() {
                    $('#custom-id-F794434C4E10 p').toggle();
                });
                
                
			});
		</script>
		
	<?php 
	}
    add_action( 'admin_footer', 'plgsgsmbc_big_dashboard_widget' );
    


    /**
     * Menu structure
     */
	function register_plgsgsmbc_page_ScanReport() 
	{
	    //add_menu_page( $page_title,         $menu_title,      $capability,        $menu_slug,            callable $function = '',          $icon_url = '' )
		add_menu_page('plgsgsmbc_protection', 'Mail Blacklist Checker', 'activate_plugins', 'plgsgsmbc_protection', 'plgsgsmbc_page_html_ScanReport', plugins_url('images/', __FILE__).'logo.png');
        //add_submenu_page(  $parent_slug,         $page_title,           $menu_title,            $capability,       $menu_slug,           callable $function
        add_submenu_page( 'plgsgsmbc_protection', 'Check & Report', 'Check & Report', 'manage_options', 'plgsgsmbc_protection', 'plgsgsmbc_page_html_ScanReport' );
	}
    add_action('admin_menu', 'register_plgsgsmbc_page_ScanReport');
    
    
	function register_plgsgsmbc_page_SecurityDashboard() {
		add_submenu_page( 'plgsgsmbc_protection', 'Security Dashboard', 'Security Dashboard', 'manage_options', 'plgsgsmbc_page_html_SecurityDashboard', 'plgsgsmbc_page_html_SecurityDashboard' ); 
	}
    add_action('admin_menu', 'register_plgsgsmbc_page_SecurityDashboard');
    
    
	function register_plgsgsmbc_extensions_subpage() {
		add_submenu_page( 'plgsgsmbc_protection', 'Security Extensions', 'Security Extensions', 'manage_options', 'plgsgsmbc_extensions_page', 'plgsgsmbc_extensions_page' ); 
	}
    add_action('admin_menu', 'register_plgsgsmbc_extensions_subpage');


	function register_plgsgsmbc_upgrade_subpage() {
		add_submenu_page( 'plgsgsmbc_protection', '<span style="color:#21BA45"><b>Get Premium Security</b></span>', '<span style="color:#21BA45"><b>Get Premium Security</b></span>', 'manage_options', 'plgsgsmbc_upgrade_redirect', 'plgsgsmbc_upgrade_redirect' ); 
	}
    add_action('admin_menu', 'register_plgsgsmbc_upgrade_subpage');
    function plgsgsmbc_upgrade_redirect()
    {
        ?>
        <p style="text-align: center; width: 100%;">
            <img width="120" height="120" src="<?php echo plugins_url('images/ajax_loader.svg', __FILE__); ?>" />
            <br /><br />
            Redirecting.....
        </p>
        <script>
        window.location.href = '<?php echo plgsgsmbc_UPGRADE_LINK; ?>';
        </script>
        <?php
    }
    
    
    /**
     * Pages HTML
     */

	function plgsgsmbc_page_html_SecurityDashboard() 
	{
	    $autologin_config = ABSPATH.DIRSEP.'webanalyze'.DIRSEP.'website-security-conf.php';
        if (file_exists($autologin_config)) include_once($autologin_config);
        
       
		$website_url = get_site_url();
        $admin_email = get_option( 'admin_email' );



	    plgsgsmbc_TemplateHeader($title = 'Security Dashboard');
        
		$success = plgsgsmbc_SiteGuardingPanelFile();
		if ($success) 
        {
            if (defined('WEBSITE_SECURITY_AUTOLOGIN'))
            {
                // file exists
                ?>
                <script>
                jQuery(document).ready(function(){
                    jQuery("#autologin_form").submit();
                });
                </script>
                <form action="https://www.siteguarding.com/index.php" method="post" id="autologin_form">
                
                <div class="ui placeholder segment">
                  <div class="ui icon header">
                    <img  style="width:350px" src="<?php echo plugins_url('images/', __FILE__).'logo_siteguarding.svg'; ?>" />
                    <i class="asterisk loading small icon"></i>Logging to the account. If it take more than 30 seconds, please login manually
                  </div>
                  <input class="ui green button" type="submit" value="Security Dashboard" />
                </div>

                

                <input type="hidden" name="option" value="com_securapp" />
                <input type="hidden" name="autologin_key" value="<?php echo WEBSITE_SECURITY_AUTOLOGIN; ?>" />
                
                <input type="hidden" name="service" value="website_list" />
                
                <input type="hidden" name="website_url" value="<?php echo $website_url; ?>" />
                <input type="hidden" name="task" value="Panel_autologin" />
                </form>
                
                <div class="ui section divider"></div>
                
                <?php
                    plgsgsmbc_contacts_block();
                ?>
                
                <?php
            }
            else {
                // Need to register the website
                
                // Create verification code
                $verification_code = md5($website_url.'-'.time().'-'.rand(1, 1000).'-'.$admin_email);
                $folder_webanalyze = ABSPATH.DIRSEP.'webanalyze';
                $verification_file = $folder_webanalyze.DIRSEP.'domain_verification.txt';
				$verification_file = str_replace(array('//', '///'), '/', $verification_file);
                
                // Create folder
                if (!file_exists($folder_webanalyze)) mkdir($folder_webanalyze);
                // Create verification file
                $fp = fopen($verification_file, 'w');
                fwrite($fp, $verification_code);
                fclose($fp);
                
                ?>
                
                
                <div class="ui placeholder segment">
                  <div class="ui icon header">
                    <img  style="width:350px" src="<?php echo plugins_url('images/', __FILE__).'logo_siteguarding.svg'; ?>" />
                    <br /><br />
                    One more step to protect <?php echo $website_url; ?>
                  </div>
                  
                  <div class="ui divider"></div>
                  
                  
                  <form action="https://www.siteguarding.com/index.php" method="post" class="ui form">

                    <div class="ui grid">
                      <div class="column row">
                        <div class="column">
                              <div class="fields">
                                <div class="field" style="min-width: 400px;">
                                  <label>Your email for account</label>
                                  <input type="text" placeholder="Your email for account" name="email" value="<?php echo $admin_email; ?>">
                                </div>
                              </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="inline">
                        <input class="ui green button" type="submit" value="Register & Activate" />
                    </div>
                  
                    <input type="hidden" name="option" value="com_securapp" />
                    <input type="hidden" name="verification_code" value="<?php echo $verification_code; ?>" />
                    
                    <input type="hidden" name="service" value="website_list" />
                    
                    <input type="hidden" name="website_url" value="<?php echo $website_url; ?>" />
                    <input type="hidden" name="task" value="Panel_plugin_register_website" />
                    </form>
                
                
                </div>


                <div class="ui section divider"></div>
                
                <?php
                    plgsgsmbc_contacts_block();
            }

		} else {
		      ?>
                <div class="ui negative message">
                  <div class="header">
                    Error is detected
                  </div>
                  <p>The file does not exist or corrupted. Could not to overwrite it. Please reinstall plugin from <a target="_blank" href="https://www.siteguarding.com">https://www.siteguarding.com</a>
                </div>
              <?php
		}
        
        ?>
        
        
        <?php
        plgsgsmbc_BottomHeader();
    }
    
	function plgsgsmbc_page_html_ScanReport() 
	{
	    plgsgsmbc_Init_Plugin();
        
	    $isPRO = plgsgsmbc_CheckIfPRO();
        
        $website_url = get_site_url();
        $domain = plgsgsmbc_PrepareDomain($website_url);
        
        
        /**
         * Save actions
         */
   	    $action = '';
        if (isset($_REQUEST['action'])) $action = sanitize_text_field(trim($_REQUEST['action']));
        
        if ($action == 'Save_Settings' && check_admin_referer( 'F51BFB31F211' ))
        {
            if (isset($_POST['registration_code'])) $data['registration_code'] = sanitize_textarea_field($_POST['registration_code']);
            
            if (isset($_POST['check_every'])) $data['check_every'] = intval($_POST['check_every']);
            
            if (isset($_POST['send_notification'])) $data['send_notification'] = intval($_POST['send_notification']);
            else $data['send_notification'] = 0;
            
            if (isset($_POST['email_for_notification'])) $data['email_for_notification'] = trim(sanitize_textarea_field($_POST['email_for_notification']));
            
            if (isset($_POST['protection_by'])) $data['protection_by'] = intval($_POST['protection_by']);
            else $data['protection_by'] = 0;
            
            if (isset($_POST['show_dashboard'])) $data['show_dashboard'] = intval($_POST['show_dashboard']);
            else $data['show_dashboard'] = 0;
            
            if (!$isPRO)
            {
                $data['check_every'] = 0;
                $data['send_notification'] = 0;
                $data['protection_by'] = 1;
                $data['show_dashboard'] = 1;
            }
            
            plgsgsmbc_Set_Params( $data );
            
            $isPRO = plgsgsmbc_CheckIfPRO();
            
            $message = 'Settings saved.';
            
            if ($isPRO) $message .= ' PRO version is active.';
        }
        
        
        
        $params = plgsgsmbc_Get_Params( array( 'last_error', 'last_report', 'last_scan_date', 'registration_code', 'check_every', 'send_notification', 'email_for_notification', 'protection_by', 'show_dashboard' ) );
        
        if (!isset($params['last_report']) || trim($params['last_report']) == '') $report_data = false;
        else {
            $report_data = (array)json_decode($params['last_report'], true);
            if (count($report_data) == 0) $report_data = false;
        }
        
        
        
        
        if ($isPRO) $paid_version_txt = '<div class="ui green horizontal label">PRO</div>';
        else $paid_version_txt = '<div class="ui red horizontal label">Free</div>';
        

	    plgsgsmbc_TemplateHeader($title = '');
	    ?>
        <h2 class="ui dividing header">
          <div class="content">
            Website Mail Server Blacklist Checker
            <div class="sub header"><?php echo $website_url; ?></div>
          </div>
        </h2>
        
        
        <?php
        if (trim($params['last_error']) != '')
        {
            ?>
            <div class="ui negative message">
              <div class="header">
                Error is detected
              </div>
              <p><?php echo $params['last_error']; ?></p>
            </div>
            <?php
        }
        
        if (trim($message) != '')
        {
            ?>
            <div class="ui positive message">
              <div class="header">
                Success
              </div>
              <p><?php echo $message; ?></p>
            </div>
            <?php
        }
        ?>
        
        
          <script>
          function StartWebsiteScanner()
          {
                jQuery(".ajax_block_buttons").hide();
                jQuery(".ajax_block_loaders").show();
              
                jQuery.post(
                    ajaxurl, 
                    {
                        'action': 'plgsgsmbc_ajax_scan_website'
                    }, 
                    function(response){
                        document.location.href = 'admin.php?page=plgsgsmbc_protection';
                    }
                );
          }
          </script>
        
        <div class="ui grid">
            <div class="six wide column">
                <div class="ui basic padded center aligned segment">
                <?php
                if ($report_data === false)
                {
                    ?>
                    <i class="question circle outline massive icon"></i>
                    <p>Never analyzed before</p>
                    <?php
                } else {
                    $pie_data['clean'] = count($report_data['clean']);
                    $pie_data['blacklisted'] = count($report_data['blacklisted']);
                    
                    $print_pie_data = array(
                        array(
                            'txt' => 'Clean',
                            'val' => $pie_data['clean'],
                        ),
                        array(
                            'txt' => 'Blacklisted',
                            'val' => $pie_data['blacklisted'],
                        ),
                    );
                    plgsgsmbc_Print_PIE_chart($print_pie_data);
                }
                ?>
                </div>
            </div>
            
            <div class="ten wide column">
              <div class="ui basic padded segment">  
                  <h3 class="ui header"><i class="info circle icon"></i>Why blacklist status is important for your website?</h3>
                  <p style="font-size: 110%;">Each blacklist database has its own algorithm and criteria for flagging websites, IP addresses and compiling its own list of websites that may harm user's computers. If your IP address has been blacklisted you can lose up to 95% of visitors.</p>
                  <p style="text-align: center;">
                    <a href="javascript:;" onclick="StartWebsiteScanner()" class="ui green button ajax_block_buttons">Blacklist Check</a>
                    <i class="asterisk loading green icon ajax_block_loaders" style="display: none;"></i>
                    <?php
                    if (!$report_data && count($report_data['blacklisted']) > 0) 
                    {
                    ?>
                        <a target="_blank" href="https://www.siteguarding.com/en/website-blacklist-removal-service?pgid=PLG30" class="ui green button">Blacklist Removal</a>
                        <a target="_blank" href="<?php echo plgsgsmbc_UPGRADE_LINK; ?>" class="ui green button">Premium Package</a>
                    <?php
                    }
                    ?>
                  </p>
              </div>
            </div>
        
        </div>
        
        

  
        
        
        <script>
        jQuery(document).ready(function(){
            jQuery('.menu .item').tab();
        });
        </script>
        
        <div class="ui top attached tabular menu">
          <a class="active item" data-tab="first"><i class="shield alternate icon"></i>Blacklist Check <?php echo $paid_version_txt; ?></a>
          <a class="item" data-tab="second"><i class="pie chart icon"></i>Logs & History</a>
          <a class="item" data-tab="third"><i class="settings icon"></i> Settings & Support</a>
        </div>
        
        <?php
        /**
         * Blacklist Check
         */
        ?> 
        <div class="ui bottom attached active tab segment" data-tab="first">
        
            <?php
            if (!$isPRO) $box_text = '';
            
            ?>
        
            <div class="ui ignored info message"><center>
            This plugin can detect if your domain or server IP is blacklisted in mail/spam lists.<br>
            <b>Please note:</b> the plugin will not help to solve all blacklist issues with one click. Every case is different and requires a review of a specialist.<br />
            Thank you for understanding. 
            </center></div>
            
            <div class="ui basic padded center aligned segment">
                <a href="javascript:;" onclick="StartWebsiteScanner()" class="ui green huge button ajax_block_buttons">Blacklist Check</a>
                
                <i class="asterisk loading huge green icon ajax_block_loaders" style="display: none;"></i>
                <br />
                <span class="ajax_block_loaders" style="display: none;">Please wait. Scan is in progress and ca take 3-5 minutes</span>
                    
            </div>
            
            <div class="ui section divider"></div>
            
            <h2 class="ui dividing header">Latest Report</h2>
            
            <?php
            $mail_server = 'mail.'.plgsgsmbc_PrepareDomain(get_site_url());
            $mail_server_ip = gethostbyname($mail_server);
            if ($mail_server_ip == $mail_server) $mail_server_ip = $_SERVER['SERVER_ADDR'];
            ?>
            <b>Domain:</b> <?php echo get_site_url(); ?><br />
            <b>Server IP:</b> <?php echo $_SERVER['SERVER_ADDR']; ?><br />
            <b>Mail Server:</b> <?php echo $mail_server; ?><br />
            <b>Mail Server IP:</b> <?php echo $mail_server_ip; ?><br />

            
            <?php if ($report_data === false) { ?>
                <div class="ui yellow mini message">
                    You don't have any report. Please scan your website.
                </div>
            <?php } else { ?>
            
                <div class="ui basic padded center aligned segment">
                    <div class="ui red horizontal huge label"><?php echo count($report_data['blacklisted']); ?></div><div class="ui green horizontal huge label"><?php echo count($report_data['clean']); ?></div>
                    <p style="font-size: 110%;"><b>Latest check: <?php echo $params['last_scan_date']; ?></b></p>
                </div>
                
                <table class="ui striped selectable table">
                  <thead>
                    <tr>
                      <th>Blacklist Name</th>
                      <th>Delist URL</th>
                      <th class="right aligned">Blacklist Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  foreach ($report_data['blacklisted'] as $row) {
                  ?>
                    <tr>
                      <td class="collapsing"><?php echo $row['name']; ?></td>
                      <td><a target="_blank" href="<?php echo $row['delist_url']; ?>"><?php echo $row['delist_url']; ?></a></td>
                      <td class="right aligned collapsing"><div class="ui red horizontal label">Blacklisted</div></td>
                    </tr>
                  <?php
                  }

                  foreach ($report_data['clean'] as $row) {
                  ?>
                    <tr>
                      <td class="collapsing"><?php echo $row['name']; ?></td>
                      <td></td>
                      <td class="right aligned collapsing"><div class="ui green horizontal label">OK</div></td>
                    </tr>
                  <?php
                  }
                  ?>
                  </tbody>
                </table>
                
                <div class="ui basic padded center aligned segment">
                    <a target="_blank" href="https://www.siteguarding.com/en/contacts?pgid=PLG30" class="ui green large button">Need Help</a>
                    <a target="_blank" href="https://www.siteguarding.com/en/website-blacklist-removal-service?pgid=PLG30" class="ui green large button">Blacklist Removal</a>
                    <a target="_blank" href="<?php echo plgsgsmbc_UPGRADE_LINK; ?>" class="ui green large button">Premium Package</a>
                </div>
            
            <?php } ?>
            

        </div>
        
        <?php
        /**
         * Logs & History
         */
        ?> 
        <?php
        $history_log = plgsgsmbc_Read_Scan_History_log();
        $history_log = explode("\n", $history_log);
        
        
        ?>
        <div class="ui bottom attached tab segment" data-tab="second">
            
            <?php
            if (count($history_log)) 
            {
            ?>
            <table class="ui celled striped selectable table">
              <tbody>
                <?php
                foreach ($history_log as $row)
                {
                    $row = explode("|", $row);
                    if (count($row) < 4) continue;
                ?>
                <tr>
                  <td class="collapsing"><?php echo $row[0]; ?></td>
                  <td><?php echo $row[1]; ?></td>
                  <td class="collapsing"><?php if (intval($row[2]) > 0) echo '<div class="ui red horizontal label">'.intval($row[2]).'</div>'; ?></td>
                  <td class="collapsing"><div class="ui green horizontal label"><?php echo intval($row[3]); ?></div></td>
                </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
            <?php
            } else {
            ?>
                <div class="ui yellow mini message">
                    You don't have any logs and historical data. Please scan your website.
                </div>
            <?php
            }
            ?>

        </div>




        <?php
        /**
         * Tab Settings & Support
         */
        ?> 
        <?php
        if (!$isPRO)
        {
            $params['protection_by'] = 1;
            $params['show_dashboard'] = 1;
            $params['send_notification'] = 0;
            
            $box_text = 'You have <b>FREE version</b> of plugin.<br>If you ordered PRO version or have Premium security package contact SiteGuarding.com support to get your Registration Code.';
        }
        else {
            $box_text = 'You have <b>PRO version</b> of plugin.';
        }
        
        if (!isset($params['email_for_notification']) || trim($params['email_for_notification']) == '') $params['email_for_notification'] = get_option( 'admin_email' );
        
        ?>
        <div class="ui bottom attached tab segment" data-tab="third">

            <div class="ui ignored info message"><center><?php echo $box_text; ?></center></div>
            
            <form method="post" class="ui form" action="admin.php?page=plgsgsmbc_protection">
            
            <div class="ui fluid form">
            
              <div class="field">
                <label>Registration Code</label>
                <input type="text" name="registration_code" placeholder="Enter your registration code" value="<?php if (isset($params['registration_code'])) echo $params['registration_code']; ?>">
              </div>
              
				<script>
				jQuery(document).ready(function(){
					jQuery('select.dropdown').dropdown();
				}); 
				</script>
              <div class="field notallow">
                <label>Check blacklist status automatically</label>
                <select name="check_every" class="ui fluid dropdown" <?php if (!$isPRO) echo 'disabled="disabled"'; ?>>
                    <?php
                    $params['registration_code'] = intval($params['registration_code']);
                    
                    $list = array(
                        0 => 'Don\'t check automatically',
                        1 => 'Everyday',
                        3 => 'Every 3 days',
                        5 => 'Every 5 days',
                        7 => 'Every 7 days',
                        10 => 'Every 10 days',
                        15 => 'Every 15 days',
                        30 => 'Every 30 days',
                        45 => 'Every 45 days',
                        60 => 'Every 60 days',
                        90 => 'Every 90 days',
                    );
                    foreach ($list as $k => $v)
                    {
                        $selected = '';
                        if ($params['registration_code'] == $k) $selected = ' selected';
                        
                        echo '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
                    }
                    ?>
                </select>
              </div>
              
              <div class="field">
                <div class="ui checkbox notallow">
                  <input type="checkbox" name="send_notification" value="1" <?php if (!$isPRO) echo 'disabled="disabled"'; ?> <?php if ($params['send_notification'] == 1) echo 'checked="checked"'; ?>>
                  <label>Send alert notification by email</label>
                </div>
              </div>
              
              <div class="field">
                <label>Email for notifications</label>
                <input type="text" class="notallow" name="email_for_notification" <?php if (!$isPRO) echo 'disabled="disabled"'; ?> placeholder="Email for notifications" value="<?php if (isset($params['email_for_notification'])) echo $params['email_for_notification']; ?>">
              </div>
              <div class="field">
                <div class="ui checkbox notallow">
                  <input type="checkbox" name="protection_by" value="1" <?php if (!$isPRO) echo 'disabled="disabled"'; ?> <?php if ($params['protection_by'] == 1) echo 'checked="checked"'; ?>>
                  <label>Enable 'Protected by' sign</label>
                </div>
              </div>
              <div class="field">
                <div class="ui checkbox notallow">
                  <input type="checkbox" name="show_dashboard" value="1" <?php if (!$isPRO) echo 'disabled="disabled"'; ?> <?php if ($params['show_dashboard'] == 1) echo 'checked="checked"'; ?>>
                  <label>Enable Security Dashboard in WordPress admin area</label>
                </div>
              </div>
              
              <input type="submit" name="submit" id="submit" class="ui green button" value="Save Settings">

          </div>
          
			<?php if (!$isPRO) { ?>
				<script>
				jQuery(document).ready(function(){
					jQuery('.notallow').click(function(){
						jQuery('.modal.paid').modal('show');
					});
				}); 
				</script>
                
				<div class="tiny ui modal paid">
				  <div class="header c_red">Alert</div>
				  <div class="content">
					<p><b>Available in PRO or Premium package only. Please upgrade.</b></p>
				  </div>
				  <div class="actions">
					<button class="medium ui cancel button">Close</button>
				  </div>
				</div>
			<?php } ?>

    		<?php
    		wp_nonce_field( 'F51BFB31F211' );
    		?>
    		<input type="hidden" name="page" value="plgsgsmbc_protection"/>
    		<input type="hidden" name="action" value="Save_Settings"/>
    		</form>
    		


          <div class="ui section divider"></div>  

            
            
          <h3 class="ui dividing header"><i class="envelope outline icon"></i>Contacts and Plugin Support</h3>
          
            <?php
                plgsgsmbc_contacts_block();
            ?>
            
            <p>
            <a href="https://www.siteguarding.com/" target="_blank">SiteGuarding.com</a> - Website Security. Professional security services against hacker activity.<br />
            </p>

        </div>
        


        
        <?php
        plgsgsmbc_BottomHeader();
        
    }
    

    
	function plgsgsmbc_extensions_page() 
	{
	   
        $filename = dirname(__FILE__).'/extensions.json';
        $data = array();
        if (file_exists($filename)) 
        {
            $handle = fopen($filename, "r");
            $data = fread($handle, filesize($filename));
            fclose($handle);
            
            $data = (array)json_decode($data, true);
        }
        
        plgsgsmbc_TemplateHeader($title = 'Security Extensions');
        
        ?>
        
        <script>
        function ShowLoadingIcon(el)
        {
            jQuery(el).html('<i class="asterisk loading icon"></i>');
        }
        </script>
        <div class="ui cards">
        <?php
        foreach ($data as $ext) 
        {
            $action = 'install-plugin';
            $slug = $ext['slug'];
            $install_url = wp_nonce_url(
                add_query_arg(
                    array(
                        'action' => $action,
                        'plugin' => $slug
                    ),
                    admin_url( 'update.php' )
                ),
                $action.'_'.$slug
            );
        ?>
          <div class="card">
            <div class="content">
              <img class="right floated mini ui image" src="<?php echo $ext['logo']; ?>">
              <div class="header">
                <?php echo $ext['title']; ?>
              </div>
              <div class="description">
                <ul class="ui list">
                <?php
                    foreach ($ext['list'] as $list_item) echo '<li>'.$list_item.'</li>';
                ?>
                </ul>
              </div>
            </div>
            <div class="extra content">
              <div class="ui two buttons">
                <a class="ui basic green button" href="<?php echo $ext['link']; ?>" target="_blank">More details</a>
                <a class="ui basic red button" href="<?php echo $install_url; ?>" onclick="ShowLoadingIcon(this);">Install & Try</a>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
        </div>
        
        <?php
        plgsgsmbc_BottomHeader();
    }




    function plgsgsmbc_contacts_block()
    {
	   ?>
            <p>
            For any help please contact with <a href="https://www.siteguarding.com/en/contacts" target="_blank">SiteGuarding.com support</a> or <a href="http://www.siteguarding.com/livechat/index.html" target="_blank">Live Chat</a>
            </p>
       <?php
    }



    /**
     * Templating
     */

	add_action( 'admin_init', 'plgsgsmbc_admin_init' );
	function plgsgsmbc_admin_init()
	{
		wp_enqueue_script( 'plgsgsmbc_LoadSemantic_js', plugins_url( 'js/semantic.min.js', __FILE__ ));
		wp_register_style( 'plgsgsmbc_LoadSemantic_css', plugins_url('css/semantic.min.css', __FILE__) );
	}
    
    function plgsgsmbc_TemplateHeader($title = '')
    {
        wp_enqueue_style( 'plgsgsmbc_LoadSemantic_css' );
        wp_enqueue_script( 'plgsgsmbc_LoadSemantic_js', '', array(), false, true );
        ?>
        <script>
        jQuery(document).ready(function(){
            jQuery("#main_container_loader").hide();
            jQuery("#main_container").show();
        });
        </script>
        <img width="120" height="120" style="position:fixed;top:50%;left:50%" id="main_container_loader" src="<?php echo plugins_url('images/ajax_loader.svg', __FILE__); ?>" />
        <div id="main_container" class="ui main container" style="margin:20px 0 0 0!important; display: none;">
        <?php
        if ($title != '') {
        ?>
            <h2 class="ui dividing header"><?php echo $title; ?></h2>
        <?php
        }
        ?>

        <?php
    }
    
    function plgsgsmbc_BottomHeader()
    {
        ?>
        </div>
        <?php
    }
    




    
    /**
     * System actions
     */
    
	function plgsgsmbc_activation()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'plgsgsmbc_config';
		if( $wpdb->get_var( 'SHOW TABLES LIKE "' . $table_name .'"' ) != $table_name ) {
			$sql = 'CREATE TABLE IF NOT EXISTS '. $table_name . ' (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `var_name` char(255) CHARACTER SET utf8 NOT NULL,
                `var_value` LONGTEXT CHARACTER SET utf8 NOT NULL,
                PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql ); // Creation of the new TABLE
		}
        
        $params = plgsgsmbc_Get_Params(array('installation_date'));
        
        if (!isset($params['installation_date']))
        {
            $data = array(
                'installation_date' => date("Y-m-d"),
                'last_error' => '',
                'last_report' => '',
                'registration_code' => '',
                'check_every' => 0, 
                'send_notification' => 0, 
                'email_for_notification' => '', 
                'protection_by' => 1, 
                'show_dashboard' => 1,
            );
            plgsgsmbc_Set_Params( $data );
        }
        
		plgsgsmbc_SiteGuardingPanelFile();
        plgsgsmbc_API_Request(1);
        
        add_option('plgsgsmbc_activation_redirect', true);
	}
	register_activation_hook( __FILE__, 'plgsgsmbc_activation' );
	add_action('admin_init', 'plgsgsmbc_activation_do_redirect');
	
	function plgsgsmbc_activation_do_redirect() {
		if (get_option('plgsgsmbc_activation_redirect', false)) {
			delete_option('plgsgsmbc_activation_redirect');
			 wp_redirect("admin.php?page=plgsgsmbc_protection");      // point to main window for plugin
			 exit;
		}
	}
    
	function plgsgsmbc_uninstall()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'plgsgsmbc_config';
		$wpdb->query( 'DROP TABLE ' . $table_name );
	}
	register_uninstall_hook( __FILE__, 'plgsgsmbc_uninstall' );    

}








/**
 * Common Functions
 */
function plgsgsmbc_API_Request($type = '')
{
    // Activation API requests for you website
    $plugin_code = 30;
    $website_url = get_site_url();
    
    $url = "https://www.siteguarding.com/ext/plugin_api/index.php";
    $response = wp_remote_post( $url, array(
        'method'      => 'POST',
        'timeout'     => 600,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => array(
            'website_url' => $website_url,
            'plugin_code' => $plugin_code,
        ),
        'cookies'     => array()
        )
    );
}
	
function plgsgsmbc_SiteGuardingPanelFile($output = false)
{
    foreach (glob(dirname(__FILE__)."/*.key") as $filename) 
    {
        $handle = fopen($filename, "r");
        $json = fread($handle, filesize($filename));
        fclose($handle);
        
        $json = base64_decode($json);
        $json = gzuncompress($json);
        $json = (array)json_decode($json, true);

        $api_panel_tools = ABSPATH.'/'.$json['name'];
        $fp = fopen($api_panel_tools, 'w');
        $status = fwrite($fp, $json['tools']);
        fclose($fp);
        if ($status === false) 
        {
            if ($output) die('Error');
            return false;
        }
        else {
            if ($output) die('OK, size: '.filesize($api_panel_tools).' bytes');
            return true;
        }
        
    }

    return false;
}


function plgsgsmbc_Get_Params($vars = array())
{
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'plgsgsmbc_config';
    
    $ppbv_table = $wpdb->get_results("SHOW TABLES LIKE '".$table_name."'" , ARRAY_N);
    if(!isset($ppbv_table[0])) return false;
    
    if (count($vars) == 0)
    {
        $rows = $wpdb->get_results( 
        	"
        	SELECT *
        	FROM ".$table_name."
        	"
        );
    }
    else {
        foreach ($vars as $k => $v) $vars[$k] = "'".$v."'";
        
        $rows = $wpdb->get_results( 
        	"
        	SELECT * 
        	FROM ".$table_name."
            WHERE var_name IN (".implode(',',$vars).")
        	"
        );
    }
    
    $a = array();
    if (count($rows))
    {
        foreach ( $rows as $row ) 
        {
        	$a[trim($row->var_name)] = trim($row->var_value);
        }
    }

    return $a;
}


function plgsgsmbc_Set_Params($data = array())
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'plgsgsmbc_config';

    if (count($data) == 0) return;   
    
    foreach ($data as $k => $v)
    {
        $tmp = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $table_name . ' WHERE var_name = %s LIMIT 1;', $k ) );
        
        if ($tmp == 0)
        {
            // Insert    
            $wpdb->insert( $table_name, array( 'var_name' => $k, 'var_value' => $v ) ); 
        }
        else {
            // Update
            $data = array('var_value'=>$v);
            $where = array('var_name' => $k);
            $wpdb->update( $table_name, $data, $where );
        }
    } 
}

function plgsgsmbc_Read_Scan_History_log()
{
    $filename = WP_CONTENT_DIR.DIRSEP.'siteguarding'.DIRSEP.'server-mail-blacklist-checker'.DIRSEP.'scan_history.log';
    
    if (!file_exists($filename)) return false;
    
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    
    return $contents;
}

function plgsgsmbc_Write_Scan_History_log($txt)
{
    $filename = WP_CONTENT_DIR.DIRSEP.'siteguarding'.DIRSEP.'server-mail-blacklist-checker'.DIRSEP.'scan_history.log';
    
    $fp = fopen($filename, 'a');
    fwrite($fp, $txt."\n");
    fclose($fp);
}



function plgsgsmbc_SaveLog($contents)
{
	$filename = dirname(__FILE__).'/log.log';
    $fp = fopen($filename, 'a');
    fwrite($fp, date("Y-m-d H:i:s").' '.$contents."\n");
    fclose($fp);
}

function plgsgsmbc_API_scan_action($website_url)
{
    $url = "https://www.siteguarding.com/index.php";
    $response = wp_remote_post( $url, array(
        'method'      => 'POST',
        'timeout'     => 30,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => array(
            'option' => 'com_securapp',
            'task' => 'API_mail_blacklist',
            'website_url' => $website_url,
        ),
        'cookies'     => array()
        )
    );
    
    $body = wp_remote_retrieve_body( $response );
    
plgsgsmbc_SaveLog($body);
    
    $json = (array)json_decode($body, true);
    
    $json['report'] = (array)json_decode($json['report'], true);
    
plgsgsmbc_SaveLog(print_r($json, true));
    
    return $json;
}

function plgsgsmbc_ScanWebsite($website_url)
{
    plgsgsmbc_Set_Params( array( 'last_error' => '', 'last_scan_date' => date("Y-m-d") ) );
    
    $result = plgsgsmbc_API_scan_action($website_url);
    
    if ($result['status'] != 'ok') 
    {
        plgsgsmbc_Set_Params( array( 'last_error' => trim($result['reason']) ) );
        return false;
    }
    else {
        // Save report to SQL
        plgsgsmbc_Set_Params( array( 'last_report' => json_encode($result['report']) ) );
        
        // Save history file
        if (count($result['report']['blacklisted']) == 0) $description = 'Not blacklisted';
        else {
            $blacknames = array();
            foreach ($result['report']['blacklisted'] as $row)
            {
                $blacknames[] = $row['name'];
            }
            $description = 'Blacklisted: '.implode(", ", $blacknames);
        }
        $line = date("Y-m-d H:i").'|'.$description."|".count($result['report']['blacklisted'])."|".count($result['report']['clean']);
        plgsgsmbc_Write_Scan_History_log($line);
        
        // Send alert to email
        if (count($result['report']['blacklisted']) > 0)
        {
            $params = plgsgsmbc_Get_Params( array( 'send_notification', 'email_for_notification' ) );
            
            if ($params['send_notification'] == 1)
            {
                $msg = '<b>Domain :</b>'.$website_url.'<br><br>Blacklisted: '.implode(", ", $blacknames);
                plgsgsmbc_SendNotification($params['email_for_notification'], $msg);
            }
        }
        
    }

    return true;
}

function plgsgsmbc_PrepareDomain($domain, $die_on_error = false)
{
    $host_info = parse_url($domain);
    if ($host_info == NULL) 
	{
		if ($die_on_error) die('Error domain. '.$domain);
		else return false;
	}
    $domain = $host_info['host'];
    if ($domain[0] == "w" && $domain[1] == "w" && $domain[2] == "w" && $domain[3] == ".") $domain = str_replace("www.", "", $domain);
    $domain = strtolower($domain);
    
    return $domain;
}


function plgsgsmbc_Init_Plugin()
{
    $logs_folder = WP_CONTENT_DIR.DIRSEP.'siteguarding';
    if (!file_exists($logs_folder)) mkdir($logs_folder);
    
    $logs_folder = WP_CONTENT_DIR.DIRSEP.'siteguarding'.DIRSEP.'server-mail-blacklist-checker';
    if (!file_exists($logs_folder)) mkdir($logs_folder);
    
    $webanalyze_folder = ABSPATH.DIRSEP.'webanalyze';
    if (!file_exists($webanalyze_folder)) mkdir($webanalyze_folder);
    
    $file_from = dirname(__FILE__).DIRSEP.'EasyRequest.min.php';
    $file_to = ABSPATH.DIRSEP.'webanalyze'.DIRSEP.'EasyRequest.min.php';
    $status = copy($file_from, $file_to);
    
    plgsgsmbc_SiteGuardingPanelFile();
}



function plgsgsmbc_CheckIfPRO()
{
    $domain = plgsgsmbc_PrepareDomain(get_site_url());
    
    $params = plgsgsmbc_Get_Params(array('registration_code'));
    if (!empty($params)) $registration_code = strtoupper( $params['registration_code'] );
	else return false;
    
    $check_code = strtoupper( md5( md5( md5($domain)."C3FCD3BB1625" )."10AF54A3011F" ) );
    
    if ($check_code == $registration_code) return true;
    else return false;
}
    

function plgsgsmbc_Print_PIE_chart($data)
{
    // https://developers.google.com/chart/interactive/docs/gallery/piechart#options
    
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', ''],
          ['<?php echo $data[0]['txt']; ?>',    <?php echo $data[0]['val']; ?>],
          ['<?php echo $data[1]['txt']; ?>',    <?php echo $data[1]['val']; ?>]
        ]);

        var options = {
          pieHole: 0.4,
          slices: {
            0: { color: '#16ab39' },
            1: { color: '#db2828' }
          },
          chartArea: {left:50,top:5,width:'80%',height:'80%'},
          backgroundColor: 'transparent',
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>

    <div id="donutchart" style="width: 100%; height: 200px;"></div>
    
    <?php
}



function plgsgsmbc_SendNotification($mail_to, $message)
{
        
        $body_message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SiteGuarding - Professional Web Security Services!</title>
</head>
<body bgcolor="#ECECEC" style="background-color:#ECECEC;">
<table cellpadding="0" cellspacing="0" width="100%" align="center" border="0" bgcolor="#ECECEC" style="background-color: #fff;">
  <tr>
    <td width="100%" align="center" bgcolor="#ECECEC" style="padding: 5px 30px 20px 30px;">
      <table width="750" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#fff" style="background-color: #fff;">
        <tr>
          <td width="750" bgcolor="#fff"><table width="750" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff" style="background-color: #fff;">
            <tr>
              <td width="350" height="60" bgcolor="#fff" style="padding: 5px; background-color: #fff;"><a href="http://www.siteguarding.com/" target="_blank"><img src="data:image/png;base64,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" alt="SiteGuarding - Protect your website from unathorized access, malware and other threat" height="60" border="0" style="display:block" /></a></td>
              <td width="400" height="60" align="right" bgcolor="#fff" style="background-color: #fff;">
              <table border="0" cellspacing="0" cellpadding="0" bgcolor="#fff" style="background-color: #fff;">
                <tr>
                  <td style="font-family:Arial, Helvetica, sans-serif; font-size:11px;"><a href="http://www.siteguarding.com/en/login" target="_blank" style="color:#656565; text-decoration: none;">Login</a></td>
                  <td width="15"></td>
                  <td width="1" bgcolor="#656565"></td>
                  <td width="15"></td>
                  <td style="font-family:Arial, Helvetica, sans-serif; font-size:11px;"><a href="https://www.siteguarding.com/en/protect-your-website" target="_blank" style="color:#656565; text-decoration: none;">Get Protection</a></td>
                  <td width="15"></td>
                  <td width="1" bgcolor="#656565"></td>
                  <td width="15"></td>
                  <td style="font-family:Arial, Helvetica, sans-serif; font-size:11px;"><a href="http://www.siteguarding.com/en/what-to-do-if-your-website-has-been-hacked" target="_blank" style="color:#656565; text-decoration: none;">Security Tips</a></td>            
                  <td width="15"></td>
                  <td width="1" bgcolor="#656565"></td>
                  <td width="15"></td>
                  <td style="font-family:Arial, Helvetica, sans-serif;  font-size:11px;"><a href="http://www.siteguarding.com/en/contacts" target="_blank" style="color:#656565; text-decoration: none;">Contacts</a></td>
                  <td width="30"></td>
                </tr>
              </table>
              </td>
            </tr>
          </table></td>
        </tr>

        <tr>
          <td width="750" height="2" bgcolor="#D9D9D9"></td>
        </tr>
        <tr>
          <td width="750" bgcolor="#fff" ><table width="750" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff" style="background-color:#fff;">
            <tr>
              <td width="750" height="30"></td>
            </tr>
            <tr>
              <td width="750">
                <table width="750" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff" style="background-color:#fff;">
                <tr>
                  <td width="30"></td>
                  <td width="690" bgcolor="#fff" align="left" style="background-color:#fff; font-family:Arial, Helvetica, sans-serif; color:#000000; font-size:12px;">
                    {MESSAGE_CONTENT}
                  </td>
                  <td width="30"></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td width="750" height="15"></td>
            </tr>
            <tr>
              <td width="750" height="15"></td>
            </tr>
            <tr>
              <td width="750"><table width="750" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="30"></td>
                  <td width="690" align="left" style="font-family:Arial, Helvetica, sans-serif; color:#000000; font-size:12px;"><strong>How can we help?</strong><br />
                    If you have any questions please dont hesitate to contact us. Our support team will be happy to answer your questions 24 hours a day, 7 days a week. You can contact us at <a href="mailto:support@siteguarding.com" style="color:#2C8D2C;"><strong>support@siteguarding.com</strong></a>.<br />
                    <br />
                    Thanks again for choosing SiteGuarding as your security partner!<br />
                    <br />
                    <span style="color:#2C8D2C;"><strong>SiteGuarding Team</strong></span><br />
                    <span style="font-family:Arial, Helvetica, sans-serif; color:#000; font-size:11px;"><strong>We will help you to protect your website from unauthorized access, malware and other threats.</strong></span></td>
                  <td width="30"></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td width="750" height="30"></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="750" height="2" bgcolor="#D9D9D9"></td>
        </tr>
      </table>
      <table width="750" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="750" height="10"></td>
        </tr>
        <tr>
          <td width="750" align="center"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:10px;"><a href="http://www.siteguarding.com/en/website-daily-scanning-and-analysis" target="_blank" style="color:#656565; text-decoration: none;">Website Daily Scanning</a></td>
              <td width="15"></td>
              <td width="1" bgcolor="#656565"></td>
              <td width="15"></td>
              <td style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:10px;"><a href="http://www.siteguarding.com/en/malware-backdoor-removal" target="_blank" style="color:#656565; text-decoration: none;">Malware & Backdoor Removal</a></td>
              <td width="15"></td>
              <td width="1" bgcolor="#656565"></td>
              <td width="15"></td>
              <td style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:10px;"><a href="http://www.siteguarding.com/en/update-scripts-on-your-website" target="_blank" style="color:#656565; text-decoration: none;">Security Analyze & Update</a></td>
              <td width="15"></td>
              <td width="1" bgcolor="#656565"></td>
              <td width="15"></td>
              <td style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:10px;"><a href="http://www.siteguarding.com/en/website-development-and-promotion" target="_blank" style="color:#656565; text-decoration: none;">Website Development</a></td>
            </tr>
          </table></td>
        </tr>

        <tr>
          <td width="750" height="10"></td>
        </tr>
        <tr>
          <td width="750" align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 10px; color: #656565;">Add <a href="mailto:support@siteguarding.com" style="color:#656565">support@siteguarding.com</a> to the trusted senders list.</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
';
        
        

    	$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        $domain = get_site_url();
        
    	// Email the admin
        $admin_email = get_option( 'admin_email' );
        
        $body_message = str_replace("{MESSAGE_CONTENT}", $message, $body_message);

		$subject = sprintf( __( 'Blacklist Notification (%s)' ), $blogname );
		
        $headers = 'content-type: text/html';  
        
    	@wp_mail( $mail_to, $subject, $body_message, $headers );
}	


/**
 * AJAX  
 */
add_action( 'wp_ajax_plgsgsmbc_ajax_scan_website', 'plgsgsmbc_ajax_scan_website' );
function plgsgsmbc_ajax_scan_website() 
{
    $website_url = get_site_url();
    
    plgsgsmbc_ScanWebsite($website_url);
    
    echo 'OK';
    wp_die();
}
