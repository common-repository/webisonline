<?php
/**
 * Plugin Name: WebisOnline
 * Author: Webis Group
 * Author URI: www.webisgroup.ru
 * Plugin URI: http://webisonline.ru/
 * Description: WebisOnline - онлайн консультант для вашего сайта повысит конверсию и увеличит продажи. Изящный и профессиональный, чат WebisOnline выглядит хорошо на любом сайте.
 * Version: 2.4
 *
 * Text Domain:   webisonline
 * Domain Path:   /
 */


if (!defined('ABSPATH')) die("good bye!");

load_plugin_textdomain('webisonline', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)));

// die(get_bloginfo("language"));

$lang = substr(get_bloginfo("language"),0,2);
define("WEBISONLINE_LANG", $lang);
define("WEBISONLINE_URL", 'http://webisonline.ru');
define("WEBISONLINE_INTEGRATION_URL", "http://chat.webiscall.com/cmsmodule/");
define("WEBISONLINE_LANGUAGES_URL", "http://chat.webiscall.com/localization/list.php");
define("WEBISONLINE_PLUGIN_URL",plugin_dir_url(__FILE__));
define("WEBISONLINE_IMG_URL",plugin_dir_url(__FILE__)."img/");

// //register hooks for plugin
register_activation_hook(__FILE__, 'webisonlineInstall');
register_deactivation_hook(__FILE__, 'webisonlineDelete');

//add plugin to options menu
function add_to_menu(){
    load_plugin_textdomain('webisonline', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)));
    add_menu_page('WebisOnline', 'WebisOnline', 5, basename(__FILE__), 'webisonlinePreferences',WEBISONLINE_IMG_URL."icon.png");
}

add_action('admin_menu', 'add_to_menu');

function webisonline_options_validate($args){
    return $args;
}

add_action('plugins_loaded', 'wan_load_textdomain');

function wan_load_textdomain() {
    load_plugin_textdomain( 'webisonline', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}



/* 
 * 
 */
add_action('admin_init', 'webisonline_register_settings');

function webisonline_register_settings(){
    register_setting('webisonline_site_id', 'webisonline_site_id', 'webisonline_options_validate');
}

add_action('wp_footer', 'webisonlineAppend', 200);

function webisonlineInstall(){
    return webisonline::getInstance()->install();
}

function webisonlineDelete(){
    return webisonline::getInstance()->delete();
}

function webisonlineAppend(){
    echo webisonline::getInstance()->append(
        webisonline::getInstance()->getId()
    );
}

function webisonlinePreferences(){
    if(isset($_POST["site_id"]))
        webisonline::getInstance()->save();
    load_plugin_textdomain('webisonline', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)));
    wp_register_style('webisonline_style', plugins_url('webisonline.css', __FILE__));
    wp_enqueue_style('webisonline_style');
    echo webisonline::getInstance()->render();
}

class webisonline {
    protected static $instance, $db, $table, $lang;
    private function __construct(){
        $this->site_id = get_option( 'webisonline_site_id');
    }
    private function __clone()    {}
    private function __wakeup()   {}

    private $site_id = '';

    public static function getInstance() {

        if ( is_null(self::$instance) ) {
            self::$instance = new webisonline();
        }
        self::$lang     = "en";
        if(isset($_GET["lang"])){
            switch ($_GET["lang"]) {
                case 'ru':  self::$lang     = "ru"; break;
                default:    self::$lang     = "en"; break;
            }
        }
        return self::$instance;
    }

    public function setID($id){
        $this->site_id = $id;
    }

    /**
     * Install
     */
    public function install() {
        if (!$this->site_id) {
            $default_site_id = '';
            if (file_exists(realpath(dirname(__FILE__))."/id") )
                $default_site_id = file_get_contents(realpath(dirname(__FILE__))."/id");
        }
        $this->site_id = $default_site_id;
        $this->save();
    }

