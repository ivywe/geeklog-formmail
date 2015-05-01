staticpages_formmail.php version: 2.1.4
EngVersion: 1.0
Created: 2010/08/24
Author: Hiroshi Sakuramoto - hiro AT winkey DOT jp
translator: Takashi Kobayashi - geeklog.crimsonj.net

Thanks for downloading. This is a formmail for Geeklog staticpage php.

This program was originally made for Japanese users. Several parts are thereforefor Japanese users only. We commented those parts out in this English version. 
For the purpose of future simultaneous upgrade for both of Japanese & English verion, those parts for Japanese users are remained in php files. Wish English users understand this and ignore those parts when you use this program.


<QUICK INSTALL>
If you already have a pemission of staticpages.PHP, please read from 3.

1. Login your geeklog site as admin and click 'gruop' of admin menu.
2. Edit 'static page admin' and check 'staticpages.PHP' to make it enable.

3. Go to 'staticpage' of admin menu.
4. Click 'Creat New' and make a new staticpage with id:formmail. Copy staticpages_formmail.php and paste it in textarea. Uncheck 'In a block' box and save with 'execute php' option.
5. upload /images directory and files in it by FTP at public_html/images/.
6. Copy custom.css.txt and past it at the end of style.css. You should do it with custom.cssinstead if you are using professionalCSS theme.
*Please copy to a proper css file in accordance with a theme you adopted.
*Basically, a css file to be rendered at last should be modified with this texts.
7. If you use CAPTCHA with a graphic package besides GD library, make yourtheme/sp_formmail directory and copy add_captcha.thtml and save as captcha.thtml in the directory.
 ex. professional theme
  make new directly of plublic_html/layout/professional/sp_formmail/.
  copy add_captcha.thtml and name it as captcha.thtml and upload the above directly.

8. if you adopt calendar date input form, upload /add_to_calendar directory & files in it to public_html/javascript/ directory.
9. try to access yoursite/staticpages/index.php?page=formmail

<setting text field max length>
add 'maxlength' in item setting. recommend to add 'valid_maxlen' as input error check as well.
ex.==================================
array('type' => 'text',
'name' => 'q_name_1',
'size' => '40',
'maxlength' => '40', <--HERE
'class' => 'bginput',
'value' => $username
),
=====================================

<input check items>
valid_require => '*' : check if blank not allowed item. You can change '*' string if necessary.
valid_equal => $essentia_email : check if equal with another colum => "email address double check"
valid_email => $propriety_email : check if email address => "email address check item"
valid_notzero => 'item name' : check if assigned item's sum is not zero (to be used such as hotel room reservation's guest numbers).
valid_numeric => 'item name' : check if numeric
valid_phone => 'item name' : check if proper phone number.
valid_maxlen => 'item name=max length' : check if text is in max length
valid_minlen => 'item name=min length' : check if text exceeds min length
valid_captcha => '*' : check for captcha

<text field max length>
You have 2 options in accordance to your needs!

I. Twitter like view with JavaScript to display allowed length remained
========================================
array('type' => 'textarea',
'name' => 'q_other',
'class' => 'bginput',
'style' => 'width: 95%; height: 100px;',
'onKeyup' => "var n=200-this.value.length;var s=document.getElementById('tasp1');s.innerHTML='('+n+')';" <--displays allowed length remained here. max=200. 'tasp1' is span id to display.
),
array('input' => '<br'.XHTML.">*input your inquiry here<strong><span id='tasp1'></span></strong><br".XHTML.'>'), <--displays in this span tag. id should be changed properly in case use this function at two or more textarea.
========================================

