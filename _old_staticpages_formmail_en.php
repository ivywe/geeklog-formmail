// +---------------------------------------------------------------------------+
// | Geeklog FormMail Static Page                                              |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2008-2010 by the following authors:                         |
// |                                                                           |
// | Authors    : Hiroshi Sakuramoto - hiro AT winkey DOT jp                   |
// | Sponser    : White Bear Family CO., LTD                                   |
// | Coordinate : Adeliae Planning CO., LTD - www.e-adeliae.com                |
// | Coordinate : IVY WE CO., LTD - www.ivywe.co.jp                            |
// | Version: 2.1.4                                                            |
// | EnglishVersion: 1.0                                                       |
// | Translated: kobab - geeklog.crimsonj.net                                  |
// | Translated: Alleman Chie and Geeklog Japanese                             |
// +---------------------------------------------------------------------------+
// | This program was originally made for Japanese users. Several              |
// | parts are therefore for Japanese users only and commented-out for         |
// | this English version.                                                     |
// | For the purpose of future upgrade for both of Japanese & English          |
// | verions, those commented-out parts are remained. Wish English             |
// | users understand this and ignore those parts when you use.                |
// +---------------------------------------------------------------------------+

global $_CONF, $_USER, $_PLUGINS;
if (!defined('XHTML')) define('XHTML', ' /');

// +---------------------------------------------------------------------------+
// | default setting                                                           |
// +---------------------------------------------------------------------------+
// fill-in statipage ID below.
// Default is 'formmail'
$pageid = 'formmail';

# receipient set
#    If 2 or more receipient, each email should be commmaed. no space allowed.
#      ex) 'info@abcd.com,admin@wxyz.com'
#    Set email for a certain colum data. If colum 1 is AAA, receipient is info@geeksite. If colum 1 is BBB, receipient becomes to admin@geeksite
#    You MUST set $owner_email_item_name if you use this feature.
#      ex) 'AAA=info@abcd.com,BBB=admin@wxyz.com'

$owner_email=$_CONF['site_mail'];


# Set colum name of receipient
//  *Remember to remove * to uncomment after setting colum name.
#$owner_email_item_name = 'q_mail_to';

# email of auto-reply message sender
$email_from = $_CONF['site_mail'];
#'noreply_mail' can be used if your Geeklog version is 1.5 or later.
#$email_from = $_CONF['noreply_mail'];

# inquirer email's item name
$email_input_name = 'q_mail';

# email double check
#   no space allowed.
#     ex) 'email=reemail'
$essential_email = 'q_mail=q_mail_re';


# email check
#   check if input string is proper email address
#   value of name attrubute with commas. no space is allowed.
#     ex) 'email,reemail'

$propriety_email = 'q_mail,q_mail_re';

# use Geeklog's userset if logged-in
#   use username, full name & email of login users
#
$username = ''; $user_email = '';
if (isset ($_USER['uid']) && ($_USER['uid'] > 1)) {
    $username = isset($_USER['fullname']) ? $_USER['fullname'] : $_USER['username'];
    $user_email = $_USER['email'];
}

# save as CSV file
#   not save: 0, save with commas: 1, save with tabs: 2
$save_csv = 1;

# path for CSV file to be saved. / is mandatory at the end of URL If you specify# bare # path.
$save_csv_path = $_CONF['path_data'];

# CSV file name
$save_csv_name = 'formmail.csv';

# character code at saving CSV file
#   If no code conversion is necesasry, make it blank, ie '' to disable this
#   feature. If garbled, make this disabled and use another tool.
#   Remember to use a code which mb_convert_encoding is covering
#   ex) UTF-8, SJIS, EUC-JP, JIS, ASCII
$save_csv_lang = 'UTF-8';

//# For Japanese language only.
//#   Set colums that auto-convert from zenkaku to hankaku neccessary.
//#   Specify name attribute's values one by one with commas. no space allowed.
// $zentohan_itemname = 'q_phone,q_code1_1,q_code2_1,q_code3_1,q_code1_2,q_code2_2,q_code3_2,q_code1_3,q_code2_3,q_code3_3';

//# For Japanese language only.
//#   Set colums that auto-convert from hankaku to zenkaku neccessary.
//#   Specify name attribute's values one by one with commas. no space allowed.
// $kana_hantozen_itemname = 'q_kana_1,q_kana_2';

//# For Japanese language only.
//#   Set colums that auto-convert from hiragana to katakana necessary.
//#   Specify name attribute's values one by one with commas. no space allowed.
// $kana_hiratokana_itemname = 'q_kana_1,q_kana_2';

# ==CAPTCHA==
#   error message if no CAPTHCA is istalled
$msg_spformmail_notinstall_captcha = 'CAPTCHA plugin is not installed';
#   error message after formmail used with CAPTCHA.
#     *if blaked, error message of CAPTCHA plugin is used
#     *if you speciy message here, it will be used
$msg_spformmail_valid_captcha = '';
#
#   *You can make your own CAPTCHA template
#   if public_html/layout/<themename>/sp_formmail/captcha.thmtl found, this will be adopted
#   private/plugins/captcha/templates/captcha_others.thtml is used by default
#

# item names at screen transition
$seni_items = array('input' => 'Input', 'confirm' => 'Confirm', 'finish' => 'Complete');

# string for mondatory item
$required_string = '<span class="spf_required">*</span>';

