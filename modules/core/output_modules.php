<?php

/**
 * Core modules
 * @package modules
 * @subpackage core
 */

/**
 * Simple search form for the folder list
 * @subpackage core/output
 */
class Hm_Output_search_from_folder_list extends Hm_Output_Module {
    /**
     * Add a search form to the top of the folder list
     */
    protected function output() {
        $res = '<li class="menu_search"><form method="get">';
        $res .= '<div class="d-flex align-items-center">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<div class="ps-1 pe-2"><a class="unread_link" href="?page=search">';
            $res .= '<i class="bi bi-search"></i></a></div>';
        }
        $res .= '<div class=""><input type="hidden" name="page" value="search" />'.
            '<input type="search" class="search_terms form-control form-control-sm" '.
            'name="search_terms" placeholder="'.$this->trans('Search').'" /></div></form></div></li>';
        if ($this->format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Start the search results content section
 * @subpackage core/output
 */
class Hm_Output_search_content_start extends Hm_Output_Module {
    /**
     * Leaves two open div tags that are closed in Hm_Output_search_content_end and Hm_Output_search_form
     */
    protected function output() {
        return '<div class="search_content px-0"><div class="content_title px-3 d-flex align-items-center">'.
            message_controls($this).$this->trans('Search');
    }
}

/**
 * End of the search results content section
 * @subpackage core/output
 */
class Hm_Output_search_content_end extends Hm_Output_Module {
    /**
     * Closes a div opened in Hm_Output_search_content_start
     */
    protected function output() {
        return '</div>';
    }
}

/**
 * Unsaved data reminder
 * @subpackage core/output
 */
class Hm_Output_save_reminder extends Hm_Output_Module {
    protected function output() {
        if ($this->get('single_server_mode')) {
            return '';
        }
        $changed = $this->get('changed_settings', array());
        if (!empty($changed)) {
            return '<div class="save_reminder"><a title="'.$this->trans('You have unsaved changes').
                '" href="?page=save"><i class="bi bi-save2-fill fs-2"></i></a></div>';
        }
        return '';
    }
}

/**
 * Start the search form
 * @subpackage core/output
 */
class Hm_Output_search_form_start extends Hm_Output_Module {
    protected function output() {
        return '<div class="search_form"><form class="d-flex align-items-center" method="get">';
    }
}

/**
 * Search form content
 * @subpackage core/output
 */
class Hm_Output_search_form_content extends Hm_Output_Module {
    protected function output() {
        $terms = $this->get('search_terms', '');

        return '<input type="hidden" name="page" value="search" />'.
            ' <label class="screen_reader" for="search_terms">'.$this->trans('Search Terms').'</label>'.
            '<input required placeholder="'.$this->trans('Search Terms').'" id="search_terms" type="search" class="search_terms form-control form-control-sm" name="search_terms" value="'.$this->html_safe($terms).'" />'.
            ' <label class="screen_reader" for="search_fld">'.$this->trans('Search Field').'</label>'.
            search_field_selection($this->get('search_fld', DEFAULT_SEARCH_FLD), $this).
            ' <label class="screen_reader" for="search_since">'.$this->trans('Search Since').'</label>'.
            message_since_dropdown($this->get('search_since', DEFAULT_SINCE), 'search_since', $this).
            combined_sort_dialog($this).
            ' | <input type="submit" class="search_update btn btn-success btn-sm" value="'.$this->trans('Update').'" />'.
            ' <input type="button" class="search_reset btn btn-light border btn-sm" value="'.$this->trans('Reset').'" />';
    }
}

/**
 * Finish the search form
 * @subpackage core/output
 */
class Hm_Output_search_form_end extends Hm_Output_Module {
    protected function output() {
        $source_link = '<a href="#" title="'.$this->trans('Sources').'" class="source_link"><i class="bi bi-folder-fill refresh_list"></i></a>';
        $refresh_link = '<a class="refresh_link ms-3" title="'.$this->trans('Refresh').'" href="#"><i class="bi bi-arrow-clockwise refresh_list"></i></a>';
        return '</form></div>'.
            list_controls($refresh_link, false, $source_link).list_sources($this->get('data_sources', array()), $this).'</div>';
    }
}

/**
 * Closes the search results table
 * @subpackage core/output
 */
class Hm_Output_search_results_table_end extends Hm_Output_Module {
    /**
     */
    protected function output() {
        return '</tbody></table>';
    }
}

/**
 * Some inline JS used by the search page
 * @subpackage core/output
 */
class Hm_Output_js_search_data extends Hm_Output_Module {
    /**
     * adds two JS functions used on the search page
     */
    protected function output() {
        return '<script type="text/javascript">'.
            'var hm_search_terms = function() { return "'.$this->html_safe($this->get('search_terms', ''), true).'"; };'.
            'var hm_run_search = function() { return "'.$this->html_safe($this->get('run_search', 0)).'"; };'.
            '</script>';
    }
}

/**
 * Outputs the end of the login or logout form
 * @subpackage core/output
 */
class Hm_Output_login_end extends Hm_Output_Module {
    /**
     * Closes the login form
     */
    protected function output() {
        $fancy_login= $this->get('fancy_login_allowed');
        if(!$fancy_login){
            return '</form>';
        }
        return '</form></div>';
    }
}

/**
 * Outputs the start of the login or logout form
 * @subpackage core/output
 */
class Hm_Output_login_start extends Hm_Output_Module {
    /**
     * Looks at the current login state and outputs the correct form
     */
    protected function output() {
        $fancy_login = $this->get('fancy_login_allowed');
        if (!$fancy_login) {
            if (!$this->get('router_login_state')) {
                return '<form class="login_form" method="POST">';
            }
            else {
                return '<form class="logout_form" method="POST">';
            }
        } else {
            if (!$this->get('router_login_state')) {
                $css = '<style type="text/css">body,html{max-width:100vw !important; max-height:100vh !important; overflow:hidden !important;}.form-container{background-color:#f1f1f1;'.
                    'background: linear-gradient( rgba(4, 26, 0, 0.85), rgba(4, 26, 0, 0.85)), url('.WEB_ROOT.'modules/core/assets/images/cloud.jpg);'.
                    'background-attachment: fixed;background-position: center;background-repeat: no-repeat;background-size: cover;'.
                    'display:grid; place-items:center; height:100vh; width:100vw;} .logged_out{display:block !important;}.sys_messages'.
                    '{position:fixed;right:20px;top:15px;min-height:30px;display:none;background-color:#fff;color:teal;'.
                    'margin-top:0px;padding:15px;padding-bottom:5px;white-space:nowrap;border:solid 1px #999;border-radius:'.
                    '5px;filter:drop-shadow(4px 4px 4px #ccc);z-index:101;}.g-recaptcha{margin:0px 10px 10px 10px;}.mobile .g-recaptcha{'.
                    'margin:0px 10px 5px 10px;}.title{font-weight:normal;padding:0px;margin:0px;margin-left:20px;'.
                    'margin-bottom:20px;letter-spacing:-1px;color:#999;}html,body{min-width:100px !important;'.
                    'background-color:#fff;}body{background:linear-gradient(180deg,#faf6f5,#faf6f5,#faf6f5,#faf6f5,'.
                    '#fff);font-size:1em;color:#333;font-family:Arial;padding:0px;margin:0px;min-width:700px;'.
                    'font-size:100%;}input,option,select{font-size:100%;padding:3px;}textarea,select,input{border:solid '.
                    '1px #ddd;background-color:#fff;color:#333;border-radius:3px;}.screen_reader{position:absolute'.
                    ';top:auto;width:1px;height:1px;overflow:hidden;}.login_form{display:flex; justify-content:space-evenly; align-items:center; flex-direction:column;font-size:90%;'.
                    'padding-top:60px;height:360px;border-radius:20px 20px 20px 20px;margin:0px;background-color:rgba(0,0,0,.6);'.
                    'min-width:300px;}.login_form input{clear:both;float:left;padding:4px;'.
                    'margin-top:10px;margin-bottom:10px;}#username,#password{width:200px; height:25px;} .err{color:red !important;}.long_session'.
                    '{float:left;}.long_session input{padding:0px;float:none;font-size:18px;}.mobile .long_session{float:left;clear:both;} @media screen and (min-width:400px){.login_form{min-width:400px;}}'.
                    '.user-icon_signin{display:block; background-color:white; border-radius:100%; padding:10px; height:40px; margin-top:-120px; box-shadow: #6eb549 .4px 2.4px 6.2px; }'.
                    '.label_signin{width:210px; margin:0px 0px -18px 0px;color:#fff;opacity:0.7;} @media (max-height : 500px){ .user-icon_signin{display:none;}}
                    </style>';
    
                return $css.'<div class="form-container"><form class="login_form" method="POST">';
            }
            else {
                return '<div class="form-container"><form class="logout_form" method="POST">';
            }
        }
    }
}

/**
 * Outputs the login or logout form
 * @subpackage core/output
 */
class Hm_Output_login extends Hm_Output_Module {
    /**
     * Looks at the current login state and outputs the correct form
     */
    protected function output() {
        $stay_logged_in = '';
        if ($this->get('allow_long_session')) {
            $stay_logged_in = '<div class="form-check form-switch long-session">
                <input type="checkbox" id="stay_logged_in" value="1" name="stay_logged_in" class="form-check-input">
                <label class="form-check-label" for="stay_logged_in">'.$this->trans('Stay logged in').'</label>
            </div>';
        }
        if (!$this->get('router_login_state')) {
            $fancy_login = $this->get('fancy_login_allowed');
            if(!$fancy_login){
                return '<div class="bg-light"><div class="d-flex align-items-center justify-content-center vh-100 p-3">
                    <div class="card col-12 col-md-6 col-lg-4 p-3">
                        <div class="card-body">
                            <p class="text-center"><img class="w-50" src="'.WEB_ROOT. 'modules/core/assets/images/logo_dark.svg"></p>
                            <div class="mt-5">
                                <div class="mb-3 form-floating">
                                    <input autofocus required type="text" placeholder="'.$this->trans('Username').'" id="username" name="username" class="form-control">
                                    <label for="username" class="form-label screen-reader">'.$this->trans('Username').'</label>
                                </div>
                                <div class="mb-3 form-floating">
                                    <input required type="password" id="password" placeholder="'.$this->trans('Password').'" name="password" class="form-control">
                                    <label for="password" class="form-label screen-reader">'.$this->trans('Password').'</label>
                                </div>'.
                                '<div class="d-grid">'.$stay_logged_in.
                                    '<input type="hidden" name="hm_page_key" value="'.Hm_Request_Key::generate().'" />
                                    <input type="submit" id="login" class="btn btn-success btn-lg" value="'.$this->trans('Login').'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div></div>';
            } else {
                return '<svg class="user-icon_signin" viewBox="0 0 20 20"><path d="M12.075,10.812c1.358-0.853,2.242-2.507,2.242-4.037c0-2.181-1.795-4.618-4.198-4.618S5.921,4.594,5.921,6.775c0,1.53,0.884,3.185,2.242,4.037c-3.222,0.865-5.6,3.807-5.6,7.298c0,0.23,0.189,0.42,0.42,0.42h14.273c0.23,0,0.42-0.189,0.42-0.42C17.676,14.619,15.297,11.677,12.075,10.812 M6.761,6.775c0-2.162,1.773-3.778,3.358-3.778s3.359,1.616,3.359,3.778c0,2.162-1.774,3.778-3.359,3.778S6.761,8.937,6.761,6.775 M3.415,17.69c0.218-3.51,3.142-6.297,6.704-6.297c3.562,0,6.486,2.787,6.705,6.297H3.415z"></path></svg>'.
                '<img src="'.WEB_ROOT. 'modules/core/assets/images/logo.svg" style="height:90px;">'.
                '<!--h1 class="title">'.$this->html_safe($this->get('router_app_name', '')).'</h1-->'.
                ' <input type="hidden" name="hm_page_key" value="'.Hm_Request_Key::generate().'" />'.
                ' <label class="label_signin" for="username">'.$this->trans('Username').'</label>'.
                '<input autofocus required type="text" placeholder="'.$this->trans('Username').'" id="username" name="username" value="">'.
                ' <label class="label_signin" for="password">'.$this->trans('Password').'</label>'.
                '<input required type="password" id="password" placeholder="'.$this->trans('Password').'" name="password">'.
                $stay_logged_in.' <input style="cursor:pointer; display:block; width:210px; background-color:#6eb549; color:white; height:40px;" type="submit" id="login" value="'.$this->trans('Login').'" />';
            }

        }
        else {
            $settings = $this->get('changed_settings', array());
            $single = $this->get('single_server_mode');
            $changed = 0;
            if (!$single && count($settings) > 0) {
                $changed = 1;
            }
           
        return '<div class="modal fade" id="confirmLogoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="confirmLogoutModalLabel">'.$this->trans('Do you want to log out?').'</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="hm_page_key" value="'.Hm_Request_Key::generate().'" />
                        <p class="text-wrap">'.$this->trans('Unsaved changes will be lost! Re-enter your password to save and exit.').' <a href="?page=save">'.$this->trans('More info').'</a></p>
                        <input type="text" value="'.$this->html_safe($this->get('username', 'cypht_user')).'" autocomplete="username" style="display: none;"/>
                        <div class="my-3 form-floating">
                            <input id="logout_password" autocomplete="current-password" name="password" class="form-control" type="password" placeholder="'.$this->trans('Password').'">
                            <label for="logout_password" class="form-label screen-reader">'.$this->trans('Password').'</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="cancel_logout save_settings btn btn-secondary" data-bs-dismiss="modal" type="button" value="'.$this->trans('Cancel').'" />
                        <input class="save_settings btn btn-success" id="logout_without_saving" type="submit" name="logout" value="'.$this->trans('Just Logout').'" />
                        <input class="save_settings btn btn-success" type="submit" name="save_and_logout" value="'.$this->trans('Save and Logout').'" />
                    </div>
                </div>
                </div>
            </div>';
        }
    }
}

/**
 * Start the content section for the servers page
 * @subpackage core/output
 */
class Hm_Output_server_content_start extends Hm_Output_Module {
    /**
     * The server_content div is closed in Hm_Output_server_content_end
     */
    protected function output() {
        return '<div class="content_title">'.$this->trans('Servers').
            '<div class="list_controls"></div>'.
            '</div><div class="server_content">';
    }
}

/**
 * Close the server content section
 * @subpackage core/output
 */
class Hm_Output_server_content_end extends Hm_Output_Module {
    /**
     * Closes the div tag opened in Hm_Output_server_content_start
     */
    protected function output() {
        return '</div>';
    }
}

/**
 * Output a date
 * @subpackage core/output
 */
class Hm_Output_date extends Hm_Output_Module {
    /**
     * Currently not show in the display (hidden with CSS)
     */
    protected function output() {
        return '<div class="date">'.$this->html_safe($this->get('date')).'</div>';
    }
}

/**
 * Display system messages
 * @subpackage core/output
 */
class Hm_Output_msgs extends Hm_Output_Module {
    /**
     * Error level messages start with ERR and will be shown in red
     */
    protected function output() {
        $res = '';
        $msgs = Hm_Msgs::get();
        $logged_out_class = '';
        if (!$this->get('router_login_state') && !empty($msgs)) {
            $logged_out_class = ' logged_out';
        }
        $res .= '<div class="z-3 position-fixed top-0 end-0 mt-3 me-3 sys_messages'.$logged_out_class.'">';
        if (!empty($msgs)) {
            $res .= implode(',', array_map(function($v) {
                if (preg_match("/ERR/", $v)) {
                    return sprintf('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="bi bi-exclamation-triangle me-2"></i><span class="err">%s</span>', $this->trans(substr((string) $v, 3)));
                }
                else {
                    return sprintf('<div class="alert alert-info alert-dismissible fade show" role="alert"><i class="bi bi-info-circle me-2"></i><span>%s</span>', $this->trans($v));
                }
            }, $msgs));
            $res .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
        $res .= '</div>';
        return $res;
    }
}

/**
 * Start the HTML5 document
 * @subpackage core/output
 */
class Hm_Output_header_start extends Hm_Output_Module {
    /**
     * Doctype, html, head, and a meta tag for charset. The head section is not closed yet
     */
    protected function output() {
        $lang = 'en';
        $dir = 'ltr';
        if ($this->lang) {
            $lang = strtolower(str_replace('_', '-', $this->lang));
        }
        if ($this->dir) {
            $dir = $this->dir;
        }
        $class = $dir."_page";
        $res = '<!DOCTYPE html><html dir="'.$this->html_safe($dir).'" class="'.
            $this->html_safe($class).'" lang='.$this->html_safe($lang).'><head>'.
            '<meta name="apple-mobile-web-app-capable" content="yes" />'.
            '<meta name="mobile-web-app-capable" content="yes" />'.
            '<meta name="apple-mobile-web-app-status-bar-style" content="black" />'.
            '<meta name="theme-color" content="#888888" /><meta charset="utf-8" />';

        if ($this->get('router_login_state')) {
            $res .= '<meta name="referrer" content="no-referrer" />';
        }
        return $res;
    }
}

/**
 * Close the HTML5 head tag
 * @subpackage core/output
 */
class Hm_Output_header_end extends Hm_Output_Module {
    /**
     * Close the head tag opened in Hm_Output_header_start
     */
    protected function output() {
        return '</head>';
    }
}

/**
 * Start the content section
 * @subpackage core/output
 */
class Hm_Output_content_start extends Hm_Output_Module {
    /**
     * Outputs the starting body tag and a noscript warning. Clears the local session cache
     * if not logged in, or adds a page wide key used by ajax requests
     */
    protected function output() {
        $res = '<body class="'.($this->get('is_mobile', false) ? 'mobile' : '').'"><noscript class="noscript">'.
            sprintf($this->trans('You need to have Javascript enabled to use %s, sorry about that!'),
                $this->html_safe($this->get('router_app_name'))).'</noscript>';
        if (!$this->get('router_login_state')) {
            $res .= '<script type="text/javascript">sessionStorage.clear();</script>';
        }
        else {
            $res .= '<input type="hidden" id="hm_page_key" value="'.$this->html_safe(Hm_Request_Key::generate()).'" />';
        }
        if (!$this->get('single_server_mode') && count($this->get('changed_settings', array())) > 0) {
            $res .= '<a class="unsaved_icon" href="?page=save" title="'.$this->trans('Unsaved Changes').
                '"><i class="bi bi-save2-fill fs-5 unsaved_reminder"></i></a>';
        }
        return $res;
    }
}

/**
 * Outputs HTML5 head tag content
 * @subpackage core/output
 */
class Hm_Output_header_content extends Hm_Output_Module {
    /**
     * Setup a title, base URL, a tab icon, and viewport settings
     */
    protected function output() {
        $title = '';
        if (!$this->get('router_login_state')) {
            $title = $this->get('router_app_name');
        }
        elseif ($this->exists('page_title')) {
            $title .= $this->get('page_title');
        }
        elseif ($this->exists('mailbox_list_title')) {
            $title .= ' '.implode('-', $this->get('mailbox_list_title', array()));
        }
        if (!trim((string) $title) && $this->exists('router_page_name')) {
            $title = '';
            if ($this->get('list_path') == 'message_list') {
                $title .= ' '.ucwords(str_replace('_', ' ', $this->get('list_path')));
            }
            elseif ($this->get('router_page_name') == 'notfound') {
                $title .= ' Nope';
            }
            else {
                $title .= ' '.ucwords(str_replace('_', ' ', $this->get('router_page_name')));
            }
        }
        return '<title>'.$this->trans(trim((string) $title)).'</title>'.
            '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">'.
            '<link rel="icon" class="tab_icon" type="image/png" href="data:image/png;base64,iVBORw0KGgo'.
            'AAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAFVJR'.
            'EFUOI3NkkEKACEMA2d92fpzfVn3oHhYqAF7qIFeSpImUMjGA1jEoEQTFKAC/UDbp3bhBRqj0m7a5C78F56Rx5MEdUB'.
            'HFMlkV09ogN3xB7kG+fgA0tc160Jy09wAAAAASUVORK5CYII=" >'.
            '<base href="'.$this->html_safe($this->get('router_url_path')).'" />';
    }
}

/**
 * Output CSS
 * @subpackage core/output
 */
class Hm_Output_header_css extends Hm_Output_Module {
    /**
     * In debug mode adds each module css file to the page, otherwise uses the combined version
     */
    protected function output() {
        $res = '';
        $mods = $this->get('router_module_list');
        if (DEBUG_MODE) {
            $res .= '<link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />';
            $res .= '<link href="vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" type="text/css" />';
            foreach (glob(APP_PATH.'modules'.DIRECTORY_SEPARATOR.'**', GLOB_ONLYDIR | GLOB_MARK) as $name) {
                $rel_name = str_replace(APP_PATH, '', $name);
                $mod = str_replace(array('modules', DIRECTORY_SEPARATOR), '', $rel_name);
                if (in_array($mod, $mods, true) && is_readable(sprintf("%ssite.css", $name))) {
                    $res .= '<link href="'.sprintf("%ssite.css", $rel_name).'" media="all" rel="stylesheet" type="text/css" />';
                }
            }
            // load pcss3t.cs only if one of: ['contacts','local_contacts','ldap_contacts','gmail_contacts'] is enabled
            if(count(array_intersect(['contacts','local_contacts','ldap_contacts','gmail_contacts'], $mods)) > 0){
                $res .= '<link href="third_party/contact-group.css" media="all" rel="stylesheet" type="text/css" />';
            }
        }
        else {
            $res .= '<link href="site.css?v='.CACHE_ID.'" ';
            if (defined('CSS_HASH') && CSS_HASH) {
                $res .= 'integrity="'.CSS_HASH.'" ';
            }
            $res .= 'media="all" rel="stylesheet" type="text/css" />';
        }
        $res .= '<style type="text/css">@font-face {font-family:"Behdad";'.
            'src:url("'.WEB_ROOT.'modules/core/assets/fonts/Behdad/Behdad-Regular.woff2") format("woff2"),'.
            'url("'.WEB_ROOT.'modules/core/assets/fonts/Behdad/Behdad-Regular.woff") format("woff");</style>';
        return $res;
    }
}

/**
 * Output JS
 * @subpackage core/output
 */
class Hm_Output_page_js extends Hm_Output_Module {
    /**
     * In debug mode adds each module js file to the page, otherwise uses the combined version.
     * Includes the cash.js library, and the forge lib if it's needed.
     */
    protected function output() {
        if (DEBUG_MODE) {
            $res = '';
            $js_lib = '<script type="text/javascript" src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>';
            $js_lib .= '<script type="text/javascript" src="third_party/cash.min.js"></script>';
            $js_lib .= '<script type="text/javascript" src="third_party/resumable.min.js"></script>';
            $js_lib .= '<script type="text/javascript" src="third_party/ays-beforeunload-shim.js"></script>';
            $js_lib .= '<script type="text/javascript" src="third_party/jquery.are-you-sure.js"></script>';
            $js_lib .= '<script type="text/javascript" src="third_party/sortable.min.js"></script>';
            if ($this->get('encrypt_ajax_requests', '') || $this->get('encrypt_local_storage', '')) {
                $js_lib .= '<script type="text/javascript" src="third_party/forge.min.js"></script>';
            }
            $core = false;
            $mods = $this->get('router_module_list');
            foreach (glob(APP_PATH.'modules'.DIRECTORY_SEPARATOR.'**', GLOB_ONLYDIR | GLOB_MARK) as $name) {
                $rel_name = str_replace(APP_PATH, '', $name);
                if ($rel_name == 'modules'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR) {
                    $core = $rel_name;
                    continue;
                }
                $mod = str_replace(array('modules', DIRECTORY_SEPARATOR), '', $rel_name);
                if (in_array($mod, $mods, true) && is_readable(sprintf("%ssite.js", $name))) {
                    $res .= '<script type="text/javascript" src="'.sprintf("%ssite.js", $rel_name).'"></script>';
                }
            }
            if ($core) {
                $res = '<script type="text/javascript" src="'.sprintf("%ssite.js", $core).'"></script>'.$res;
            }
            return $js_lib.$res;
        }
        else {
            $res = '<script type="text/javascript" ';
            if (defined('JS_HASH') && JS_HASH) {
                $res .= 'integrity="'.JS_HASH.'" ';
            }
            $res .= 'src="site.js?v='.CACHE_ID.'" async></script>';
            return $res;
        }
    }
}

/**
 * End the page content
 * @subpackage core/output
 */
class Hm_Output_content_end extends Hm_Output_Module {
    /**
     * Closes the body and html tags
     */
    protected function output() {
        return '</body></html>';
    }
}

/**
 * Outputs page details for the JS
 * @subpackage core/output
 */
class Hm_Output_js_data extends Hm_Output_Module {
    /**
     * Uses function wrappers to make the data immutable from JS
     */
    protected function output() {
        $res = '<script type="text/javascript">'.
            'var globals = {};'.
            'var hm_is_logged = function () { return '.($this->get('is_logged') ? '1' : '0').'; };'.
            'var hm_empty_folder = function() { return "'.$this->trans('So alone').'"; };'.
            'var hm_mobile = function() { return '.($this->get('is_mobile') ? '1' : '0').'; };'.
            'var hm_debug = function() { return "'.(DEBUG_MODE ? '1' : '0').'"; };'.
            'var hm_mailto = function() { return '.($this->get('mailto_handler') ? '1' : '0').'; };'.
            'var hm_page_name = function() { return "'.$this->html_safe($this->get('router_page_name')).'"; };'.
            'var hm_language_direction = function() { return "'.$this->html_safe($this->dir).'"; };'.
            'var hm_list_path = function() { return "'.$this->html_safe($this->get('list_path', '')).'"; };'.
            'var hm_list_parent = function() { return "'.$this->html_safe($this->get('list_parent', '')).'"; };'.
            'var hm_msg_uid = function() { return Hm_Utils.get_from_global("msg_uid", "'.$this->html_safe($this->get('uid', '')).'"); };'.
            'var hm_encrypt_ajax_requests = function() { return "'.$this->html_safe($this->get('encrypt_ajax_requests', '')).'"; };'.
            'var hm_encrypt_local_storage = function() { return "'.$this->html_safe($this->get('encrypt_local_storage', '')).'"; };'.
            'var hm_web_root_path = function() { return "'.WEB_ROOT.'"; };'.
            'var hm_flag_image_src = function() { return "<i class=\"bi bi-star-half\"></i>"; };'.
            'var hm_check_dirty_flag = function() { return '.($this->get('warn_for_unsaved_changes', '') ? '1' : '0').'; };'.
            format_data_sources($this->get('data_sources', array()), $this);

        if (!$this->get('disable_delete_prompt')) {
            $res .= 'var hm_delete_prompt = function() { return confirm("'.$this->trans('Are you sure?').'"); };';
        }
        else {
            $res .= 'var hm_delete_prompt = function() { return true; };';
        }
        $res .= 'window.hm_current_lang = "'.$this->lang.'";'.
            'window.hm_translations = '.json_encode($this->all_trans()).';'.
            'var hm_trans = function(key, lang = window.hm_current_lang) {'.
            '    const langTranslations = window.hm_translations && window.hm_translations[lang];'.
            '    if (langTranslations && langTranslations[key] !== undefined && langTranslations[key] !== false) {'.
            '        return langTranslations[key];'.
            '    }'.
            '    return key;'.
            '};';
        $res .= '</script>';
        return $res;
    }
}

/**
 * Outputs a load icon
 * @subpackage core/output
 */
class Hm_Output_loading_icon extends Hm_Output_Module {
    /**
     * Sort of ugly loading icon animated with js/css
     */
    protected function output() {
        return '<div class="loading_icon"></div>';
    }
}

/**
 * Start the main form on the settings page
 * @subpackage core/output
 */
class Hm_Output_start_settings_form extends Hm_Output_Module {
    /**
     * Opens a div, form and table
     */
    protected function output() {
        return '<div class="user_settings px-0"><div class="content_title px-3">'.$this->trans('Site Settings').'</div>'.
            '<form method="POST"><input type="hidden" name="hm_page_key" value="'.$this->html_safe(Hm_Request_Key::generate()).'" />'.
            '<div class="px-3"><table class="settings_table table table-borderless"><colgroup>'.
            '<col class="label_col"><col class="setting_col"></colgroup>';
    }
}

/**
 * Outputs the start page option on the settings page
 * @subpackage core/output
 */
class Hm_Output_start_page_setting extends Hm_Output_Module {
    /**
     * Can be any of the main combined pages
     */
    protected function output() {
        $options = start_page_opts();
        $settings = $this->get('user_settings', array());
        $res = '';
        $reset = '';

        if (array_key_exists('start_page', $settings)) {
            $start_page = $settings['start_page'];
        }
        else {
            $start_page = false;
        }
        $res = '<tr class="general_setting"><td><label for="start_page">'.
            $this->trans('First page after login').'</label></td>'.
            '<td><select class="form-select form-select-sm w-auto" id="start_page" name="start_page">';
        foreach ($options as $label => $val) {
            $res .= '<option ';
            if ($start_page == $val) {
                $res .= 'selected="selected" ';
                if ($start_page != 'none') {
                    $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_select"></i></span>';
                }
            }
            $res .= 'value="'.$val.'">'.$this->trans($label).'</option>';
        }
        $res .= '</select>'.$reset.'</td></tr>';
        return $res;
    }
}

/**
 * Outputs the default sort order option on the settings page
 * @subpackage core/output
 */
class Hm_Output_default_sort_order_setting extends Hm_Output_Module {
    /**
     * Can be any of the main combined pages
     */
    protected function output() {
        $options = default_sort_order_opts();
        $settings = $this->get('user_settings', array());
        $reset = '';

        if (array_key_exists('default_sort_order', $settings)) {
            $default_sort_order = $settings['default_sort_order'];
        }
        else {
            $default_sort_order = null;
        }
        $res = '<tr class="general_setting"><td><label for="default_sort_order">'.
            $this->trans('Default message sort order').'</label></td>'.
            '<td><select class="form-select form-select-sm w-auto" id="default_sort_order" name="default_sort_order">';
        foreach ($options as $val => $label) {
            $res .= '<option ';
            if ($default_sort_order == $val) {
                $res .= 'selected="selected" ';
                if ($default_sort_order != 'arrival') {
                    $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_select"></i></span>';
                }
            }
            $res .= 'value="'.$val.'">'.$this->trans($label).'</option>';
        }
        $res .= '</select>'.$reset.'</td></tr>';
        return $res;
    }
}

/**
 * Outputs the list style option on the settings page
 * @subpackage core/output
 */
class Hm_Output_list_style_setting extends Hm_Output_Module {
    /**
     * Can be either "news style" or the default "email style"
     */
    protected function output() {
        $options = array('email_style' => 'Email', 'news_style' => 'News');
        $settings = $this->get('user_settings', array());
        $reset = '';

        if (array_key_exists('list_style', $settings)) {
            $list_style = $settings['list_style'];
        }
        else {
            $list_style = false;
        }
        $res = '<tr class="general_setting"><td><label for="list_style">'.
            $this->trans('Message list style').'</label></td>'.
            '<td><select class="form-select form-select-sm w-auto" id="list_style" name="list_style">';
        foreach ($options as $val => $label) {
            $res .= '<option ';
            if ($list_style == $val) {
                $res .= 'selected="selected" ';
                if ($list_style != 'email_style') {
                    $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_select"></i></span>';
                }
            }
            $res .= 'value="'.$val.'">'.$this->trans($label).'</option>';
        }
        $res .= '</select>'.$reset.'</td></tr>';
        return $res;
    }
}

/**
 * @subpackage core/output
 */
class Hm_Output_mailto_handler_setting extends Hm_Output_Module {
    protected function output() {
        $settings = $this->get('user_settings');
        if (array_key_exists('mailto_handler', $settings) && $settings['mailto_handler']) {
            $checked = ' checked="checked"';
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_checkbox"></i></span>';
        }
        else {
            $checked = '';
            $reset = '';
        }
        return '<tr class="general_setting"><td><label class="form-check-label" for="mailto_handler">'.$this->trans('Allow handling of mailto links').'</label></td>'.
            '<td><input class="form-check-input" type="checkbox" '.$checked.' value="1" id="mailto_handler" name="mailto_handler" />'.$reset.'</td></tr>';
    }
}

/**
 * @subpackage core/output
 */
class Hm_Output_no_folder_icon_setting extends Hm_Output_Module {
    protected function output() {
        $settings = $this->get('user_settings');
        if (array_key_exists('no_folder_icons', $settings) && $settings['no_folder_icons']) {
            $checked = ' checked="checked"';
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_checkbox"></i></span>';
        }
        else {
            $checked = '';
            $reset = '';
        }
        return '<tr class="general_setting"><td><label class="form-check-label" for="no_folder_icons">'.$this->trans('Hide folder list icons').'</label></td>'.
            '<td><input class="form-check-input" type="checkbox" '.$checked.' value="1" id="no_folder_icons" name="no_folder_icons" />'.$reset.'</td></tr>';
    }
}

/**
 * @subpackage core/output
 */
class Hm_Output_no_password_setting extends Hm_Output_Module {
    protected function output() {
        $settings = $this->get('user_settings');
        if (array_key_exists('no_password_save', $settings) && $settings['no_password_save']) {
            $checked = ' checked="checked"';
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_checkbox"></i></span>';
        }
        else {
            $checked = '';
            $reset = '';
        }
        return '<tr class="general_setting"><td><label class="form-check-label" for="no_password_save">'.$this->trans('Don\'t save account passwords between logins').'</label></td>'.
            '<td><input class="form-check-input" type="checkbox" '.$checked.' value="1" id="no_password_save" name="no_password_save" />'.$reset.'</td></tr>';
    }
}

/**
 * @subpackage core/output
 */
class Hm_Output_delete_prompt_setting extends Hm_Output_Module {
    protected function output() {
        $settings = $this->get('user_settings');
        if (array_key_exists('disable_delete_prompt', $settings) && $settings['disable_delete_prompt']) {
            $checked = ' checked="checked"';
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_checkbox"></i></span>';
        }
        else {
            $checked = '';
            $reset = '';
        }
        return '<tr class="general_setting"><td><label class="form-check-label" for="disable_delete_prompt">'.$this->trans('Disable prompts when deleting').'</label></td>'.
            '<td><input class="form-check-input" type="checkbox" '.$checked.' value="1" id="disable_delete_prompt" name="disable_delete_prompt" />'.$reset.'</td></tr>';
    }
}

/**
 * Starts the Flagged section on the settings page
 * @subpackage core/output
 */
class Hm_Output_start_flagged_settings extends Hm_Output_Module {
    /**
     * Settings in this section control the flagged messages view
     */
    protected function output() {
        return '<tr><td data-target=".flagged_setting" colspan="2" class="settings_subtitle cursor-pointer border-bottom p-2 text-secondary">'.
            '<i class="bi bi-flag-fill fs-5 me-2"></i>'.
            $this->trans('Flagged').'</td></tr>';
    }
}

/**
 * Start the Everything section on the settings page
 * @subpackage core/output
 */
class Hm_Output_start_everything_settings extends Hm_Output_Module {
    /**
     * Setttings in this section control the Everything view
     */
    protected function output() {
        if ($this->get('single_server_mode')) {
            return '';
        }
        return '<tr><td data-target=".all_setting" colspan="2" class="settings_subtitle cursor-pointer border-bottom p-2 text-secondary">'.
            '<i class="bi bi-box2-fill fs-5 me-2"></i>'.
            $this->trans('Everything').'</td></tr>';
    }
}

/**
 * Start the Unread section on the settings page
 * @subpackage core/output
 */
class Hm_Output_start_unread_settings extends Hm_Output_Module {
    /**
     * Settings in this section control the Unread view
     */
    protected function output() {
        return '<tr><td data-target=".unread_setting" colspan="2" class="settings_subtitle cursor-pointer border-bottom p-2 text-secondary">'.
            '<i class="bi bi-envelope-fill fs-5 me-2"></i>'.
            $this->trans('Unread').'</td></tr>';
    }
}

/**
 * Start the E-mail section on the settings page.
 * @subpackage core/output
 */
class Hm_Output_start_all_email_settings extends Hm_Output_Module {
    /**
     * Settings in this section control the All E-mail view. Skipped if there are no
     * E-mail enabled module sets.
     */
    protected function output() {
        if (!email_is_active($this->get('router_module_list'))) {
            return '';
        }
        if ($this->get('single_server_mode')) {
            return '';
        }
        return '<tr><td data-target=".email_setting" colspan="2" class="settings_subtitle cursor-pointer border-bottom p-2 text-secondary">'.
            '<i class="bi bi-envelope-fill fs-5 me-2"></i>'.
            $this->trans('All Email').'</td></tr>';
    }
}

/**
 * Start the general settings section
 * @subpackage core/output
 */
class Hm_Output_start_general_settings extends Hm_Output_Module {
    /**
     * General settings like langauge and timezone will go here
     */
    protected function output() {
        return '<tr><td data-target=".general_setting" colspan="2" class="settings_subtitle cursor-pointer border-bottom p-2 text-secondary">'.
            '<i class="bi bi-gear-wide-connected fs-5 me-2"></i>'.
            $this->trans('General').'</td></tr>';
    }
}

/**
 * Option for the maximum number of messages per source for the Unread page
 * @subpackage core/output
 */
class Hm_Output_unread_source_max_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_unread_source_max_setting
     */
    protected function output() {
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('unread_per_source', $settings)) {
            $sources = $settings['unread_per_source'];
        }
        if ($sources != 20) {
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_input"></i></span>';
        }
        return '<tr class="unread_setting"><td><label for="unread_per_source">'.
            $this->trans('Max messages per source').'</label></td>'.
            '<td><input class="form-control form-control-sm w-auto" type="text" size="2" id="unread_per_source" name="unread_per_source" value="'.$this->html_safe($sources).'" />'.$reset.'</td></tr>';
    }
}

/**
 * Option for the "received since" date range for the Unread page
 * @subpackage core/output
 */
class Hm_Output_unread_since_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_unread_since_setting
     */
    protected function output() {
        $since = DEFAULT_SINCE;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('unread_since', $settings) && $settings['unread_since']) {
            $since = $settings['unread_since'];
        }
        return '<tr class="unread_setting"><td><label for="unread_since">'.
            $this->trans('Show messages received since').'</label></td>'.
            '<td>'.message_since_dropdown($since, 'unread_since', $this).'</td></tr>';
    }
}

