<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');

global $jLang, $mosConfig_absolute_path, $mosConfig_live_site;

require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/utils/compat.php');

$jLang = array();
//Info Dash
$jLang['_COM_NO_USER'] = 'You are not currently part of the jTips competitions. Please contact the administrator to apply';
$jLang['_COM_DASH_TITLE'] = 'Info Dash';
$jLang['_COM_DASH_ROUND'] = 'Round';
$jLang['_COM_DASH_SCORE'] = 'Score';
$jLang['_COM_DASH_AVERAGE'] = 'Average';
$jLang['_COM_DASH_PROJECTED'] = 'Projected';
$jLang['_COM_DASH_DOUBLEUP'] = 'DoubleUP';
$jLang['_COM_DASH_PAID'] = 'Paid';
$jLang['_COM_TIP_LADDER_TITLE'] = 'Tipping Ladder';
$jLang['_COM_TIP_LADDER_USER'] = 'User';
$jLang['_COM_TIP_LADDER_LAST'] = 'Last';
$jLang['_COM_TIP_LADDER_SCORE'] = 'Score';
$jLang['_COM_TIP_LADDER_VIEW_PROFILE'] = 'View Profile';
$jLang['_COM_MYTIPS_TITLE'] = 'My Tips This Week';
$jLang['_COM_MYTIPS_UNAVAILABLE'] = 'Next round unavailable';
$jLang['_COM_MYTIPS_NOTIPS'] = 'Nothing submitted';
$jLang['_COM_MYTIPS_SUBMIT'] = 'Submit Tips';
$jLang['_COM_MYTIPS_TIPS_PANEL'] = 'Tips Panel';
$jLang['_COM_TEAMS_TITLE'] = 'Team Ladder';
$jLang['_COM_TEAMS_UNAVAILABLE'] = 'Ladder unavailable';

//1.0 Additions to Dashboard
$jLang['_COM_DASH_CURR_ROUND'] = 'Current Round';
$jLang['_COM_DASH_TOT_ROUND'] = 'Total Rounds';
$jLang['_COM_DASH_GPER_ROUND'] = 'Games Per Round';
$jLang['_COM_DASH_START'] = 'Start';
$jLang['_COM_DASH_END'] = 'End';
$jLang['_COM_DASH_SEASON'] = 'Season';
$jLang['_COM_DASH_RANK'] = 'Rank';
$jLang['_COM_DASH_LAST_WON'] = 'Last Round Won By';
$jLang['_COM_DASH_LAST_ROUND'] = 'Last Round';
$jLang['_COM_DASH_LAST_TOT'] = 'Total';
$jLang['_COM_DASH_LAST_COMM'] = 'Comment';
$jLang['_COM_DASH_NO_ROUNDS'] = 'No Rounds Played';
$jLang['_COM_DASH_PAYPAL_ALT'] = 'Pay Now with PayPal';
$jLang['_COM_DASH_TIPPED'] = 'Tipped';
$jLang['_COM_DASH_NOT_TIPPED'] = 'Not Tipped';
$jLang['_COM_DASH_ITALICS_DEF'] = 'Italics indicate a draw has been selected';
$jLang['_COM_DASH_JOIN_COMP'] = 'Join The Competition';
$jLang['_COM_DASH_JOIN_PEND'] = 'Pending Approval';
$jLang['_COM_DASH_REG_REQ'] = 'Please register to be part of the competition';
$jLang['_COM_DASH_REG_DENY'] = 'Registrations are closed for this competition';
$jLang['_COM_DASH_NOSEASONS'] = 'NO SEASONS FOUND!';
$jLang['_COM_DASH_PRECISION'] = 'Precision';
$jLang['_COM_DASH_LAST_PREC'] = 'Last Precision';
$jLang['_COM_DASH_LAST_PRECT'] = 'Precision';
$jLang['_COM_DASH_GAMES_TIPPED'] = 'Games Tipped';
$jLang['_COM_DASH_OVERLIB_DRAW'] = 'Draw Selected';
$jLang['_COM_DASH_SUMMARY'] = 'My Score Summary';
$jLang['_COM_DASH_TO_BEAT'] = 'to defeat';
$jLang['_COM_DASH_TO_DRAW'] = 'to draw with';
$jLang['_COM_DASH_COMMENT'] = 'Comment';
$jLang['_COM_DASH_USER'] = 'User';
$jLang['_COM_DASH_POINTS'] = 'Score';
$jLang['_COM_DASH_PREC'] = 'Precision';
$jLang['_COM_DASH_POINTST'] = 'Total Score';
$jLang['_COM_DASH_PRECT'] = 'Total Precision';
$jLang['_COM_DASH_NO_SEASONS'] = 'No Competitions Available';
$jLang['_COM_DASH_MOVED'] = '';

//Team Ladder
$jLang['_COM_TLD_TEAM'] = 'Team';
$jLang['_COM_TLD_POINTS_SHORT'] = 'Points';
$jLang['_COM_TLD_ABR_PLAYED'] = 'P';
$jLang['_COM_TLD_PLAYED'] = 'Played';
$jLang['_COM_TLD_ABR_WINS'] = 'W';
$jLang['_COM_TLD_WINS'] = 'Wins';
$jLang['_COM_TLD_ABR_DRAWS'] = 'D';
$jLang['_COM_TLD_DRAWS'] = 'Draws';
$jLang['_COM_TLD_ABR_LOSSES'] = 'L';
$jLang['_COM_TLD_LOSSES'] = 'Losses';
$jLang['_COM_TLD_ABR_POINTS_FOR'] = 'F';
$jLang['_COM_TLD_POINTS_FOR'] = 'For (Total points scored)';
$jLang['_COM_TLD_ABR_POINTS_AGAINST'] = 'A';
$jLang['_COM_TLD_POINTS_AGAINST'] = 'Against (Total points conceeded)';
$jLang['_COM_TLD_ABR_FOR_AGAINST'] = 'FA';
$jLang['_COM_TLD_FOR_AGAINST'] = 'The difference between For and Against';
$jLang['_COM_TLD_ABR_POINTS'] = 'T';
$jLang['_COM_TLD_POINTS'] = 'Current team standing (Total season points)';
$jLang['_COM_TLD_LEGEND'] = 'Legend';


//Tips Panel
$jLang['_COM_TIPS_PANEL'] = 'Tips Panel';
$jLang['_COM_PREV_ROUND'] = 'Prev';
$jLang['_COM_NEXT_ROUND'] = 'Next';
$jLang['_COM_CLOSE_TIME'] = 'Closes at';
$jLang['_COM_CLOSE_DATE'] = 'on';
$jLang['_COM_GAME_HOME'] = 'Home';
$jLang['_COM_GAME_AWAY'] = 'Away';
$jLang['_COM_GAME_DRAW'] = 'Draw';
$jLang['_COM_GAME_USEDOUBLE'] = 'Use DoubleUP';
$jLang['_COM_ROUND_CLOSED'] = 'Tipping for this round is closed';
$jLang['_COM_ROUND_NOGAMES'] = 'No games found for this round for this Season';

//1.0 Additions to Tips Panel
$jLang['_COM_TIPS_HOMESCORE'] = 'Home Score';
$jLang['_COM_TIPS_AWAYSCORE'] = 'Away Score';
$jLang['_COM_TIPS_MARGIN'] = 'Margin';
$jLang['_COM_TIPS_BONUS'] = 'Bonus';
$jLang['_COM_TIPS_SAVE'] = 'Save Tips';
$jLang['_COM_TIPS_SHOWHIDE'] = 'Show Team Ladder';
$jLang['_COM_TIPS_TEAMLAD'] = 'Team Ladder';
$jLang['_COM_TIPS_NEVER'] = 'Never';
$jLang['_COM_TIPS_LASTUP'] = 'Last Updated';

//1.0 New - Competition Ladder
$jLang['_COM_COMP_LADAT'] = 'Ladder at Round';
$jLang['_COM_COM_OF'] = 'of';
$jLang['_COM_COMP_OVERALL'] = 'Overall Leaderboard';
$jLang['_COM_COMP_RANK'] = 'Rank';
$jLang['_COM_COMP_NAME'] = 'Name';
$jLang['_COM_COMP_LASTR'] = 'Last Round';
$jLang['_COM_COMP_TOTAL'] = 'Total';
$jLang['_COM_COMP_ROUNDSEL'] = 'Select a Season Round';
$jLang['_COM_COMP_PRECISION'] = 'Precision';
$jLang['_COM_TIPS_RESULT'] = 'Result';

//Configuration
$jLang['_ADMIN_CONF_TITLE'] = 'jTips Configuration';
$jLang['_ADMIN_CONF_SETTING'] = 'Setting';
$jLang['_ADMIN_CONF_VARIABLE'] = 'Variable';
$jLang['_ADMIN_CONF_DESCRIPTION'] = 'Description';
$jLang['_ADMIN_CONF_GPR'] = 'Max Games pre Round';
$jLang['_ADMIN_CONF_GPR_DEF'] = 'The maximum number of games likely to occur for the season';
$jLang['_ADMIN_CONF_RPS'] = 'Rounds For Season';
$jLang['_ADMIN_CONF_RPS_DEF'] = 'The number of rounds for the season';
$jLang['_ADMIN_CONF_BP'] = 'Bonus Points';
$jLang['_ADMIN_CONF_BP_DEF'] = 'If a user scores a perfect round (the maximum possible points), you can award them bonus points';
$jLang['_ADMIN_CONF_CT'] = 'Correct Tip';
$jLang['_ADMIN_CONF_CT_DEF'] = 'The number of points awarded for a correct tip';
$jLang['_ADMIN_CONF_MT'] = 'Missing Tips';
$jLang['_ADMIN_CONF_MT_DEF'] = 'The number of points given to users that do not submit tips. Set as -1 to have these users receive the equivalent to the user that did tips and scores the lowest';
$jLang['_ADMIN_CONF_TD'] = 'Tipable Draw';
$jLang['_ADMIN_CONF_TD_DEF'] = 'Users are able to predict the result of a game to be a draw';
$jLang['_ADMIN_CONF_DB'] = 'Draw Bonus';
$jLang['_ADMIN_CONF_DB_DEF'] = 'The number of points awarded to users that predicted a draw';
$jLang['_ADMIN_SEASON_TWP'] = 'Team Win Points';
$jLang['_ADMIN_SEASON_TWP_DEF'] = 'The number of points awarded to the winning team';
$jLang['_ADMIN_SEASON_TDP'] = 'Team Draw Points';
$jLang['_ADMIN_SEASON_TDP_DEF'] = 'The number of points awarded to a team that draws';
$jLang['_ADMIN_SEASON_TLP'] = 'Team Lose Points';
$jLang['_ADMIN_SEASON_TLP_DEF'] = 'The number of points awarded to the losing team';
$jLang['_ADMIN_SEASON_TBP'] = 'Team Bye Points';
$jLang['_ADMIN_SEASON_TBP_DEF'] = 'The number of points awarded to a team that has a bye';
$jLang['_ADMIN_SEASON_EPTB'] = "Enable 'Pick the Bonus'";
$jLang['_ADMIN_SEASON_EPTB_DEF'] = 'Allow users to pick wich team(s) will be awarded the bonus point';
$jLang['_ADMIN_SEASON_EPTD'] = "Enable 'Pick the Draw'";
$jLang['_ADMIN_SEASON_EPTD_DEF'] = 'Allow user to pick a draw as the result of the game';
$jLang['_ADMIN_SEASON_EPTB_DIS'] = 'Disable';
$jLang['_ADMIN_SEASON_EPTB_SIN'] = 'Single Team';
$jLang['_ADMIN_SEASON_EPTB_BOT'] = 'Both Teams';
$jLang['_ADMIN_SEASON_BONUS_TEAM'] = 'Bonus Point Value for Teams';
$jLang['_ADMIN_SEASON_EPTS'] = "Enable 'Pick the Score'";
$jLang['_ADMIN_SEASON_EPTM'] = "Enable 'Pick the Margin'";
$jLang['_ADMIN_SEASON_UCORR'] = 'User Tips Correct Points';
$jLang['_ADMIN_SEASON_UDRAW'] = 'User Tips Draw Points';
$jLang['_ADMIN_SEASON_UNONE'] = 'User Does Not Tip Points';
$jLang['_ADMIN_SEASON_UPERF'] = 'User Tips Perfect Round Points';
$jLang['_ADMIN_SEASON_USCOR'] = 'User Picks Correct Score Points';
$jLang['_ADMIN_SEASON_UMARG'] = 'User Picks Correct Margin Points';
$jLang['_ADMIN_SEASON_UBONU'] = 'User Picks Correct Bonus Team Points';
$jLang['_ADMIN_SEASON_UPGRADE'] = 'Click here to upgrade to jTips 1.0 Ultimate today to use multiple seasons/competitions!';
$jLang['_ADMIN_SEASON_NAMEONLY'] = 'Team Names Only';
$jLang['_ADMIN_SEASON_LOGOONLY'] = 'Team Logos Only';
$jLang['_ADMIN_SEASON_NAMELOGO'] = 'Team Names &amp; Logos';
$jLang['_ADMIN_SEASON_TIPSDISPLAY'] = 'Team Display on Submit Tips';
$jLang['_ADMIN_SEASON_LINKURL'] = 'Image Link URL';
$jLang['_ADMIN_SEASON_LOGO_PATH'] = 'Season Logo';
$jLang['_ADMIN_SEASON_MANAGER'] = 'Season Manager';
$jLang['_ADMIN_GAME_BONUS'] = 'Bonus Point';
$jLang['_ADMIN_CONF_EP'] = 'Enable Payments';
$jLang['_ADMIN_CONF_EP_DEF'] = 'Enable payments for tipping competition';
$jLang['_ADMIN_CONF_DN'] = 'Display Name';
$jLang['_ADMIN_CONF_DN_DEF'] = 'Display full names or usernames in competition ladder';
$jLang['_ADMIN_CONF_CBI'] = 'CB Integration';
$jLang['_ADMIN_CONF_CBI_DEF'] = 'Link to Community Builder user profiles from competition ladder';
$jLang['_ADMIN_CONF_SNS'] = 'Start New Season';
$jLang['_ADMIN_CONF_SNS_DEF'] = 'Empties all rounds and game information and resets users, ready for a new season';
$jLang['_ADMIN_CONF_UPDATE'] = 'Update database';
$jLang['_ADMIN_CONF_UPDATE_DEF'] = 'Update database tables for use with the latest jTips version';

