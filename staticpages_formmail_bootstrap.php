// +---------------------------------------------------------------------------+
// | FormMail Static Page for Geeklog 1.8 higher Bootstrap class added, Japanese
// +---------------------------------------------------------------------------+
// | staticpages_formmail_bootstrap.php
// | 
// | Version: 2.1.9 Bootstrap ja
// +---------------------------------------------------------------------------+
// | Copyright (C) 2008-2015 by the following authors:
// | Authors    : Hiroshi Sakuramoto - hiro AT winkey DOT jp
// |            : Tetsuko Komma - komma AT ivywe DOT co DOT jp
// +---------------------------------------------------------------------------+
global $_CONF,$_USER,$_PLUGINS,$_SCRIPTS,$page; // Geeklog variables
global $_fmtokenttl; // FormMail variables
if (!defined('XHTML')) define('XHTML', ' /');

// --[[ Default Setting ]]------------------------------------------------------

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


# CSRF Token (sec)
$_fmtokenttl = 1800;
# Referer check (CSRF)  no check:0 check:1
$_spreferercheck = 1;
# Referer error message
$_spreferererrormsg = '<p class="text-danger">アクセスできません。サイト管理者にご連絡ください。</p>';


# use Geeklog's userset if logged-in
#   use username, full name & email of login users
#
$username = ''; $user_email = '';
if (!COM_isAnonUser()) {
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

# For Japanese language only.
#   Set colums that auto-convert from zenkaku to hankaku neccessary.
#   Specify name attribute's values one by one with commas. no space allowed.
$zentohan_itemname = 'q_phone,q_code1_1,q_code2_1,q_code3_1,q_code1_2,q_code2_2,q_code3_2,q_code1_3,q_code2_3,q_code3_3';

# For Japanese language only.
#   Set colums that auto-convert from hankaku to zenkaku neccessary.
#   Specify name attribute's values one by one with commas. no space allowed.
$kana_hantozen_itemname = 'q_kana_1,q_kana_2';

# For Japanese language only.
#   Set colums that auto-convert from hiragana to katakana necessary.
#   Specify name attribute's values one by one with commas. no space allowed.
$kana_hiratokana_itemname = 'q_kana_1,q_kana_2';

# item names at screen transition
$seni_items = array('input' => '情報入力', 'confirm' => '入力項目確認', 'finish' => '入力完了');

# string for mondatory item
$required_string = '<span class="text-danger">*</span>';

# === CAPTCHA === {
#   error message after formmail used with CAPTCHA.
#     *if blaked, error message of CAPTCHA plugin is used
#     *if you speciy message here, it will be used
$msg_spformmail_notinstall_captcha = 'CAPTCHA plugin is not installed';

# CAPTCHA error message. If not set, CAPTCA plugin message is used.
$msg_spformmail_valid_captcha = '';
#
#   ※ CAPTCHA templates
#   private/plugins/captcha/templates/captcha_contact.thtml
# } === CAPTCHA ===

# ==About date and time { ==
#   The date on the following JavaScript calendar.
#     php date format. ref: http://www.php.net/manual/en/function.date.php
#     "d, D, j, l, N, w, S, F, m, M, n, Y, y" can be assigned.
#   change layout/theme/vendor/uikit/js/components/datepicker.js

#   The receipt date and time indicated in an email.
#     Any php date format can be used here. (ref: http://www.php.net/manual/en/function.date.php)
$date_mail = 'Y/m/d H:i';

#   When the csv is outputted, the date and time are written to the first row of the csv.
#     Any php date format can be used here. (ref: http://www.php.net/manual/en/function.date.php)
$date_csv = 'Y/m/d H:i';

# } ==About date and time { ==


#####
# display message
#####
$lang = array(
// { complete & email message
  'receipt_admin' =>'管理者のみなさま'.LB.LB.$_CONF['site_name'].'サイトにおいて'.LB.'問い合わせがありました。'.LB.LB.'==========お問い合わせ =========='.LB.'受付日時：'.date($date_mail),
  'receipt_user' =>'※本メールは、'.$_CONF['site_name'].'サイトより自動的に配信しています。'.LB.'このメールは送信専用のため、このメールにご返信いただけません。'.LB.'＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝'.LB.'お問い合わせありがとうございました。'.LB.LB.'========== お問い合わせ内容 =========='.LB.'受付日時：'.date($date_mail),
  'subject_admin'=> '['.$_CONF['site_name'].']お問い合わせ',
  'subject_user'=> '['.$_CONF['site_name'].']お問い合わせを受け付けました',
  'sign_admin'    => '-----------------------------------------'.LB.$_CONF['site_name'].LB.$_CONF['site_url'].LB.'-----------------------------------------',
  'sign_user'    => '-----------------------------------------'.LB.$_CONF['site_name'].LB.'URL：' . $_CONF['site_url'].LB.'-----------------------------------------',
// } 完了HTML＆メールのメッセージ
// { システムエラーのメッセージ
  'ownertransmiterror'=>'オーナーメール処理中、一部のアドレスでエラーが発生しましたが、処理を続けました。',
  'transmiterror'=>'処理中にエラーが発生しました。',
// } system error message
);



#####
# table's item names
#####
$form_items = array(
## table {
array('title'=>'お客様情報', 'table'=>array(
// table 1 row {
array('header'=>'法人様名',
  'valid_notkanahan'=>'q_kaisha', 'error_notkanahan'=>'法人様名に半角カタカナがあります。すべて全角で入力してください',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_organization', 'size'=>'40', 'maxlength'=>'60', 'class'=>'form-control ime_on', 'placeholder' => '全角で入力してください。' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'お名前（漢字）',
  'valid_require'=>$required_string, 'error_require'=>'お名前（漢字）が入力されていません',
  'valid_notkanahan'=>'q_name', 'error_notkanahan'=>'お名前（漢字）に半角カタカナがあります。すべて全角で入力してください',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_name', 'size'=>'40', 'maxlength'=>'40', 'class'=>'form-control ime_on', 'value'=>$username, 'placeholder' => '全角で入力してください。' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'お名前（カタカナ）',
  'valid_require'=>$required_string, 'error_require'=>'お名前（カタカナ）が入力されていません',
  'valid_notkanahan'=>'q_kana', 'error_notkanahan'=>'お名前（カタカナ）に半角カタカナがあります。すべて全角で入力してください',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_kana', 'size'=>'40', 'maxlength'=>'40', 'class'=>'form-control ime_on','placeholder' => '全角で入力してください。' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'メールアドレス',
  'valid_require'=>$required_string, 'error_require'=>'メールアドレスが入力されていません',
  'valid_equal'=>$essential_email, 'error_equal'=>'メールアドレスが一致しません',
  'valid_email'=>$propriety_email, 'error_email'=>'メールアドレスを正しく入力してください',
  'valid_hankaku'=>'q_mail,q_mail_re', 'error_hankaku'=>'メールアドレスはすべて半角で入力してください',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_mail', 'size'=>'40', 'maxlength'=>'240', 'class'=>'form-control ime_off', 'value'=>$user_email,'placeholder' => '半角でメールアドレスを入力してください。' ),
array( 'type'=>'text', 'name'=>'q_mail_re', 'size'=>'40', 'maxlength'=>'240', 'class'=>'form-control email ime_off mt10', 'not_confirm'=>'true', 'not_csv'=>'true', 'value'=>$user_email, 'placeholder' => '確認のため、もう一度入力してください。' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'ご連絡方法',
  'data'=>array(
array( 'string'=>'<br'.XHTML.'>' ), // for Bootstrap
array( 'type'=>'radio', 'name'=>'q_answer_means', 'value'=>'メール', 'checked'=>'checked' ),
array( 'input'=>'メール ' ),
array( 'type'=>'radio', 'name'=>'q_answer_means', 'value'=>'電話' ),
array( 'input'=>'電話 ' ),
array( 'string'=>'<br'.XHTML.'>' ),
array( 'input'=>'※お問い合わせ内容によって、メールをご希望の場合も電話連絡とさせて頂く場合があります。' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'電話番号',
  'valid_require'=>$required_string, 'error_require'=>'電話番号が入力されていません',
  'valid_phone'=>'q_phone', 'error_phone'=>'電話番号を正しく入力してください。数字と+(プラス)と-(ハイフン)と (半角スペース)が使えます',
  'valid_minlen'=>'q_phone=6', 'error_minlen'=>'電話番号の文字数は6文字以上で入力してください',
  'valid_maxlen'=>'q_phone=13', 'error_maxlen'=>'電話番号の文字数は13文字以内で入力してください',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_phone', 'size'=>'20', 'style'=>'width: 20em;', 'maxlength'=>'13', 'class'=>'form-control ime_off', 'placeholder' => '※例&nbsp;0311112222' ),
array( 'type'=>'radio', 'name'=>'q_phone_kind', 'value'=>'自宅', 'checked'=>'checked' ),
array( 'input'=>'自宅 &nbsp; ' ),
array( 'type'=>'radio', 'name'=>'q_phone_kind', 'value'=>'勤務先' ),
array( 'input'=>'勤務先 &nbsp; ' ),
array( 'type'=>'radio', 'name'=>'q_phone_kind', 'value'=>'携帯' ),
array( 'input'=>'携帯' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'希望日',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_date1', 'size'=>'20', 'class'=>'form-control', 'style'=>'width: 20em;', 'data-uk-datepicker'=>"{format:'YYYY.MM.DD'}"), 
  ),
),
// } table 1 row
// table 1 row {
array('header' => '時間帯',
  'data'=>array(
array( 'type'=>'select', 'name'=>'q_access_time', 'style'=>'width: 20em;', 'class'=>'form-control', 'options'=>array('selected' => '特に希望なし', 'values' => '特に希望なし,午前,午後   - 夕方まで,夕方以降') ),
array( 'input'=>'※電話連絡の場合のご連絡を希望する時間帯。' ),
  ),
),
// } table 1 row
),),
## } table
## table {
array('title'=>'申し込み内容', 'table'=>array(
// table 1 row {
array('header'=>'お申し込みセミナー',
  'data'=>array(
array( 'input'=>'<br'.XHTML.'>' ), // for Bootstrap
array( 'type'=>'checkbox', 'name'=>'q_order_1', 'value'=>'セミナー１' ),
array( 'input'=>' ' ),
array( 'type'=>'checkbox', 'name'=>'q_order_2', 'value'=>'セミナー２' ),
array( 'input'=>' ' ),
array( 'type'=>'checkbox', 'name'=>'q_order_3', 'value'=>'セミナー３' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'お問い合わせ内容',
  'valid_notkanahan'=>'q_other', 'error_notkanahan'=>'お問い合わせ内容に半角カタカナがあります。すべて全角で入力してください',
  'valid_maxlen'=>'q_other=500', 'error_maxlen'=>'お問い合わせ内容の文字数は500文字以内で入力してください',
  'data'=>array(
array( 'type'=>'textarea', 'name'=>'q_other', 'class'=>'ime_on form-control ime_on', 'style'=>'width: 95%; height: 100px;', 'onKeyup'=>"var n=500-this.value.length;var s=document.getElementById('tasp1');s.innerHTML='('+n+')';", 'placeholder' => 'お問い合わせ内容を入力してください。' ),
array( 'input'=>'<br'.XHTML.'>'."<strong><span id='tasp1'></span></strong>".'<br'.XHTML.'>' ),
  ),
),
// } table 1 row
),),
## } table
## table CAPTCHA {
array('title_captcha' => '', 'table_captcha' => array(
// { table 1 row CAPTCHA
array('header_captcha' => '画像認証',
  'valid_captcha' => $required_string,
  'error_captcha' => $msg_spformmail_valid_captcha,
  'error_notcaptcha' => $msg_spformmail_notinstall_captcha,
  'data' => array()
),
// } table 1 row CAPTCHA
),),
## } table CAPTCHA
## submit input {
array('action'=>'input',
  'data'=>array(
array( 'string'=>'<div class="text-center">' ),
array( 'type'=>'submit', 'name'=>'submit', 'class'=>'uk-button', 'value'=>'入力項目確認画面へ' ),
array( 'string'=>'</div>' ),
  ),
),
## } submit input
## submit confirm {
array('action'=>'confirm',
  'data'=>array(
array( 'string'=>'<div class="text-center">' ),
array( 'type'=>'submit', 'name'=>'goback', 'class'=>'uk-button', 'value'=>'戻る' ),
array( 'string'=>'　' ),
array( 'type'=>'submit', 'name'=>'submit', 'class'=>'uk-button', 'value'=>'送信する' ),
array( 'string'=>'</div>' ),
  ),
),
## } submit confirm
);



// --[[ Functions ]]---------------------------------------------------------------
if(!function_exists('_fmGetAction')){
function _fmGetAction ($err) {
    $buf = '';
    $action = COM_applyFilter($_POST['action']);
    if (!empty($action) && empty($err) && $action == 'input') { $buf = 'confirm'; }
    elseif (!empty($action) && empty($err) && $action == 'confirm') { $buf = empty($_POST['goback']) ? 'finish' : 'input'; }
    else { $buf = 'input'; }
    return $buf;
}

function _fmMkSeni ($items, $action) {
    $buf = '<ul class="list-inline">'.LB;
    foreach ($items as $key => $value) {
        if ($action == $key) {
            $buf .= '    <li><h4><span class="label label-default label-info">'.$value.'</span></li>'.LB;
        } else {
            $buf .= '    <li><h4><span class="label label-default label-default">'.$value.'</span></li>'.LB;
        }
    }
    $buf .= '</ul>'.LB;
    return $buf;
}

function _fmPutiFilter($s) {
    $se = array('%','(',')',chr(92),chr(13).chr(10),chr(13),chr(10));
    $re = array('&#37;','&#40;','&#41;','&#92;','','','');
    return str_replace($se, $re, htmlspecialchars($s,ENT_QUOTES));
}

function _fmChkUseCAPTCHA_HTML () {
    global $_CP_CONF, $_USER;
    if ( ($_CP_CONF['anonymous_only'] && $_USER['uid'] < 2) || $_CP_CONF['anonymous_only'] == 0 || ($_CP_CONF['remoteusers'] == 1 && SEC_inGroup("Remote Users") ) ) {
        return true;
    }
    return false;
}
function _fmVldCAPTCHA ($type, $errmsg) {
    $msg = '';
    if (!function_exists('CAPTCHA_sid')) { return $msg; }
    if ( _fmChkUseCAPTCHA_HTML() ) {
        $str = COM_applyFilter($_POST['captcha']);
        list( $rc, $msg )  = CAPTCHA_checkInput( $type, $str );
    }
    if ( !empty($msg) && !empty($errmsg) ) { $msg = $errmsg; }
    return $msg;

}

function _fmVld_isPhone($s) { return (preg_match('/^(?:[0-9'.chr(92).'+'.chr(92).'-'.chr(92).'s])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isHankaku($s) { return (preg_match('/^(?:'.chr(92).'xEF'.chr(92).'xBD['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xEF'.chr(92).'xBE['.chr(92).'x80-'.chr(92).'x9F]|['.chr(92).'x20-'.chr(92).'x7E])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isZenkaku($s) { return (preg_match('/(?:'.chr(92).'xEF'.chr(92).'xBD['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xEF'.chr(92).'xBE['.chr(92).'x80-'.chr(92).'x9F]|['.chr(92).'x20-'.chr(92).'x7E])+/D',$s)) ? FALSE : TRUE; }
function _fmVld_isEisuHan($s) { return (preg_match('/^(?:[0-9A-Za-z])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isKanaZen($s) { return (preg_match('/^(?:'.chr(92).'xE3'.chr(92).'x82['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xE3'.chr(92).'x83['.chr(92).'x80-'.chr(92).'xB6])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isHiraZen($s) { return (preg_match('/^(?:'.chr(92).'xE3'.chr(92).'x81['.chr(92).'x81-'.chr(92).'xBF]|'.chr(92).'xE3'.chr(92).'x82['.chr(92).'x80-'.chr(92).'x93])+$/D',$s)) ? TRUE : FALSE; }
function _fmVld_isNotKanaHan($s) { return (preg_match('/(?:'.chr(92).'xEF'.chr(92).'xBD['.chr(92).'xA1-'.chr(92).'xBF]|'.chr(92).'xEF'.chr(92).'xBE['.chr(92).'x80-'.chr(92).'x9F])+/D',$s)) ? TRUE : FALSE; }

function _fmChkValidate ($mode, $datas, $errmsg, $attributes = '') {
    $msg = '';
    foreach ($datas as $data) {
        if (isset($data['type'])) {
            $name = $data['name'];
//<Input check>
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
        if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isPhone($_POST[$name])) { $msg = $errmsg; }
        break;
    // (For Japanese language only) Check byte
    case 'hankaku':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isHankaku($_POST[$name])) { $msg = $errmsg; }
        break;
    // (For Japanese language only) Check em
    case 'zenkaku':
        if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !_fmVld_isZenkaku($_POST[$name])) { $msg = $errmsg; }
        break;
    // (For Japanese language only) Alphanumeric check
    case 'eisuhan':
        if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isEisuHan($_POST[$name])) { $msg = $errmsg; }
        break;
    // (For Japanese language only) Check em kana
    case 'kanazen':
       if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !_fmVld_isKanaZen($_POST[$name])) { $msg = $errmsg; }
        break;
    // (For Japanese language only) Check em hiragana
    case 'hirazen':
       if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !_fmVld_isHiraZen($_POST[$name])) { $msg = $errmsg; }
        break;
    // (For Japanese language only) Check for non-katakana
    case 'notkanahan':
       if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && _fmVld_isNotKanaHan($_POST[$name])) { $msg = $errmsg; }
       break;
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
//</Input check>
        }
    }
    // check CAPTCHA
    if ( $mode == 'captcha' ) { $msg = _fmVldCAPTCHA('contact', $errmsg); }
    return $msg;
}

function _fmValidateLines ($lines) {
    $errmsg;
    foreach (array('require','equal','email','notzero','numeric','phone','hankaku','zenkaku','eisuhan','kanazen','hirazen','notkanahan','captcha','maxlen','minlen') as $chk) {
        // check require,match,email,CAPTCH,error
        if (isset($lines['valid_'.$chk])) {
            $errmsg = _fmChkValidate($chk, $lines['data'], $lines['error_'.$chk], $lines['valid_'.$chk]);
            // arrays if error found
            if ($errmsg) {
                break;
            }
        }
    }
    return $errmsg;
}

function _fmValidateItems ($items) {
    $errs;
    foreach ($items as $item) {
        // eah table
        foreach ($item as $key => $value) {
            // 1 table
            if ($key == 'table' || $key == 'table_captcha') {
                $action = _fmGetAction('');
                if ($key == 'table_captcha' && $action == 'finish') { continue; }
                foreach ($value as $key2 => $value2) {
                    // table 1 row
                    $errmsg = _fmValidateLines($value2);
                    if ($errmsg) { $errs[] = $errmsg; }
                }
            }
        }
    }
    return $errs;
}

function _fmValidate ($items) {
    $buf = '';
    $errs = _fmValidateItems($items);
    if (!empty($errs)) {
        $errmsg = '';
        foreach ($errs as $err) {
            $errmsg .= '    <li>'.$err.'</li>'.LB;
        }
        $buf = <<<END

<div class="alert alert-warning" role="alert">
<p style="margin-top:10px">入力エラーがありました。下記について再度ご確認の上、ご記入ください。</p>
<ol>
$errmsg
</ol>
</div>

END;
    }
    return $buf;
}


function _fmMkTitle ($title) {
    return <<<END

    <h3>$title</h3>
END;
}

function _fmMkForm_Value ($name, $value) {
    return (empty($_POST[$name]) && $_POST[$name] != "0") ? $value : _fmPutiFilter($_POST[$name]);
}

function _fmMkForm_Radio_Checked (&$attributes) {
    $name = $attributes['name'];
    if ((!empty($_POST[$name]) || $_POST[$name] == "0")) {
        if (isset($attributes['checked'])) unset($attributes['checked']);
        if ($_POST[$name] == $attributes['value']) {
            $attributes['checked'] = 'checked';
        }
    }
}

function _fmMkForm_Input ($attributes, $hidden = false) {
    if ($hidden) {
        if ($attributes['type'] == 'radio' || $attributes['type'] == 'checkbox') {
            if ($attributes['value'] != $_POST[$attributes['name']]) return '';
        }
        $attributes['type'] = 'hidden';
    }
    if ($attributes['type'] == 'radio' || $attributes['type'] == 'checkbox') {
        _fmMkForm_Radio_Checked($attributes);
    } else {
        if ($attributes['type'] != 'submit') $attributes['value'] = _fmMkForm_Value($attributes['name'], $attributes['value']);
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

function _fmMkForm_Select_Options ($name, $attributes) {
    $buf = '';
    $selected = _fmMkForm_Value($name, $attributes['selected']);
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

function _fmMkForm_Select ($attributes) {
    $buf = '<select';
    foreach ($attributes as $key => $value) {
        if ($key != 'options') {
            $buf .= ' '.$key.'="'.$value.'"';
        }
    }
    $buf .= '>';
    $buf .= _fmMkForm_Select_Options($attributes['name'], $attributes['options']);
    $buf .= '</select>';
    return $buf;
}

function _fmMkForm_Textarea ($attributes) {
    $attributes['value'] = _fmMkForm_Value($attributes['name'], $attributes['value']);
    $buf = '<textarea';
    foreach ($attributes as $key => $value) {
        if ($key != 'value') $buf .= ' '.$key.'="'.$value.'"';
    }
    $buf .= '>'.$attributes['value'].'</textarea>';
    return $buf;
}

function _fmMkForm_Item ($items, $action) {
    $buf = '';
    if ($action != 'input' && $items['type'] != 'submit' && $items['type'] != 'hidden') {
        $buf .= _fmMkForm_Input($items, true);
    } else {
        switch ($items['type']) {
            case 'text': $buf .= _fmMkForm_Input($items); break;
            case 'password': $buf .= _fmMkForm_Input($items); break;
            case 'hidden': $buf .= _fmMkForm_Input($items); break;
            case 'radio': $buf .= _fmMkForm_Input($items); break;
            case 'checkbox': $buf .= _fmMkForm_Input($items); break;
            case 'select': $buf .= _fmMkForm_Select($items); break;
            case 'textarea': $buf .= _fmMkForm_Textarea($items); break;
            case 'submit': $buf .= _fmMkForm_Input($items); break;
            case 'reset': $buf .= _fmMkForm_Input($items); break;
            case 'button': $buf .= _fmMkForm_Input($items); break;
        }
    }
    return $buf;
}

function _fmMkTable_Data ($datas, $action) {
    $buf = '';
    foreach ($datas as $data) {
        // 1 data
        if (!empty($data['type'])) {
            // form
            $buf .= _fmMkForm_Item($data, $action);
        }
        else {
            // string
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

function _fmMkCAPTCHA_HTML($name, $msg_notcaptcha) {
    global $_CP_CONF, $_USER, $_TABLES;
    $captcha = '';
    if (!function_exists('CAPTCHA_sid')) { return $msg_notcaptcha; }
    if ( _fmChkUseCAPTCHA_HTML() ) {
        $csid = 0;
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
        DB_save($_TABLES['cp_sessions'],"session_id,cptime,validation,counter","'$csid','$time','','0'");
        $captcha = CAPTCHA_getHTML($csid,$name);
    }
    return $captcha;
}

function _fmMkTable ($tables, $action) {
    global $_fmhelppageurl;
    $buf = '';
    foreach ($tables as $lines) {
        $flg_valid_captcha=false;
        $errflg = '';
        $tdclass='';
        // Error check
        if (!empty($_POST)) { $errflg = _fmValidateLines($lines); }
        if ($errflg) { $div_errorclass=' has-error has-feedback'; $label_errorclass =' bg-danger'; } else { $tdclass=''; }
        $buf .= LB .'            ' . LB;
        $buf .= '<div class="form-group'.$div_errorclass.'"><label class="control-label'.$label_errorclass.'">';
        if (isset($lines['header'])) { $buf .= $lines['header'] ; }
        if (isset($lines['header_captcha'])) { $buf .= $lines['header_captcha']; }
        if (isset($lines['valid_require'])) { $buf .= $lines['valid_require']; }
        if (isset($lines['valid_captcha'])) { $buf .= $lines['valid_captcha']; $flg_valid_captcha=true; }
        $buf .= '</label>'.LB;
        if (isset($lines['data'])) {
            if ($flg_valid_captcha) {
                $buf .= _fmMkCAPTCHA_HTML('contact',$lines['error_notcaptcha']);
            } else {
                $buf .= _fmMkTable_Data($lines['data'], $action);
            }
        }

        if ($errflg) { 
			$buf .= '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(error)</span>';
		} else { 
			$tdclass=''; 
		}

        $buf .= '</div>'.LB;
    }
    return $buf;
}

function _fmMkForm ($items, $action) {
    global $_fmtokenttl;
    $ttl = (isset($_fmtokenttl) && $_fmtokenttl > 1) ? $_fmtokenttl : 1800;
    $buf = '';
    foreach ($items as $item) {
        // table
        if (!empty($item['table'])) {
            foreach ($item as $key => $value) {
                // 1 table
                switch ($key) {
                    case 'title': $buf .= _fmMkTitle($value); break;
                    case 'table': $buf .= _fmMkTable($value, $action); break;
                }
            }
            $buf .= <<<END

END;
        } elseif (!empty($item['table_captcha'])) {  // CAPTCHA
            if ((!empty($action) && $action == 'input') && _fmChkUseCAPTCHA_HTML()) {
                foreach ($item as $key => $value) {
                    // 1 table
                    switch ($key) {
                        case 'title_captcha': $buf .= _fmMkTitle($value); break;
                        case 'table_captcha': $buf .= _fmMkTable($value, $action); break;
                    }
                }
                $buf .= <<<END

END;
            }
        } elseif (!empty($item['action'])) {         // Submit button
            if ($item['action'] == $action) {
                $buf .= LB . '    <input type="hidden" name="action" value="' . $action . '"' . XHTML . '>';
                $buf .= LB . _fmMkTable_Data($item['data'], $action);
            }
        }
    }
    if (!empty($buf) && ($action=='input' || $action=='confirm')) { $buf.=LB.'    <input type="hidden" name="_glsectoken" value="'.SEC_createToken($ttl).'"'.XHTML.'>'; }
    return $buf;
}

function _fmMkCsv ($items, $level=0, $dupcheck=array()) {
    $ret = array();
    if ($level > 5) { return; } $level++;
    if (!empty($items['type']) && strtolower($items['type']) != 'submit' ) {
        if((!empty($items['not_csv']) && $items['not_csv']) || empty($items['name'])) { return; }
        if(strtolower($items['type']) == 'radio' && in_array($items['name'], $dupcheck)) { return; }
        return $items['name'];
    } else {
        if (!is_array($items)) { return; }
        foreach ($items as $i) {
            $name = _fmMkCsv($i,$level, $ret);
            if (!empty($name)) {
                if(is_array($name)) { $ret = array_merge($ret,$name); } else { $ret[] = $name; }
            }
        }
    }
    return $ret;
}

function _fmChkReferer ($pu,$err) {
    global $_CONF;  $msg = '';  $action = COM_applyFilter($_POST['action']);
    if (!isset($_SERVER['HTTP_REFERER'])) {
        if (!empty($_POST)) { $msg = '<p class="text-danger">REFERERチェックが設定されていますが環境変数にREFERERがセットされていないためチェックできません。サイト管理者にご連絡ください。</p>'; }
    } elseif (!empty($action) && ($action=='input' || $action=='confirm')) {
        if (strpos($_SERVER['HTTP_REFERER'],$pu)===FALSE) {
            $msg = $err;
        }
    } elseif (strpos($_SERVER['HTTP_REFERER'],$_CONF['site_url'])===FALSE) {
        $msg = $err;
    }
    return $msg;
}
}




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
if (!empty($page)) { $pageurl = COM_buildUrl($_CONF['site_url'].'/staticpages/index.php?page='.$page); }
if (empty($_fmhelppageurl) && !empty($helppageid)) { $_fmhelppageurl = COM_buildUrl($_CONF['site_url'].'/staticpages/index.php?page='.$helppageid); }
# CSRF
if (!empty($_POST) && !SECINT_checkToken()) { $m=isset($_POST[$email_input_name]) ? 'email='.$_POST[$email_input_name].' ' : ''; COM_accessLog("tried {$m}to staticpage({$pageid}) failed CSRF checks."); header('Location: '.$pageurl); exit; }


// Referer check
if (!empty($_spreferercheck) && $_spreferercheck = 1) {
    $valid = _fmChkReferer($pageurl,$_spreferererrormsg);
}

// Error check
if (empty($valid) && !empty($_POST) && !empty($_POST['action'])) {
    $valid = _fmValidate($form_items);
}
$action = _fmGetAction($valid);



// --[[ Step 1 : Form (Input & Confirm) ]]-------------------------------
if ($action == 'input' || $action == 'confirm') {
/**
* Form HTML {
*/

    // transition
    $seni = _fmMkSeni($seni_items, $action);
    // input form
    $form = _fmMkForm($form_items, $action);

    $retval = <<<END

<div data-uk-button-checkbox>
$seni
</div>
<div id="formmail">
$valid
<form name="subForm" class="form-block" method="post" action="{$pageurl}">
$form
</form>
</div>

END;

/**
* } Form HTML
*/



// --[[ Step 2 : Notice User Process & email submission  ]]-----------------------------------
} elseif ($action == 'finish') {
/**
* COMPLETE MESSAGE HTML {
*/
    // transition
    $seni = _fmMkSeni($seni_items, $action);
    
    $out_html = <<<END

<div>
$seni
</div>
<div>
<p><strong>お問い合わせを受け付けました。</strong></p>
<p>※お問い合わせ確認のメールを自動送信しました。<br />
メールが届かない場合は、ご登録のメールアドレスが間違っている可能性があります。<br />
その際は、お手数ですが再度お問い合わせください。</p>
</div>

END;
/**
* } COMPLETE MESSAGE HTML
*/



    # convert <br /> to LB
    foreach ($fld_list as $k => $v) { $fld_list[$k] = ereg_replace("<br />", LB, $fld_list[$k]); }
    $lang['sign_admin'] = ereg_replace("<br />", LB, $lang['sign_admin']);
    $lang['sign_user'] = ereg_replace("<br />", LB, $lang['sign_user']);
    // 入力内容
    $input4mail=<<<END

会社名: {$fld_list['q_organization']}
お名前（漢字）: {$fld_list['q_name']}
お名前（カタカナ）: {$fld_list['q_kana']}
メールアドレス: {$fld_list['q_mail']}
ご連絡方法: {$fld_list['q_answer_means']}
TEL: {$fld_list['q_phone']}
連絡先: {$fld_list['q_phone_kind']}
希望日: {$fld_list['q_date1']}
連絡ご希望時間帯: {$fld_list['q_access_time']}
お申し込み内容: {$fld_list['q_order_1']} {$fld_list['q_order_2']} {$fld_list['q_order_3']}
お問い合わせ内容: {$fld_list['q_other']}

END;

/**
* mail to admin  {
*/
    $out_mail_admin = <<<END

{$lang['receipt_admin']}

$input4mail

{$lang['sign_admin']}
END;
/**
* } mail to admin
*/
/**
* mail to sender {
*/
    $out_mail_user = <<<END

{$lang['receipt_user']}

$input4mail

{$lang['sign_user']}
END;
/**
* } mail to sender
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
            $fldnames = _fmMkCsv($form_items);
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
            fwrite($fp, $str);  # csv output
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