/**
 * Option for the maximum number of messages per source for the Flagged page
 * @subpackage core/output
 */
class Hm_Output_flagged_source_max_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_flagged_source_max_setting
     */
    protected function output() {
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('flagged_per_source', $settings)) {
            $sources = $settings['flagged_per_source'];
        }
        if ($sources != 20) {
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_input"></i></span>';
        }
        return '<tr class="flagged_setting"><td><label for="flagged_per_source">'.
            $this->trans('Max messages per source').'</label></td>'.
            '<td><input class="form-control form-control-sm w-auto" type="text" size="2" id="flagged_per_source" name="flagged_per_source" value="'.$this->html_safe($sources).'" />'.$reset.'</td></tr>';
    }
}

/**
 * Option for the "received since" date range for the Flagged page
 * @subpackage core/output
 */
class Hm_Output_flagged_since_setting extends Hm_Output_Module {
    protected function output() {
        /**
         * Processed by Hm_Handler_process_flagged_since_setting
         */
        $since = DEFAULT_SINCE;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('flagged_since', $settings) && $settings['flagged_since']) {
            $since = $settings['flagged_since'];
        }
        return '<tr class="flagged_setting"><td><label for="flagged_since">'.
            $this->trans('Show messages received since').'</label></td>'.
            '<td>'.message_since_dropdown($since, 'flagged_since', $this).'</td></tr>';
    }
}