//1.0 Additions
$jLang['_ADMIN_CONF_UPGRADE'] = 'Click here to Upgrade!';
$jLang['_ADMIN_CONF_TAB_GENERAL'] = 'General';
$jLang['_ADMIN_CONF_TAB_DISPLAY'] = 'Display';
$jLang['_ADMIN_CONF_TAB_SCORING'] = 'Scoring';
$jLang['_ADMIN_CONF_TAB_PAYPAL'] = 'PayPal';
$jLang['_ADMIN_CONF_TAB_NOTIFY'] = 'Notifications';
$jLang['_ADMIN_CONF_TAB_IMPORT'] = 'Import';
$jLang['_ADMIN_CONF_TAB_EXPORT'] = 'Export';
$jLang['_ADMIN_CONF_TAB_ACTIVATE'] = 'Activate';
$jLang['_ADMIN_CONF_LOCATION'] = 'Joomla! Location';
$jLang['_ADMIN_CONF_LOCATION_DEF'] = 'If Joomla! is not installed in the root directory, please enter the directory here';
$jLang['_ADMIN_CONF_ALLOWREG'] = 'Allow Registrations';
$jLang['_ADMIN_CONF_ALLOWREG_DEG'] = 'Allow users to join the competition. If unchecked, administrators will have to added each user to the competition';
$jLang['_ADMIN_CONF_AUTOAPP'] = 'Auto-Approve Registrations';
$jLang['_ADMIN_CONF_AUTOAPP_DEF'] = 'Users will automatically be added to the competition when they apply to join';
$jLang['_ADMIN_CONF_DOUBLE'] = 'Enable DoubleUP';
$jLang['_ADMIN_CONF_DOUBLE_DEF'] = 'Users may choose to double the points they score for one round in a season';
$jLang['_ADMIN_CONF_MARGIN'] = 'Allow Margin Tipping';
$jLang['_ADMIN_CONF_MARGIN_DEF'] = 'Allow users to pick the margins (applied per correct game)';
$jLang['_ADMIN_CONF_PICKSC'] = "Allow 'Pick the Score' Tipping";
$jLang['_ADMIN_CONF_PICKSC_DEF'] = 'Allow users to pick the exact scores (applied per correct game)';
$jLang['_ADMIN_CONF_PAYOPT_MAN'] = 'Manual';
$jLang['_ADMIN_CONF_NEW_CONF'] = 'Are you sure you wish to start a new season?\\nThis will remove all season game data and reset team scores!';
$jLang['_ADMIN_CONF_SNS_BTN'] = 'Go!';
$jLang['_ADMIN_CONF_COM_TITLE'] = 'Component Title';
$jLang['_ADMIN_CONF_COM_TITLE_DEF'] = 'The title to display at the top of each page - only applies in Joomla! 1.0. For Joomla! 1.5, please set the page title when configuring the menu item(s).';
$jLang['_ADMIN_CONF_LOGO'] = 'Competition Logo';
$jLang['_ADMIN_CONF_LOGO_DEF'] = 'Logo for the competition. Appears at top of all pages';
$jLang['_ADMIN_CONF_LOGO_POS'] = 'Logo Position';
$jLang['_ADMIN_CONF_LOGO_LEFT'] = 'Left';
$jLang['_ADMIN_CONF_LOGO_RIGHT'] = 'Right';
$jLang['_ADMIN_CONF_LOGO_CENTRE'] = 'Centre';
$jLang['_ADMIN_CONF_LOGO_POS_DEF'] = 'Position for the competition logo';
$jLang['_ADMIN_CONF_LOGO_RM'] = 'Remove Competition Logo';
$jLang['_ADMIN_CONF_LOGO_RM_DEF'] = 'Remove the current competition logo';
$jLang['_ADMIN_CONF_LOGO_LINK'] = 'Logo Link';
$jLang['_ADMIN_CONF_LOGO_LINK_DEF'] = 'URL that the competition logo links to (include http://) - opens in a new window/tab';
$jLang['_ADMIN_CONF_DASH_TITLE'] = 'Dashboard Title';
$jLang['_ADMIN_CONF_TIPS_TITLE'] = 'Tips Panel Title';
$jLang['_ADMIN_CONF_COMP_TITLE'] = 'Competition Ladder Title';
$jLang['_ADMIN_CONF_TEAM_TITLE'] = 'Team Ladder Title';
$jLang['_ADMIN_CONF_CENT'] = 'Centre Position';
$jLang['_ADMIN_CONF_LEFT'] = 'Left Position';
$jLang['_ADMIN_CONF_RIHT'] = 'Right Position';
$jLang['_ADMIN_CONF_MOD_COMP'] = 'Competition Ladder';
$jLang['_ADMIN_CONF_MOD_TEAM'] = 'Team Ladder';
$jLang['_ADMIN_CONF_MOD_TIPS'] = 'Tips Summary';
$jLang['_ADMIN_CONF_DATEF'] = 'Date Format';
$jLang['_ADMIN_CONF_DATEF_DEF'] = 'The date format used in jTips. Available formats can be found here: http://au.php.net/manual/en/function.strftime.php';
$jLang['_ADMIN_CONF_TIMEF'] = 'Time Format';
$jLang['_ADMIN_CONF_CUSTLAYOUT'] = 'Allow Custom Layout';
$jLang['_ADMIN_CONF_CUSTLAYOUT_DEF'] = 'Allow users to modify the layout of the dashboard';
$jLang['_ADMIN_CONF_LADSTYLE'] = 'Visual Effect Style';
$jLang['_ADMIN_CONF_LADSTYLE_DEF'] = 'Select the style for the visual effects used in jTips.';
$jLang['_ADMIN_CONF_SHOWLAST'] = 'Show Last Round Winner';
$jLang['_ADMIN_CONF_SHOWLAST_DEF'] = 'Show the user(s) that scored the highest from the previous round on the home page';
$jLang['_ADMIN_CONF_SHOWLAST_SEAS'] = 'Show Last Round Winner (Per Season)';
$jLang['_ADMIN_CONF_SHOWLAST_SEAS_DEF'] = 'Show the last round winners for the selected season only. If left unchecked, last round winners from all season will be displayed';
$jLang['_ADMIN_CONF_SHOWDEF'] = 'Default Users to Show';
$jLang['_ADMIN_CONF_SHOWDEF_DEF'] = 'The number of users to show by default in the competition ladder';
$jLang['_ADMIN_CONF_SHOWMAX'] = 'Max Users to Show';
$jLang['_ADMIN_CONF_SHOWMAX_DEF'] = 'The maximum number of users to show by per page in the competition ladder';
$jLang['_ADMIN_CONF_NAMER'] = 'Real Name';
$jLang['_ADMIN_CONF_NAMEU'] = 'Username';
$jLang['_ADMIN_CONF_MARGIN_SCORE'] = 'Correct Margin Bonus';
$jLang['_ADMIN_CONF_MARGIN_SCORE_DEF'] = 'The number of bonus points awarded to users that pick the correct margin for a game';
$jLang['_ADMIN_CONF_PICKSC_SCORE'] = "Correct 'Pick the Score' Bonus";
$jLang['_ADMIN_CONF_PICKSC_SCORE_DEF'] = 'The number of bonus points awarded to users that pick the correct scores for a game (applied per correct game)';
$jLang['_ADMIN_CONF_PAYPALACC'] = 'Your PayPal Account Email';
$jLang['_ADMIN_CONF_PAYPALITEM'] = 'Name of Item';
$jLang['_ADMIN_CONF_PAYPALITEM_DEF'] = 'Example: jTips Registration';
$jLang['_ADMIN_CONF_PAYPALCURR'] = 'Currency';
$jLang['_ADMIN_CONF_PAYPALCURR_DEF'] = 'Examples: AUD, USD, GBP';
$jLang['_ADMIN_CONF_PAYPALAMNT'] = 'Amount';
$jLang['_ADMIN_CONF_PAYPALAMNT_DEF'] = 'Examples: 20.00 (Do not include any currency symbols!)';
$jLang['_ADMIN_CONF_PAYPALBTN'] = 'Button Image';
$jLang['_ADMIN_CONF_PAYPALBTN_DEF'] = "Default: <img src='https://www.paypal.com/en_US/i/btn/x-click-but6.gif' align='absmiddle' />";
$jLang['_ADMIN_CONF_PAYPALTEST'] = 'Enable Testing for PayPal';
$jLang['_ADMIN_CONF_PAYPALTEST_DEF'] = '';
$jLang['_ADMIN_CONF_PAYPALTYPE'] = 'Use PayPal Subscriptions?';
$jLang['_ADMIN_CONF_PAYPALTYPE_DEF'] = 'If you are using PayPal Subscriptions, enter the unsubcribe code given to you by PayPal in the UnSubscribe Code field.';
$jLang['_ADMIN_CONF_PAYPAL'] = 'PayPal Code:';
$jLang['_ADMIN_CONF_PAYPALUNSUB'] = 'PayPal UnSubscribe Code:';
$jLang['_ADMIN_CONF_PAYPALUNSUB_DEF'] = 'The HTML code provided by PayPal to UnSubscribe - only required if the above code corresponds to a subscription in your PayPal account';
$jLang['_ADMIN_CONF_EXP_HIST'] = 'Export History';
$jLang['_ADMIN_CONF_EXP_TYPE'] = 'Select Data Type';
$jLang['_ADMIN_CONF_EXP_TYPE_ERR'] = 'Select a Data Type first';
$jLang['_ADMIN_CONF_IMP_FILE'] = 'Select File';
$jLang['_ADMIN_CONF_IMP_PENDING'] = 'Pending Import';
$jLang['_ADMIN_CONF_IMP_INTO'] = 'Import Into';
$jLang['_ADMIN_CONF_IMP_MATCH'] = 'Match Up The Fields';
$jLang['_ADMIN_CONF_ACT_STATUS'] = 'Activation Status';
$jLang['_ADMIN_CONF_ACT_DONE'] = 'Activated';
$jLang['_ADMIN_CONF_JTIP_VER'] = 'jTips Version';
$jLang['_ADMIN_CONF_ACT_EMAIL'] = 'Activation Email Address';
$jLang['_ADMIN_CONF_ACT_KEY'] = 'Acitvation Key';
$jLang['_ADMIN_CONF_EXPBTN'] = 'Export Data';
$jLang['_ADMIN_CONF_EXPERR'] = 'Nothing Selected';
$jLang['_ADMIN_CONF_NOTIFY_UOR'] = 'Notify User On Registration';
$jLang['_ADMIN_CONF_NOTIFY_UOR_DEF'] = 'Tick this box to enable email notifications when a user registers for the competition';
$jLang['_ADMIN_CONF_NOTOFY_FROMN'] = 'From Name';
$jLang['_ADMIN_CONF_NOTIFY_FROMN_DEF'] = 'The name the email will come from';
$jLang['_ADMIN_CONF_NOTIFY_FROMA'] = 'From Address';
$jLang['_ADMIN_CONF_NOTIFY_FROMA_DEF'] = 'The email address the email will come from';
$jLang['_ADMIN_CONF_NOTIFY_SUBJ'] = 'Message Subject';
$jLang['_ADMIN_CONF_NOTIFY_SUBJU_DEF'] = 'The subject of the notification email to be sent to users';
$jLang['_ADMIN_CONF_NOTIFY_MSG'] = 'Message Body';
$jLang['_ADMIN_CONF_NOTIFY_MSGU_DEF'] = 'The body of the notification email to be sent to users<br />You may add replaceable values that will get replaced with details of the registering user. Valid placeholders are:<ul><li>{name}</li><li>{username}</li><li>{email}</li><li>{competition}</li></ul>';
$jLang['_ADMIN_CONF_NOTIFY_AOR'] = 'Notify Admin On Registration';
$jLang['_ADMIN_CONF_NOTIFY_TO'] = 'To Address';
$jLang['_ADMIN_CONF_NOTIFY_TO_DEF'] = 'The address the email will be sent to';
$jLang['_ADMIN_CONF_NOTIFY_SUBJA_DEF'] = 'The subject of the notification email to be sent to admins';
$jLang['_ADMIN_CONF_NOTIFY_MSGA_DEF'] = 'The body of the notification email to be sent to admins.<br />You may add replaceable values that will get replaced with details of the registering user. Valid placeholders are:<ul><li>{name}</li><li>{username}</li><li>{email}</li><li>{competition}</li></ul>';
$jLang['_ADMIN_NOTIFY_APPSUB'] = 'Approval Message Subject';
$jLang['_ADMIN_NOTIFY_APPSUB_DEF'] = 'The subject of the notification email to be sent to users when they are manually approved from the list of users';
$jLang['_ADMIN_NOTIFY_APPMSG'] = 'Approval Message Body';
$jLang['_ADMIN_NOTIFY_APPMSG_DEF'] = 'The body of the notification email to be sent to users when they are manually approved from the list of users.<br />You may add replaceable values that will get replaced with details of the registering user. Valid placeholders are:<ul><li>{name}</li><li>{username}</li><li>{email}</li><li>{competition}</li></ul>';
$jLang['_ADMIN_NOTIFY_UAPPSUB'] = 'UnApproval Message Subject';
$jLang['_ADMIN_NOTIFY_UAPPSUB_DEF'] = 'The subject of the notification email to be sent to users when they are manually unapproved from the list of users';
$jLang['_ADMIN_NOTIFY_UAPPMSG'] = 'UnApproval Message Body';
$jLang['_ADMIN_NOTIFY_UAPPMSG_DEF'] = 'The body of the notification email to be sent to users when they are manually unapproved from the list of users.<br />You may add replaceable values that will get replaced with details of the registering user. Valid placeholders are:<ul><li>{name}</li><li>{username}</li><li>{email}</li><li>{competition}</li></ul>';
$jLang['_ADMIN_CONF_RES_DIS'] = 'Registration Disabled Message';
$jLang['_ADMIN_CONF_REF_DIS_DEF'] = 'Message to display if registrations are disabled';
$jLang['_ADMIN_CONF_JS_ROLL'] = 'Roll Up/Down';
$jLang['_ADMIN_CONF_JS_SLIDE'] = 'Slide Up/Down';
$jLang['_ADMIN_CONF_JS_FADE'] = 'Fade In/Out';
$jLang['_ADMIN_CONF_JS_GROW'] = 'Grow / Shrink';
$jLang['_ADMIN_CONF_SUMM_SEL'] = 'Summary Score Columns';
$jLang['_ADMIN_CONF_SUMM_SEL_DEF'] = 'Select the columns that will appear in the Score Summary area of the dashboard. Move the columns up and down to determine their order of appearance from left to right. To select multiple options, hold down the CTRL key and click on the options.';
$jLang['_ADMIN_CONF_LAST_SUMM_SEL'] = 'Last Round Winner Details';
$jLang['_ADMIN_CONF_LAST_SUMM_SEL_DEF'] = 'Select the columns that will appear in the Last Round Winner area of the dashboard. Move the columns up and and down to determine their order of appearance from left to right. To select multiple options, hold down the CTRL key and click the options.';
$jLang['_ADMIN_CONF_NONE'] = '--None--';
$jLang['_ADMIN_CONF_JS_STATIC'] = 'Static (Always Visible)';
$jLang['_ADMIN_CONF_JS_NONE'] = 'None / Hide';
$jLang['_ADMIN_CONF_LICENCE'] = 'Show Licence Info';
$jLang['_ADMIN_CONF_LICENCE_DEF'] = 'Display the jTips logo, version and copyright information';
$jLang['_ADMIN_CONF_GO'] = 'Go!';
$jLang['_ADMIN_CONF_UPLOAD'] = 'Upload';
$jLang['_ADMIN_CONF_ACT_NA'] = "N / A";
$jLang['_ADMIN_CONF_UPGRADE_LINK'] = '<a href="http://www.jtips.com.au/" target="_blank">' .$jLang['_ADMIN_CONF_UPGRADE']. '</a>';
$jLang['_ADMIN_CONF_BUTTON_UP'] = 'Up';
$jLang['_ADMIN_CONF_BUTTON_DOWN'] = 'Down';
$jLang['_ADMIN_CONF_UPDATE_NOW'] = 'Update Now';
$jLang['_ADMIN_CONF_UPDATE_NA'] = 'Not Available';
$jLang['_ADMIN_CONF_JSTIME'] = 'Show Countdown Timer';
$jLang['_ADMIN_CONF_JSTIME_DEF'] = 'Display a timer that counts down to the close of the round on the Tips Panel';
$jLang['_ADMIN_CONF_COMMENTS'] = 'Enable Comments';
$jLang['_ADMIN_CONF_COMMENTS_DEF'] = 'Allow users to add a comment to be viewed by other users for each round';
$jLang['_ADMIN_CONF_COMMENTSFILTER'] = 'Enable Bad-Words Filter';
$jLang['_ADMIN_CONF_COMMENTSFILTER_DEF'] = '';
$jLang['_ADMIN_CONF_COMMENTSACTION_REPLACE'] = 'Replace';
$jLang['_ADMIN_CONF_COMMENTSACTION_DELETE'] = 'Delete';
$jLang['_ADMIN_CONF_COMMENTSFILTERACTION'] = 'Comments Filter Action';
$jLang['_ADMIN_CONF_COMMENTSFILTERACTION_DEF'] = 'The action to take when a comment is found that contains a prohibited word or phrase';
$jLang['_ADMIN_CONF_TEAM_LADD_SEL'] = 'Team Ladder Columns';
$jLang['_ADMIN_CONF_TEAM_LADD_SEL_DEF'] = 'Select the columns that will appear on the Team Ladder page. Move the columns up and down to determine their order of appearance from left to right. To select multiple options, hold down the CTRL key and click on the options.';
$jLang['_ADMIN_CONF_SHOWTIPS'] = 'Enable ShowTips';
$jLang['_ADMIN_CONF_SHOWTIPS_DEF'] = 'Other users tips will be visible once the round has been finalized';
$jLang['_ADMIN_CONF_SHOWTIPS_WIDTH'] = 'ShowTips Window Width';
$jLang['_ADMIN_CONF_SHOWTIPS_WIDTH_DEF'] = 'Window width in pixels - default 640';
$jLang['_ADMIN_CONF_SHOWTIPS_HEIGHT'] = 'ShowTips Window Height';
$jLang['_ADMIN_CONF_SHOWTIPS_HEIGHT_DEF'] = 'Window height in pixels - default 480';
$jLang['_ADMIN_CONF_COMP_LADD_SEL'] = 'Competition Ladder Columns';
$jLang['_ADMIN_CONF_COMP_LADD_SEL_DEF'] = 'Select the columns that will appear in the Competition Ladder page. Move the columns up and and down to determine their order of appearance from left to right. To select multiple options, hold down the CTRL key and click the options.';
$jLang['_ADMIN_REMIND_ENABLE'] = 'Enable Email Reminders';
$jLang['_ADMIN_REMIND_ENABLE_DEF'] = 'Allow users to optionally receive an automated reminder to submit their tips. To enable this feature please add the following line to your cron tab: <br /><strong><pre>0 10 * * 5 cd; wget --delete-after --no-cache "' .jTipsRoute($mosConfig_live_site. '/index2.php?option=com_jtips&view=Dashboard&action=MailMan&key=[PASSKEY]', false). '";</pre></strong>This will run the email reminder mailout every Friday at 10am.<br />For more information on setting up your own schedule, take a look at <a href="http://en.wikipedia.org/wiki/Cron">Wikipedia</a>';
$jLang['_ADMIN_REMIND_FROMNAME'] = 'From Name';
$jLang['_ADMIN_REMIND_FROMADDRESS'] = 'From Address';
$jLang['_ADMIN_REMIND_SUBJECT'] = 'Reminder Email Subject';
$jLang['_ADMIN_REMIND_BODY'] = 'Reminder Email Body';
$jLang['_ADMIN_REMIND_BODY_DEF'] = 'The body of the reminder email to be automatically sent to users to remind them to submit their tips.<br />You may add replaceable values that will get replaced with details of the registering user. Valid placeholders are:<ul><li>{name}</li><li>{username}</li><li>{email}</li><li>{competition}</li></ul>';
$jLang['_ADMIN_CONF_TEAM_LADD_BTNS'] = 'Team Ladder Option Ordering';
$jLang['_ADMIN_CONF_TEAM_LADD_BTNS_DEF'] = 'Select one or more items in the Team Ladder Columns list and use the Up and Down buttons to change their order';
$jLang['_ADMIN_CONF_COMP_LADD_BTNS'] = 'Competition Ladder Option Ordering';
$jLang['_ADMIN_CONF_COMP_LADD_BTNS_DEF'] = 'Select one or more items in the Competition Ladder Columns list and use the Up and Down buttons to change their order';
$jLang['_ADMIN_CONF_LAST_SUMM_BTNS'] = 'Last Rounder Summary Option Ordering';
$jLang['_ADMIN_CONF_LAST_SUMM_BTNS_DEF'] = 'Select one or more items in the Last Round Summary list and use the Up and Down buttons to change their order';
$jLang['_ADMIN_CONF_SUMM_BTNS'] = 'Summary Score Option Ordering';
$jLang['_ADMIN_CONF_SUMM_BTNS_DEF'] = 'Select one or more items in the Summary Score Columns list and use the Up and Down buttons to change their order';
$jLang['_ADMIN_CONF_LADSTYLE2'] = 'Use Effect For:';
$jLang['_ADMIN_CONF_LADSTYLE2_DEF'] = 'Determines when the above effect will be applied';
$jLang['_ADMIN_CONF_LADSTYLE3'] = 'Effect Duration:';
$jLang['_ADMIN_CONF_LADSTYLE3_DEF'] = 'The number of seconds that the effect should take';

