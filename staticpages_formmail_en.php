// +---------------------------------------------------------------------------+
// | FormMail Static Page for Geeklog 2.1 higher for UIkit
// +---------------------------------------------------------------------------+
// | Copyright (C) 2008-2015 by the following authors:
// | Authors    : Hiroshi Sakuramoto - hiro AT winkey DOT jp
// | Version: 2.1.10
// +---------------------------------------------------------------------------+
global $_CONF,$_USER,$_PLUGINS,$_SCRIPTS,$page; // Geeklog変数
global $_fmtokenttl; // FormMail変数
if (!defined('XHTML')) define('XHTML', ' /');

// --[[ 初期設定 ]]------------------------------------------------------------
# 問合せを管理者へ通知の設定
#    複数のE-mailはカンマ(,)で区切りで指定する(スペース等はあけない)
#      例) 'info@hoge.com,admin@page.com'
#    特定の入力項目に応じて送り先を変える
#    ※この方法を利用する時は必ず $owner_email_item_name を指定してください。
#      例) 'AAA=info@hoge.com,BBB=admin@page.com'
$owner_email=$_CONF['site_mail'];

# 管理者Emailを入力項目から選択する項目名
#   (selectなどの選択でメールの送り先を変えるのに利用)
//  ※送り先を変える指定をしたら先頭の#を削除してください。(コメントをはずします)
#$owner_email_item_name = 'q_mail_to';

# メール送信者E-mail
$email_from = $_CONF['site_mail'];
#Geeklog1.5から，noreplyを指定できます。
#$email_from = $_CONF['noreply_mail'];

# 問合せ者のメールアドレスの項目名
$email_input_name = 'q_mail';

# メール一致チェック項目指定
#   メール確認でどちらも同じものを入力 というname属性を(=)で区切る(スペース等はあけない)
#     例) 'email=reemail'
$essential_email = 'q_mail=q_mail_re';

# メールアドレスチェック項目指定
#   入力された値がメールアドレスとして正しいかチェックをする
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
#     例) 'email,reemail'
$propriety_email = 'q_mail,q_mail_re';

# CSRF対策のTokenの有効時間(秒)
$_fmtokenttl = 1800;
# Refererチェック (CSRF対策)  チェックしない:0 チェックする:1
$_spreferercheck = 1;
# Refererエラーのメッセージ
$_spreferererrormsg = '<p class="uk-text-danger">アクセスできません。サイト管理者にご連絡ください。</p>';


# ログイン済みならユーザ情報を利用
#   Geeklogユーザー名やメールアドレスを利用
$username = ''; $user_email = '';
if (!COM_isAnonUser()) {
    $username = isset($_USER['fullname']) ? $_USER['fullname'] : $_USER['username'];
    $user_email = $_USER['email'];
}

# CSVファイルに保存
#   指定方法 保存しない: 0 , 保存する(カンマ区切り): 1 , 保存する(タブ区切り): 2
$save_csv = 1;

# CSVファイル保存場所 (直接入力時は最後にスラッシュ必須)
$save_csv_path = $_CONF['path_data'];

# CSVファイル名
$save_csv_name = $page.'.csv';

# CSVファイル保存の文字コード
#   文字コード変換をしない場合は '' と指定してください。
#   機能がOFFになります。（文字化けするようなら機能を''で
#   OFFにして別途フリーの文字変換ツールなどをご利用ください）
# 注意) mb_convert_encodingで使える文字コードを指定してください
#   例) UTF-8, SJIS, EUC-JP, JIS, ASCII
$save_csv_lang = 'UTF-8';

# 全角を半角に自動変換する項目名(英数字、スペース、カタカナ、ひらがな)
#   入力された値を自動で変換する項目を指定
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
$zentohan_itemname = 'q_phone,q_code1_1,q_code2_1,q_code3_1,q_code1_2,q_code2_2,q_code3_2,q_code1_3,q_code2_3,q_code3_3';

# カタカナの半角をカタカナの全角に自動変換する項目名
#   入力された値を自動で変換する項目を指定
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
$kana_hantozen_itemname = 'q_kana_1,q_kana_2';