/**
 * Option for the maximum number of messages per source for the All E-mail  page
 * @subpackage core/output
 */
class Hm_Output_all_email_source_max_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_all_email_source_max_setting
     */
    protected function output() {
        if (!email_is_active($this->get('router_module_list'))) {
            return '';
        }
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('all_email_per_source', $settings)) {
            $sources = $settings['all_email_per_source'];
        }
        if ($sources != 20) {
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_input"></i></span>';
        }
        return '<tr class="email_setting"><td><label for="all_email_per_source">'.
            $this->trans('Max messages per source').'</label></td>'.
            '<td><input class="form-control form-control-sm w-auto" type="text" size="2" id="all_email_per_source" name="all_email_per_source" value="'.$this->html_safe($sources).'" />'.$reset.'</td></tr>';
    }
}

/**
 * Option for the maximum number of messages per source for the Everything page
 * @subpackage core/output
 */
class Hm_Output_all_source_max_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_all_source_max_setting
     */
    protected function output() {
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('all_per_source', $settings)) {
            $sources = $settings['all_per_source'];
        }
        if ($sources != 20) {
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_input"></i></span>';
        }
        return '<tr class="all_setting"><td><label for="all_per_source">'.
            $this->trans('Max messages per source').'</label></td>'.
            '<td><input class="form-control form-control-sm w-auto" type="text" size="2" id="all_per_source" name="all_per_source" value="'.$this->html_safe($sources).'" />'.$reset.'</td></tr>';
    }
}