//Team Manager
$jLang['_ADMIN_TEAM_TITLE'] = 'Team Manager';
$jLang['_ADMIN_TEAM_TEAM'] = 'Team';
$jLang['_ADMIN_TEAM_LOCATION'] = 'Location';
$jLang['_ADMIN_TEAM_NAME'] = 'Team Name';
$jLang['_ADMIN_TEAM_LOCAREA'] = 'Location/Area';
$jLang['_ADMIN_TEAM_ABOUT'] = 'About';

//1.0 Additions
$jLang['_ADMIN_TEAM_INSEASON'] = 'In Season';
$jLang['_ADMIN_TEAM_POINTS'] = 'Points';
$jLang['_ADMIN_TEAM_SEASON'] = 'Season / Competition';
$jLang['_ADMIN_TEAM_ADJUST'] = 'Adjust Team Score';
$jLang['_ADMIN_TEAM_ADJUST_WINS'] = 'Adjust Team Wins';
$jLang['_ADMIN_TEAM_ADJUST_LOSSES'] = 'Adjust Team Losses';
$jLang['_ADMIN_TEAM_ADJUST_FOR'] = 'Set Team For Points';
$jLang['_ADMIN_TEAM_ADJUST_AGAINST'] = 'Set Team Against Points';
$jLang['_ADMIN_TEAM_LOGO'] = 'Update Team Image/Logo';
$jLang['_ADMIN_TEAM_LOGO_CURR'] = 'Current Image/Logo';
$jLang['_ADMIN_TEAM_LOGO_ERR'] = 'No Image';
$jLang['_ADMIN_TEAM_LOGO_RM'] = 'Remove Image';
$jLang['_ADMIN_TEAM_URL'] = 'Team Website';