# ひらがなをカタカナに自動変換する項目名
#   入力された値を自動で変換する項目を指定
#   INPUTタグの name属性の値をカンマ(,)区切りで指定する(スペース等はあけない)
$kana_hiratokana_itemname = 'q_kana_1,q_kana_2';

# 遷移の項目名
$seni_items = array('input' => '情報入力', 'confirm' => '入力項目確認', 'finish' => '入力完了');

# 必須入力の文字列
$required_string = '<span class="uk-text-warning">*</span>';

# ==画像認証関係==
#   画像認証(CAPTCHA)がインストールされていない場合のエラーメッセージ
$msg_spformmail_notinstall_captcha = '';

#   送信時に画像認証でエラーの場合のエラーメッセージ
#     ※空文字にするとCAPTCHAプラグインが作成するエラーメッセージを使います。
#     ※空文字意外にするとそれを無視して固定メッセージにできます。
$msg_spformmail_valid_captcha = '';

#   ※ CAPTCHAのテンプレート
#   private/plugins/captcha/templates/captcha_contact.thtml
#

# ==日付関係==
#   JavaScriptカレンダーでの日付表記
#     phpのdate参照 http://php.net/manual/ja/function.date.php
#       day   => 'd,D,j,l,N,S,w,z'
#       month => 'F,m,M,n,t'
#       year  => 'Y,y'
#   ※テンプレート layout/theme/vendor/uikit/js/components/datepicker.js

#   メールに記載される受付日時表記
#     phpのdateのものがすべて使えます http://www.php.net/manual/en/function.date.php
$date_mail = 'Y年m月d日H:i';
#   csv書き出し時、1列目に記載される日時表記
#     phpのdateのものがすべて使えます http://www.php.net/manual/en/function.date.php
$date_csv = 'Y/m/d H:i';


#####
# 表示メッセージ
#####
$lang = array(
// { 完了HTML＆メールのメッセージ
  'receipt_admin' =>'管理者のみなさま'.LB.LB.$_CONF['site_name'].'サイトにおいて'.LB.'問い合わせがありました。'.LB.LB.'==========お問い合わせ =========='.LB.'受付日時：'.date($date_mail),
  'receipt_user' =>'※本メールは、'.$_CONF['site_name'].'サイトより自動的に配信しています。'.LB.'このメールは送信専用のため、このメールにご返信いただけません。'.LB.'＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝'.LB.'お問い合わせありがとうございました。'.LB.LB.'========== お問い合わせ内容 =========='.LB.'受付日時：'.date($date_mail),
  'subject_admin'=> '['.$_CONF['site_name'].']お問い合わせ',
  'subject_user'=> '['.$_CONF['site_name'].']お問い合わせを受け付けました',
  'sign_admin'    => '-----------------------------------------'.LB.$_CONF['site_name'].LB.$_CONF['site_url'].LB.'-----------------------------------------',
  'sign_user'    => '-----------------------------------------'.LB.$_CONF['site_name'].LB.'URL：' . $_CONF['site_url'].LB.'-----------------------------------------',
// } 完了HTML＆メールのメッセージ
// { システムエラーのメッセージ
  'ownertransmiterror'=>'管理者向けメール処理中に一部のメールでエラーが発生しましたが、処理は継続しました。',
  'transmiterror'=>'処理中にエラーが発生しました。',
// } システムエラーのメッセージ
);