/**
 * Option for the "received since" date range for the All E-mail page
 * @subpackage core/output
 */
class Hm_Output_all_email_since_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_all_email_since_setting
     */
    protected function output() {
        if (!email_is_active($this->get('router_module_list'))) {
            return '';
        }
        $since = DEFAULT_SINCE;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('all_email_since', $settings) && $settings['all_email_since']) {
            $since = $settings['all_email_since'];
        }
        return '<tr class="email_setting"><td><label for="all_email_since">'.
            $this->trans('Show messages received since').'</label></td>'.
            '<td>'.message_since_dropdown($since, 'all_email_since', $this).'</td></tr>';
    }
}

/**
 * Option for the "received since" date range for the Everything page
 * @subpackage core/output
 */
class Hm_Output_all_since_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_all_since_setting
     */
    protected function output() {
        $since = DEFAULT_SINCE; 
        $settings = $this->get('user_settings', array());
        if (array_key_exists('all_since', $settings) && $settings['all_since']) {
            $since = $settings['all_since'];
        }
        return '<tr class="all_setting"><td><label for="all_since">'.
            $this->trans('Show messages received since').'</label></td>'.
            '<td>'.message_since_dropdown($since, 'all_since', $this).'</td></tr>';
    }
}

/**
 * Option for the language setting
 * @subpackage core/output
 */