//Season Manager
$jLang['_ADMIN_SEASON_TITLE'] = 'Season Manager';
$jLang['_ADMIN_SEASON_NAME'] = 'Season Name';
$jLang['_ADMIN_SEASON_START'] = 'Season Start';
$jLang['_ADMIN_SEASON_END'] = 'Season End';
$jLang['_ADMIN_SEASON_START_DATE'] = 'Start Date';
$jLang['_ADMIN_SEASON_START_DATE_DEF'] = 'The date the season starts';
$jLang['_ADMIN_SEASON_END_DATE'] = 'End Date';
$jLang['_ADMIN_SEASON_END_DATE_DEF'] = 'The date the season ends';
$jLang['_ADMIN_SEASON_ROUNDS'] = 'Total Rounds';
$jLang['_ADMIN_SEASON_GPR'] = 'Max Games Per Round';
$jLang['_ADMIN_SEASON_DESCR'] = 'Description';
$jLang['_ADMIN_SEASON_PRECISION'] = "Enable 'Precision Score'<sup>&copy;</sup>";
$jLang['_ADMIN_SEASON_PRECISION_DEF'] = "Use in conjunction with either the 'enable pick the score', or 'enable pick the margin' options to more accurately obtain a single winner at the end of the season. See the help documentation for more information on 'Precision Score'<sup>&copy;</sup>";

$jLang['_ADMIN_GAME_HOMESCORE'] = 'Home Team Score';
$jLang['_ADMIN_GAME_AWAYSCORE'] = 'Away Team Score';
$jLang['_ADMIN_GAME_HAS_BONUS'] = 'Enable Bonus Pick';
$jLang['_ADMIN_GAME_HAS_MARGIN'] = 'Enable Margin Pick';
$jLang['_ADMIN_GAME_HAS_SCORE'] = 'Enable Score Pick';

//Round Manager
$jLang['_ADMIN_ROUND_TITLE'] = 'Round Manager';
$jLang['_ADMIN_ROUND_START'] = 'Start';
$jLang['_ADMIN_ROUND_END'] = 'End';
$jLang['_ADMIN_ROUND_ADDRESULT'] = 'Add Result';
$jLang['_ADMIN_ROUND_ROUND'] = 'Round';
$jLang['_ADMIN_ROUND_DATE'] = 'Date';
$jLang['_ADMIN_ROUND_TIME'] = 'Time';
$jLang['_ADMIN_ROUND_GAMES'] = 'Games';
$jLang['_ADMIN_ROUND_USE'] = 'Use';
$jLang['_ADMIN_ROUND_HOME'] = 'Home';
$jLang['_ADMIN_ROUND_AWAY'] = 'Away';
$jLang['_ADMIN_ROUND_ORDER'] = 'Order';
$jLang['_ADMIN_ROUND_WINNER'] = 'Winner';
$jLang['_ADMIN_ROUND_DRAW'] = 'Draw';
$jLang['_ADMIN_ROUND_NOTEAMS'] = 'No teams available!';
$jLang['_ADMIN_ROUND_NOTSTARTED'] = 'Round Not Yet Started';
$jLang['_ADMIN_ROUND_INPROGRESS'] = 'Round in Progress';

//1.0 Additions
$jLang['_ADMIN_ROUND_SEASON'] = 'Season';
$jLang['_ADMIN_ROUND_STATUS'] = 'Status';
$jLang['_ADMIN_ROUND_EDITGAMES'] = 'Edit Games';
$jLang['_ADMIN_ROUND_STATUS_NS'] = 'Not Started';
$jLang['_ADMIN_ROUND_STATUS_C'] = 'Complete';
$jLang['_ADMIN_ROUND_STATUS_P'] = 'Pending Results';
$jLang['_ADMIN_ROUND_STATUS_IP'] = 'In Progress';
$jLang['_ADMIN_ROUND_CURRTIME'] = 'Current Time';

//User Manager
$jLang['_ADMIN_USERS_TITLE'] = 'User Manager';
$jLang['_ADMIN_USERS_USERNAME'] = 'Username';
$jLang['_ADMIN_USERS_DOUBLE'] = 'DoubleUP';
$jLang['_ADMIN_USERS_AVERAGE'] = 'Average';
$jLang['_ADMIN_USERS_TOTAL'] = 'Total';
$jLang['_ADMIN_USERS_PAID'] = 'Paid';
$jLang['_ADMIN_USERS_SELECT'] = 'Select User';
$jLang['_ADMIN_USERS_AVERAGE_SCORE'] = 'Average Score';
$jLang['_ADMIN_USERS_TOTAL_SCORE'] = 'Total Score';
$jLang['_ADMIN_USERS_ROUND'] = 'Round';
$jLang['_ADMIN_USERS_NEWUSER'] = 'New User';

//1.0 Additions
$jLang['_ADMIN_USERS_APPROVED'] = 'Approved';
$jLang['_ADMIN_USERS_NAME'] = 'Name';
$jLang['_ADMIN_USERS_FULLNAME'] = 'Full Name';
$jLang['_ADMIN_USERS_EMAIL'] = 'Email';
$jLang['_ADMIN_USERS_LASTROUND'] = 'Last Round';
$jLang['_ADMIN_USERS_DOUBLEUP_RESET'] = 'Reset DoubleUP';
$jLang['_ADMIN_USERS_DOUBLEUP_RESET_DEF'] = 'Tick this box to allow this use to use their DoubleUP again';
$jLang['_ADMIN_USERS_SEASON'] = 'Add To Season';

//Admin (Other)
$jLang['_ADMIN_OTHER_EDIT'] = 'Edit';
$jLang['_ADMIN_OTHER_EDITING'] = 'Editing';
$jLang['_ADMIN_OTHER_NEW'] = 'New';

//CSS
$jLang['_ADMIN_CSS_TITLE'] = 'Style Sheet Editor';





/////////////////////////////////////////////////
///
///	jSeason Field Defintion Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JSEASON_ID'] = 'Primary Key';
$jLang['_ADMIN_JSEASON_NAME'] = 'Season Name';
$jLang['_ADMIN_JSEASON_DESCRIPTION'] = 'Description';
$jLang['_ADMIN_JSEASON_START'] = 'Start Date';
$jLang['_ADMIN_JSEASON_END'] = 'End Date';
$jLang['_ADMIN_JSEASON_ROUNDS'] = 'Total Rounds';
$jLang['_ADMIN_JSEASON_GAME_PER_ROUND'] = 'Games Per Round';
$jLang['_ADMIN_JSEASON_PICK_SCORE'] = 'Enable Pick the Score';
$jLang['_ADMIN_JSEASON_PICK_MARGIN'] = 'Enable Pick the Margin';
$jLang['_ADMIN_JSEASON_PICK_BONUS'] = 'Enable Pick the Bonus Team';
$jLang['_ADMIN_JSEASON_PICK_DRAW'] = 'Enable Pick the Draw';
$jLang['_ADMIN_JSEASON_TEAM_BONUS'] = 'Team Bonus Points';
$jLang['_ADMIN_JSEASON_TEAM_WIN'] = 'Team Win Points';
$jLang['_ADMIN_JSEASON_TEAM_LOSE'] = 'Team Lose Points';
$jLang['_ADMIN_JSEASON_TEAM_DRAW'] = 'Team Draw Points';
$jLang['_ADMIN_JSEASON_TEAM_BYE'] = 'Team Bye Points';
$jLang['_ADMIN_JSEASON_USER_CORRECT'] = 'User Correct Tip Points';
$jLang['_ADMIN_JSEASON_USER_DRAW'] = 'User Tip the Draw Points';
$jLang['_ADMIN_JSEASON_USER_BONUS'] = 'User Tip All Games Bonus Points';
$jLang['_ADMIN_JSEASON_USER_NONE'] = 'User No Tips Points';
$jLang['_ADMIN_JSEASON_USER_PICK_SCORE'] = 'User Pick the Score Points';
$jLang['_ADMIN_JSEASON_USER_PICK_MARGIN'] = 'User Pick the Margin Points';
$jLang['_ADMIN_JSEASON_USER_PICK_BONUS'] = 'User Pick the Bonus Points';
$jLang['_ADMIN_JSEASON_URL'] = 'Season Website';
$jLang['_ADMIN_JSEASON_IMAGE'] = 'Season Header Image';
$jLang['_ADMIN_JSEASON_PRECISION_SCORE'] = 'Enable Precision Score';
$jLang['_ADMIN_JSEASON_TIP_DISPLAY'] = 'Team Display Setting';
$jLang['_ADMIN_JSEASON_UPDATED'] = 'Last Modified';

/////////////////////////////////////////////////
///
/// jBadWord Feild Definition Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JBADWORD_ID'] = 'Primary Key';
$jLang['_ADMIN_JBADWORD_WORD'] = 'Bad Word';
$jLang['_ADMIN_JBADWORD_MATCH_CASE'] = 'Match Case';
$jLang['_ADMIN_JBADWORD_ACTION'] = 'Action';
$jLang['_ADMIN_JBADWORD_REPALCE'] = 'Replacement';
$jLang['_ADMIN_JBADOWRD_HITS'] = 'Hits';
$jLang['_ADMIN_JBADWORD_UPDATED'] = 'Last Modified';

/////////////////////////////////////////////////
///
/// jGame Feild Definition Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JGAME_ID'] = 'Primary Key';
$jLang['_ADMIN_JGAME_ROUND_ID'] = 'Round Number';
$jLang['_ADMIN_JGAME_HOME_ID'] = 'Home Team';
$jLang['_ADMIN_JGAME_AWAY_ID'] = 'Away Team';
$jLang['_ADMIN_JGAME_POSITION'] = 'Game Order';
$jLang['_ADMIN_JGAME_WINNER_ID'] = 'Winning Team';
$jLang['_ADMIN_JGAME_DRAW'] = 'Draw';
$jLang['_ADMIN_JGAME_HOME_SCORE'] = 'Home Team Score';
$jLang['_ADMIN_JGAME_AWAY_SCORE'] = 'Away Team Score';
$jLang['_ADMIN_JGAME_BONUS_ID'] = 'Bonus Point Awarded To';
$jLang['_ADMIN_JGAME_HAS_BONUS'] = 'Allow Bonus Point Team Selection';
$jLang['_ADMIN_JGAME_HAS_MARGIN'] = 'Allow Score Margin Selection';
$jLang['_ADMIN_JGAME_HAS_SCORE'] = 'Allow Exact Score Selection';
$jLang['_ADMIN_JGAME_UPDATED'] = 'Last Modified';
$jLang['_ADMIN_JGAME_SEASON_ID'] = 'Season';