II. check posted contents and display error message if if exceeds the max lengthset
========================================
array('header' => 'your inquiry',
'valid_len' => 'q_other=200',
'error_len' => 'Please complete your inquiry within 200 characters',
'data' =>
========================================

<Inputting date by JavaScript calendar>

//This uses calendar library by:
// Calendar RC4, (c) 2007 Aeron Glemann, MIT Style License
// http://www.electricprism.com/aeron/calendar/

I. default setting
Followings are default settings for using JavaScript and CSS to enable your favourable settings or to use other JavaScript library.

set TRUE for $use_jslib to enable JavaScript library. In case you don't, set FALSE.
========================================
# use of JavaScript library for calendar
$use_jslib = TRUE;
========================================

set calendar JavaScript as follows.
"q_date1" matches with the name of II.exmaple. Make sure to change both of them if you want to change it.
========================================
# declaration of JavaScript library etc
# JavaScript+CSS for calendar display
# Calendar RC4, (c) 2007 Aeron Glemann <http://electricprism.com/aeron>, MIT Style License
# (http://www.electricprism.com/aeron/calendar/)
$def_jslib = <<<JSLIB

<script type="text/javascript" src="{$_CONF['site_url']}/javascript/calendar/mootools.js"></script>
<script type="text/javascript" src="{$_CONF['site_url']}/javascript/calendar/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="{$_CONF['site_url']}/javascript/calendar/calendar.css" media="screen" />
<script type="text/javascript">
window.addEvent('domready', function() { myCal = new Calendar({ q_date1: 'Y/m/d/' }, { days: ['SUN','MON','TUE','WED','TUE','FRI','SAT'], months: ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'] }); });
</script>
JSLIB;
========================================


II. example
set CSS 'calendar' in class to enable date input by calendar.
set float:left; at style to make input form and calendar icon in line.
name=>q_date1 matches with 'declaration of JavaScript library etc' in I.default setting mentioned above. Make sure to change both of them if you want to change it.
========================================
// < table 1 raw
array('header' => 'request date', 'data' =>
array(
array('type' => 'text',
'name' => 'q_date1',
'id' => 'q_date1',
'size' => '20',
'maxlength' => '10',
'class' => 'bginput calendar', <--describe calendar here
'style' => 'float:left;' <--describe float:left to put calendar icon right side of input form
),
),
),
// > table 1 raw
========================================


<select email receipient by selected strings>
For example at travel agent's website, you can manage email receipient depending on city name the user selected.
-set $owner_email like blow.
 $owner_email='Tokyo=tokyo@geeksite.com,Paris=paris@geeksite.com,HongKong=hongkong@geeksite.com';
-set item name to distribute email receipient, value of name.
 $owner_email_item_name = 'q_city';
-form should be like below.
===========================================================================
array('type' => 'select',
                'name' => 'q_city',
                'style' => 'width: 10em;',
                'class' => 'bginput',
                'options' => array('selected' => 'please select', 'values' => 'please select="",Tokyo,Paris,HongKong'),
            ),
===========================================================================

<set initial value by adding argument on URL>
example: http://yoursite.com/staticpages/index.php?'variables'='strings'
add the following set at subject array
'value' => $_GET['variable']




<history>
2010/08/23  2.1.4
*implemented a XSS counter measure at form action.
2010/07/14  2.1.3
*Fixed bug of javascript calender no display when this formmail used in MediaGallery1.6.0WKZ.
*CSS compiled
*color of image changed
*added valid_phone for telephone number check
*issued English version (thanks kobab, ivy)
*made default form sample's name area common for both of English & Japanese
*added input tag for item setting;hidden, reset, password and button
*changed directory name from add_to_calendar to add_to_javascript for better usage.
2010/05/28 2.1.2
*fixed bug of date calendar no display when media gallery is used.
2010/05/27 2.1.1
*added a feature to use calendar for date input
*fixeded bug for numeric check
*added a text minimum length check
2010/05/07 2.1.0 presented by IvyWe
*added IME On/off mode for Japanese character input
*added sample of max length setting at default form
*added input check of numeric and max number. For Japanese users, check for hankaku, zenkau, hankaku-eisu, zenkaku-katakana, zenkaku-hiragana, non-hankaku-katakana were added as well. Those are not provided at English version.
*implemented XSS coutermeasures
*added sample of Twitter like view of text field displaying allowed max length remained
2010/04/19 2.0.8
*fixed sample CSS to avoid abnormal view at adopting CAPTCHA
2010/04/12 2.0.7
*implemented a feature to set full name at name item if fullname found at login user name set
*removed 'echo' at the end of staticpages_formmail.php file to workable for 1.6.x or later.
*fixed bug that CAPTCHA item colum alone displayed for login users.
2010/03/22 2.0.6
*implemented a feature to use user name and email address for login users
2009/10/11 2.0.5
*fixed typo of $_CP_CONFIG instead of $_CP_CONF
2009/03/28 2.0.4
*fixed problems in case meta plugin enabled
2009.03.26 2.0.3
*fixed errors at Japanese mobile phones.
2009/03/06 2.0.2
*add features not to ouput CSV, ie 'not_csv'
*fixed CSV colum error by check box on/off
2009/02/23 2.0.1
*added a feature not display double check items, ie 'not_confirm'
2009/02/12 2.0.0
*implemented a feature to enable CAPTCHA