class Hm_Output_language_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_language_setting
     */
    protected function output() {
        $langs = interface_langs();
        $translated = array();
        $reset = '';
        foreach ($langs as $code => $name) {
            $translated[$code] = $this->trans($name);
        }
        asort($translated);
        $mylang = $this->get('language', '');
        $res = '<tr class="general_setting"><td><label for="language">'.
            $this->trans('Language').'</label></td>'.
            '<td><select id="language" class="form-select form-select-sm w-auto" name="language">';
        foreach ($translated as $id => $lang) {
            $res .= '<option ';
            if ($id == $mylang) {
                $res .= 'selected="selected" ';
                if ($id != 'en') {
                    $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_select"></i></span>';
                }
            }
            $res .= 'value="'.$id.'">'.$lang.'</option>';
        }
        $res .= '</select>'.$reset.'</td></tr>';
        return $res;
    }
}

/**
 * Option for the timezone setting
 * @subpackage core/output
 */
class Hm_Output_timezone_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_timezone_setting
     */
    protected function output() {
        $zones = timezone_identifiers_list();
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('timezone', $settings)) {
            $myzone = $settings['timezone'];
        }
        else {
            $myzone = false;
        }
        $res = '<tr class="general_setting"><td><label for="timezone">'.
            $this->trans('Timezone').'</label></td><td><select class="w-auto form-select form-select-sm" id="timezone" name="timezone">';
        foreach ($zones as $zone) {
            $res .= '<option ';
            if ($zone == $myzone) {
                $res .= 'selected="selected" ';
                if ($zone != 'Africa/Abidjan') {
                    $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_select"></i></span>';
                }
            }
            $res .= 'value="'.$zone.'">'.$zone.'</option>';
        }
        $res .= '</select>'.$reset.'</td></tr>';
        return $res;
    }
}

/**
 * Option to enable/disable message list icons
 * @subpackage core/output
 */
class Hm_Output_msg_list_icons_setting extends Hm_Output_Module {
    protected function output() {
        $checked = '';
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('show_list_icons', $settings) && $settings['show_list_icons']) {
            $checked = ' checked="checked"';
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_checkbox"></i></span>';
        }
        return '<tr class="general_setting"><td><label class="form-check-label" for="show_list_icons">'.
            $this->trans('Show icons in message lists').'</label></td>'.
            '<td><input class="form-check-input" type="checkbox" '.$checked.' id="show_list_icons" name="show_list_icons" value="1" />'.$reset.'</td></tr>';
    }
}

/**
 * Ends the settings table
 * @subpackage core/output
 */
class Hm_Output_end_settings_form extends Hm_Output_Module {
    /**
     * Closes the table, form and div opened in Hm_Output_start_settings_form
     */
    protected function output() {
        return '<tr><td class="submit_cell" colspan="2">'.
            '<input class="save_settings btn btn-success" type="submit" name="save_settings" value="'.$this->trans('Save').'" />'.
            '</td></tr></table></div></form>'.
            '<div class="px-3 d-flex justify-content-end"><form method="POST"><input type="hidden" name="hm_page_key" value="'.$this->html_safe(Hm_Request_Key::generate()).'" />'.
            '<input class="reset_factory_button btn btn-light border" type="submit" name="reset_factory" value="'.$this->trans('Restore Defaults').'" /></form></div>'.
            '</div>';
    }
}

/**
 * Start the folder list
 * @subpackage core/output
 */
class Hm_Output_folder_list_start extends Hm_Output_Module {
    /**
     * Opens the folder list nav tag
     */
    protected function output() {
        $res = '<a class="folder_toggle" href="#">'.$this->trans('Show folders').'<i class="bi bi-menu-up"></i></a>'.
            '<nav class="folder_cell"><div class="folder_list">';
        return $res;
    }
}

/**
 * Start the folder list content
 * @subpackage core/output
 */