/////////////////////////////////////////////////
///
/// jRound Feild Definition Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JROUND_ID'] = 'Primary Key';
$jLang['_ADMIN_JROUND_ROUND'] = 'Round Number';
$jLang['_ADMIN_JROUND_SEASON_ID'] = 'Season';
$jLang['_ADMIN_JROUND_START_TIME'] = 'Start Date & Time';
$jLang['_ADMIN_JROUND_END_TIME'] = 'End Date & Time';
$jLang['_ADMIN_JROUND_SCORED'] = 'Scored & Complete';
$jLang['_ADMIN_JROUND_UPDATED'] = 'Last Modified';

/////////////////////////////////////////////////
///
/// jTeam Feild Definition Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JTEAM_ID'] = 'Primary Key';
$jLang['_ADMIN_JTEAM_SEASON_ID'] = 'Season Name';
$jLang['_ADMIN_JTEAM_NAME'] = 'Name';
$jLang['_ADMIN_JTEAM_LOCATION'] = 'Location';
$jLang['_ADMIN_JTEAM_ABOUT'] = 'About';
$jLang['_ADMIN_JTEAM_LOGO'] = 'Logo';
$jLang['_ADMIN_JTEAM_URL'] = 'Website';
$jLang['_ADMIN_JTEAM_WINS'] = 'Wins';
$jLang['_ADMIN_JTEAM_DRAWS'] = 'Draws';
$jLang['_ADMIN_JTEAM_LOSSES'] = 'Losses';
$jLang['_ADMIN_JTEAM_POINTS_FOR'] = 'Points For';
$jLang['_ADMIN_JTEAM_POINTS_AGAINST'] = 'Points Against';
$jLang['_ADMIN_JTEAM_POINTS'] = 'Total Points';
$jLang['_ADMIN_JTEAM_UPDATED'] = 'Last Modified';

$jLang['_ADMIN_DASH_CONFIGURATION'] = 'Configuration';
$jLang['_ADMIN_DASH_ROUND_MANAGER'] = 'Round and Game Management';
$jLang['_ADMIN_DASH_TEAM_MANAGER'] = 'Team Manager';
$jLang['_ADMIN_DASH_SEASON_MANAGER'] = 'Season Manager';
$jLang['_ADMIN_DASH_USER_MANAGER'] = 'User Manager';
$jLang['_ADMIN_DASH_TIPS_MANAGER'] = 'Tips Manager';
$jLang['_ADMIN_DASH_NEW_ROUND'] = 'New Round';
$jLang['_ADMIN_DASH_NEW_TEAM'] = 'New Team';
$jLang['_ADMIN_DASH_NEW_SEASON'] = 'New Season';
$jLang['_ADMIN_DASH_EXPORT_MANAGER'] = 'Data Export Manager';
$jLang['_ADMIN_DASH_IMPORT_MANAGER'] = 'Data Import Manager';
$jLang['_ADMIN_DASH_COMMENT_MANAGER'] = 'Comment Manager';
$jLang['_ADMIN_DASH_BADWORD_MANAGER'] = 'Bad Word Manager';
$jLang['_ADMIN_DASH_STYLE_EDITOR'] = 'Style Editor';
$jLang['_ADMIN_DASH_SUPPORT'] = 'Help &amp; Support';


//July 14 2008. v2.0.8
$jLang['_ADMIN_SEASON_ETS'] = 'Enable Team Starts';
$jLang['_ADMIN_SEASON_ETS_DEF'] = 'Allows the administrator to give a team a head start. Users must select the winner while taking thie head start into consideration. This feature is also known as a Team Handicap.';
$jLang['_ADMIN_JSEASON_TEAM_STARTS'] = 'Team Starts';
$jLang['_ADMIN_GAME_HOME_START'] = 'Home Start';
$jLang['_ADMIN_GAME_AWAY_START'] = 'Away Start';
$jLang['_ADMIN_JGAME_HOME_START'] = 'Home Start';
$jLang['_ADMIN_JGAME_AWAY_START'] = 'Away Start';
$jLang['_COM_TIPS_HOMESTART'] = 'Home Start';
$jLang['_COM_TIPS_AWAYSTART'] = 'Away Start';
$jLang['_COM_MY_SUB_SEASONS'] = 'My Subscribed Seasons';
$jLang['_COM_MY_UNSUB_SEASONS'] = 'UnSubscribed Seasons';
$jLang['_COM_UNSUBLINK_PART1'] = 'Are you sure you wish to unsubscribe from the';
$jLang['_COM_UNSUBLINK_PART2'] = 'competition?\nAll competition points will be forfeited.';
$jLang['_COM_ONE_ROUND_REQUIRED'] = 'At least one round must be complete';
$jLang['_COM_SCORE'] = 'Score';
$jLang['_COM_MARGIN'] = 'Margin';
$jLang['_COM_BONUS'] = 'Bonus';
$jLang['_COM_COMMENT_VALIDATED'] = 'Comment validated.';
$jLang['_COM_COMMENT_NOT_ALLOWED'] = 'Comment contains words that are not allowed!';
$jLang['_COM_COMMENT_REPLACED'] = 'Comment contains words that are not allowed. These words will be replaced.';

//August 11 2008. v2.0.9
$jLang['_ADMIN_DASH_TOTAL_USERS'] = 'Total Users';
$jLang['_ADMIN_DASH_PENDING_TIPS'] = 'Pending Tips';
$jLang['_ADMIN_DASH_PENDING_PAYMENT'] = 'Pending Payment';
$jLang['_ADMIN_DASH_REVALIDATE'] = 'ReValidate';
$jLang['_ADMIN_DASH_LOGGING'] = 'Logging';
$jLang['_ADMIN_DASH_FILE_SIZE'] = 'File Size';
$jLang['_ADMIN_DASH_DOWNLOAD'] = 'Download';
$jLang['_ADMIN_DASH_PURGE'] = 'Purge';
$jLang['_ADMIN_DASH_LOG_ROTATED'] = 'Log file automatically rotated after 10MB';
$jLang['_ADMIN_DASH_ABOUT_UPDATES'] = 'Stay up-to-date on the latest jTips development and downloads at';
$jLang['_ADMIN_DASH_ABOUT_SALES'] = 'For sales and licencing enquiries, contact';
$jLang['_ADMIN_DASH_ABOUT_SUPPORT'] = 'For support, please visit';
$jLang['_ADMIN_DASH_ABOUT_REBUILD'] = 'If you need to rebuild the database tables for jTips,';
$jLang['_COMMON_CLICK_HERE'] = 'click here';
$jLang['_ADMIN_DASH_CREDITS'] = 'jTips uses the following packages';
$jLang['_ADMIN_DASH_CREDITS_PACKAGE'] = 'Package';
$jLang['_ADMIN_DASH_CREDITS_HOMEPAGE'] = 'Home Page';
$jLang['_ADMIN_DASH_TAB_HELP'] = 'Help';
$jLang['_ADMIN_DASH_HELP'] = 'Getting Help and Support';
$jLang['_ADMIN_DASH_HELP_INTRO'] = 'The following areas of help and support can be found at';
$jLang['_ADMIN_DASH_HELP_GETTING_STARTED'] = 'Getting Started';
$jLang['_ADMIN_DASH_HELP_GUIDES'] = 'In-Depth Guides';
$jLang['_ADMIN_DASH_HELP_TRICKS'] = 'Tips, Tricks and Hints';
$jLang['_ADMIN_DASH_UPG_SUCCESS'] = 'Upgrade Successful!';
$jLang['_ADMIN_DASH_UPG_FAILED'] = 'Upgrade Failed';
$jLang['_ADMIN_DASH_REBUILD_WARNING'] = 'This will delete all your existing jTips data.\nAre you sure you wish to continue?';
$jLang['_ADMIN_DASH_REBUILD_TITLE'] = 'Rebuild';
$jLang['_ADMIN_DASH_TAB_CREDITS'] = 'Credits';
$jLang['_ADMIN_DASH_TAB_ABOUT'] = 'About';
$jLang['_ADMIN_DASH_LAST_VALIDATED'] = 'Last Validated';
$jLang['_ADMIN_DASH_LIC_EXPIRED'] = 'Expired. Please ReValidate';
$jLang['_ADMIN_DASH_VALIDATION'] = 'Validation';
$jLang['_ADMIN_DASH_TAB_UPDATED'] = 'Updates';
$jLang['_ADMIN_DASH_TAB_SUMMARY'] = 'Summary';
$jLang['_ADMIN_DASH_CPANEL'] = 'jTips Administrator Dashboard';
$jLang['_ADMIN_CONF_SHOWTIPS_PROCESSED'] = 'After Round is Processed';
$jLang['_ADMIN_CONF_SHOWTIPS_TIPPED'] = 'After User has Submitted Tips';
$jLang['_ADMIN_CONF_SHOWTIPS_ANY'] = 'At Any Time';
$jLang['_ADMIN_CONF_SHOWTIPS_ACCESS'] = 'ShowTips Access Level';
$jLang['_ADMIN_CONF_SHOWTIPS_ACCESS_DEF'] = 'Controls when users will be able to view the tips of other participants for the current round.';
$jLang['_COM_TIPS_MORE'] = 'More';
$jLang['_ADMIN_CONF_TIP_LOCKOUT'] = 'Disable Tips Edit';
$jLang['_ADMIN_CONF_TIP_LOCKOUT_DEF'] = 'Once a user has submitted their tips, their are unable to edit them';
$jLang['_POPUP_TIPS_PEEK'] = 'Peek into the Current Round';
$jLang['_ADMIN_TIPSMAN_SCORE_MARGIN'] = 'Score Margin';
$jLang['_ADMIN_SEASON_SELECT'] = 'Select Season';
$jLang['_ADMIN_AJAX_PROCESSING'] = 'Processing. Please wait...';
$jLang['_ADMIN_DASH_UPG_FILE_CHECK'] = 'File Check';
$jLang['_ADMIN_DASH_UPG_UPGRADE'] = 'Upgrade';
$jLang['_ADMIN_DASH_UPG_LATESTVERSION'] = 'Latest Version';
$jLang['_ADMIN_DASH_UPG_THISVERSION'] = 'This Version';
$jLang['_ADMIN_DASH_UPG_FILE_CHECK_OK'] = 'All files are writable. You can OneClick Upgrade this system.';
$jLang['_ADMIN_DASH_UPG_FILE_CHECK_OK_TITLE'] = 'File Check OK';
$jLang['_ADMIN_DASH_UPG_FILE_CHECK_FAIL'] = 'Not all files are writable. Click for more information.';
$jLang['_ADMIN_DASH_UPG_FILE_CHECK_FAIL_TITLE'] = 'File Check Failed';
$jLang['_ADMIN_UPGRADER_TITLE'] = 'jTips Upgrade Check';
$jLang['_ADMIN_UPGRADER_TYPE'] = 'Type';
$jLang['_ADMIN_UPGRADER_LOCATION'] = 'File Location';
$jLang['_ADMIN_UPGRADER_PERMISSIONS'] = 'Permissions';
$jLang['_ADMIN_UPGRADER_OCTAL'] = 'Octal Permissions';
$jLang['_ADMIN_UPGRADER_OWNER'] = 'Owner';
$jLang['_ADMIN_UPGRADER_DIR_TITLE'] = 'Item is a Directory';
$jLang['_ADMIN_UPGRADER_DIR_INFO'] = 'The web server must be able to write to existing files within this directory, and also create new files and directories within this directory.';
$jLang['_ADMIN_UPGRADER_FILE_TITLE'] = 'Item is a File';
$jLang['_ADMIN_UPGRADER_FILE_INFO'] = 'The web server must be able to write to this file.';
$jLang['_ADMIN_UPGRADER_LIST_INFO'] = 'This page lists all jTips system files and directories that are not currently writable by the web server. The permissions on these files and directories need to be corrected in order to use OneClick Upgrade.';
$jLang['_ADMIN_TIPS_WIN'] = 'Win';