# ==About date and time==
#   The date on the following JavaScript calendar.
#     php date format. ref: http://www.php.net/manual/en/function.date.php
#     "d, D, j, l, N, w, S, F, m, M, n, Y, y" can be assigned.
$date_js_cal = 'd/m/Y';
#   The receipt date and time indicated in an email.
#     Any php date format can be used here. (ref: http://www.php.net/manual/en/function.date.php)
$date_mail = 'M j Y H:i';
#   When the csv is outputted, the date and time are written to the first row of the csv.
#     Any php date format can be used here. (ref: http://www.php.net/manual/en/function.date.php)
$date_csv = 'M j Y H:i';

# use JavaScript library for calendar
$use_jslib = TRUE;

# declaration of JavaScript library etc
#   JavaScript+CSS for calendar
#     Calendar RC4, (c) 2007 Aeron Glemann <http://electricprism.com/aeron>, MIT Style License
#     (http://www.electricprism.com/aeron/calendar/)
$def_jslib = <<<JSLIB

<script type="text/javascript" src="{$_CONF['site_url']}/javascript/calendar/mootools.js"></script>
<script type="text/javascript" src="{$_CONF['site_url']}/javascript/calendar/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="{$_CONF['site_url']}/javascript/calendar/calendar.css" media="screen"{XHTML}>
<script type="text/javascript">
 window.addEvent('domready', function() { myCal = new Calendar({ q_date1: '$date_js_cal' }, { days: ['SUN','MON','TUE','WED','THU','FRI','SAT'], months: ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'] }); });
</script>
JSLIB;

#####
# display message
#####
$lang = array(
// { complete & email message
  'receipt_admin' =>'Dear admins,'.LB.LB.$_CONF['site_name'].' site received a inquiry.'.LB.LB.'========== Inquiry =========='.LB.'receipt at:'.date($date_mail),
  'receipt_user' =>'*This email is sent from '.$_CONF['site_name'].' site automatically.'.LB.'This email address is used for transmission only and not repliable.'.LB.'============================='.LB.'Thank you very much for your inquiry.'.LB.LB.'========== Inquiry =========='.LB.'Receipt at:'.date($date_mail),
  'subject_admin'=> '['.$_CONF['site_name'].']Inquiry',
  'subject_user'=> '['.$_CONF['site_name'].']Thanks for your inquiry',
  'sign_admin'    => '-----------------------------------------'.LB.$_CONF['site_name'].LB.$_CONF['site_url'].LB.'-----------------------------------------',
  'sign_user'    => '-----------------------------------------'.LB.$_CONF['site_name'].LB.'URL:' . $_CONF['site_url'].LB.'-----------------------------------------',
// } complete & email message
// { system error message
  'ownertransmiterror'=>'An error has occurred at several email addresses during processing owner email, but process continued and completed.',
  'transmiterror'=>'An error has occurred during processing.',
// } system error message
);



#####
# table's item names
#####
$form_items = array(
  // < table
  array('title' => 'Personal Information', 'table' => array(
    // < table 1 row
    array('header' => 'Organization', 'q_organization', 'data' =>
        array(
            array('type' => 'text',
                  'name' => 'q_organization',
                  'size' => '40',
                  'maxlength' => '60',
                  'class' => 'bginput'
            ),
            array('input' => '<br'.XHTML.'>'),
        ),
    ),
    // > table 1 row
    // < table 1 row
    array('header' => 'Name', 'valid_require' => $required_string, 'error_require' => 'Please type your name.', 'data' =>
        array(
            array('input' => 'First Name: '),
            array('type' => 'text',
                  'name' => 'q_first_name',
                  'size' => '16',
                  'maxlength' => '40',
                  'class' => 'bginput',
                  'value' => $username
            ),
            array('input' => ' Last Name: '),
            array('type' => 'text',
                  'name' => 'q_last_name',
                  'size' => '16',
                  'maxlength' => '40',
                  'class' => 'bginput',
            ),
        ),
    ),
    // > table 1 row
    // < table 1 row
    array('header' => 'Email',
          'valid_require' => $required_string, 'error_require' => 'Please type your email address.',
          'valid_equal' => $essential_email, 'error_equal' => 'Email address not match.',
          'valid_email' => $propriety_email, 'error_email' => 'Please type out proper email address.',
          'data' =>
        array(
            array('type' => 'text',
                  'name' => 'q_mail',
                  'size' => '40',
                  'maxlength' => '240',
                  'class' => 'bginput',
                  'value' => $user_email
            ),
            array('input' => '<br'.XHTML.'>'),
            array('type' => 'text',
                  'name' => 'q_mail_re',
                  'size' => '40',
                  'maxlength' => '240',
                  'class' => 'bginput',
                  'not_confirm' => 'true',
                  'not_csv' => 'true',
                  'value' => $user_email
            ),
            array('string' => '<br'.XHTML.'>'),
            array('input' => 'Please enter your e-mail address again to confirm.'),
        ),
    ),
    // > table 1 row
    // < table 1 row
    array('header' => 'Inquiry Method', 'data' =>
        array(
            array('type' => 'radio',
                  'name' => 'q_answer_means',
                  'value' => 'Email',
                  'checked' => 'checked'
            ),
            array('input' => 'Email '),

            array('type' => 'radio',
                  'name' => 'q_answer_means',
                  'value' => 'Phone'
            ),
            array('input' => 'Phone'),
            array('string' => '<br'.XHTML.'>'),
            array('input' => '*We may call you in the event e-mail inquiry fails.'),
        ),
    ),
    // > table 1 row
    // < table 1 row
    array('header' => 'Phone',
          'valid_require' => $required_string, 'error_require' => 'Please type your phone number.',
          'valid_phone' => 'q_phone', 'error_phone' => 'Please input your phone number correctly. Only numbers, plus, minus, and space characters are allowed as input.',
          'valid_minlen' => 'q_phone=6', 'error_minlen' => 'Phone number shouold be longer than 6 digit.',
          'valid_maxlen' => 'q_phone=17', 'error_maxlen' => 'Phone number should not exceed 17 digit.',
          'data' =>
       array(
            array('type' => 'text',
                  'name' => 'q_phone',
                  'size' => '20',
                  'maxlength' => '17',
                  'class' => 'bginput'
            ),
            array('string' => '<br'.XHTML.'>'),
            array('input' => '(ex +00 0 0000 0000)<br'.XHTML.'>'),

            array('type' => 'radio',
                  'name' => 'q_phone_kind',
                  'value' => 'Home',
                  'checked' => 'checked'
            ),
            array('input' => 'Home &nbsp; '),

            array('type' => 'radio',
                  'name' => 'q_phone_kind',
                  'value' => 'Office'
            ),
            array('input' => 'Office &nbsp; '),

            array('type' => 'radio',
                  'name' => 'q_phone_kind',
                  'value' => 'Mobile'
            ),
            array('input' => 'Mobile '),
      ),
    ),
    // > table 1 row
    // < table 1 row
    array('header' => 'Requested Inquiry Date', 'data' =>
        array(
            array('type' => 'text',
                  'name' => 'q_date1',
                  'id' => 'q_date1',
                  'size' => '20',
                  'maxlength' => '10',
                  'class' => 'bginput calendar',
                  'style' => 'float:left;'
            ),
        ),
    ),
    // > table 1 row
    // < table 1 row
    array('header' => 'Requested Inquiry Time', 'data' =>
        array(
            array('type' => 'select',
                  'name' => 'q_access_time',
                  'style' => 'width: 15em;',
                  'class' => 'bginput',
                  'options' => array('selected' => 'Not Specified', 'values' => 'Not Specified,AM,Early PM, Late PM'),
            ),
            array('input' => '<br'.XHTML.'>*Please indicate a time we may call you if telephone inquiry is preferred.'),
        ),
    ),
    // > table 1 row
   ),
  ),
  // > table
  // < table
  array('title' => 'Preferences', 'table' => array(
    // < table 1row
    array('header' => 'Apply', 'data' =>
      array(array('type' => 'checkbox',
                  'name' => 'q_order_1',
                  'value' => 'Seminar1',
            ),
            array('input' => ' '),
            array('type' => 'checkbox',
                  'name' => 'q_order_2',
                  'value' => 'Seminar2',
            ),
            array('input' => ' '),
            array('type' => 'checkbox',
                  'name' => 'q_order_3',
                  'value' => 'Seminar3',
            ),
      ),
    ),
    // > table 1 row
    // < rable 1 row
    array('header' => 'Comments', 'valid_maxlen' => 'q_other=200', 'error_maxlen' => 'Please complete within 200 characters.', 'data' =>
        array(
            array('type' => 'textarea',
                  'name' => 'q_other',
                  'class' => 'bginput',
                  'style' => 'width: 95%; height: 100px;',
                  'onKeyup' => "var n=200-this.value.length;var s=document.getElementById('tasp1');s.innerHTML='('+n+')';"
            ),
            array('input' => '<br'.XHTML.">*Please enter your comments up to 200 characters. <strong><span id='tasp1'></span><strong><br".XHTML.'>'),
        ),
    ),
    // > table 1 row
   ),
  ),
  // > table
  // < table (CAPTCHA)
  array('title_captcha' => 'CAPTCHA', 'table_captcha' => array(
    // < table 1 row (CAPTCHA)
    array('header_captcha' => 'CAPTCHA',
          'valid_captcha' => $required_string,
          'error_captcha' => $msg_spformmail_valid_captcha,
          'error_notcaptcha' => $msg_spformmail_notinstall_captcha,
          'data' => array()
    ),
    // > table 1 row (CAPTCHA)
   ),
  ),
  // > table (CAPTCHA)
  // < submit button - input
  array('action' => 'input', 'data' =>
      array(
          array('string' => '    <div style="text-align: center;" class="text_center mt20 mb20">'),
          array('type' => 'submit',
                'name' => 'submit',
                'class' => 'button_form_next',
                'value' => 'Confirm'
          ),
          array('string' => '</div>'),
      ),
  ),
  // > submit button - input
  // < submit button - confirm
  array('action' => 'confirm', 'data' =>
      array(array('string' => '    <div style="text-align: center;" class="text_center mt20 mb20">'),
          array('type' => 'submit',
                'name' => 'goback',
                'class' => 'button_form_prev',
                'value' => 'Return to the previous page.'
          ),
          array('string' => '&nbsp;'),
          array('type' => 'submit',
                'name' => 'submit',
                'class' => 'button_form_next',
                'value' => 'Submit'
          ),
          array('string' => '</div>'),
      ),
  ),
  // > submit button - confirm
);



// +---------------------------------------------------------------------------+
// | initial process                                                           |
// +---------------------------------------------------------------------------+
//# For Japanese language only.
//# Direct convert of POST data, ie. from em-size to normal-width and from 
//# the katakana normal-width to the katakana. This is for Japanese user only.
//# This program was originally made for Japanese users. Several parts are 
//# therefore for Japanese users only. We commented those parts 
//# out for this English version. 
//# For the convenience of authors of future upgrade for both of Japanese 
//# & English versions, those parts for Japanese users are remained in php 
//# files. Wish English users understand this and ignore those parts when 
//# you use this php.

//if (!empty($zentohan_itemname)) { foreach (explode(',',$zentohan_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'askh'); } }
//if (!empty($kana_hantozen_itemname)) { foreach (explode(',',$kana_hantozen_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'K'); } }
//if (!empty($kana_hiratokana_itemname)) { foreach (explode(',',$kana_hiratokana_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'C'); } }
# process data for saving
foreach ($_POST as $k => $v) {
    $fld_list[$k] = preg_replace('/,/', '，', $_POST[$k]);
    $fld_list[$k] = preg_replace('/"/', '”', $fld_list[$k]);
    $fld_list[$k] = preg_replace("/'/", "’", $fld_list[$k]);
    $fld_list[$k] = preg_replace('/`/', '‘', $fld_list[$k]);
    $fld_list[$k] = preg_replace('/;/', '；', $fld_list[$k]);
    $fld_list[$k] = preg_replace(preg_quote('#'.chr(92).'#'), '￥', $fld_list[$k]);
    $fld_list[$k] = COM_applyFilter($fld_list[$k]);
}
# full path of CSV file
$save_csv_file = $save_csv_path . $save_csv_name;
# create pageurl from pageid
if (!empty($pageid)) { $pageurl = COM_buildUrl($_CONF['site_url'].'/staticpages/index.php?page='.$pageid); }

// +---------------------------------------------------------------------------+
// | functions:                                                                |
// +---------------------------------------------------------------------------+
if(!function_exists('getAction')){
function getAction ($err) {
    $buf = '';
    $action = COM_applyFilter($_POST['action']);
    if (!empty($action) && empty($err) && $action == 'input') { $buf = 'confirm'; }
    elseif (!empty($action) && empty($err) && $action == 'confirm') { $buf = empty($_POST['goback']) ? 'finish' : 'input'; }
    else { $buf = 'input'; }
    return $buf;
}

function mkJS4MG ($lib) {
    $buf = preg_replace("@<script[^>]+mootools.js[^>]+>[^<]*</script>@", "", $lib);
    return str_replace('calendar.js', 'calendar_mg.js', $buf);
}

function mkJS ($use,$lib,$action,$plg) {
    $buf='';
    if (!empty($action) && $action == 'input' && isset($use) && $use == TRUE) {
        if (in_array('mediagallery', $plg)) {
            global $_MG_CONF;
            $mgv = $_MG_CONF['pi_version'];
            $mglib = $_MG_CONF['js_lib'];
            if (!empty($mgv) && !empty($mglib) && (version_compare($mgv, '1.6.10', '<') || strtolower($mglib) == 'mootools')) {
                $buf = mkJS4MG($lib);
            } elseif(!empty($mglib) && strtolower($mglib) == 'jquery') {
                $buf = <<<JQNC
<script>
<!--
jQuery.noConflict();
-->
</script>{$lib}
JQNC;
            } else {
                $buf = $lib;
            }
        } else {
            $buf = $lib;
        }
    }
    return $buf;
}

function mkSeni ($items, $action) {
    $buf = '<ul>'.LB;
    foreach ($items as $key => $value) {
        if ($action == $key) {
            $buf .= '    <li class="on">'.$value.'</li>'.LB;
        } else {
            $buf .= '    <li>'.$value.'</li>'.LB;
        }
    }
    $buf .= '</ul>'.LB;
    return $buf;
}

function putiFilter($s) {
    $se = array('%','(',')',chr(92),chr(13).chr(10),chr(13),chr(10));
    $re = array('&#37;','&#40;','&#41;','&#92;','','','');
    return str_replace($se, $re, htmlspecialchars($s,ENT_QUOTES));
}

function vldCAPTCHA ($type, $errmsg) {
    global $_CP_CONF, $_USER;
    $msg = '';
    if (!function_exists('CAPTCHA_sid')) { return $msg; }
    if ( ($_CP_CONF['anonymous_only'] && $_USER['uid'] < 2) || $_CP_CONF['anonymous_only'] == 0 || ($_CP_CONF['remoteusers'] == 1 && SEC_inGroup("Remote Users") ) ) {
        $str = COM_applyFilter($_POST['captcha']);
        list( $rc, $msg )  = CAPTCHA_checkInput( $type, $str );
    }
    if ( !empty($msg) && !empty($errmsg) ) { $msg = $errmsg; }
    return $msg;

}

function vld_isPhone($s) { return (preg_match('/^(?:[0-9'.chr(92).'+'.chr(92).'-'.chr(92).'s])+$/D',$s)) ? TRUE : FALSE; }
function vld_isHankaku($s) { return (preg_match('/^(?:'.chr(92).'xEF'.chr(92).'xBD['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xEF'.chr(92).'xBE['.chr(92).'x80-'.chr(92).'x9F]|['.chr(92).'x20-'.chr(92).'x7E])+$/D',$s)) ? TRUE : FALSE; }
function vld_isZenkaku($s) { return (preg_match('/(?:'.chr(92).'xEF'.chr(92).'xBD['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xEF'.chr(92).'xBE['.chr(92).'x80-'.chr(92).'x9F]|['.chr(92).'x20-'.chr(92).'x7E])+/D',$s)) ? FALSE : TRUE; }
function vld_isEisuHan($s) { return (preg_match('/^(?:[0-9A-Za-z])+$/D',$s)) ? TRUE : FALSE; }
function vld_isKanaZen($s) { return (preg_match('/^(?:'.chr(92).'xE3'.chr(92).'x82['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xE3'.chr(92).'x83['.chr(92).'x80-'.chr(92).'xB6])+$/D',$s)) ? TRUE : FALSE; }
function vld_isHiraZen($s) { return (preg_match('/^(?:'.chr(92).'xE3'.chr(92).'x81['.chr(92).'x81-'.chr(92).'xBF]|'.chr(92).'xE3'.chr(92).'x82['.chr(92).'x80-'.chr(92).'x93])+$/D',$s)) ? TRUE : FALSE; }
function vld_isNotKanaHan($s) { return (preg_match('/(?:'.chr(92).'xEF'.chr(92).'xBD['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xEF'.chr(92).'xBE['.chr(92).'x80-'.chr(92).'x9F])+/D',$s)) ? TRUE : FALSE; }

function ChkValidate ($mode, $datas, $errmsg, $attributes = '') {
    $msg = '';
    foreach ($datas as $data) {
        if (isset($data['type'])) {
            $name = $data['name'];
            switch ($mode) {
                // check required item
                case 'require':
                    if (empty($data['notrequire']) && empty($_POST[$name]) && $_POST[$name] != "0") { $msg = $errmsg; }
                    break;
                // check matching
                case 'equal':
                    if (!empty($attributes)) {
                        $es_emails = explode(',', $attributes);
                        foreach ($es_emails as $es_email) {
                            list($eq1,$eq2) = explode('=', $es_email);
                            // initial key ANT it exist
                            if ($name == $eq1 && !empty($_POST[$eq2])) {
                                if ($_POST[$eq1] != $_POST[$eq2]) {
                                    $msg = $errmsg;
                                }
                            }
                        }
                    }
                    break;
                // email check
                case 'email':
                    if (!empty($attributes)) {
                        $pr_emails = explode(',', $attributes);
                        foreach ($pr_emails as $pr_email) {
                            if ($name == $pr_email) {
                                if (!COM_isemail($_POST[$name])) {
                                    $msg = $errmsg;
                                }
                            }
                        }
                    }
                    break;
                // num check - sum is more than 0
                case 'notzero':
                    if (!empty($attributes)) {
                        $values_key = explode(',', $attributes);
                        foreach ($values_key as $val_key) {
                            // check at initial key
                            if ($name == $val_key) {
                                $sum_val = 0;
                                foreach ($values_key as $chk_key) {
                                    if (!empty($_POST[$chk_key])) {
                                        $sum_val += $_POST[$chk_key];
                                    }
                                }
                                if ($sum_val <= 0) {
                                    $msg = $errmsg;
                                    break;
                                }
                            }
                        }
                    }
                    break;
                // check if numeric
                case 'numeric':
                    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !ctype_digit($_POST[$name])) { $msg = $errmsg; }
                    break;
                // check if phone
                case 'phone':
                    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !vld_isPhone($_POST[$name])) { $msg = $errmsg; }
                    break;
                // (For Japanese language only) Check byte
//                case 'hankaku':
//                    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !vld_isHankaku($_POST[$name])) { $msg = $errmsg; }
//                    break;
                // (For Japanese language only) Check em
//                case 'zenkaku':
//                    if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !vld_isZenkaku($_POST[$name])) { $msg = $errmsg; }
//                    break;
                // (For Japanese language only) Alphanumeric check
//                case 'eisuhan':
//                    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !vld_isEisuHan($_POST[$name])) { $msg = $errmsg; }
//                    break;
                // (For Japanese language only) Check em katakana
//                case 'kanazen':
//                    if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !vld_isKanaZen($_POST[$name])) { $msg = $errmsg; }
//                    break;
                // (For Japanese language only) Check em hiragana
//                case 'kanazen':
//                    if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !vld_isHiraZen($_POST[$name])) { $msg = $errmsg; }
//                    break;
                // (For Japanese language only) Check for non-katakana
//                case 'notkanahan':
//                    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && vld_isNotKanaHan($_POST[$name])) { $msg = $errmsg; }
//                    break;
                // check max length
                case 'maxlen':
                    if ((!empty($_POST[$name]) || $_POST[$name] == "0")) {
                        foreach (explode(',', $attributes) as $attr1) {
                            list($name2,$max2) = explode('=',$attr1);
                            if ($name === $name2) {
                                if ($max2 < mb_strlen($_POST[$name], 'UTF-8')) { $msg = $errmsg; }
                            }
                        }
                    }
                    break;
                // check min length
                case 'minlen':
                    if ((!empty($_POST[$name]) || $_POST[$name] == "0")) {
                        foreach (explode(',', $attributes) as $attr1) {
                            list($name2,$min2) = explode('=',$attr1);
                            if ($name === $name2) {
                                if ($min2 > mb_strlen($_POST[$name], 'UTF-8')) { $msg = $errmsg; }
                            }
                        }
                    }
                    break;
            }
        }
    }
    // check CAPTCHA
    if ( $mode == 'captcha' ) { $msg = vldCAPTCHA('sp_formmail', $errmsg); }
    return $msg;
}

function ChkFormValidateLines ($lines) {
    $errmsg;
    foreach (array('require','equal','email','notzero','numeric','phone','hankaku','zenkaku','eisuhan','kanazen','hirazen','notkanahan','captcha','maxlen','minlen') as $chk) {
        // check require,match,email,CAPTCH,error
        if (isset($lines['valid_'.$chk])) {
            $errmsg = ChkValidate($chk, $lines['data'], $lines['error_'.$chk], $lines['valid_'.$chk]);
            // arrays if error found
            if ($errmsg) {
                break;
            }
        }
    }
    return $errmsg;
}

function ChkFormValidate ($items) {
    $errs;
    foreach ($items as $item) {
        // eah table
        foreach ($item as $key => $value) {
            // 1 table
            if ($key == 'table' || $key == 'table_captcha') {
                $action = getAction('');
                if ($key == 'table_captcha' && $action == 'finish') { continue; }
                foreach ($value as $key2 => $value2) {
                    // table 1 row
                    $errmsg = ChkFormValidateLines($value2);
                    if ($errmsg) { $errs[] = $errmsg; }
                }
            }
        }
    }
    return $errs;
}

function FormValidate ($items) {
    $buf = '';
    $errs = ChkFormValidate($items);
    if (!empty($errs)) {
        $errmsg = '';
        foreach ($errs as $err) {
            $errmsg .= '    <li>'.$err.'</li>'.LB;
        }
        $buf = <<<END

<p class="error">Some error found. Please check below and correct your input.</p>
<ol class="errorList">
$errmsg
</ol>
END;
    }
    return $buf;
}


function mkTitle ($title) {
    return <<<END

    <h4>$title</h4>
    <table class="formmail" cellspacing="0">
        <colgroup><col width="130" /></colgroup>
        <tbody>
END;
}

function mkForm_Value ($name, $value) {
    return (empty($_POST[$name]) && $_POST[$name] != "0") ? $value : putiFilter($_POST[$name]);
}

function mkForm_Radio_Checked (&$attributes) {
    $name = $attributes['name'];
    if ((!empty($_POST[$name]) || $_POST[$name] == "0")) {
        if (isset($attributes['checked'])) unset($attributes['checked']);
        if ($_POST[$name] == $attributes['value']) {
            $attributes['checked'] = 'checked';
        }
    }
}

function mkForm_Input ($attributes, $hidden = false) {
    if ($hidden) {
        if ($attributes['type'] == 'radio' || $attributes['type'] == 'checkbox') {
            if ($attributes['value'] != $_POST[$attributes['name']]) return '';
        }
        $attributes['type'] = 'hidden';
    }
    if ($attributes['type'] == 'radio' || $attributes['type'] == 'checkbox') {
        mkForm_Radio_Checked($attributes);
    } else {
        if ($attributes['type'] != 'submit') $attributes['value'] = mkForm_Value($attributes['name'], $attributes['value']);
    }
    $buf = '<input';
    foreach ($attributes as $key => $value) {
        if ($key != 'not_confirm') { $buf .= ' '.$key.'="'.$value.'"'; }
    }
    $buf .= XHTML.'>';
    if ($hidden || $attributes['type'] == 'checkbox') {
        if ( !isset($attributes['not_confirm']) || ! $attributes['not_confirm'] ) { $buf .= ' ' . $attributes['value']; }
    }
    return $buf;
}

function mkForm_Select_Options ($name, $attributes) {
    $buf = '';
    $selected = mkForm_Value($name, $attributes['selected']);
    $values = explode(',', $attributes['values']);
    foreach ($values as $value) {
        list($k,$v) = explode('=',$value);
        if (empty($v)) $v = $k;
        if ($selected == $k) {
            $buf .= '<option selected="selected" value="'.$v.'">'.$k.'</option>';
        } else {
            $buf .= '<option value="'.$v.'">'.$k.'</option>';
        }
    }
    return $buf;
}

function mkForm_Select ($attributes) {
    $buf = '<select';
    foreach ($attributes as $key => $value) {
        if ($key != 'options') {
            $buf .= ' '.$key.'="'.$value.'"';
        }
    }
    $buf .= '>';
    $buf .= mkForm_Select_Options($attributes['name'], $attributes['options']);
    $buf .= '</select>';
    return $buf;
}

function mkForm_Textarea ($attributes) {
    $attributes['value'] = mkForm_Value($attributes['name'], $attributes['value']);
    $buf = '<textarea';
    foreach ($attributes as $key => $value) {
        if ($key != 'value') $buf .= ' '.$key.'="'.$value.'"';
    }
    $buf .= '>'.$attributes['value'].'</textarea>';
    return $buf;
}

function mkForm_Item ($items, $action) {
    $buf = '';
    if ($action != 'input' && $items['type'] != 'submit' && $items['type'] != 'hidden') {
        $buf .= mkForm_Input($items, true);
    } else {
        switch ($items['type']) {
            case 'text': $buf .= mkForm_Input($items); break;
            case 'password': $buf .= mkForm_Input($items); break;
            case 'hidden': $buf .= mkForm_Input($items); break;
            case 'radio': $buf .= mkForm_Input($items); break;
            case 'checkbox': $buf .= mkForm_Input($items); break;
            case 'select': $buf .= mkForm_Select($items); break;
            case 'textarea': $buf .= mkForm_Textarea($items); break;
            case 'submit': $buf .= mkForm_Input($items); break;
            case 'reset': $buf .= mkForm_Input($items); break;
            case 'button': $buf .= mkForm_Input($items); break;
        }
    }
    return $buf;
}

function mkTable_Data ($datas, $action) {
    $buf = '';
    foreach ($datas as $data) {
        // 1data
        if (!empty($data['type'])) {
            // form
            $buf .= mkForm_Item($data, $action);
        }
        else {
            // strings
            foreach ($data as $key => $value) {
                if ($key == 'string') {
                    $buf .= $value;
                } elseif ($key == $action) {
                    $buf .= $value;
                }
            }
        }
    }
    return $buf;
}

function plugin_templatePath_sp_formmail () {
    global $_CONF;
    $pi_name = 'sp_formmail';
    $layout_path = $_CONF['path_layout'] . $pi_name;
    return $layout_path;
}

function chk_Use_CAPTCHA_HTML () {
    global $_CP_CONF, $_USER;
    if ( ($_CP_CONF['anonymous_only'] && $_USER['uid'] < 2) || $_CP_CONF['anonymous_only'] == 0 || ($_CP_CONF['remoteusers'] == 1 && SEC_inGroup("Remote Users") ) ) {
        return true;
    }
    return false;
}
function mkCAPTCHA_HTML ($name, $msg_notcaptcha) {
    global $_CP_CONF, $_USER, $_TABLES;
    $captcha = '';
    if (!function_exists('CAPTCHA_sid')) { return $msg_notcaptcha; }
    if ( chk_Use_CAPTCHA_HTML() ) {

        $csid = 0;
        if ($_CP_CONF['gfxDriver'] != 1) {

            // housekeeping, delete old captcha sessions
            $oldSessions = time() - ($_CP_CONF['expire']+900);
            DB_query("DELETE FROM {$_TABLES['cp_sessions']} WHERE cptime < " . $oldSessions,1);

            // OK, we need to insert the CAPTCHA, so now we need to setup the session_id:

            // check to see if a failed entry happened...
            if ( isset($_POST['csid']) ) {
                $csid = COM_applyFilter($_POST['csid']);
            } else {
                $csid = CAPTCHA_sid();
            }
            $time    = time();
            $counter = 0;
            $validation = '';  // this will be filled in by the CAPTCHA

                "  `session_id` varchar(40) NOT NULL default '', " .
                "  `cptime`  INT(11) NOT NULL default 0, " .
                "  `validation` varchar(40) NOT NULL default '', " .
                "  `counter`    TINYINT(4) NOT NULL default 0, " .

            DB_save($_TABLES['cp_sessions'],"session_id,cptime,validation,counter","'$csid','$time','','0'");
        }

        $captcha = CAPTCHA_getHTML($csid,$name);
    }
    return $captcha;
}

function mkTable ($tables, $action) {
    $buf = '';
    foreach ($tables as $lines) {
        $flg_valid_captcha = false;
        $errflg = '';
        $tdclass='';
        $buf .= LB .'            <tr>' . LB;
        $buf .= '                <th>';
        if (isset($lines['header'])) { $buf .= $lines['header']; }
        if (isset($lines['header_captcha'])) { $buf .= $lines['header_captcha']; }
        if (isset($lines['valid_require'])) { $buf .= $lines['valid_require']; }
        if (isset($lines['valid_captcha'])) { $buf .= $lines['valid_captcha']; $flg_valid_captcha = true; }
        // error check
        if (!empty($_POST)) { $errflg = ChkFormValidateLines($lines); }
        if ($errflg) { $tdclass=' class="warning_bgc"'; }
        $buf .= '</th>'.LB;
        $buf .= '                <td'.$tdclass.'>';
        if (isset($lines['data'])) {
            if ($flg_valid_captcha) {
                $buf .= mkCAPTCHA_HTML('sp_formmail',$lines['error_notcaptcha']);
            } else {
                $buf .= mkTable_Data($lines['data'], $action);
            }
        }
        $buf .= '</td>'.LB;
        $buf .= '            </tr>'.LB;
    }
    return $buf;
}

function mkForm ($items, $action) {
    $buf = '';
    foreach ($items as $item) {
        // each table
        if (!empty($item['table'])) {
            foreach ($item as $key => $value) {
                // 1 table
                switch ($key) {
                    case 'title': $buf .= mkTitle($value); break;
                    case 'table': $buf .= mkTable($value, $action); break;
                }
            }
            $buf .= <<<END

        </tbody>
    </table>
END;
        } elseif (!empty($item['table_captcha'])) {  // CAPTCHA table
            if ((!empty($action) && $action == 'input') && chk_Use_CAPTCHA_HTML()) {
                foreach ($item as $key => $value) {
                    // 1 table
                    switch ($key) {
                        case 'title_captcha': $buf .= mkTitle($value); break;
                        case 'table_captcha': $buf .= mkTable($value, $action); break;
                    }
                }
                $buf .= <<<END

            </tbody>
        </table>
END;
            } elseif (!empty($action)) {
                $buf .= LB . '    <input type="hidden">';
            }
        } elseif (!empty($item['action'])) {         // subimit button
            if ($item['action'] == $action) {
                $buf .= LB . '    <input type="hidden" name="action" value="' . $action . '"' . XHTML . '>';
                $buf .= LB . mkTable_Data($item['data'], $action);
            }
        }
    }
    return $buf;
}

function mkCsv ($items, $level=0, $dupcheck=array()) {
    global $_CONF;
    $ret = array();
    if ($level > 5) { return; } $level++;
    if (!empty($items['type']) && strtolower($items['type']) != 'submit' ) {
        if((!empty($items['not_csv']) && $items['not_csv']) || empty($items['name'])) { return; }
        if(strtolower($items['type']) == 'radio' && in_array($items['name'], $dupcheck)) { return; }
        return $items['name'];
    } else {
        if (!is_array($items)) { return; }
        foreach ($items as $i) {
            $name = mkCsv($i,$level, $ret);
            if (!empty($name)) {
                if(is_array($name)) { $ret = array_merge($ret,$name); } else { $ret[] = $name; }
            }
        }
    }
    return $ret;
}
}


// error check
if (!empty($_POST) && !empty($_POST['action'])) {
    $valid = FormValidate($form_items);
}
$action = getAction($valid);

// +---------------------------------------------------------------------------+
// | first step: display form (type & confirm)                                 |
// +---------------------------------------------------------------------------+
if ($action == 'input' || $action == 'confirm') {

/**
* form HTML { from here
*/
    // JS
    $js = mkJS($use_jslib, $def_jslib, $action, $_PLUGINS);
    // transition
    $seni = mkSeni($seni_items, $action);
    // input form
    $form = mkForm($form_items, $action);

    $retval = <<<END

$js
<div id="form_step">
$seni
</div>
<div id="FORM">
$valid
<form name="subForm" method="post" action="{$pageurl}">
$form
</form>
</div>

END;

/**
* } END of form HTML
*/



// +---------------------------------------------------------------------------+
// | SECOND STEP: Notice User Process & email submission                       |
// +---------------------------------------------------------------------------+
} elseif ($action == 'finish') {

/**
* COMPLETE MESSAGE HTML  { from HERE
*/
    // transition
    $seni = mkSeni($seni_items, $action);
    
    $out_html = <<<END

<div id="form_step">
$seni
</div>
<div id="contact_thanks">
<p><strong>We've received your inquiry.</strong></p>
<p>A confirmation e-mail has been sent to you.<br />
If you do not receive it, please re-submit your inquiry with this form.</p>
</div>

END;
/**
* } COMPLETE MESSAGE HTML ends HERE
*/



    # convert <br /> to LB
    foreach ($fld_list as $k => $v) { $fld_list[$k] = ereg_replace("<br />", LB, $fld_list[$k]); }
    $lang['sign_admin'] = ereg_replace("<br />", LB, $lang['sign_admin']);
    $lang['sign_user'] = ereg_replace("<br />", LB, $lang['sign_user']);
    // input content
    $input4mail=<<<END
Organization: {$fld_list['q_organizaiton']}
Name: {$fld_list['q_first_name']} {$fld_list['q_last_name']}
Email: {$fld_list['q_mail']}
Inquiry Method: {$fld_list['q_answer_means']}
Phone: {$fld_list['q_phone']} ({$fld_list['q_phone_kind']})
Inquiry Time: {$fld_list['q_access_time']}
Apply: {$fld_list['q_order_1']} {$fld_list['q_order_2']} {$fld_list['q_order_3']}
Comments: {$fld_list['q_other']}
END;

/**
* mail to admin  { from here
*/
    $out_mail_admin = <<<END

{$lang['receipt_admin']}

$input4mail

{$lang['sign_admin']}
END;
/**
* } end mail to admin
*/
/**
* mail to sender { from here
*/
    $out_mail_user = <<<END

{$lang['receipt_user']}

$input4mail

{$lang['sign_user']}
END;
/**
* } end mail to sender
*/


    # send email
    $ownererr = false;
    $ownersend = false;
    $om_array = explode(',', $owner_email);
    $owner_mails = array_unique($om_array);  # delete email double checked
    if (!empty($owner_email_item_name)) {
        $selmail;
        foreach ($owner_mails as $v) {
            list($key, $mail) = explode('=', $v);
            if ($_POST[$owner_email_item_name] == $key) {
                $selmail = explode('|', $mail);
                break;
            }
        }
        $owner_mails = $selmail;
    }
    $owner_subject = $lang['subject_admin'];
    foreach ($owner_mails as $v) {
        $email1 = COM_mail( $v, "$owner_subject", $out_mail_admin, $email_from, false); # mail to admin
        if (!$email1) { $ownererr = true; } else { $ownersend = true; }  # set flag of transmit error
    }
    # if error at admin email transmission
    if ($ownererr) {
        # if transmission succed for some of admins
        if ($ownersend) {
            # logs error. no error display for user since some of admin
            # received the email
            COM_errorLog($lang['ownertransmiterror'], 1);
            $email1 = true;
        # if transmission failed to all admins
        } elseif (!$ownersend) {
            # makes it a process error. no email to user.
            $email1 = false;
        }
    }
    if ($email1) {
        $usr_subject = $lang['subject_user'];
        $email2 = COM_mail( $fld_list[$email_input_name], "$usr_subject", $out_mail_user, $email_from, false); # email to user
    }
    if ($email1 && $email2) { # if succeed both of admins and user.
        # csv output
        if ($save_csv > 0) {
            $fldnames = mkCsv($form_items);
            $delimiter = ',';
            if ($save_csv > 1) { $delimiter = chr(9); }
            $enclosure = '"';
            # csv output
            $str = '';
            $escape_char = chr(92);
            foreach ($fldnames as $n) {
                $v = empty($fld_list[$n]) ? '' : $fld_list[$n] ;
                if (strpos($v, $delimiter) !== false ||
                    strpos($v, $enclosure) !== false ||
                    strpos($v, chr(10)) !== false ||
                    strpos($v, chr(13)) !== false ||
                    strpos($v, chr(9)) !== false ||
                    strpos($v, ' ') !== false) {
                  $str2 = $enclosure;
                  $escaped = 0;
                  $len = strlen($v);
                  for ($i=0;$i<$len;$i++) {
                    if ($v[$i] == $escape_char) {
                      $escaped = 1;
                    } else if (!$escaped && $v[$i] == $enclosure) {
                      $str2 .= $enclosure;
                    } else {
                      $escaped = 0;
                    }
                    $str2 .= $v[$i];
                  }
                  $str2 .= $enclosure;
                  $str .= $str2.$delimiter;
                } else {
                  $str .= $v.$delimiter;
                }
            }
            $str = date($date_csv) . $delimiter . substr($str,0,-1);
            $str .= LB;
            if( !empty( $save_csv_lang ) ) {
                $str = mb_convert_encoding($str, $save_csv_lang);
            }
            $fp = fopen($save_csv_file, 'a');
            fwrite($fp, $str);  # CSV output
            fclose($fp);
        }
        $retval = $out_html;
    } else {
        $retval = $lang['transmiterror']; # if email failed
    }
}
// execute Geeklog PHP
echo $retval;
// if you use 'execute PHP(return)' with Geeklog 1.6 or later, Please comment (#)the above 'echo' and uncomment the blow 'return' to enable.
# return $retval;