class Hm_Output_folder_list_content_start extends Hm_Output_Module {
    /**
     * Creates a modfiable string called formatted_folder_list other modules append to
     */
    protected function output() {
        if ($this->format == 'HTML5') {
            return '';
        }
        $this->out('formatted_folder_list', '', false);
    }
}

/**
 * Start the Main menu section of the folder list
 * @subpackage core/output
 */
class Hm_Output_main_menu_start extends Hm_Output_Module {
    /**
     * Opens a div and unordered list tag
     */
    protected function output() {
        $res = '<div class="src_name main_menu d-flex justify-content-between pe-2" data-source=".main">'.$this->trans('Main');
        if (DEBUG_MODE) {
            $res .= ' <span title="'.
                $this->trans('Running in debug mode. See https://cypht.org/install.html Section 6 for more detail.').
                '" class="debug_title">'.$this->trans('Debug').'</span>';
        }
        $res .= '<i class="bi bi-chevron-down"></i>'.
        '</div><div class="main"><ul class="folders">';
        if ($this->format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Content for the Main menu section of the folder list
 * @subpackage core/output
 */
class Hm_Output_main_menu_content extends Hm_Output_Module {
    /**
     * Links to default pages: Home, Everything, Unread and Flagged
     * @todo break this up into smaller modules
     */
    protected function output() {
        $res = '';
        $email = false;
        $single = $this->get('single_server_mode');
        if (array_key_exists('email_folders', merge_folder_list_details($this->get('folder_sources', array())))) {
            $email = true;
        }
        if (!$single) {
            $res .= '<li class="menu_combined_inbox"><a class="unread_link" href="?page=message_list&amp;list_path=combined_inbox">';
            if (!$this->get('hide_folder_icons')) {
                $res .= '<i class="bi bi-box2-fill fs-5 me-2"></i>';
            }
            $res .= $this->trans('Everything').'</a><span class="combined_inbox_count"></span></li>';
        }
        $res .= '<li class="menu_unread d-flex align-items-center"><a class="unread_link d-flex align-items-center" href="?page=message_list&amp;list_path=unread">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-envelope-fill fs-5 me-2"></i>';
        }
        $res .= $this->trans('Unread').'</a><span class="total_unread_count badge bg-secondary ms-2 px-1"></span></li>';
        $res .= '<li class="menu_flagged"><a class="unread_link" href="?page=message_list&amp;list_path=flagged">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-flag-fill fs-5 me-2"></i>';
        }
        $res .= $this->trans('Flagged').'</a> <span class="flagged_count"></span></li>';
        $res .= '<li class="menu_junk"><a class="unread_link" href="?page=message_list&amp;list_path=junk">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-envelope-x-fill fs-5 me-2"></i>';
        }
        $res .= $this->trans('Junk').'</a></li>';
        $res .= '<li class="menu_trash"><a class="unread_link" href="?page=message_list&amp;list_path=trash">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-trash3-fill fs-5 me-2"></i>';
        }
        $res .= $this->trans('Trash').'</a></li>';
        $res .= '<li class="menu_drafts"><a class="unread_link" href="?page=message_list&amp;list_path=drafts">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-pencil-square fs-5 me-2"></i>';
        }
        $res .= $this->trans('Drafts').'</a></li>';

        if ($this->format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Outputs the logout link in the Main menu of the folder list
 * @subpackage core/output
 */
class Hm_Output_logout_menu_item extends Hm_Output_Module {
    protected function output() {
        $res =  '<li class="menu_logout"><a class="unread_link logout_link" href="#">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-power fs-5 me-2"></i>';
        }
        $res .= $this->trans('Logout').'</a></li>';

        if ($this->format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Close the Main menu section of the folder list
 * @subpackage core/output
 */
class Hm_Output_main_menu_end extends Hm_Output_Module {
    protected function output() {
        $res = '</ul></div>';
        if ($this->format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Output the E-mail section of the folder list
 * @subpackage core/output
 */
class Hm_Output_email_menu_content extends Hm_Output_Module {
    /**
     * Displays a list of all configured E-mail accounts
     */
    protected function output() {
        $res = '';
        $folder_sources = merge_folder_list_details($this->get('folder_sources'));
        $single = $this->get('single_server_mode');
        foreach ($folder_sources as $src => $content) {
            $parts = explode('_', $src);
            array_pop($parts);
            $name = ucwords(implode(' ', $parts));
            if (!$single) {
                $res .= '<div class="src_name d-flex justify-content-between pe-2" data-source=".'.$this->html_safe($src).'">'.$this->trans($name).
                    '<i class="bi bi-chevron-down"></i></div>';
            }

            if ($single) {
                $res .= '<div ';
            }
            else {
                $res .= '<div style="display: none;" ';
            }
            $res .= 'class="'.$this->html_safe($src).'"><ul class="folders">';
            if ($name == 'Email' && !$single) {
                $res .= '<li class="menu_email"><a class="unread_link" href="?page=message_list&amp;list_path=email">';
                if (!$this->get('hide_folder_icons')) {
                    $res .= '<i class="bi bi-globe-americas fs-5 me-2"></i>';
                }
                $res .= $this->trans('All').'</a> <span class="unread_mail_count"></span></li>';
            }
            $res .= $content.'</ul></div>';
        }
        if ($this->format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Start the Settings section of the folder list
 * @subpackage core/output
 */
class Hm_Output_settings_menu_start extends Hm_Output_Module {
    /**
     * Opens an unordered list
     */
    protected function output() {
        $res = '<div class="src_name d-flex justify-content-between pe-2" data-source=".settings">'.$this->trans('Settings').
            '<i class="bi bi-chevron-down"></i></div>'.
            '</div><ul style="display: none;" class="settings folders">';
        $res .= '<li class="menu_home"><a class="unread_link" href="?page=home">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-house-door-fill fs-5 me-2"></i>';
        }
        $res .= $this->trans('Home').'</a></li>';
        if ($this->format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Save settings page content
 * @subpackage core/output
 */
class Hm_Output_save_form extends Hm_Output_Module {
    /**
     * Outputs save form
     */
    protected function output() {
        $changed = $this->get('changed_settings', array());
        $res = '<div class="save_settings_page p-0"><div class="content_title px-3">'.$this->trans('Save Settings').'</div>';
        $res .= '<div class="save_details p-3">'.$this->trans('Settings are not saved permanently on the server unless you explicitly allow it. '.
            'If you don\'t save your settings, any changes made since you last logged in will be deleted when your '.
            'session expires or you logout. You must re-enter your password for security purposes to save your settings '.
            'permanently.');
        $res .= '<div class="save_subtitle mt-3"><b>'.$this->trans('Unsaved Changes').'</b></div>';
        $res .= '<ul class="unsaved_settings">';
        if (!empty($changed)) {
            $changed = array_count_values($changed);
            foreach ($changed as $change => $num) {
                $res .= '<li>'.$this->trans($change).' ('.$this->html_safe($num).'X)</li>';
            }
        }
        else {
            $res .= '<li>'.$this->trans('No changes need to be saved').'</li>';
        }
        $res .= '</ul></div><div class="save_perm_form px-3"><form method="post">'.
            '<input type="hidden" name="hm_page_key" value="'.$this->html_safe(Hm_Request_Key::generate()).'" />'.
            '<input type="text" value="'.$this->html_safe($this->get('username', 'cypht_user')).'" autocomplete="username" style="display: none;"/>'.
            '<label class="screen_reader" for="password">Password</label><input required id="password" '.
            'name="password" autocomplete="current-password" class="save_settings_password form-control mb-2" type="password" placeholder="'.$this->trans('Password').'" />'.
            '<input class="save_settings btn btn-success me-2" type="submit" name="save_settings_permanently" value="'.$this->trans('Save').'" />'.
            '<input class="save_settings btn btn-outline-secondary me-2" type="submit" name="save_settings_permanently_then_logout" value="'.$this->trans('Save and Logout').'" />'.
            '</form><form method="post"><input type="hidden" name="hm_page_key" value="'.$this->html_safe(Hm_Request_Key::generate()).'" />'.
            '<input class="save_settings btn btn-outline-secondary" type="submit" name="logout" value="'.$this->trans('Just Logout').'" />'.
            '</form></div>';

        $res .= '</div>';
        return $res;
    }
}

/**
 * Servers link for the Settings menu section of the folder list
 * @subpackage core/output
 */
class Hm_Output_settings_servers_link extends Hm_Output_Module {
    /**
     * Outputs links to the Servers settings pages
     */
    protected function output() {
        $res = '<li class="menu_servers"><a class="unread_link" href="?page=servers">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-pc-display-horizontal fs-5 me-2"></i>';
        }
        $res .= $this->trans('Servers').'</a></li>';
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Site link for the Settings menu section of the folder list
 * @subpackage core/output
 */
class Hm_Output_settings_site_link extends Hm_Output_Module {
    /**
     * Outputs links to the Site Settings pages
     */
    protected function output() {
        $res = '<li class="menu_settings"><a class="unread_link" href="?page=settings">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-gear-wide-connected fs-5 me-2"></i>';
        }
        $res .= $this->trans('Site').'</a></li>';
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Save link for the Settings menu section of the folder list
 * @subpackage core/output
 */
class Hm_Output_settings_save_link extends Hm_Output_Module {
    /**
     * Outputs links to the Servers and Site Settings pages
     */
    protected function output() {
        if ($this->get('single_server_mode')) {
            return;
        }
        $res = '<li class="menu_save"><a class="unread_link" href="?page=save">';
        if (!$this->get('hide_folder_icons')) {
            $res .= '<i class="bi bi-download fs-5 me-2"></i>';
        }
        $res .= $this->trans('Save').'</a></li>';
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Closes the Settings menu in the folder list
 * @subpackage core/output
 */
class Hm_Output_settings_menu_end extends Hm_Output_Module {
    /**
     * Closes the ul tag opened in Hm_Output_settings_menu_start
     */
    protected function output() {
        $res = '</ul>';
        if ($this->format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * End of the content section of the folder list
 * @subpackage core/output
 */
class Hm_Output_folder_list_content_end extends Hm_Output_Module {
    /**
     * Adds collapse and reload links
     */
    protected function output() {
        $res = '<a href="#" class="update_message_list">'.$this->trans('[reload]').'</a>';
        $res .= '<a href="#" class="hide_folders">'.$this->trans('Hide folders').'<i class="bi bi-caret-down-fill"'.'" alt="'.$this->trans('Collapse').'></i></a>';
        if ($this->format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

/**
 * Closes the folder list
 * @subpackage core/output
 */
class Hm_Output_folder_list_end extends Hm_Output_Module {
    /**
     * Closes the div and nav tags opened in Hm_Output_folder_list_start
     */
    protected function output() {
        return '</div></nav>';
    }
}

/**
 * Starts the main content section
 * @subpackage core/output
 */
class Hm_Output_content_section_start extends Hm_Output_Module {
    /**
     * Opens a main tag for the primary content section
     */
    protected function output() {
        return '<main class="container-fluid content_cell"><div class="offline">'.$this->trans('Offline').'</div><div class="row m-0 position-relative">';
    }
}

/**
 * Closes the main content section
 * @subpackage core/output
 */
class Hm_Output_content_section_end extends Hm_Output_Module {
    /**
     * Closes the main tag opened in Hm_Output_content_section_start
     */
    protected function output() {
        return '</div></main>';
    }
}

/**
 * Starts the message view page
 * @subpackage core/output
 */
class Hm_Output_message_start extends Hm_Output_Module {
    /**
     * @todo this is pretty ugly, clean it up and remove module specific stuff
     */
    protected function output() {
        if ($this->in('list_parent', array('advanced_search', 'github_all', 'sent', 'search', 'flagged', 'combined_inbox', 'unread', 'feeds', 'email'))) {
            if ($this->get('list_parent') == 'combined_inbox') {
                $list_name = $this->trans('Everything');
            }
            elseif ($this->get('list_parent') == 'email') {
                $list_name = $this->trans('All Email');
            }
            else {
                $list_name = $this->trans(ucwords(str_replace('_', ' ', $this->get('list_parent', ''))));
            }
            if ($this->get('list_parent') == 'advanced_search') {
                $page = 'advanced_search';
            }
            elseif ($this->get('list_parent') == 'search') {
                $page = 'search';
            }
            else {
                $page = 'message_list';
            }
            $title = '<a href="?page='.$page.'&amp;list_path='.$this->html_safe($this->get('list_parent')).'">'.$list_name.'</a>';
            if (count($this->get('mailbox_list_title', array())) > 0) {
                $mb_title = array_map( function($v) { return $this->trans($v); }, $this->get('mailbox_list_title', array()));
                if (($key = array_search($list_name, $mb_title)) !== false) {
                    unset($mb_title[$key]);
                }
                $title .= '<i class="bi bi-caret-right-fill path_delim"></i>'.
                    '<a href="?page=message_list&amp;list_path='.$this->html_safe($this->get('list_path')).'">'.
                    implode('<i class="bi bi-caret-right-fill path_delim"></i>',
                    array_map( function($v) { return $this->trans($v); }, $mb_title)).'</a>';
            }
        }
        elseif ($this->get('mailbox_list_title')) {
            $url = '?page=message_list&amp;list_path='.$this->html_safe($this->get('list_path'));
            if ($this->get('list_page', 0)) {
                $url .= '&list_page='.$this->html_safe($this->get('list_page'));
            }
            if ($this->get('list_filter', '')) {
                $url .= '&filter='.$this->html_safe($this->get('list_filter'));
            }
            if ($this->get('list_sort', '')) {
                $url .= '&sort='.$this->html_safe($this->get('list_sort'));
            }
            $title = '<a href="'.$url.'">'.
                implode('<i class="bi bi-caret-right-fill path_delim"></i>',
                array_map( function($v) { return $this->trans($v); }, $this->get('mailbox_list_title', array()))).'</a>';
        }
        else {
            $title = '';
        }
        $res = '';
        if ($this->get('uid')) {
            $res .= '<input type="hidden" class="msg_uid" value="'.$this->html_safe($this->get('uid')).'" />';
        }
        $res .= '<div class="content_title">'.$title.'</div>';
        $res .= '<div class="msg_text">';
        return $res;
    }
}

/**
 * Close the message view content section
 * @subpackage core/output
 */
class Hm_Output_message_end extends Hm_Output_Module {
    /**
     * Closes the div opened in Hm_Output_message_start
     */
    protected function output() {
        return '</div>';
    }
}

/**
 * Not found content for a bad page request
 * @subpackage core/output
 */
class Hm_Output_notfound_content extends Hm_Output_Module {
    /**
     * Simple "page not found" page content
     */
    protected function output() {
        $res = '<div class="content_title">'.$this->trans('Page Not Found!').'</div>';
        $res .= '<div class="empty_list"><br />'.$this->trans('Nothingness').'</div>';
        return $res;
    }
}

/**
 * Start the table for a message list
 * @subpackage core/output
 */
class Hm_Output_message_list_start extends Hm_Output_Module {
    /**
     * Uses the message_list_fields input to determine the format.
     */
    protected function output() {

        $col_flds = array();
        $header_flds = array();
        foreach ($this->get('message_list_fields', array()) as $vals) {
            if ($vals[0]) {
                $col_flds[] = sprintf('<col class="%s">', $vals[0]);
            }
            if ($vals[1] && $vals[2]) {
                $header_flds[] = sprintf('<th class="%s">%s</th>', $vals[1], $this->trans($vals[2]));
            }
            else {
                $header_flds[] = '<th></th>';
            }
        }
        $res = '<div class="p-3"><table class="message_table table pt-5">';
        if (!$this->get('no_message_list_headers')) {
            if (!empty($col_flds)) {
                $res .= '<colgroup>'.implode('', $col_flds).'</colgroup>';
            }
            if (!empty($header_flds)) {
                $res .= '<thead><tr>'.implode('', $header_flds).'</tr></thead>';
            }
        }
        $res .= '<tbody class="message_table_body">';
        return $res;
    }
}

/**
 * Output the heading for the home page
 * @subpackage core/output
 */
class Hm_Output_home_heading extends Hm_Output_Module {
    /**
     */
    protected function output() {
        return '<div class="content_title">'.$this->trans('Home').'</div>';
    }
}

/**
 * Output password dialogs if no_password_save is active
 * @subpackage core/output
 */
class Hm_Output_home_password_dialogs extends Hm_Output_Module {
    /**
     * Allow the user to input passwords for this session
     */
    protected function output() {
        $missing = $this->get('missing_pw_servers', array());
        if (count($missing) > 0) {
            $res = '<div class="home_password_dialogs">';
            $res .= '<div class="nux_title">Passwords</div>'.$this->trans('You have elected to not store passwords between logins.').
                ' '.$this->trans('Enter your passwords below to gain access to these services during this session.').'<br /><br />';
                
            foreach ($missing as $vals) {
                $id = $this->html_safe(sprintf('%s_%s', strtolower($vals['type']), $vals['id']));
                $res .= '<div class="div_'.$id.'" >'.$this->html_safe($vals['type']).' '.$this->html_safe($vals['name']).
                    ' '.$this->html_safe($vals['user']).' '.$this->html_safe($vals['server']).' <input placeholder="'.$this->trans('Password').
                    '" type="password" class="pw_input" id="update_pw_'.$id.'" /> <input type="button" class="pw_update" data-id="'.$id.
                    '" value="'.$this->trans('Update').'" /></div>';
            }
            $res .= '</div>';
            return $res;
        }
    }
}

/**
 * Output the heading for a message list
 * @subpackage core/output
 */
class Hm_Output_message_list_heading extends Hm_Output_Module {
    /**
     * Title, list controls, and message controls
     */
    protected function output() {
        $search_field = '';
        $terms = $this->get('search_terms', '');
        if ($this->get('custom_list_controls', '')) {
            $config_link = $this->get('custom_list_controls');
            $source_link = '';
            $refresh_link = '<a class="refresh_link" title="'.$this->trans('Refresh').'" href="#"><i class="bi bi-arrow-clockwise refresh_list"></i></a>';
        }
        elseif (!$this->get('no_list_controls', false)) {
            $source_link = '<a href="#" title="'.$this->trans('Sources').'" class="source_link"><i class="bi bi-folder-fill refresh_list"></i></a>';
            if ($this->get('list_path') == 'combined_inbox') {
                $path = 'all';
            }
            else {
                $path = $this->get('list_path');
            }
            $config_link = '<a title="'.$this->trans('Configure').'" href="?page=settings#'.$path.'_setting"><i class="bi bi-gear-wide refresh_list"></i></a>';
            $refresh_link = '<a class="refresh_link" title="'.$this->trans('Refresh').'" href="#"><i class="bi bi-arrow-clockwise refresh_list"></i></a>';
            //$search_field = '<form method="GET">
            //<input type="hidden" name="page" value="message_list" />
            //<input type="hidden" name="list_path" value="'.$this->html_safe($this->get('list_path')).'"/>
            //<input required type="search" placeholder="'.$this->trans('Search').'" id="search_terms" class="imap_keyword" name="search_terms" value="'.$this->html_safe($terms).'"/></form>';

        }
        else {
            $config_link = '';
            $source_link = '';
            $refresh_link = '';
            $search_field = '';
        }
        $res = '';
        $res .= '<div class="message_list p-0 '.$this->html_safe($this->get('list_path')).'_list"><div class="content_title d-flex gap-3 justify-content-between px-3 align-items-center">';
        $res .= '<div class="d-flex align-items-center gap-1">' . message_controls($this).'<div class="mailbox_list_title">'.
            implode('<i class="bi bi-caret-right-fill path_delim"></i>', array_map( function($v) { return $this->trans($v); },
                $this->get('mailbox_list_title', array()))).'</div>';
        if (!$this->get('is_mobile') && substr((string) $this->get('list_path'), 0, 5) != 'imap_') {
            $res .= combined_sort_dialog($this);
        }
        $res .= '</div>';
        $res .= message_list_meta($this->module_output(), $this);
        $res .= list_controls($refresh_link, $config_link, $source_link, $search_field);
        $res .= list_sources($this->get('data_sources', array()), $this);
        $res .= '</div>';
        return $res;
    }
}

/**
 * End a message list table
 * @subpackage core/output
 */
class Hm_Output_message_list_end extends Hm_Output_Module {
    /**
     * Close the table opened in Hm_Output_message_list_start
     */
    protected function output() {
        $res = '</tbody></table></div><div class="mb-5 page_links d-flex justify-content-center gap-3 align-content-center"></div></div>';
        return $res;
    }
}

/**
 * Add move/copy dialog to the search page
 * @subpackage imap/output
 */
class Hm_Output_search_move_copy_controls extends Hm_Output_Module {
    protected function output() {
        $res = '<span class="ctr_divider"></span> <a class="imap_move disabled_input btn btn-sm btn-secondary" href="#" data-action="copy">'.$this->trans('Copy').'</a>';
        $res .= '<a class="imap_move disabled_input btn btn-sm btn-secondary" href="#" data-action="move">'.$this->trans('Move').'</a>';
        $res .= '<div class="move_to_location"></div>';
        $res .= '<input type="hidden" class="move_to_type" value="" />';
        $res .= '<input type="hidden" class="move_to_string1" value="'.$this->trans('Move to ...').'" />';
        $res .= '<input type="hidden" class="move_to_string2" value="'.$this->trans('Copy to ...').'" />';
        $res .= '<input type="hidden" class="move_to_string3" value="'.$this->trans('Removed non-IMAP messages from selection. They cannot be moved or copied').'" />';
        // $res = "<strong>COPY/MOVE</strong>";
        $this->concat('msg_controls_extra', $res);
    }
}

/**
 * Starts the Junk section on the settings page
 * @subpackage core/output
 */
class Hm_Output_start_junk_settings extends Hm_Output_Module {
    /**
     * Settings in this section control the flagged messages view
     */
    protected function output() {
        return '<tr><td data-target=".junk_setting" colspan="2" class="settings_subtitle cursor-pointer border-bottom p-2 text-secondary">'.
            '<i class="bi bi-envelope-x-fill fs-5 me-2"></i>'.
            $this->trans('Junk').'</td></tr>';
    }
}

/**
 * Option for the maximum number of messages per source for the Junk page
 * @subpackage core/output
 */
class Hm_Output_junk_source_max_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_all_source_max_setting
     */
    protected function output() {
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('junk_per_source', $settings)) {
            $sources = $settings['junk_per_source'];
        }
        if ($sources != 20) {
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_input"></i></span>';
        }
        return '<tr class="junk_setting"><td><label for="junk_per_source">'.
            $this->trans('Max messages per source').'</label></td>'.
            '<td><input class="form-control form-control-sm w-auto" type="text" size="2" id="junk_per_source" name="junk_per_source" value="'.$this->html_safe($sources).'" />'.$reset.'</td></tr>';
    }
}

/**
 * Option for the "junk since" date range for the Junk page
 * @subpackage core/output
 */
class Hm_Output_junk_since_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_junk_since_setting
     */
    protected function output() {
        $since = DEFAULT_SINCE;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('junk_since', $settings) && $settings['junk_since']) {
            $since = $settings['junk_since'];
        }
        return '<tr class="junk_setting"><td><label for="junk_since">'.
            $this->trans('Show junk messages since').'</label></td>'.
            '<td>'.message_since_dropdown($since, 'junk_since', $this).'</td></tr>';
    }
}

/**
 * Starts the Trash section on the settings page
 * @subpackage core/output
 */
class Hm_Output_start_trash_settings extends Hm_Output_Module {
    /**
     * Settings in this section control the flagged messages view
     */
    protected function output() {
        return '<tr><td data-target=".trash_setting" colspan="2" class="settings_subtitle cursor-pointer border-bottom p-2 text-secondary">'.
            '<i class="bi bi-trash3-fill fs-5 me-2"></i>'.
            $this->trans('Trash').'</td></tr>';
    }
}

/**
 * Option for the maximum number of messages per source for the Trash page
 * @subpackage core/output
 */
class Hm_Output_trash_source_max_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_all_source_max_setting
     */
    protected function output() {
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('trash_per_source', $settings)) {
            $sources = $settings['trash_per_source'];
        }
        if ($sources != 20) {
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_input"></i></span>';
        }
        return '<tr class="trash_setting"><td><label for="trash_per_source">'.
            $this->trans('Max messages per source').'</label></td>'.
            '<td><input class="form-control form-control-sm w-auto" type="text" size="2" id="trash_per_source" name="trash_per_source" value="'.$this->html_safe($sources).'" />'.$reset.'</td></tr>';
    }
}

/**
 * Option for the "trash since" date range for the Trash page
 * @subpackage core/output
 */
class Hm_Output_trash_since_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_trash_since_setting
     */
    protected function output() {
        $since = DEFAULT_SINCE;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('trash_since', $settings) && $settings['trash_since']) {
            $since = $settings['trash_since'];
        }
        return '<tr class="trash_setting"><td><label for="trash_since">'.
            $this->trans('Show trash messages since').'</label></td>'.
            '<td>'.message_since_dropdown($since, 'trash_since', $this).'</td></tr>';
    }
}

/**
 * Starts the Draft section on the settings page
 * @subpackage core/output
 */
class Hm_Output_start_drafts_settings extends Hm_Output_Module {
    /**
     * Settings in this section control the flagged messages view
     */
    protected function output() {
        return '<tr><td data-target=".drafts_setting" colspan="2" class="settings_subtitle cursor-pointer border-bottom p-2 text-secondary">'.
            '<i class="bi bi-pencil-square fs-5 me-2"></i>'.
            $this->trans('Drafts').'</td></tr>';
    }
}

/**
 * Option for the maximum number of messages per source for the Draft page
 * @subpackage core/output
 */
class Hm_Output_drafts_source_max_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_all_source_max_setting
     */
    protected function output() {
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('drafts_per_source', $settings)) {
            $sources = $settings['drafts_per_source'];
        }
        if ($sources != 20) {
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_input"></i></span>';
        }
        return '<tr class="drafts_setting"><td><label for="drafts_per_source">'.
            $this->trans('Max messages per source').'</label></td>'.
            '<td><input class="form-control form-control-sm w-auto" type="text" size="2" id="drafts_per_source" name="drafts_per_source" value="'.$this->html_safe($sources).'" />'.$reset.'</td></tr>';
    }
}

/**
 * Option for the "draft since" date range for the Draft page
 * @subpackage core/output
 */
class Hm_Output_drafts_since_setting extends Hm_Output_Module {
    /**
     * Processed by Hm_Handler_process_draft_since_setting
     */
    protected function output() {
        $since = DEFAULT_SINCE;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('drafts_since', $settings) && $settings['drafts_since']) {
            $since = $settings['drafts_since'];
        }
        return '<tr class="drafts_setting"><td><label for="drafts_since">'.
            $this->trans('Show draft messages since').'</label></td>'.
            '<td>'.message_since_dropdown($since, 'drafts_since', $this).'</td></tr>';
    }
}

/**
 * Option to warn user when he has unsaved changes.
 * @subpackage imap/output
 */
class Hm_Output_warn_for_unsaved_changes_setting extends Hm_Output_Module {
    protected function output() {
        $checked = '';
        $settings = $this->get('user_settings', array());
        $reset = '';
        if (array_key_exists('warn_for_unsaved_changes', $settings) && $settings['warn_for_unsaved_changes']) {
            $checked = ' checked="checked"';
            $reset = '<span class="tooltip_restore" restore_aria_label="Restore default value"><i class="bi bi-arrow-repeat refresh_list reset_default_value_checkbox"></i></span>';
        }
        return '<tr class="general_setting"><td><label class="form-check-label" for="warn_for_unsaved_changes">'.
            $this->trans('Warn for unsaved changes').'</label></td>'.
            '<td><input type="checkbox" '.$checked.' id="warn_for_unsaved_changes" name="warn_for_unsaved_changes" class="form-check-input" value="1" />'.$reset.'</td></tr>';
    }
}