//August 17 2008
$jLang['_COM_MENU_JTIPS_TITLE'] = 'jTips';
$jLang['_COM_TEAM_AWAY'] = 'Away';
$jLang['_COM_TEAM_HOME'] = 'Home';
$jLang['_COM_TEAM_HOME_PAGE'] = 'Home Page';
$jLang['_COM_TEAM_HISTORY'] = 'History';
$jLang['_COM_TEAM_VS'] = 'Vs.';

//version 2.0.11
$jLang['_ADMIN_CONF_SSL'] = 'SSL Validation';
$jLang['_ADMIN_CONF_SSL_DEF'] = 'Force secure connection when validating activation details. If you are having trouble validating, disable this option.';
$jLang['_AMDIN_CONF_PAYPAL_OPT'] = 'PayPal';
$jLang['_AMDIN_CONF_MANUAL_OPT'] = 'Manual';
$jLang['_ADMIN_CONF_DEBUG_LEVEL'] = 'Logging';
$jLang['_ADMIN_CONF_DEBUG_LEVEL_DEF'] = 'Select the logging level. Determines what information is written to the log file. Set to None to disable logging.';
$jLang['_ADMIN_CONF_LOG_INFO'] = 'Information';
$jLang['_ADMIN_CONF_LOG_DEBUG'] = 'Debugging';
$jLang['_ADMIN_CONF_LOG_ERROR'] = 'Errors';

//Version 2.0.12
$jLang['_COM_UNSUBSCRIBE'] = 'Unsubscribe';
$jLang['_COM_CLOSED_TIME'] = 'Closed at';
$jLang['_ADMIN_CONF_PAYPAL_DEF'] = 'The HTML code generated from PayPal.<br /><strong>IMPORTANT: </strong>Add the following line just above the &lt;/form&gt; line at the bottom:<br /><strong><pre>&lt;input type="hidden" name="return" value="{RETURN_URL}"&gt;</pre></strong>';
$jLang['_COM_EDIT_PREFERENCES'] = 'Edit Preferences';
$jLang['_ADMIN_CONF_DISABLE_MENU'] = 'Disable/Hide Menu';
$jLang['_ADMIN_CONF_DISABLE_MENU_DEF'] = 'Completely hides the main jTips navigation bar. Useful if you have Joomla menu items directly linked to a competition.';
$jLang['_ADMIN_CONF_DISABLE_SEASON_SELECT'] = 'Disable/Hide Season Selection';
$jLang['_ADMIN_CONF_DISABLE_SEASON_SELECT_DEF'] = 'Completely hides the season/competition select field. Useful if you have Joomla menu items directly linked to a competition.';
$jLang['_ADMIN_CONF_NOTIFY_TIPS'] = 'Send Tips Confirmation Email';
$jLang['_ADMIN_CONF_NOTIFY_TIPS_DEF'] = 'Send an email when a user submits their tips. The email contains their selected tips.';

//version 2.0.13
$jLang['_ADMIN_STYLE_SAVED'] = 'Style Saved';
$jLang['_ADMIN_STYLE_SAVING_ERROR'] = 'Error Writing Stylesheet';
$jLang['_ADMIN_FILE_DELETED'] = 'File Deleted';
$jLang['_ADMIN_FILE_DELETE_FAILED'] = 'Failed to Delete File';
$jLang['_ADMIN_FILE_DELETE_NOFILE'] = 'No File to Delete';
$jLang['_ADMIN_FILE_UPLOAD_SUCCESS'] = 'Upload Successful';
$jLang['_ADMIN_FILE_UPLOAD_FAILED'] = 'Upload Failed';
$jLang['_MOD_LAST_ROUND_POINTS'] = 'Last Round Points';
$jLang['_MOD_TOTAL_POINTS'] = 'Total Points';
$jLang['_MOD_LAST_ROUND_PRECISION'] = 'Last Round Precision';
$jLang['_MOD_PRECISION_SCORE'] = 'Precision Score';
$jLang['_MOD_LAST_ROUND_COMMENT'] = 'Last Round Comment';
$jLang['_MOD_LADDER_MOVEMENT'] = 'Ladder Movement';
$jLang['_LADDER_VIEW_TIPS_FOR'] = 'View Tips for';
$jLang['_MOD_VIEW_MORE'] = 'View More';
$jLang['_MOD_INVALID_SEASON_ERROR'] = 'Missing or Invalid Competition Specified';

//version 2.0.14
$jLang['_ADMIN_REMINDER_EMAILMANKEY'] = 'Email Reminder PassKey';
$jLang['_ADMIN_REMINDER_EMAILMANKEY_DEF'] = 'This key will need to be entered (as part of the url) when configuring the scheduled task (or cron).';
$jLang['_ADMIN_DASH_LANG_EDITOR'] = 'Language Editor';
$jLang['_ADMIN_LANG_KEY'] = 'Variable Key';
$jLang['_ADMIN_LANG_DEF'] = 'Displayed Text';
$jLang['_ADMIN_LANGUAGE_TITLE'] = 'Language Editor';
$jLang['_ADMIN_LANGUAGE_LIST_INFO'] = 'Any sentence or word used in jTips can be edited from here. Select the variable you wish to edit and click the Edit button.';
$jLang['_COM_USER_PREFERENCES'] = 'User Preferences';
$jLang['_COM_TIME_ZONE'] = 'Time Zone:';
$jLang['_COM_DEFAULT_SEASON'] = 'Default Season:';
$jLang['_COM_SEND_REMINDER_EMAIL'] = 'Send Reminder Emails:';
$jLang['_COM_SAVE'] = 'Save';
$jLang['_ADMIN_LANGUAGE_EDIT'] = 'Key Definition:';
$jLang['_ADMIN_LANGUAGE_SYSKEY'] = 'System Key:';
$jLang['_ADMIN_LANGUAGE_EDITING'] = 'Editing Language Variable';
$jLang['_COM_ROUND_SCORE'] = 'Round Score';
$jLang['_COM_MOVEMENT'] = 'Movement';
$jLang['_COM_DOUBLEUP_USED'] = 'DoubleUP Used';
$jLang['_ADMIN_CONF_SSUMM_PROJECTED_SCORE'] = 'Projected Score';
$jLang['_ADMIN_CONF_SS_DBLUP_STATUS'] = 'DoubleUP Status';
$jLang['_ADMIN_CONF_SS_PAID_STATUS'] = 'Paid Status';
$jLang['_ADMIN_CONF_LRS_LRS'] = 'Last Round Score';
$jLang['_COM_TLD_POINTS_FORAGAINST'] = 'For and Against Difference';
$jLang['_ADMIN_MT_LINEAR'] = 'Linear';
$jLang['_ADMIN_MT_QUADRATIC'] = 'Quadratic';
$jLang['_ADMIN_MT_CUBIC'] = 'Cubic';
$jLang['_ADMIN_MT_QUARTIC'] = 'Quartic';
$jLang['_ADMIN_MT_QUNITIC'] = 'Quintic';
$jLang['_ADMIN_MT_SINUSOIDAL'] = 'Sinusoidal';
$jLang['_ADMIN_MT_EXPONENTIAL'] = 'Exponential';
$jLang['_ADMIN_MT_CIRCULAR'] = 'Circular';
$jLang['_ADMIN_MT_BOUNCING'] = 'Bouncing';
$jLang['_ADMIN_MT_BACK'] = 'Back';
$jLang['_ADMIN_MT_ELASTIC'] = 'Elastic';
$jLang['_ADMIN_MT_EASEIN'] = 'Ease In';
$jLang['_ADMIN_MT_EASEOUT'] = 'Ease Out';
$jLang['_ADMIN_MT_EASEINOUT'] = 'Ease In and Out';
$jLang['_ADMIN_SEASON_DEFAULT_POINTS_LOW'] = 'Lowest Points';
$jLang['_ADMIN_SEASON_DEFAULT_POINTS_AVG'] = 'Average';
$jLang['_ADMIN_SEASON_DEFAULT_POINTS'] = 'Late Entrant Starting Points';
$jLang['_ADMIN_SEASON_DEFAULT_POINTS_DEF'] = 'Users that join the competition after it has started can be allocated default points. Lowest Points will give the user the same points a current user in last place. Average will give the user an average from all current users in the competition.';
$jLang['_COM_LADDER_NO_ROUNDS'] = 'has No Completed Rounds';
$jLang['_COM_BONUS_CHOICE_BOTH'] = 'Both';
$jLang['_ADMIN_UPGRADE_BUTTON'] = 'Upgrade to jTips 2.0';
$jLang['_ADMIN_CONF_FILE_NOT_FOUND'] = 'Configuration File Not Found!';
$jLang['_ADMIN_SEASON_UCORR_DEF'] = 'The number of points allocated for a correct tip, per game';
$jLang['_ADMIN_SEASON_EPTS_DEF'] = 'Allow users to pick the exact score for selected games';
$jLang['_ADMIN_SEASON_EPTM_DEF'] = 'Allow users to pick the margin for selected games';
$jLang['_ADMIN_SEASON_SELECT_ALERT'] = 'You must select a season';
$jLang['_ADMIN_USER_NEW_NO_SELECTION'] = 'Please select a user from the list';
$jLang['_ADMIN_EXP_BADWORDS'] = 'BadWords';
$jLang['_ADMIN_EXP_COMMENTS'] = 'Comments';
$jLang['_ADMIN_EXP_GAMES'] = 'Games';
$jLang['_ADMIN_EXP_HISTORY'] = 'Historical Data';
$jLang['_ADMIN_EXP_ROUNDS'] = 'Rounds';
$jLang['_ADMIN_EXP_TEAMS'] = 'Teams';
$jLang['_ADMIN_EXP_SEASONS'] = 'Seasons';
$jLang['_ADMIN_EXP_USERS'] = 'Users';
$jLang['_ADMIN_EXP_TIPS'] = 'Tips';
$jLang['_ADMIN_EXP_INFO'] = 'Export Data from jTips';
$jLang['_ADMIN_EXP_PREVIOUS'] = 'Previous Exports';
$jLang['_ADMIN_EXP_SELECT_TYPE'] = 'Select Export Type';
$jLang['_ADMIN_IMP_HEADER'] = 'Import Data';
$jLang['_ADMIN_IMP_INFO'] = 'Import Data into jTips';
$jLang['_ADMIN_IMP_SELECT_TYPE'] = 'Select Data Type';
$jLang['_ADMIN_IMP_MATCH_SELECT'] = 'Update Items that Match these Fields';
$jLang['_ADMIN_IMP_COL_HEADER'] = 'Import Column Header';
$jLang['_ADMIN_IMP_FIELD_MAP'] = 'Field Mapping';
$jLang['_ADMIN_IMP_UPLOAD'] = 'Upload File';
$jLang['_ADMIN_COMM_POSTED'] = 'Posted';
$jLang['_ADMIN_LOADING'] = 'Loading...';
$jLang['_ADMIN_COMM_EDIT_COMMENT'] = 'Edit Comment';
$jLang['_ADMIN_COMM_EDIT_COMMENT_INFO'] = 'Editing Comment';
$jLang['_ADMIN_BW_HEADER'] = 'Manage BadWords';
$jLang['_ADMIN_BW_BAD_WORD'] = 'Bad Word';
$jLang['_ADMIN_BW_CASE_SENSITIVE'] = 'Case-Sensitive Match';
$jLang['_ADMIN_BW_ACTION'] = 'Action';
$jLang['_ADMIN_BW_REPLACEMENT'] = 'Replacement';
$jLang['_ADMIN_BW_HITS'] = 'Hits';
$jLang['_ADMIN_BW_UPDATED'] = 'Updated';
$jLang['_ADMIN_BW_RESET_HITS'] = 'Reset Hits';
$jLang['_COM_LADDER_SELECT_SEASON'] = '--Select A Season--';
$jLang['_ADMIN_SHOW_ALL'] = 'Show All';
$jLang['_COM_DASH_POINTSA'] = 'Average Score';
$jLang['_COM_DASH_PRECA'] = 'Average Precision';
$jLang['_MOD_NO_DATA'] = 'No data available';
$jLang['_COM_COMMENT_VALIDATING'] = 'Validating Comment...';
$jLang['_COM_GAME_LOSE'] = 'Lose';

