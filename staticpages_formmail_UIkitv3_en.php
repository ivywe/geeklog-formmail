// +------------------------------------------------------------------+
// | FormMail Static Page for Geeklog 2.1 higher for UIkit
// +------------------------------------------------------------------+
// | Copyright (C) 2008-2018 by the following authors:
// | Authors    : Hiroshi Sakuramoto - hiro AT winkey DOT jp
// | Version: 2.1.13
// |  front-end framewaork for UIKit: 3.0
// | staticpages_formmail_UIkitv3_2_en.php
// +------------------------------------------------------------------+
global $_CONF,$_USER,$_PLUGINS,$_SCRIPTS,$page; // Geeklog変数
global $_fmtokenttl; // FormMail変数
if (!defined('XHTML')) define('XHTML', ' /');

// --[[ 初期設定 ]]---------------------------------------------------
# 問合せをAdministrator へ通知の設定
#    複数のE-mailはカンマ(,)で区切りで指定する(スペース等はあけない)
#      例) 'info@hoge.com,admin@page.com'
#    特定のInput 項目に応じて送り先を変える
#    ※この方法を利用する時は必ず $owner_email_item_name を指定してください。
#      例) 'AAA=info@hoge.com,BBB=admin@page.com'
$owner_email=$_CONF['site_mail'];

# Administrator EmailをInput 項目から選択する項目Name
#   (selectなどの選択でMail の送り先を変えるのに利用)
//  ※送り先を変える指定をしたら先頭の#を削除してください。(コメントをはずします)
#$owner_email_item_name = 'q_mail_to';

# Mail Send 者E-mail
$email_from = $_CONF['site_mail'];
#Geeklog1.5から，noreplyを指定できます。
#$email_from = $_CONF['noreply_mail'];

# 問合せ者のMail アドレスの項目Name
$email_input_name = 'q_mail';

# Mail MatchCheck項目指定
#   Mail 確認でどちらも同じものをInput  というname属性を(=)で区切る(スペース等はあけない)
#     例) 'email=reemail'
$essential_email = 'q_mail=q_mail_re';

# Mail アドレスCheck項目指定
#   Input された値がMail アドレスとして正しいかCheckをする
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
#     例) 'email,reemail'
$propriety_email = 'q_mail,q_mail_re';

# CSRF対策のToken expierd second
$_fmtokenttl = 1800;
# Referer Check (CSRF)  Not Check:0 Check:1
$_spreferercheck = 1;
# Referer Error Message
$_spreferererrormsg = '<p class="uk-text-danger">アクセスできません。サイトAdministrator にご連絡ください。</p>';


# ログイン済みならユーザ情報を利用
#   GeeklogユーザーNameやMail アドレスを利用
$username = ''; $user_email = '';
if (!COM_isAnonUser()) {
    $username = isset($_USER['fullname']) ? $_USER['fullname'] : $_USER['username'];
    $user_email = $_USER['email'];
}

# Save CSV File 
#   指定方法 保存しない: 0 , 保存する(カンマ区切り): 1 , 保存する(タブ区切り): 2
$save_csv = 1;

# CSV File File Path need /
$save_csv_path = $_CONF['path_data'];

# CSV File Name
$save_csv_name = $page.'.csv';

# CSV File 保存のEncode
#   Encode変換をしないCase は '' と指定してください。
#   機能がOFFになります。（文字化けするようなら機能を''で
#   OFFにして別途フリーの文字変換ツールなどをご利用ください）
# 注意) mb_convert_encodingで使えるEncodeを指定してください
#   例) UTF-8, SJIS, EUC-JP, JIS, ASCII
$save_csv_lang = 'UTF-8';

# 全角を半角に自動変換する項目Name(英数字、スペース、カタカナ、ひらがな)
#   Input された値を自動で変換する項目を指定
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
$zentohan_itemname = 'q_phone,q_code1_1,q_code2_1,q_code3_1,q_code1_2,q_code2_2,q_code3_2,q_code1_3,q_code2_3,q_code3_3';


