<?php

# Exit if accessed directly
if (!defined('ABSPATH')) exit();



class passgrinder {
    
    // Call hooks and filters automatically
    public function __construct() {
        // Create shortcode [password_grinder]
        add_shortcode( 'passgrinder', array( $this, 'shortcode_form') );
        
        // Modify Wordpress login form
        add_action('login_form', array( $this, 'wdm_login_form_captcha') );
        
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue') );
        
        add_action( 'wp_ajax_nopriv_eval_helper', array( $this, 'eval_helper' ) );
        add_action( 'wp_ajax_eval_helper', array( $this, 'eval_helper' ) );
    }
    
    
    function enqueue() {
        wp_enqueue_style( 'passgrinder', plugin_dir_url(__FILE__) . '../assets/css/style.css' );
        
        // Load script file and pass jquery as 3rd argument to make sure it gets loaded first
        wp_enqueue_script( 'passgrinder', plugin_dir_url(__FILE__) . '../assets/js/script.js', array('jquery'), '1.0', true );
        
        // Load md5 hashing for jquery
        wp_enqueue_script( 'passgrinder-md5', plugin_dir_url(__FILE__) . '../assets/js/jquery.md5.js' );
        
        // Load z85 encoding
        wp_enqueue_script( 'passgrinder-z85', plugin_dir_url(__FILE__) . '../assets/js/encodeZ85.js' );
        
        // Load clipboard.js
        wp_enqueue_script( 'passgrinder-clipboardjs', plugin_dir_url(__FILE__) . '../assets/js/clipboard.min.js' );
        
        
        // set variables for script
        wp_localize_script( 'passgrinder', 'settings', array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'ajaxnonce' => wp_create_nonce( 'ajax_post_validation' ),
        ) );
    }
    
    
    public function eval_helper() {
        // check the nonce
//        if ( check_ajax_referer( 'report_a_bug_', 'nonce', false ) == false ) {
//            wp_send_json_error();
//        }
        
        // check if pass delivered
        $pass = $_POST['pg-result-pass'];
        
        // send success or error
        if ( $pass !== "" ) {
            wp_send_json_success( __( 'Successfully generated your password!', 'passgrinder' ) );
        } else {
            wp_send_json_error( __( 'Something went wrong. Please refresh and try again!', 'passgrinder' ) );
        }
        
        // prevent response of 0
        die();
    }
    
    
    public function shortcode_form($atts) {
        extract(shortcode_atts(array(
            'showtitle' => 'true', // Show title by default
        ), $atts));
        
        if ($showtitle == 'true') {
            $title = 'PassGrinder';
        } else {
            $title = '';
        }
        
        $content = '
<div id="passgrinder">
    <h1>' . $title . '</h1>
    <form method="post" id="passgrinder-form" autocomplete="off">
        <div id="pg-input1" class="pt-2">
            <div class="input-group flex-nowrap">
                <input id="pg-password" name="pg-password" type="password" class="form-control" placeholder="Master Password (Required)" autocomplete="off" required />
                <div class="input-group-text toggle-password"><i class="fa fa-eye"></i></div>
            </div>
        </div>
        
        <div id="pg-input2" class="py-2">
            <div class="input-group flex-nowrap">
                <input id="pg-salt" name="pg-salt" type="password" class="form-control" placeholder="Unique Phrase (Optional)" autocomplete="off" />
                <div class="input-group-text toggle-password"><i class="fa fa-eye"></i></div>
            </div>
            <small id="pg-salt-help" class="form-text mt-2 text-muted">It is recommended to use the website URL, domain name, or app name where this password will be used to grind your master password into something more unique.</small>
        </div>
        
        <div id="pg-variations" class="form-group pt-2">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pg-variation" id="default" value="0" checked>
                <label class="form-check-label" for="default">
                Default
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pg-variation" id="variation1" value="1">
                <label class="form-check-label" for="variation1">
                Variation 1
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pg-variation" id="variation2" value="2">
                <label class="form-check-label" for="variation2">
                Variation 2
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pg-variation" id="variation3" value="3">
                <label class="form-check-label" for="variation3">
                Variation 3
                </label>
            </div>
            <small id="pg-variations-help" class="form-text text-muted">You may want to use a variation if you are required to change your password without needing to change your master password or unique phrase.</small>
        </div>
        
        <div id="pg-variations" class="form-group row pt-3">
            <div class="input-group col">
                <input id="pg-submit" type="submit" value="Submit" class="btn btn-primary" />
            </div>
            <div class="input-group col">
                <input id="pg-reset" type="reset" value="Reset" class="btn btn-primary" />
            </div>
        </div>

        <div id="pg-result" class="form-group row pt-3">
            <div class="input-group flex-nowrap">
                <input type="text" id="pg-result-pass" name="pg-result-pass" class="form-control" value="" placeholder="Your Generated Password" />
                <div class="input-group-text toggle-copy" data-clipboard-target="#pg-result-pass"><i class="fa fa-copy"></i></div>
            </div>
        </div>

        <div id="pg-message" class="form-group row">
            <span id="success"></span>
            <span id="fail"></span>
            <span id="reset"></span>
        </div>

    </form>
</div>
        ';  // end form
        
        return $content;
    }
    
    
    // Update WP login form
    public function wdm_login_form_captcha() {
        // php stuff
        ?>
        <style type="text/css">
            label[for="user_pass"] {
        /*        display: none;*/
            }
        </style>

        <p>
            <label for="pg-master-pass">Master Password<br>
                <input type="password" name="pg-password" id="pg-password" class="input" value="" size="20">
            </label>
        </p>
        <p>
            <label for="pg-unique-phrase">Unique Phrase<br>
                <input type="password" name="pg-salt" id="pg-salt" class="input" value="" size="20">
            </label>
        </p>
        
        <div id="pg-variations" class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pg-variation" id="default" value="0" checked>
                <label class="form-check-label" for="default">
                Default
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pg-variation" id="variation1" value="1">
                <label class="form-check-label" for="variation1">
                Variation 1
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pg-variation" id="variation2" value="2">
                <label class="form-check-label" for="variation2">
                Variation 2
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pg-variation" id="variation3" value="3">
                <label class="form-check-label" for="variation3">
                Variation 3
                </label>
            </div>
            <small id="pg-variations-help" class="form-text text-muted">You may want to use a variation if you are required to change your password without needing to change your master password or unique phrase.</small>
        </div>

        <br />
        <?php
    }

}


new passgrinder();