//v2.1
$jLang['_ADMIN_CONF_HOME'] = 'Home v Away';
$jLang['_ADMIN_CONF_AWAY'] = 'Away v Home';
$jLang['_ADMIN_CONF_TIPS_PANEL_LAYOUT'] = 'Tips Panel Team Order';
$jLang['_ADMIN_CONF_TIPS_PANEL_LAYOUT_DEF'] = 'Determines which team to show on the left and right sides on the Tips Panel';
$jLang['_ADMIN_CONF_LOAD_MOOTOOLS'] = 'Load mootools';
$jLang['_ADMIN_CONF_LOAD_MOOTOOLS_DEF'] = 'Loads the mootools effects library. Only do this if your template does not use the mootools library.';
$jLang['_ADMIN_CONF_HIDE_TEAM_SELECT'] = 'Hide Team Selection Fields (SmartTips)';
$jLang['_ADMIN_CONF_HIDE_TEAM_SELECT_DEF'] = 'Only applicable with \'Pick the Score\'. When \'Pick the Score\' is enabled, and this option is set to \'Yes\', the fields to indicate which Team is selected on the Tips Panel are hidden. jTips will rely on the estimated score entered by the user to determine the selected team.';
$jLang['_COM_SHOWTIPS_PREDICTED'] = 'Predicted';
$jLang['_COM_SHOWTIPS_ACTUAL'] = 'Actual';
$jLang['_COM_SHOWTIPS_AWARDED'] = 'Awarded';
$jLang['_COM_SHOWTIPS_BONUS_TEAM'] = 'Team Bonus';
$jLang['_COM_SHOWTIPS_TOTAL'] = 'TOTAL';
$jLang['_ADMIN_SEASON_CORE_FIELDS_FS'] = 'Main Information';
$jLang['_ADMIN_SEASON_TEAM_POINTS_FS'] = 'Team Points Allocation';
$jLang['_ADMIN_SEASON_TIPS_CONFIG_FS'] = 'Tips Panel Configuration';
$jLang['_ADMIN_SEASON_USER_POINTS_FS'] = 'User Points Allocation';
$jLang['_ADMIN_SEASON_UNONE_DEF'] = 'The number of points to allocate, per round, to users that do not submit any tips (for that round). This can be any number, positive or negative. Enter -1 to have these users given the same number of points as the user that did tip and scored the lowest. Enter -2 to allocate points to these users as if they had tipped all the Away teams.';
$jLang['_ADMIN_CONFIRM_REMOVE'] = 'Are you sure you wish to delete these records?';
$jLang['_ADMIN_LANG_EDIT_VALUE'] = 'Label Definition';
$jLang['_ADMIN_BASIC_INFORMATION'] = 'Basic Information';
$jLang['_ADMIN_TEAM_POINTS_ADJUST'] = 'Points Adjustments';
$jLang['_ADMIN_TEAM_REMOVE'] = 'Deleting a team will result in invalid user scores. Are you sure you wish to continue? You can safely delete a team if the competition has not yet started.';
$jLang['_ADMIN_USERS_SELECT_SEASON'] = '- Select Season -';
$jLang['_ADMIN_USERS_INFO'] = 'This lists all your current competition participants. Select a season to filter this list, or you can remove users from a competition. To add new users to a competition manually, click the \'New\' button in the toolbar.';
$jLang['_ADMIN_COMMENTS_INFO'] = 'Any comments that have been submitted are listed here. You can edit any available comment if necessary. Select a season to filter this list.';
$jLang['_ADMIN_COMMENT_LOAD_ERROR'] = 'Error loading Comment - Comment does not exists';
$jlang['_ADMIN_COMMENT_SAVE_SUCCESS'] = 'Comment Saved!';
$jlang['_ADMIN_COMMENT_SAVE_FAILURE'] = 'Save Failed!';
$jLang['_ADMIN_COMMENTS_UDPATED_DESCRIPTION'] = 'This will be updated when the Comment is saved.';
$jLang['_ADMIN_EDIT_MAIN_INFORMATION'] = 'Main Information';
$jlang['_ADMIN_BADWORD_SAVE_SUCCESS'] = 'BadWord Saved!';
$jlang['_ADMIN_BADWORD_SAVE_FAILURE'] = 'Save Failed!';
$jLang['_ADMIN_ROUND_LEGEND'] = 'Round Details';
$jLang['_ADMIN_ROUND_GAMES_LEGEND'] = 'Games for Round';
$jLang['_ADMIN_ROUND_STATUS_SELECT'] = '- Select Status ';
$jLang['_ADMIN_ROUND_DELETED'] = 'Rounds Deleted';
$jLang['_ADMIN_ROUND_PROCESSED'] = 'Round(s) Processed';
$jLang['_ADMIN_IMPORT_UPLOAD_SUCCESS'] = 'Upload Successful';
$jLang['_ADMIN_IMPORT_UPLOAD_FAILURE'] = 'Upload Failed';
$jLang['_ADMIN_IMPORT_UPLOAD_TYPE_FAILURE'] = 'Upload Failed - Invalid File Type';
$jLang['_ADMIN_IMPORT_UPLOAD_LEGEND'] = 'File Upload';
$jLang['_ADMIN_IMP_FIELD_MAPPING_LEGEND'] = 'Field Mapping';
$jLang['_ADMIN_IMPORT_SETUP_LEGEND'] = 'Import Setup';
$jLang['_ADMIN_IMPORT_RESULT'] = 'Records Imported or Updated';
$jLang['_ADMIN_CONFIG_SAVE_SUCCESS'] = 'Configuration Saved';
$jLang['_ADMIN_CONFIG_SAVE_FAILURE'] = 'Save Failed - Configuration file is not writable';
$jLang['_ADMIN_LICENSE_USER_COUNT'] = 'Licensed Users';
$jLang['_ADMIN_GAME_TIME'] = 'Time';
$jLang['_ADMIN_SEASON_GAME_TIMES'] = 'Enable Game Times';
$jLang['_ADMIN_SEASON_GAME_TIMES_DEF'] = 'Configure and display the start time for each game';
$jLang['_COM_TIPS_TIME'] = 'Start Time';
$jLang['_ADMIN_CONF_TEAM_LADDER_POPUP'] = 'Enable Team Ladder Popup';
$jLang['_ADMIN_CONF_TEAM_LADDER_POPUP_DEF'] = 'Displays a link on the Tips Panel to popup the current Team Ladder';
$jLang['_COM_TIPS_TIPPING_CLOSE'] = 'Tipping Close Time';
$jLang['_COM_TIPS_TIME_TO_CLOSE'] = 'Time to Close';
$jLang['_COM_LAST_ROUND_SUMMARY'] = 'Last Round Summary';
$jLang['_ADMIN_CONFG_GAME_REMOVE_NONE'] = 'No game selected to remove';
$jLang['_ADMIN_SEASON_DISABLE_TIPS'] = 'Disable Tips';
$jLang['_ADMIN_SEASON_DISABLE_TIPS_DEF'] = 'Disables the Tipping functionality. jTips can then be used solely for league management.';
$jLang['_ADMIN_SEASON_SCORER'] = 'Scorer';
$jLang['_ADMIN_SEASON_SCORER_DEF'] = 'Nominate a regular user to adminster the results for this competition from the website.';
$jLang['_COM_ADMIN_RESULTS'] = 'Administration';
$jLang['_COM_ADMIN_RESULTS_PROCESS_ROUND'] = 'Processing Round';
$jLang['_COM_ADMIN_RESULTS_SELECT_ROUND'] = 'Select Round';
$jLang['_COM_ADMIN_RESULTS_SAVE_PROCESS'] = 'Save and Process Results';
$jLang['_COM_CANCEL'] = 'Cancel';
$jLang['_COM_FAILED'] = 'Failed';
$jLang['_COM_SAVE'] = 'Save';
$jLang['_ADMIN_CONF_AUTO_UPGRADE'] = 'Auto Upgrade';
$jLang['_ADMIN_CONF_AUTO_UPGRADE_DEF'] = 'Enable automatic updates. As soon as an update is available, your system will be upgraded.';
$jLang['_ADMIN_DASH_CUSTOMISATIONS'] = 'Customisations';
$jLang['_ADMIN_DASH_CUSTOMISATION_LIST'] = 'Applied Customisations';
$jLang['_ADMIN_CSTM_NO_FILE_SPECIFIED'] = 'No file specified';
$jLang['_ADMIN_EDIT_CUSTOMISATIONS_TITLE'] = 'Editing Custom File';
$jLang['_ADMIN_CSTM_SAVE_SUCCESS'] = 'File Saved';
$jLang['_ADMIN_CSTM_SAVE_FAILURE'] = 'Save Failed';
$jLang['_ADMIN_CSTM_SELECT_VIEW'] = 'Please select a view';
$jLang['_ADMIN_CSTM_FILE_EDIT'] = 'Customisation File';
$jLang['_ADMIN_CSTM_FILE_SETUP'] = 'File Setup';
$jLang['_ADMIN_CSTM_VIEW'] = 'View';
$jLang['_ADMIN_CSTM_IS_TMPL'] = 'Is Template?';
$jLang['_ADMIN_CSTM_FILE_NAME'] = 'File Name';
$jLang['_ADMIN_CUSTOMISATIONS_INFO'] = 'Create and manage custom files to alter the look and feel of the front-end of jTips.<p style="color:red;font-weight:bold;">You should have a good understanding of PHP, HTML, Javascript, CSS and the Joomla! Framework in order to use this section.</p><span style="font-weight:bold;">If you require customisations, please contact jTips at <a href="mailto:sales@jtips.com.au">sales@jtips.com.au</a>.</span>';
$jLang['_ADMIN_CSTM_FILE_UPLOAD'] = 'Upload File';
$jLang['_ADMIN_CSTM_FILES_DELETED'] = 'Files Deleted';
$jLang['_OUT_OF'] = 'out of';
$jLang['_COM_TIPS_PANEL_LOCKED'] = 'Please join the competition in order to submit Tips';

//v2.1.1
$jLang['_ADMIN_CONF_LOAD_CUSTOMCSS'] = 'Load Custom CSS';
$jLang['_ADMIN_CONF_LOAD_CUSTOMCSS_DEF'] = 'Load the customisable CSS file that can be edited through jTips. Leave this option disabled to use the default site styling.';
$jLang['_ADMIN_TEAM_CURRENT_LOGO'] = 'Current Logo';
$jLang['_ADMIN_TEAM_REMOVE_LOGO'] = 'Remove?';
$jLang['_ADMIN_TEAM_REMOVE_LOGO_DEF'] = 'This will delete the current logo for this team.';
$jLang['_COM_TEAM_WIN'] = 'Win';
$jLang['_COM_TEAM_DRAW'] = 'Draw';
$jLang['_COM_TEAM_LOSS'] = 'Loss';

//2.1.2
$jLang['_ADMIN_CONF_SHOWTIPSSTATS'] = 'ShowTips Statistics';
$jLang['_ADMIN_CONF_SHOWTIPSSTATS_DEF'] = 'Show additional statistics for the user.';
$jLang['_COM_PRECISION_SCORE'] = 'Precision Score';
$jLang['_COM_TIP_ACCURACY'] = 'Tip Accuracy';