# 遷移の項目Name
$seni_items = array('input' => 'Input', 'confirm' => 'Confirm', 'finish' => 'Complete');

# Required Input の文字列
$required_string = '<span class="uk-text-warning">*</span>';

# ==reCaptcha==
#   reCaptcha(reCAPTCHA)がインストールされていないCase のErrorメッセージ
$msg_spformmail_notinstall_captcha = 'CAPTHCA is not istalled.';

#   Send 時にreCaptchaでErrorのCase のErrorメッセージ
#     ※空文字にするとreCAPTCHAプラグインが作成するErrorメッセージを使います。
#     ※空文字意外にするとそれを無視して固定メッセージにできます。
$msg_spformmail_valid_captcha = 'Check reCaptcha';

# ==日付関係==
#   JavaScriptカレンダーでの日付表記
#     phpのdate参照 http://php.net/manual/ja/function.date.php
#       day   => 'd,D,j,l,N,S,w,z'
#       month => 'F,m,M,n,t'
#       year  => 'Y,y'
#   ※テンプレート layout/theme/vendor/uikit/js/components/datepicker.js

#   The receipt date and time indicated in an email.
#     Any php date format can be used here. (ref: http://www.php.net/manual/en/function.date.php)
$date_mail = 'M j Y H:i';
#   When the csv is outputted, the date and time are written to the first row of the csv.
#     Any php date format can be used here. (ref: http://www.php.net/manual/en/function.date.php)
$date_csv = 'M j Y H:i';


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
// 1 Group {
array('title'=>'Organization', 'table'=>array(
// table 1 row {
array('header'=>'Organization',
  'help'=>'Please type your organization.',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_organization', 'size'=>'40', 'maxlength'=>'60', 'class'=>'uk-input', 'placeholder'=>'e.g. Organization name' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'Name',
  'valid_require'=>$required_string, 'error_require'=>'Please type your name.',
  'help'=>'Please type your name.',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_name', 'size'=>'40', 'maxlength'=>'40', 'class'=>'uk-input ime_on', 'value'=>$username, 'placeholder'=>'e.g. Jone Smith', 'required'=>'' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'Email',
  'valid_require'=>$required_string, 'error_require'=>'Please type your email address.',
  'valid_equal'=>$essential_email, 'error_equal'=>'Email address not match.',
  'valid_email'=>$propriety_email, 'error_email'=>'Please type your email address.',
  'valid_hankaku'=>'q_mail,q_mail_re', 'error_hankaku'=>'Please type out proper email address.',
  'help'=>'Please type out proper email address.',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_mail', 'size'=>'40', 'maxlength'=>'240', 'class'=>'uk-input uk-margin-small-bottom', 'value'=>$user_email, 'required'=>'' ),
array( 'input'=>'<br'.XHTML.'>' ),
array( 'type'=>'text', 'name'=>'q_mail_re', 'size'=>'40', 'maxlength'=>'240', 'class'=>'uk-input', 'not_confirm'=>'true', 'not_csv'=>'true', 'value'=>$user_email, 'placeholder'=>'Please enter your e-mail address again to confirm.', 'required'=>'' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'Inquiry Method',
  'help'=>'Select inquiry method.',
  'data'=>array(
array( 'type'=>'radio', 'name'=>'q_answer_means', 'value'=>'Email ', 'class'=>'uk-radio', 'checked'=>'checked' ),
array( 'input'=>' Mail ' ),
array( 'type'=>'radio', 'name'=>'q_answer_means', 'value'=>'Phone', 'class'=>'uk-radio' ),
array( 'input'=>' Phone' ),
array( 'string'=>'<br'.XHTML.'>' ),
array( 'input'=>'* We may call you in the event e-mail inquiry fails.' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'Phone',
  'valid_require'=>$required_string, 'error_require'=>'Please type your phone number.',
  'valid_phone'=>'q_phone', 'error_phone'=>'Please input your phone number correctly. Only numbers, plus, minus, and space characters are allowed as input.',
  'valid_minlen'=>'q_phone=6', 'error_minlen'=>'Phone number shouold be longer than 6 digit.',
  'valid_maxlen'=>'q_phone=17', 'error_maxlen'=>'Phone number should not exceed 17 digit.',
  'help'=>'Please input your phone number correctly. Only numbers, plus, minus, and space characters are allowed as input.',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_phone', 'size'=>'20', 'maxlength'=>'17', 'aria-required'=>'true', 'required'=>'', 'class'=>'uk-input uk-form-width-medium ime_off', 'placeholder'=>'e.g. +00 0 0000 0000', 'required'=>'' ),
array( 'string'=>'<br'.XHTML.'>' ),
array( 'type'=>'radio', 'name'=>'q_phone_kind', 'value'=>'Home', 'checked'=>'checked' ),
array( 'input'=>'Home&nbsp;' ),
array( 'type'=>'radio', 'name'=>'q_phone_kind', 'value'=>'Office' ),
array( 'input'=>'Office&nbsp; ' ),
array( 'type'=>'radio', 'name'=>'q_phone_kind', 'value'=>'Mobile' ),
array( 'input'=>'Mobile' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'Date',
  'help'=>'Requested Inquiry Date.',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_date1', 'size'=>'20', 'data-uk-datepicker'=>"{format:'YYYY.MM.DD'}", 'class'=>'uk-input uk-form-width-small', 'placeholder'=>'YYYY/MM/DD' ),
  ),
),
// } table 1 row
// table 1 row {
array('header' => 'Requested Inquiry Time',
  'help'=>'Please indicate a time we may call you if telephone inquiry is preferred.',
  'data'=>array(
array( 'type'=>'select', 'name'=>'q_access_time', 'options'=>array('selected' => 'Not Specified', 'values' => 'Not Specified,AM,Early PM, Late PM'), 'class'=>'uk-select uk-form-width-small'  ),
array( 'input'=>'<br'.XHTML.'>* Please indicate a time we may call you if telephone inquiry is preferred.' ),
  ),
),
// } table 1 row
),),
// } 1 Group
// 1 Group {
array('title'=>'Preferences', 'table'=>array(
// table 1 row {
array('header'=>'Apply',
  'help'=>'Select option.',
  'data'=>array(
array( 'type'=>'checkbox', 'name'=>'q_order_1', 'value'=>'Option 1', 'class'=>'uk-checkbox' ),
array( 'input'=>' ' ),
array( 'type'=>'checkbox', 'name'=>'q_order_2', 'value'=>'Option 2', 'class'=>'uk-checkbox' ),
array( 'input'=>' ' ),
array( 'type'=>'checkbox', 'name'=>'q_order_3', 'value'=>'Option 3', 'class'=>'uk-checkbox' ),
  ),
),
// } table 1 row
// table 1 row {
array('header'=>'Comments',
  'valid_require'=>$required_string, 'error_require'=>'Please complete within 1000 characters.',
  'valid_maxlen'=>'q_other=1000', 'error_maxlen'=>'Please complete within 1000 characters.',
  'help'=>'Please complete within 1000 characters.',
  'data'=>array(
array( 'type'=>'textarea', 'name'=>'q_other', 'class'=>'uk-textarea ime_on', 'style'=>'width: 95%; height: 100px;', 'onKeyup'=>"var n=1000-this.value.length;var s=document.getElementById('tasp1');s.innerHTML='('+n+')';", 'placeholder'=>'Please complete within 1000 characters.', 'required'=>'' ),
array( 'input'=>'<br'.XHTML.'>'."<strong><span id='tasp1'></span></strong>".'<br'.XHTML.'>' ),
  ),
),
// } table 1 row
),),
// } 1 Group
// 1 Group reCaptcha {
array('title_captcha' => 'reCaptcha', 'table_captcha' => array(
// table 1 row reCaptcha {
array('header_captcha' => 'reCaptcha',
  'valid_captcha' => $required_string,
  'error_captcha' => $msg_spformmail_valid_captcha,
  'error_notcaptcha' => $msg_spformmail_notinstall_captcha,
  'data' => array()
),
// } table 1 row reCaptcha
),),
// } 1 Group reCaptcha
## Submit Input Screen {
array('action'=>'input',
  'data'=>array(
array( 'string'=>'<div class="uk-text-center uk-margin-top">' ),
array( 'type'=>'submit', 'name'=>'submit', 'class'=>'uk-button uk-button-primary', 'value'=>'Next' ),
array( 'string'=>'</div>' ),
  ),
),
## } Submit Input Screen
## Submit Confirmation Screen {
array('action'=>'confirm',
  'data'=>array(
array( 'string'=>'<div class="uk-text-center uk-margin-top">' ),
array( 'type'=>'submit', 'name'=>'goback', 'class'=>'uk-button', 'value'=>'Go back' ),
array( 'string'=>'　' ),
array( 'type'=>'submit', 'name'=>'submit', 'class'=>'uk-button uk-button-primary', 'value'=>'Submit' ),
array( 'string'=>'</div>' ),
  ),
),
## } Submit Confirmation Screen
);



// --[[ Functions ]]-----------------------------------------------------
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
  $buf = '<div class="uk-button-group uk-child-width-1-3@m uk-text-center uk-margin">'.LB;
  foreach ($items as $key => $value) {
    if ($action == $key) {
      $buf .= '  <button class="uk-button uk-button-secondary uk-text-nowrap" style="cursor: default">'.$value.'</button>'.LB;
    } else {
      $buf .= '  <button class="uk-button uk-button-default uk-text-nowrap" disabled>'.$value.'</button>'.LB;
    }
  }
  $buf .= '</div>'.LB;
  return $buf;
}

function _fmPutiFilter($s) {
  $se = array('%','(',')',chr(92),chr(13).chr(10),chr(13),chr(10));
  $re = array('&#37;','&#40;','&#41;','&#92;','','','');
  return str_replace($se, $re, htmlspecialchars($s,ENT_QUOTES));
}

function _fmChkUseCAPTCHA_HTML () {
  global $_RECAPTCHA_CONF;
  if ( RECAPTCHA_isEnabled() && RECAPTCHA_requireCaptcha('contact') ) {
    return true;
  }
  return false;
}
function _fmVldCAPTCHA ($type, $errmsg) {
  $msg = '';
  if (!function_exists('plugin_itemPreSave_recaptcha')) { return $msg; }
  if ( _fmChkUseCAPTCHA_HTML() ) {
    $msg = plugin_itemPreSave_recaptcha($type);
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
// Input Check {
switch ($mode) {
  // Required Check
  case 'require':
    if (empty($data['notrequire']) && empty($_POST[$name]) && $_POST[$name] != "0") { $msg = $errmsg; }
    break;
  // MatchCheck
  case 'equal':
    if (!empty($attributes)) {
      $es_emails = explode(',', $attributes);
      foreach ($es_emails as $es_email) {
        list($eq1,$eq2) = explode('=', $es_email);
        // 最初のキー かつ Checkするキーが存在
        if ($name == $eq1 && !empty($_POST[$eq2])) {
          if ($_POST[$eq1] != $_POST[$eq2]) {
            $msg = $errmsg;
          }
        }
      }
    }
    break;
  // Mail Check
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
  // Number Check - 足して0以上
  case 'notzero':
    if (!empty($attributes)) {
      $values_key = explode(',', $attributes);
      foreach ($values_key as $val_key) {
        // 最初のキーのときにCheck
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
  // Number Check
  case 'numeric':
    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !ctype_digit($_POST[$name])) { $msg = $errmsg; }
    break;
  // 電話番号かCheck
  case 'phone':
    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isPhone($_POST[$name])) { $msg = $errmsg; }
    break;
  // 文字数Check
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
  // 最低文字数Check
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
// } Input Check
    }
  }
  // reCaptchaCheck
  if ( $mode == 'captcha' ) { $msg = _fmVldCAPTCHA('contact', $errmsg); }
  return $msg;
}

function _fmValidateLines ($lines) {
  $errmsg;
  foreach (array('require','equal','email','notzero','numeric','phone','captcha','maxlen','minlen') as $chk) {
    // Neede Match Mail ,reCaptcha,Error のCheck
    if (isset($lines['valid_'.$chk])) {
      $errmsg = _fmChkValidate($chk, $lines['data'], $lines['error_'.$chk], $lines['valid_'.$chk]);
      // Errorがあれば配列に格納
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
    // 各グループ
    foreach ($item as $key => $value) {
      // 1 Group
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
      $errmsg .= '  <li>'.$err.'</li>'.LB;
    }
    $buf = <<<END

<div class="uk-alert uk-alert-danger">
<p>Some error found. Please check below and correct your input.</p>
	<ul class="uk-text-danger">
	$errmsg
	</ul>
</div>

END;
  }
  return $buf;
}


function _fmMkTitle ($title) {
  return <<<END

  <h3 class="uk-h3">$title</h3>

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

function _fmMkForm_Input ($attributes, $addclass, $hidden = false) {
  if ($hidden) {
    if ($attributes['type'] == 'radio' || $attributes['type'] == 'checkbox') {
      if ($attributes['value'] != $_POST[$attributes['name']]) return '';
    }
    $attributes['type'] = 'hidden';
  }
  if (array_key_exists('class', $attributes)) { $attributes['class'] .= $addclass; } elseif (!empty($addclass)) { $attributes['class'] = ltrim($addclass); }
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

function _fmMkForm_Select ($attributes, $addclass) {
  unset($attributes['type']);
  if (array_key_exists('class', $attributes)) { $attributes['class'] .= $addclass; } elseif (!empty($addclass)) { $attributes['class'] = ltrim($addclass); }
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

function _fmMkForm_Textarea ($attributes, $addclass) {
  unset($attributes['type']);
  $attributes['value'] = _fmMkForm_Value($attributes['name'], $attributes['value']);
  if (array_key_exists('class', $attributes)) { $attributes['class'] .= $addclass; } elseif (!empty($addclass)) { $attributes['class'] = ltrim($addclass); }
  $buf = '<textarea';
  foreach ($attributes as $key => $value) {
    if ($key != 'value') $buf .= ' '.$key.'="'.$value.'"';
  }
  $buf .= '>'.$attributes['value'].'</textarea>';
  return $buf;
}

function _fmMkForm_Item ($items, $action, $addclass) {
  $buf = '';
  unset($items['not_csv']);
  if ($action != 'input' && $items['type'] != 'submit' && $items['type'] != 'hidden') {
    $buf .= _fmMkForm_Input($items,'', true);
  } else {
    switch ($items['type']) {
      case 'text': $buf .= _fmMkForm_Input($items, $addclass); break;
      case 'password': $buf .= _fmMkForm_Input($items, $addclass); break;
      case 'hidden': $buf .= _fmMkForm_Input($items, $addclass, true); break;
      case 'radio': $buf .= _fmMkForm_Input($items, $addclass); break;
      case 'checkbox': $buf .= _fmMkForm_Input($items, $addclass); break;
      case 'select': $buf .= _fmMkForm_Select($items, $addclass); break;
      case 'textarea': $buf .= _fmMkForm_Textarea($items, $addclass); break;
      case 'submit': $buf .= _fmMkForm_Input($items, $addclass); break;
      case 'reset': $buf .= _fmMkForm_Input($items, $addclass); break;
      case 'button': $buf .= _fmMkForm_Input($items, $addclass); break;
    }
  }
  return $buf;
}

function _fmMkTable_Data ($datas, $action, $addclass='') {
  $buf = '';
  foreach ($datas as $data) {
    // １つのデータ
    if (!empty($data['type'])) {
      // フォーム
      $buf .= _fmMkForm_Item($data, $action, $addclass);
    }
    else {
      // 文字列
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
  global $_RECAPTCHA_CONF;
  $captcha = '';
  if (!function_exists('plugin_itemPreSave_recaptcha')) { return $msg_notcaptcha; }
  if ( _fmChkUseCAPTCHA_HTML() ) {
    $lang = RECAPTCHA_getLangCode();
    $captcha = '<div class="g-recaptcha" data-sitekey="'
      . RECAPTCHA_esc($_RECAPTCHA_CONF['public_key']) . '"></div>'
      . '<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl='
      . RECAPTCHA_esc($lang) . '" async defer></script>';
  }
  return $captcha;
}

function _fmMkTable ($tables, $action) {
  $buf = '';
  foreach ($tables as $lines) {
    $flg_valid_captcha=false;
    $errflg = '';
    $textclass=''; $formclass=''; $formid=''; $labelfor='';
    // table 1 rowのErrorCheck
    if (!empty($_POST) && !empty($_POST['action'])) { $errflg = _fmValidateLines($lines); }
    if ($errflg) { $textclass=' uk-text-danger'; $formclass=' uk-form-danger'; }
    for ($i=0; $i<count($lines['data']); $i++) {
      if (isset($lines['data'][$i]['name']) && !isset($lines['data'][$i]['id'])) { $lines['data'][$i]['id'] = $lines['data'][$i]['name']; }
      if (isset($lines['data'][$i]['id'])) { if(isset($lines['data'][$i]['type']) && !in_array($lines['data'][$i]['type'],array('radio','checkbox'))) { $formid=$lines['data'][$i]['id'];break; } }
    }
    if (!empty($formid)) { $labelfor=' for="'.$formid.'"'; }
    $buf .= LB;
    $buf .= '  <div class="uk-margin">'.LB;
    $buf .= '    <label class="uk-form-label'.$textclass.'"'.$labelfor.'>';
    if (isset($lines['header'])) { $buf .= $lines['header']; }
    if (isset($lines['header_captcha'])) { $buf .= $lines['header_captcha']; }
    if (isset($lines['valid_require'])) { $buf .= $lines['valid_require']; }
    if (isset($lines['valid_captcha'])) { $buf .= $lines['valid_captcha']; $flg_valid_captcha=true; }
    $buf .= '</label>'.LB;
    $buf .= '    <div class="uk-form-controls">'.LB;
    if (isset($lines['data'])) {
      if ($flg_valid_captcha) {
        $buf .= _fmMkCAPTCHA_HTML('contact',$lines['error_notcaptcha']);
      } else {
        $buf .= _fmMkTable_Data($lines['data'], $action, $formclass);
      }
    }

    if (isset($lines['help']) && $action == 'input') { $buf .= ' <div class="uk-text-warning uk-text-small"> '.$lines['help'].'</div>'; }

    $buf .= LB.'    </div>'.LB;
    $buf .= '  </div>'.LB;
  }
  return $buf;
}

function _fmMkForm ($items, $action) {
  global $_fmtokenttl;
  $ttl = (isset($_fmtokenttl) && $_fmtokenttl > 1) ? $_fmtokenttl : 1800;
  $buf = '';
  foreach ($items as $item) {
    // 各グループ
    if (!empty($item['table'])) {
      foreach ($item as $key => $value) {
        // 1 Group
        switch ($key) {
          case 'title': $buf .= _fmMkTitle($value); break;
          case 'table': $buf .= _fmMkTable($value, $action); break;
        }
      }
      $buf .= "".LB;

    } elseif (!empty($item['table_captcha'])) {  //reCaptcha Table
      if ((!empty($action) && $action == 'input') && _fmChkUseCAPTCHA_HTML()) {
        foreach ($item as $key => $value) {
          // １ Table
          switch ($key) {
            case 'title_captcha': $buf .= _fmMkTitle($value); break;
            case 'table_captcha': $buf .= _fmMkTable($value, $action); break;
          }
        }
        $buf .= "".LB;
      }
    } elseif (!empty($item['action'])) {         //Send ボタン
      if ($item['action'] == $action) {
        $buf .= LB . '  <input type="hidden" name="action" value="' . $action . '"' . XHTML . '>';
        $buf .= LB . _fmMkTable_Data($item['data'], $action);
      }
    }
  }
  if (!empty($buf) && ($action=='input' || $action=='confirm')) { $buf.=LB.'  <input type="hidden" name="_glsectoken" value="'.SEC_createToken($ttl).'"'.XHTML.'>'; }
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
    if (!empty($_POST)) { $msg = '<p class="uk-text-danger">REFERERCheckが設定されていますが環境変数にREFERERがセットされていないためCheckできません。サイトAdministrator にご連絡ください。</p>'; }
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



// --[[ 初期処理 ]]---------------------------------------------------
# POSTデータを直接変換 (全角から半角へ、カタカナ半角からカタカナ全角へ)
if (!empty($zentohan_itemname)) { foreach (explode(',',$zentohan_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'askh'); } }
if (!empty($kana_hantozen_itemname)) { foreach (explode(',',$kana_hantozen_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'K'); } }
if (!empty($kana_hiratokana_itemname)) { foreach (explode(',',$kana_hiratokana_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'C'); } }
# データを保存用に加工
foreach ($_POST as $k => $v) {
  #以下の記号は大文字にして保存
  $fld_list[$k] = preg_replace('/,/', '，', $_POST[$k]);
  $fld_list[$k] = preg_replace('/"/', '”', $fld_list[$k]);
  $fld_list[$k] = preg_replace("/'/", "’", $fld_list[$k]);
  $fld_list[$k] = preg_replace('/`/', '‘', $fld_list[$k]);
  $fld_list[$k] = preg_replace('/;/', '；', $fld_list[$k]);
  $fld_list[$k] = preg_replace(preg_quote('#'.chr(92).'#'), '￥', $fld_list[$k]);
  $fld_list[$k] = COM_applyFilter($fld_list[$k]);
}
# CSV File のフルパス
$save_csv_file = $save_csv_path . $save_csv_name;
# idからurlを作成
if (!empty($page)) { $pageurl = COM_buildUrl($_CONF['site_url'].'/staticpages/index.php?page='.$page); }
# CSRF
if (!empty($_POST) && !SECINT_checkToken()) { $m=isset($_POST[$email_input_name]) ? 'email='.$_POST[$email_input_name].' ' : ''; COM_accessLog("tried {$m}to staticpage({$pageid}) failed CSRF checks."); header('Location: '.$pageurl); exit; }


// RefererCheck
$_spflg_ref_err=false;
if (!empty($_spreferercheck) && $_spreferercheck == 1) {
  $valid = _fmChkReferer($pageurl,$_spreferererrormsg);
  if (!empty($valid)) { $_spflg_ref_err=true; }
}

// ErrorCheck
if (empty($valid) && !empty($_POST) && !empty($_POST['action'])) {
  $valid = _fmValidate($form_items);
}
$action = _fmGetAction($valid);



// --[[ 第1ステップ : フォーム表示(Input ＆確認) ]]---------------------
if ($action == 'input' || $action == 'confirm') {
/**
* Form HTML { ここから
*/
  // 遷移
  $seni = _fmMkSeni($seni_items, $action);
  // Input フォーム
  $form = _fmMkForm($form_items, $action);
  if ($_spflg_ref_err) { $form=''; COM_accessLog("REFERER Error in staticpage({$page}) - Referring: {$_SERVER['HTTP_REFERER']}"); }

  $retval = <<<END

$seni
<div id="$page" class="uk-margin-left uk-margin-right">
$valid
<form name="subForm" class="uk-form-horizontal uk-margin-large" method="post" action="{$pageurl}">
$form
</form>
</div>

END;

/**
* } Form HTML
*/



// --[[ 第2ステップ : 完了表示＆Mail Send  ]]-------------------------
} elseif ($action == 'finish') {
/**
* 完了画面HTML { ここから
*/
  // 遷移
  $seni = _fmMkSeni($seni_items, $action);

  $out_html = <<<END

<div class="uk-hidden-small">
$seni
</div>
<div>
<p><strong>We\'ve received your inquiry.</strong></p>
<p>A confirmation e-mail has been sent to you.<br />
If you do not receive it, please re-submit your inquiry with this form.</p>
</div>

END;
/**
* } 完了画面HTML
*/



  # <br /> を改行コードに変換
  $pat = '/<br[[:space:]]*'.chr(92).'/?[[:space:]]*>/i';
  foreach ($fld_list as $k => $v) { $fld_list[$k] = preg_replace($pat, LB, $fld_list[$k]); }
  $lang['sign_admin'] = preg_replace($pat, LB, $lang['sign_admin']);
  $lang['sign_user'] = preg_replace($pat, LB, $lang['sign_user']);
  // Input 内容
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
* Send Mail 内容 - Administrator  { ここから
*/
  $out_mail_admin = <<<END

{$lang['receipt_admin']}

$input4mail

{$lang['sign_admin']}
END;
/**
* } Send Mail 内容 - Administrator 
*/
/**
* Send Mail 内容 - Input 者 { ここから
*/
  $out_mail_user = <<<END

{$lang['receipt_user']}

$input4mail

{$lang['sign_user']}
END;
/**
* } Send Mail - Cutomer
*/

  # Mail Send 前にcsvを出力
  # ->Send ErrorでもCSVで確認できるように対応
  if ($save_csv > 0) {
    $fldnames = _fmMkCsv($form_items);
    $delimiter = ',';
    if ($save_csv > 1) { $delimiter = chr(9); }
    $enclosure = '"';
    # CSV出力
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
    $str = date($date_csv) . $delimiter . $_SERVER['REMOTE_ADDR'] . $delimiter . substr($str,0,-1);
    $str .= LB;
    if( !empty( $save_csv_lang ) ) { $str = mb_convert_encoding($str, $save_csv_lang); }
    $fp = fopen($save_csv_file, 'a');
    fwrite($fp, $str);  # CSV書き出し
    fclose($fp);
  }

  # Mail Send 
  $ownererr = false;
  $ownersend = false;
  $om_array = explode(',', $owner_email);
  $owner_mails = array_unique($om_array);  # Remove double
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
    $email1 = COM_mail( $v, "$owner_subject", $out_mail_admin, $email_from, false); # Administrator Mail 
    if (!$email1) { $ownererr = true; } else { $ownersend = true; }  # Set Send Error flag
  }
  # Administrator Mail Send でErrorがあったCase 
  if ($ownererr) {
    # 一部にSend できているCase 
    if ($ownersend) {
      # Output Error log
      COM_errorLog($lang['ownertransmiterror'], 1);
      $email1 = true;
    # all users error
    } elseif (!$ownersend) {
      # Error not send user
      $email1 = false;
    }
  }
  if ($email1) {
    $usr_subject = $lang['subject_user'];
    $email2 = COM_mail( $fld_list[$email_input_name], "$usr_subject", $out_mail_user, $email_from, false); # to customer 
  }
  if ($email1 && $email2) { # both ok
    $retval = $out_html;
  } else {
    $retval = $lang['transmiterror']; # Mail error
    #Mail ErrorをCSVにexport
    if ($save_csv > 0) {
      $str = date($date_csv) . $delimiter . $lang['transmiterror'];
      $str .= LB;
      if( !empty( $save_csv_lang ) ) { $str = mb_convert_encoding($str, $save_csv_lang,"auto"); }
      $fp = fopen($save_csv_file, 'a');
      fwrite($fp, $str);  # Oytput CSV
      fclose($fp);
    }
  }
}
echo $retval;
