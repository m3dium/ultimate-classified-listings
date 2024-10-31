<?php
/**
* Initialize the Plugin base
*/
class UCLWP_Init
{
    public $uclwp_options = array(
        'submission_mode' => 'publish'
    );
	
	function __construct(){
		add_action( 'admin_init', array($this, 'register_role_caps') , 99);
		add_filter( 'use_block_editor_for_post_type', array($this, 'disable_gutenberg'), 10, 2);
        add_action( 'plugins_loaded', array($this, 'ucl_load_plugin_textdomain' ) );

        // Restrict Access to Media
        add_filter('ajax_query_attachments_args', array($this, 'show_current_user_attachments'));
        add_filter( 'user_has_cap', array($this, 'allow_attachment_actions'), 10, 3 );
	}

    function ucl_load_plugin_textdomain(){
        load_plugin_textdomain( 'ultimate-classified-listings', FALSE, basename( UCLWP_PATH ) . '/languages/' );
    }

	function register_role_caps(){
        if (!$GLOBALS['wp_roles']->is_role( 'ucl_listing_seller' )) {
            add_role(
                'ucl_listing_seller',
                __( 'Seller', 'ultimate-classified-listings' ),
                array(
                    'read' => true,
                    'edit_posts' => true,
                    'delete_posts' => false,
                    'publish_posts' => false,
                    'upload_files' => true,
                )
            );
            flush_rewrite_rules();
        }

        $roles = array('ucl_listing_seller', 'editor', 'administrator');

        // Loop through each role and assign capabilities
        foreach($roles as $the_role) {

            $role = get_role($the_role);

            if ($role) {
                $role->add_cap( 'read' );
                $role->add_cap( 'read_uclwp_listing');
                $role->add_cap( 'read_private_uclwp_listings' );
                $role->add_cap( 'edit_uclwp_listing' );
                $role->add_cap( 'edit_uclwp_listings' );

                if($the_role == 'administrator'){
                    $role->add_cap( 'edit_others_uclwp_listings' );
                    $role->add_cap( 'delete_others_uclwp_listings' );
                    if ($this->uclwp_options['submission_mode'] == 'restrict') {
                        $role->add_cap( 'publish_uclwp_listings' );
                    }
                }
                if ($this->uclwp_options['submission_mode'] == 'publish') {
                    $role->add_cap( 'publish_uclwp_listings' );
                }
                $role->add_cap( 'edit_published_uclwp_listings' );
                $role->add_cap( 'delete_private_uclwp_listings' );
                $role->add_cap( 'delete_published_uclwp_listings' );
            }
        }
	}

    function disable_gutenberg($current_status, $post_type){
        if ($post_type === 'uclwp_listing') return false;
        return $current_status;        
    }

    function show_current_user_attachments($query){
        $user_id = get_current_user_id();
        if ( $user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_uclwp_listings') ) {
            $query['author'] = $user_id;
        }
        return $query;
    }

    function allow_attachment_actions( $user_caps, $req_cap, $args ){
      // if no property is connected with capabilities check just return original array
      if ( empty($args[2]) )
        return $user_caps;
      $post = get_post( $args[2] );

      if ( isset($post->post_type) && 'attachment' == $post->post_type ) {
        $user_caps[$req_cap[0]] = true;
        return $user_caps;
      }

      // for any other post type return original capabilities
      return $user_caps;
    }
}

new UCLWP_Init();
?>