#####
# フォーム項目の設定
#####
$form_items = array(
// 1グループ {
array('title'=>'お客様情報', 'table'=>array(
// 1行 {
array('header'=>'法人様名',
  'valid_notkanahan'=>'q_organization', 'error_notkanahan'=>'法人様名に半角カタカナがあります。すべて全角で入力してください',
  'help'=>'法人様名を入力してください。',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_organization', 'size'=>'40', 'maxlength'=>'60', 'class'=>'ime_on', 'placeholder'=>'全角で入力してください。' ),
  ),
),
// } 1行
// 1行 {
array('header'=>'お名前（漢字）',
  'valid_require'=>$required_string, 'error_require'=>'お名前（漢字）が入力されていません',
  'valid_notkanahan'=>'q_name', 'error_notkanahan'=>'お名前（漢字）に半角カタカナがあります。すべて全角で入力してください',
  'help'=>'全角で名前を入力してください。',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_name', 'size'=>'40', 'maxlength'=>'40', 'aria-required'=>'true', 'class'=>'ime_on', 'value'=>$username, 'placeholder'=>'全角で入力してください。' ),
  ),
),
// } 1行
// 1行 {
array('header'=>'お名前（カタカナ）',
  'valid_require'=>$required_string, 'error_require'=>'お名前（カタカナ）が入力されていません',
  'valid_notkanahan'=>'q_kana', 'error_notkanahan'=>'お名前（カタカナ）に半角カタカナがあります。すべて全角で入力してください',
  'help'=>'全角カタカナでお名前（カタカナ）を入力してください。',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_kana', 'size'=>'40', 'maxlength'=>'40', 'aria-required'=>'true', 'class'=>'ime_on', 'placeholder'=>'全角で入力してください。' ),
  ),
),
// } 1行
// 1行 {
array('header'=>'メールアドレス',
  'valid_require'=>$required_string, 'error_require'=>'メールアドレスが入力されていません',
  'valid_equal'=>$essential_email, 'error_equal'=>'メールアドレスが一致しません',
  'valid_email'=>$propriety_email, 'error_email'=>'メールアドレスを正しく入力してください',
  'valid_hankaku'=>'q_mail,q_mail_re', 'error_hankaku'=>'メールアドレスはすべて半角で入力してください',
  'help'=>'半角でメールアドレスを入力してください。',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_mail', 'size'=>'40', 'maxlength'=>'240', 'aria-required'=>'true', 'class'=>'uk-margin-small-bottom ime_off', 'value'=>$user_email ),
array( 'input'=>'<br'.XHTML.'>' ),
array( 'type'=>'text', 'name'=>'q_mail_re', 'size'=>'40', 'maxlength'=>'240', 'aria-required'=>'true', 'class'=>'ime_off', 'not_confirm'=>'true', 'not_csv'=>'true', 'placeholder'=>'確認たのめ、もう一度入力してください。' ),
  ),
),
// } 1行
// 1行 {
array('header'=>'ご連絡方法',
  'help'=>'ご連絡方法を選んでください。',
  'data'=>array(
array( 'type'=>'radio', 'name'=>'q_answer_means', 'value'=>'メール', 'checked'=>'checked' ),
array( 'input'=>'メール ' ),
array( 'type'=>'radio', 'name'=>'q_answer_means', 'value'=>'電話' ),
array( 'input'=>'電話 ' ),
array( 'string'=>'<br'.XHTML.'>' ),
array( 'input'=>'※お問い合わせ内容によって、メールをご希望の場合も電話連絡とさせて頂く場合があります。' ),
  ),
),
// } 1行
// 1行 {
array('header'=>'電話番号',
  'valid_require'=>$required_string, 'error_require'=>'電話番号が入力されていません',
  'valid_phone'=>'q_phone', 'error_phone'=>'電話番号を正しく入力してください。数字と+(プラス)と-(ハイフン)と (半角スペース)が使えます',
  'valid_minlen'=>'q_phone=6', 'error_minlen'=>'電話番号の文字数は6文字以上で入力してください',
  'valid_maxlen'=>'q_phone=13', 'error_maxlen'=>'電話番号の文字数は13文字以内で入力してください',
  'help'=>'半角数字と＋（プラス）と－（ハイフン）と半角スペースで電話番号を入力してください。',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_phone', 'size'=>'20', 'maxlength'=>'13', 'aria-required'=>'true', 'class'=>'ime_off' ),
array( 'string'=>'<br'.XHTML.'>' ),
array( 'input'=>'※半角（例&nbsp;0311112222）<br'.XHTML.'>' ),
array( 'type'=>'radio', 'name'=>'q_phone_kind', 'value'=>'自宅', 'checked'=>'checked' ),
array( 'input'=>'自宅 &nbsp; ' ),
array( 'type'=>'radio', 'name'=>'q_phone_kind', 'value'=>'勤務先' ),
array( 'input'=>'勤務先 &nbsp; ' ),
array( 'type'=>'radio', 'name'=>'q_phone_kind', 'value'=>'携帯' ),
array( 'input'=>'携帯' ),
  ),
),
// } 1行
// 1行 {
array('header'=>'希望日',
  'help'=>'ご連絡希望日を選んでください。',
  'data'=>array(
array( 'type'=>'text', 'name'=>'q_date1', 'size'=>'20', 'data-uk-datepicker'=>"{format:'YYYY.MM.DD'}" ),
  ),
),
// } 1行
// 1行 {
array('header' => '時間帯',
  'help'=>'ご連絡時間帯を選んでください。',
  'data'=>array(
array( 'type'=>'select', 'name'=>'q_access_time', 'style'=>'width: 15em;', 'options'=>array('selected' => '特に希望なし', 'values' => '特に希望なし,午前,午後   - 夕方まで,夕方以降') ),
array( 'input'=>'<br'.XHTML.'>※電話連絡の場合のご連絡を希望する時間帯。' ),
  ),
),
// } 1行
),),
// } 1グループ
// 1グループ {
array('title'=>'申し込み内容', 'table'=>array(
// 1行 {
array('header'=>'お申し込みセミナー',
  'help'=>'セミナーを選んでください。',
  'data'=>array(
array( 'type'=>'checkbox', 'name'=>'q_order_1', 'value'=>'セミナー１' ),
array( 'input'=>' ' ),
array( 'type'=>'checkbox', 'name'=>'q_order_2', 'value'=>'セミナー２' ),
array( 'input'=>' ' ),
array( 'type'=>'checkbox', 'name'=>'q_order_3', 'value'=>'セミナー３' ),
  ),
),
// } 1行
// 1行 {
array('header'=>'お問い合わせ内容',
  'valid_notkanahan'=>'q_other', 'error_notkanahan'=>'お問い合わせ内容に半角カタカナがあります。すべて全角で入力してください',
  'valid_maxlen'=>'q_other=500', 'error_maxlen'=>'お問い合わせ内容の文字数は500文字以内で入力してください',
  'help'=>'全角500文字以内でお問い合わせを入力してください。',
  'data'=>array(
array( 'type'=>'textarea', 'name'=>'q_other', 'class'=>'ime_on', 'style'=>'width: 95%; height: 100px;', 'onKeyup'=>"var n=500-this.value.length;var s=document.getElementById('tasp1');s.innerHTML='('+n+')';", 'placeholder'=>'お問い合わせ内容を入力してください。' ),
array( 'input'=>'<br'.XHTML.'>'."<strong><span id='tasp1'></span></strong>".'<br'.XHTML.'>' ),
  ),
),
// } 1行
),),
// } 1グループ
// 1グループ 画像認証 {
array('title_captcha' => '', 'table_captcha' => array(
// 1行 画像認証 {
array('header_captcha' => '',
  'valid_captcha' => '',
  'error_captcha' => $msg_spformmail_valid_captcha,
  'error_notcaptcha' => $msg_spformmail_notinstall_captcha,
  'data' => array()
),
// } 1行 画像認証
),),
// } 1グループ 画像認証
## submit 入力画面 {
array('action'=>'input',
  'data'=>array(
array( 'string'=>'<div class="uk-text-center uk-margin-top">' ),
array( 'type'=>'submit', 'name'=>'submit', 'class'=>'uk-button', 'value'=>'入力項目確認画面へ' ),
array( 'string'=>'</div>' ),
  ),
),
## } submit 入力画面
## submit 確認画面 {
array('action'=>'confirm',
  'data'=>array(
array( 'string'=>'<div class="uk-text-center uk-margin-top">' ),
array( 'type'=>'submit', 'name'=>'goback', 'class'=>'uk-button', 'value'=>'戻る' ),
array( 'string'=>'　' ),
array( 'type'=>'submit', 'name'=>'submit', 'class'=>'uk-button', 'value'=>'送信する' ),
array( 'string'=>'</div>' ),
  ),
),
## } submit 確認画面
);