    public function catchPost(){
       if(isset($_REQUEST['bsite'])){
            unset($query);
            $query['action']        = "bgetsiteid";
            $query['bsite']         = $_REQUEST['bsite'];
            $query['cms']           = "WordPress";
            try{
                $response   = $this->do_post_request(WEBISONLINE_INTEGRATION_URL, $query);
                $responceAr = (array)$response;


                if(isset($responceAr['error'])){

                    return array("error"=>$responceAr['error']);
                }
                else if($responceAr){
                    $this->site_id = $responceAr['sid'];
                    $this->save();
                }
            } catch (Exception $e) {
                _e("Ошибка подключения к серверу WebisOnline",'webisonline');
            }
        }
        else if(isset($_REQUEST['email'])&&isset($_REQUEST['userPassword'])&&isset($_REQUEST['url'])){
            unset($query);
            $query['action']        = "bregisterusersite";
            $query['password']      = $_REQUEST['userPassword'];
            $query['email']         = $_REQUEST['email'];
            $query['url']           = $_REQUEST['url'];
			$query['lang']          = $_REQUEST['selected_language'];
            $query['cms']           = "WordPress";
            try{
                $response   = $this->do_post_request(WEBISONLINE_INTEGRATION_URL, $query);
                $responceAr = (array)$response;
                if(isset($responceAr['error'])){
                    return array("error"=>$responceAr['error']);
                }
                else if($responceAr){

                    $this->site_id = $responceAr['sid'];
                    $this->save();
                }
            } catch (Exception $e) {
                _e("<div class='error'>Ошибка подключения к серверу WebisOnline</div>",'webisonline');
            }
        } else  if(isset($_REQUEST['mode']) && $_REQUEST['mode']=='reset'){
            $this->site_id = '';
            $this->save();
        }

    }

    public function getWebisOnlineLanguagesList(){
        global $lang;
        try{
            $list_of_languages = $this->do_post_request(WEBISONLINE_LANGUAGES_URL, array("action"=>"get_languages"));
        } catch (Exception $e) {
            _e("Ошибка подключения к серверу WebisOnline",'webisonline');
        }
        $list_of_languages = $list_of_languages;
        $list = "<select id='selected_language' name='selected_language'>";
        foreach ($list_of_languages as $language) {
            $selected = ($lang==$language['code'])?"selected":"";
            $list .= "<option ".$selected." value='".$language['code']."'>".$language['name']."</option>";
        }
        $list .= "</select>";

        return $list;
    }

    private function do_post_request($url, $data = NULL){

        $d = http_build_query($data);
        
        $params = array('http' => array(
                        'method' => 'POST',
                        'content' => $d
        ));
        
        $ctx = stream_context_create($params);
        
        $fp = @fopen($url, 'rb', false, $ctx);
        
        if (!$fp) {

             throw new Exception("error connection to WebisOnlineServer. Please check your internet connection");
           // throw new Exception(GetMessage("JS_ERR_CONN")." $url $php_errormsg");
        }
        $response = @stream_get_contents($fp);
    
        if ($response === false) {
            throw new Exception("error connection to WebisOnlineServer. Please check your internet connection");
        }
       
        return json_decode($response, true);
    }

    /**
     * delete plugin
     */
    public function delete(){

    }


    public function getId(){
        return $this->site_id;
    }

    /**
     * render admin page
     */
    public function render(){
        $result = $this->catchPost();
        $error = '';
        $site_id = $this->site_id;
        if (is_array($result)&&isset($result['error'])) {
            $error = "<div class='error clear'>".$result['error']."</div>";
            // die("error");
        }		
		
		if (ini_get('allow_url_fopen')) {
			$requirementsOk = true;
		} elseif(!extension_loaded('curl')) {
			if (!dl('curl.so')) {
				$requirementsOk = false;
			} else {
				$requirementsOk = true;
			}
		} else {
			$requirementsOk = true;
		}
		
		if ($requirementsOk) 
			require_once "templates/page.php";
		else
			require_once "templates/error.php";
    }

    public function append($site_id = false){
        if($site_id)
            require_once "templates/script.php";
    }

    public function save(){
        do_settings_sections( __FILE__ );
        update_option('webisonline_site_id', $this->site_id);
    }

}