//2.1.3
$jLang['_ADMIN_CONF_IMAGEOUTPUTFORMAT'] = 'Image Format';
$jLang['_ADMIN_CONF_IMAGEOUTPUTFORMAT_DEF'] = 'The target format for images uploaded through jTips.';

//2.1.4
$jLang['_ADMIN_DASH_EXPIRY_DATE'] = 'Renewal Date';
$jLang['_COM_YES'] = 'Yes';
$jLang['_COM_NO'] = 'No';

//2.1.5
$jLang['_ADMIN_SEASON_TOUGH_SCORE'] = 'Enable ToughScore<sup>&copy;</sup>';
$jLang['_ADMIN_SEASON_TOUGH_SCORE_DEF'] = 'Allows allocating extra point for users that make the correct pick for selected games.';
$jLang['_ADMIN_GAME_TOUGH_SCORE'] = 'ToughScore';
$jLang['_ADMIN_CONF_SHOWTIPS_PADDING'] = 'Enable ShowTips Padding';
$jLang['_ADMIN_CONF_SHOWTIPS_PADDING_DEF'] = 'If you require some extra space around the content displayed in the ShowTips and ShowTeam popups, enable this option.';

// v2.1.6
$jLang['_COM_CLOSED'] = 'Closed';
$jLang['_COM_BYES'] = 'Byes';
$jLang['_COM_CLICK_TO_EXPAND'] = 'Click to expand';
$jLang['_COM_BYE'] = 'Bye';
$jLang['_ADMIN_CONF_SOCIAL_INTEGRATION'] = 'Social Integration';
$jLang['_ADMIN_CONF_SOCIAL_INTEGRATION_DEF'] = 'Integrate jTips with Community Builder or JomSocial. Provides user avatars and link to user profiles. NOTE: JomSocial integration requires Joomla! 1.5.7 or greater.';
$jLang['_ADMIN_CONF_SOCIAL_CBI'] = 'Community Builder';
$jLang['_ADMIN_CONF_SOCIAL_CCI'] = 'JomSocial';

$jLang['_ADMIN_CONF_ALPHAUSERPOINTS'] = 'Enable AlphaUserPoints Integration';
$jLang['_ADMIN_CONF_ALPHAUSERPOINTS_DEF'] = 'Requires the jTips AlphaUserPoints Total Points Plugin for AlphaUserPoints. This will add the number of points a user scores per round to their AlphaUserPoints total.';

$jLang['_ADMIN_CONF_JOMSOCIAL_ACTIVITIES'] = 'Enable JomSocial Activity Stream';
$jLang['_ADMIN_CONF_JOMSOCIAL_ACTIVITIES_DEF'] = 'Writes messages to the JomSocial activity stream when a round in processed.';

// v2.1.9
$jLang['_COM_TLD_PERCENTAGE'] = 'F/A Percentage';
$jLang['_COM_TLD_ABR_PERCENTAGE'] = '%';
$jLang['_COM_TIPS_EMAIL_SUCCESS'] = 'Tips Notification Sent';
$jLang['_COM_TIPS_EMAIL_FAILURE'] = 'Failed to send Tips Notification';
$jLang['_COM_TIPS_SAVED_MESSAGE'] = 'Tips Saved';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONSAVETIPS'] = 'When User Submits Tips';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONSAVETIPS_DEF'] = 'When a user submits or updates their tips, add a message to JomSocial - requires the JomSocial Activity Stream option to be enabled'.
$jLang['_ADMIN_CONF_JOMSOCIAL_USER_RESULTS'] = 'User Results';
$jLang['_ADMIN_CONF_JOMSOCIAL_USER_RESULTS_DEF'] = "When a round is processed, add a message with the each user's score";
$jLang['_ADMIN_CONF_JOMSOCIAL_ONNOTIPS'] = 'No Tips Submitted';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONNOTIPS_DEF'] = 'If a user does not submit their tips, add a message to JomSocial so everyone knows.';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONUSERJOIN'] = 'User Joins Competition';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONUSERJOIN_DEF'] = 'Add a message to JomSocial when a new user registers/joins a competition.';
$jLang['_ADMIN_SEARCH'] = 'Search';
$jLang['_ADMIN_RESET'] = 'Reset';
$jLang['_ADMIN_GO'] = 'Go';
$jLang['_ADMIN_ROUND_COPY'] = 'Copy Round';
$jLang['_ADMIN_ROUND_COPY_NOROUND'] = 'No round specified';
$jLang['_ADMIN_ROUND_COPY_INFO'] = 'Enter the round number to copy this round to. Immediately edit the round and update the dates and times and each of the games.';
$jLang['_ADMIN_ROUND_COPY_FROM'] = 'Copy Round To';
$jLang['_ADMIN_ROUND_COPYING'] = 'Copying';
$jLang['_ADMIN_ROUND_COPY_TO_SEASON'] = 'Copy To Season';
$jLang['_ADMIN_ROUND_COPY_TO_ROUND'] = 'Copy To Round';
$jLang['_ADMIN_ROUND_COPY_FAIL'] = 'Failed to copy round';
$jLang['_ADMIN_ROUND_COPY_SUCCESS'] = 'Round Copied';
$jLang['_ADMIN_CONF_SHOWTIPS_INPROGRESS'] = 'After Round Closes';
$jLang['_COM_JOIN_COMPLETE'] = 'You are now part of the competition. Good luck!';
$jLang['_COM_JOIN_PENDING'] = 'Your application is pending approval';
$jLang['_COM_JOIN_CONFIRM_MESSAGE'] = 'Are you sure you wish to subscribe to this competition?';
$jLang['_COM_REGISTRATIONS_DISABLED'] = 'Registrations currently disabled';
$jLang['_ADMIN_CONF_REGISTERALLCOMP'] = 'Registration User for All';
$jLang['_ADMIN_CONF_REGISTERALLCOMP_DEF'] = 'When enabled, a users will be added to all competitions when subscribing from the website. The User Management area will only add users to a single competition.';
$jLang['_COM_UNSUBSCRIBE_SUCCESS'] = 'You have been successfully removed from the competition';
$jLang['_ADMIN_ROUNDS_NONE_TO_PROCESS'] = 'No Rounds Selected';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONUSERJOIN_MSG'] = 'JomSocial On User Join Message';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONUSERJOIN_MSG_DEF'] = 'The text written to the JomSocial Activity Stream when a new user joins a competition. Available variables are: <ul><li>{actor} - This is always required!</li><li>{season} - The name of the season/competition</li></ul>';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONSAVETIPS_MSG'] = 'JomSocial On Save Tips Message';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONSAVETIPS_MSG_DEF'] = 'The text written to the JomSocial Activity Stream when a user submits their tips. Available variables are: <ul><li>{actor} - This is always required!</li><li>{season} - The name of the season/competition</li><li>{round} - The round number.</li></ul>';
$jLang['_ADMIN_CONF_JOMSOCIAL_RESULTS_MSG'] = 'JomSocial Round Results Message';
$jLang['_ADMIN_CONF_JOMSOCIAL_RESULTS_MSG_DEF'] = 'The text written to the JomSocial Activity Stream when a round is processed. Available variables are: <ul><li>{actor} - This is always required!</li><li>{season} - The name of the season/competition</li><li>{round} - The round number.</li><li>{score} - The users score for the round</li></ul>';
$jLang['_ADMIN_CONF_JOMSOCIAL_WINNERS_MSG'] = 'JomSocial Round Winners Message';
$jLang['_ADMIN_CONF_JOMSOCIAL_WINNERS_MSG_DEF'] = 'The text written to the JomSocial Activity Stream when a round is processed about the winners for the round. Available variables are: <ul><li>{actor} - This is always required!</li><li>{season} - The name of the season/competition</li><li>{round} - The round number.</li></ul>';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONNOTIPS_MSG'] = 'JomSocial No Tips Message';
$jLang['_ADMIN_CONF_JOMSOCIAL_ONNOTIPS_MSG_DEF'] = 'The text written to the JomSocial Activity Stream when a round is processed about users that failed to submit their tips. Available variables are: <ul><li>{actor} - This is always required!</li><li>{season} - The name of the season/competition</li><li>{round} - The round number.</li></ul>';
$jLang['_COM_JOIN_NOW'] = 'Join Now!';
$jLang['_COM_REGISTER_TITLE'] = 'Register';

// v2.1.10
$jLang['_ADMIN_LIC_VALIDATION_SUCCESS'] = 'Validation Successful';
$jLang['_ADMIN_LIC_WRITE_FAILED'] = 'Could not write licence data';
$jLang['_ADMIN_LIC_VALIDATION_FAILURE'] = 'Failed to validate licence';
$jLang['_COM_DASHBOARD'] = 'Dashboard';
$jLang['_COM_COMPETITION_LADDER'] = 'Competition Ladder';
$jLang['_ADMIN_CONF_JOMSOCIAL_LINK'] = 'Link Season Name To';
$jLang['_ADMIN_CONF_JOMSOCIAL_LINK_DEF'] = 'The season/competition name in the JomSocial Activity Stream will link to the selected area of jTips. If nothing selected, the season name will be displayed.';
$jLang['_ADMIN_ROUNDS_UNCOMPLETED'] = 'Round(s) Marked UnComplete';

// v2.1.11
$jLang['_ADMIN_LANGUAGE_UPDATED'] = 'Language item updated';
$jLang['_ADMIN_LANGUAGE_UPDATE_FAILED'] = 'Failed to updated language item. Is the file writable?';

// v2.1.12
$jLang['_ADMIN_STYLE_EDIT'] = 'CSS Stylesheet Editor';
$jLang['_ADMIN_STYLE_FILENAME'] = 'CSS Filename';
$jLang['_ADMIN_STYLE_CONTENT'] = 'CSS Content';
$jLang['_ADMIN_STYLE_BAD_FILENAME'] = 'Invalid filename protection';
$jLang['_COM_SHOWTIPS_THIS_ROUND'] = 'This Round';
$jLang['_COM_SHOWTIPS_OVERALL'] = 'Overall';
$jLang['_COM_SHOWTIPS_ROUND'] = 'Round';

// v2.1.14
$jLang['_COM_ADMIN_CLEAN_USERS'] = 'Clean';
$jLang['_COM_ADMIN_USERS_CLEANED_MESSAGE'] = '%s Users Cleaned From jTip(s)';
$jLang['_ADMIN_CONF_TIME_MINUTES'] = 'Show All Minutes';
$jLang['_ADMIN_CONF_TIME_MINUTES_DEF'] = 'When configuring rounds, allow all minute values, for 0 to 59 to be selected';
$jLang['_ADMIN_CONF_IS24HOUR'] = 'Use 24-Hour Format In Admin';
$jLang['_ADMIN_CONF_IS24HOUR_DEF'] = 'Use 24-Hour time format when enter rounds';
$jLang['_COM_ROUND_START_TIME'] = 'Round Start';
$jLang['_COM_ROUND_TIME_TO_START'] = 'Time to Start';
$jLang['_ADMIN_ROUNDS_INFO'] = 'Additional Info';
$jLang['_COM_GAME_ADDITIONAL_INFO'] = 'Additional Information';
$jLang['_COM_CANNOT_POST_CLOSED'] = 'Tips not saved. Round in progress.';
$jLang['_COM_NOT_A_SCORER'] = 'You are not a scorer for this competition';
$jLang['_ADMIN_ROUND_SHOW_COMMENTS'] = 'Show Additional Info';

// v2.1.15
$jLang['_ADMIN_CONF_POSTDASHTEXT'] = 'Post Dashboard Message';
$jLang['_ADMIN_CONF_POSTDASHTEXT_DEF'] = 'Optional message to display beneath the Dashboard registration link. HTML is allowed.';
$jLang['_COM_DASH_GOTO_TIPS'] = 'Place your tips!';
$jLang['_ADMIN_REMINDER_DEFAULT'] = 'Enable Reminders By Default';
$jLang['_ADMIN_REMINDER_DEFAULT_DEF'] = 'Set reminder email notifications as ON for new competition participants. They can then optionally opt-out from the Preferences area.';
?>