// --[[ 関数群 ]]---------------------------------------------------------------
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
  $buf = '<ul class="uk-grid uk-margin">'.LB;
  foreach ($items as $key => $value) {
    if ($action == $key) {
      $buf .= '  <li class="uk-panel uk-panel-box uk-panel-box-primary uk-width-1-4 uk-margin-left">'.$value.'</li>'.LB;
    } else {
      $buf .= '  <li class="uk-panel uk-panel-box uk-width-1-4 uk-margin-left">'.$value.'</li>'.LB;
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
// 入力チェック {
switch ($mode) {
  // 必須チェック
  case 'require':
    if (empty($data['notrequire']) && empty($_POST[$name]) && $_POST[$name] != "0") { $msg = $errmsg; }
    break;
  // 一致チェック
  case 'equal':
    if (!empty($attributes)) {
      $es_emails = explode(',', $attributes);
      foreach ($es_emails as $es_email) {
        list($eq1,$eq2) = explode('=', $es_email);
        // 最初のキー かつ チェックするキーが存在
        if ($name == $eq1 && !empty($_POST[$eq2])) {
          if ($_POST[$eq1] != $_POST[$eq2]) {
            $msg = $errmsg;
          }
        }
      }
    }
    break;
  // メールチェック
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
  // 数値チェック - 足して0以上
  case 'notzero':
    if (!empty($attributes)) {
      $values_key = explode(',', $attributes);
      foreach ($values_key as $val_key) {
        // 最初のキーのときにチェック
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
  // 数値のみかチェック
  case 'numeric':
    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !ctype_digit($_POST[$name])) { $msg = $errmsg; }
    break;
  // 電話番号かチェック
  case 'phone':
    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isPhone($_POST[$name])) { $msg = $errmsg; }
    break;
  // 半角チェック
  case 'hankaku':
    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isHankaku($_POST[$name])) { $msg = $errmsg; }
    break;
  // 全角チェック
  case 'zenkaku':
    if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !_fmVld_isZenkaku($_POST[$name])) { $msg = $errmsg; }
    break;
  // 半角英数字チェック
  case 'eisuhan':
    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && !_fmVld_isEisuHan($_POST[$name])) { $msg = $errmsg; }
    break;
  // 全角カタカナチェック
  case 'kanazen':
    if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !_fmVld_isKanaZen($_POST[$name])) { $msg = $errmsg; }
    break;
  // 全角ひらがなチェック
  case 'hirazen':
    if (!empty($_POST[$name]) && in_array($name,explode(',',$attributes)) && !_fmVld_isHiraZen($_POST[$name])) { $msg = $errmsg; }
    break;
  // 半角カタカナ以外かチェック
  case 'notkanahan':
    if ((!empty($_POST[$name]) || $_POST[$name] == "0") && in_array($name,explode(',',$attributes)) && _fmVld_isNotKanaHan($_POST[$name])) { $msg = $errmsg; }
    break;
  // 文字数チェック
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
  // 最低文字数チェック
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
// } 入力チェック
    }
  }
  // 画像認証チェック
  if ( $mode == 'captcha' ) { $msg = _fmVldCAPTCHA('contact', $errmsg); }
  return $msg;
}

function _fmValidateLines ($lines) {
  $errmsg;
  foreach (array('require','equal','email','notzero','numeric','phone','hankaku','zenkaku','eisuhan','kanazen','hirazen','notkanahan','captcha','maxlen','minlen') as $chk) {
    // 必須,一致,メール,画像認証,エラー のチェック
    if (isset($lines['valid_'.$chk])) {
      $errmsg = _fmChkValidate($chk, $lines['data'], $lines['error_'.$chk], $lines['valid_'.$chk]);
      // エラーがあれば配列に格納
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
      // 1グループ
      if ($key == 'table' || $key == 'table_captcha') {
        $action = _fmGetAction('');
        if ($key == 'table_captcha' && $action == 'finish') { continue; }
        foreach ($value as $key2 => $value2) {
          // 1行
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
<p>入力エラーがありました。下記について再度ご確認の上、ご記入ください。</p>
<ol class="uk-text-danger">
$errmsg
</ol>
</div>

END;
  }
  return $buf;
}


function _fmMkTitle ($title) {
  return <<<END

  <h3 class="uk-h3">$title</h3>
  <dl class="uk-description-list-horizontal">
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
      case 'hidden': $buf .= _fmMkForm_Input($items); break;
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
  $buf = '';
  foreach ($tables as $lines) {
    $flg_valid_captcha=false;
    $errflg = '';
    $textclass=''; $formclass='';
    // エラーチェック
    if (!empty($_POST) && !empty($_POST['action'])) { $errflg = _fmValidateLines($lines); }
    if ($errflg) { $textclass=' uk-text-danger'; $formclass=' uk-form-danger'; }
    $buf .= LB;
    $buf .= '    <dt class="uk-margin-top'.$tdclass.'">';
    if (isset($lines['header'])) { $buf .= $lines['header']; }
    if (isset($lines['header_captcha'])) { $buf .= $lines['header_captcha']; }
    if (isset($lines['valid_require'])) { $buf .= $lines['valid_require']; }
    if (isset($lines['valid_captcha'])) { $buf .= $lines['valid_captcha']; $flg_valid_captcha=true; }
    if (isset($lines['help']) && $action == 'input') { $buf .= ' (<span data-uk-tooltip title="'.$lines['help'].'">?</span>)'; }
    $buf .= '</dt>'.LB;
    $buf .= '    <dd class="uk-margin-top' . $textclass . '">';
    if (isset($lines['data'])) {
      if ($flg_valid_captcha) {
        $buf .= _fmMkCAPTCHA_HTML('contact',$lines['error_notcaptcha']);
      } else {
        $buf .= _fmMkTable_Data($lines['data'], $action, $formclass);
      }
    }
    $buf .= '</dd>'.LB;
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
        // 1グループ
        switch ($key) {
          case 'title': $buf .= _fmMkTitle($value); break;
          case 'table': $buf .= _fmMkTable($value, $action); break;
        }
      }
      $buf .= <<<END

    </dl>
END;
    } elseif (!empty($item['table_captcha'])) {  //画像認証テーブル
      if ((!empty($action) && $action == 'input') && _fmChkUseCAPTCHA_HTML()) {
        foreach ($item as $key => $value) {
          // １テーブル
          switch ($key) {
            case 'title_captcha': $buf .= _fmMkTitle($value); break;
            case 'table_captcha': $buf .= _fmMkTable($value, $action); break;
          }
        }
        $buf .= <<<END

    </dl>
END;
      }
    } elseif (!empty($item['action'])) {         //送信ボタン
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
    if (!empty($_POST)) { $msg = '<p class="uk-text-danger">REFERERチェックが設定されていますが環境変数にREFERERがセットされていないためチェックできません。サイト管理者にご連絡ください。</p>'; }
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



// --[[ 初期処理 ]]------------------------------------------------------------
# POSTデータを直接変換 (全角から半角へ、カタカナ半角からカタカナ全角へ)
if (!empty($zentohan_itemname)) { foreach (explode(',',$zentohan_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'askh'); } }
if (!empty($kana_hantozen_itemname)) { foreach (explode(',',$kana_hantozen_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'K'); } }
if (!empty($kana_hiratokana_itemname)) { foreach (explode(',',$kana_hiratokana_itemname) as $k) { if (!empty($_POST[$k])) $_POST[$k] = mb_convert_kana($_POST[$k], 'C'); } }
# データを保存用に加工
foreach ($_POST as $k => $v) {
    $fld_list[$k] = preg_replace('/,/', '，', $_POST[$k]);
    $fld_list[$k] = preg_replace('/"/', '”', $fld_list[$k]);
    $fld_list[$k] = preg_replace("/'/", "’", $fld_list[$k]);
    $fld_list[$k] = preg_replace('/`/', '‘', $fld_list[$k]);
    $fld_list[$k] = preg_replace('/;/', '；', $fld_list[$k]);
    $fld_list[$k] = preg_replace(preg_quote('#'.chr(92).'#'), '￥', $fld_list[$k]);
    $fld_list[$k] = COM_applyFilter($fld_list[$k]);
}
# CSVファイルのフルパス
$save_csv_file = $save_csv_path . $save_csv_name;
# idからurlを作成
if (!empty($page)) { $pageurl = COM_buildUrl($_CONF['site_url'].'/staticpages/index.php?page='.$page); }
# CSRF
if (!empty($_POST) && !SECINT_checkToken()) { $m=isset($_POST[$email_input_name]) ? 'email='.$_POST[$email_input_name].' ' : ''; COM_accessLog("tried {$m}to staticpage({$pageid}) failed CSRF checks."); header('Location: '.$pageurl); exit; }


// Refererチェック
if (!empty($_spreferercheck) && $_spreferercheck = 1) {
  $valid = _fmChkReferer($pageurl,$_spreferererrormsg);
}

// エラーチェック
if (empty($valid) && !empty($_POST) && !empty($_POST['action'])) {
  $valid = _fmValidate($form_items);
}
$action = _fmGetAction($valid);



// --[[ 第1ステップ : フォーム表示(入力＆確認) ]]-------------------------------
if ($action == 'input' || $action == 'confirm') {
/**
* フォーム画面HTML { ここから
*/
  // 遷移
  $seni = _fmMkSeni($seni_items, $action);
  // 入力フォーム
  $form = _fmMkForm($form_items, $action);

  $retval = <<<END

<div data-uk-button-checkbox>
$seni
</div>
<div>
$valid
<form name="subForm" class="uk-form uk-form-stacked" method="post" action="{$pageurl}">
<div class="uk-form-row">
$form
</div>
</form>
</div>

END;

/**
* } ここまで フォーム画面HTML
*/



// --[[ 第2ステップ : 完了表示＆メール送信 ]]-----------------------------------
} elseif ($action == 'finish') {
/**
* 完了画面HTML { ここから
*/
  // 遷移
  $seni = _fmMkSeni($seni_items, $action);

  $out_html = <<<END

<div data-uk-button-checkbox>
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
* } ここまで 完了画面HTML
*/



  # <br /> を改行コードに変換
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
* 送信メール内容 - 管理者 { ここから
*/
  $out_mail_admin = <<<END

{$lang['receipt_admin']}

$input4mail

{$lang['sign_admin']}
END;
/**
* } ここまで 送信メール内容 - 管理者
*/
/**
* 送信メール内容 - 入力者 { ここから
*/
  $out_mail_user = <<<END

{$lang['receipt_user']}

$input4mail

{$lang['sign_user']}
END;
/**
* } ここまで 送信メール内容 - 入力者
*/


  # メール送信
  $ownererr = false;
  $ownersend = false;
  $om_array = explode(',', $owner_email);
  $owner_mails = array_unique($om_array);  # 重複した値(メールアドレス)を削除
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
    $email1 = COM_mail( $v, "$owner_subject", $out_mail_admin, $email_from, false); # 管理者あてメール
    if (!$email1) { $ownererr = true; } else { $ownersend = true; }  # 送信/エラーのフラグをセット
  }
  # 管理者メール送信でエラーがあった場合
  if ($ownererr) {
    # 一部に送信できている場合
    if ($ownersend) {
      # エラーをログへ出力(一部へは配送されているのでユーザにエラー画面を出さない)
      COM_errorLog($lang['ownertransmiterror'], 1);
      $email1 = true;
    # 全員がエラーの場合
    } elseif (!$ownersend) {
      # 処理エラーとし、ユーザへのメールは送らない
      $email1 = false;
    }
  }
  if ($email1) {
    $usr_subject = $lang['subject_user'];
    $email2 = COM_mail( $fld_list[$email_input_name], "$usr_subject", $out_mail_user, $email_from, false); # 問合せ者へメール
  }
  if ($email1 && $email2) { # どちらの送信も成功したら
    # csv出力する
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
      $str = date($date_csv) . $delimiter . substr($str,0,-1);
      $str .= LB;
	  if( !empty( $save_csv_lang ) ) { $str = mb_convert_encoding($str, $save_csv_lang,"auto"); 
      $fp = fopen($save_csv_file, 'a');
      fwrite($fp, $str);  # CSV書き出し
      fclose($fp);
    }
    $retval = $out_html;
  } else {
    $retval = $lang['transmiterror']; # メール送信が失敗したら
  }
}
// 「PHPを実行」の場合
echo $retval;
// 「PHPを実行(return)」 の場合、上のechoをコメント(#)にして以下のreturnのコメントをはずしてください
# return $retval;
