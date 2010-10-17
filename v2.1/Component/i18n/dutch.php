<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');

//Info Dash
$jLang['_COM_NO_USER'] = 'Je hebt momenteel geen toegang tot de jTips competitie. Neem contact op met administrator';
$jLang['_COM_DASH_TITLE'] = 'Info Overzicht';
$jLang['_COM_DASH_ROUND'] = 'Speelronde';
$jLang['_COM_DASH_SCORE'] = 'Score';
$jLang['_COM_DASH_AVERAGE'] = 'Gemiddelde';
$jLang['_COM_DASH_PROJECTED'] = 'Aantal voorspellingen';
$jLang['_COM_DASH_DOUBLEUP'] = 'DoubleUP';
$jLang['_COM_DASH_PAID'] = 'Betaald';
$jLang['_COM_TIP_LADDER_TITLE'] = 'Ranglijst';
$jLang['_COM_TIP_LADDER_USER'] = 'Gebruiker';
$jLang['_COM_TIP_LADDER_LAST'] = 'Laatste';
$jLang['_COM_TIP_LADDER_SCORE'] = 'Score';
$jLang['_COM_TIP_LADDER_VIEW_PROFILE'] = 'Bekijk Profiel';
$jLang['_COM_MYTIPS_TITLE'] = 'Mijn voorspellingen van deze week';
$jLang['_COM_MYTIPS_UNAVAILABLE'] = 'Volgende speelronde niet beschikbaar';
$jLang['_COM_MYTIPS_NOTIPS'] = 'Niets ingevoerd';
$jLang['_COM_MYTIPS_SUBMIT'] = 'Voorspellingen invoeren';
$jLang['_COM_MYTIPS_TIPS_PANEL'] = 'Voorspellingen invoeren';
$jLang['_COM_TEAMS_TITLE'] = 'Ranglijst';
$jLang['_COM_TEAMS_UNAVAILABLE'] = 'Ranglijst niet beschikbaar';

//Tips Panel
$jLang['_COM_TIPS_PANEL'] = 'Voorspellingen invoeren';
$jLang['_COM_PREV_ROUND'] = 'Vorige';
$jLang['_COM_NEXT_ROUND'] = 'Volgende';
$jLang['_COM_CLOSE_TIME'] = 'Eindigt op';
$jLang['_COM_CLOSE_DATE'] = 'op';
$jLang['_COM_GAME_HOME'] = 'Thuis';
$jLang['_COM_GAME_AWAY'] = 'Uit';
$jLang['_COM_GAME_DRAW'] = 'Gelijkspel';
$jLang['_COM_GAME_USEDOUBLE'] = 'Gebruik DoubleUP';
$jLang['_COM_ROUND_CLOSED'] = 'Voor deze speelronde kunnen geen voorspellingen meer gedaan worden';
$jLang['_COM_ROUND_NOGAMES'] = 'Er zijn geen wedstrijden voor deze speelronde';

//Configuration
$jLang['_ADMIN_CONF_TITLE'] = 'jTips Configuratie';
$jLang['_ADMIN_CONF_SETTING'] = 'Optie';
$jLang['_ADMIN_CONF_VARIABLE'] = 'Variabele';
$jLang['_ADMIN_CONF_DESCRIPTION'] = 'Omschrijving';
$jLang['_ADMIN_CONF_GPR'] = 'Max wedstrijden per speelronde';
$jLang['_ADMIN_CONF_GPR_DEF'] = 'Het waarschijnlijk maximum aantal wedstrijden voor dit seizoen';
$jLang['_ADMIN_CONF_RPS'] = 'Speelrondes voor seizoen';
$jLang['_ADMIN_CONF_RPS_DEF'] = 'Aantal speelrondes voor het seizoen';
$jLang['_ADMIN_CONF_BP'] = 'Bonus punten';
$jLang['_ADMIN_CONF_BP_DEF'] = 'Wanneer een gebruiker een perfecte speelronde scoort (het maximum aan mogelijke punten), dan kun je deze speler bonus punten toekennen';
$jLang['_ADMIN_CONF_CT'] = 'Correcte voorspelling';
$jLang['_ADMIN_CONF_CT_DEF'] = 'Het aantal punten, die aan een correcte voorspelling worden toegekend';
$jLang['_ADMIN_CONF_MT'] = 'Geen voorspelling gedaan';
$jLang['_ADMIN_CONF_MT_DEF'] = 'Het aantal punten, dat toegekend worden aan een speler, die g��n voorspelling heeft gedaan. Zet dit op -1 om deze gebruiker hetzelfde aantal punten toe te kennen als de speler (die w�l een voorspelling heeft gedaan) en de laagste score heeft behaald';
$jLang['_ADMIN_CONF_TD'] = 'Gelijkspel kan voorspeld worden';
$jLang['_ADMIN_CONF_TD_DEF'] = 'Spelers kunnen een gelijkspel als uitslag voorspellen';
$jLang['_ADMIN_CONF_DB'] = 'Gelijkspel bonus';
$jLang['_ADMIN_CONF_DB_DEF'] = 'Het aantal punten, die aan een correcte voorspelling toegekend worden';
$jLang['_ADMIN_CONF_TWP'] = 'Punten winnend team';
$jLang['_ADMIN_CONF_TWP_DEF'] = 'Het aantal punten, die aan een winnend team toegekend worden';
$jLang['_ADMIN_CONF_TDP'] = 'Punten gelijkspelende teams';
$jLang['_ADMIN_CONF_TDP_DEF'] = 'Het aantal punten, die aan de gelijkspelende teams toegekend worden';
$jLang['_ADMIN_CONF_TLP'] = 'Punten verliezend team';
$jLang['_ADMIN_CONF_TLP_DEF'] = 'Het aantal punten, die aan een verliezend team toegekend worden';
$jLang['_ADMIN_CONF_TBP'] = 'Punten niet-spelende team';
$jLang['_ADMIN_CONF_TBP_DEF'] = 'Het aantal punten, die aan een niet-spelende team toegekend worden';
$jLang['_ADMIN_CONF_EP'] = 'Betalingen activeren';
$jLang['_ADMIN_CONF_EP_DEF'] = 'Activeer betalingen voor de competitie';
$jLang['_ADMIN_CONF_DN'] = 'Gebruikersnaam';
$jLang['_ADMIN_CONF_DN_DEF'] = 'Toon volledige naam of gebruikers naam in de ranglijst';
$jLang['_ADMIN_CONF_CBI'] = 'Integratie met CB';
$jLang['_ADMIN_CONF_CBI_DEF'] = 'Link naar Community Builder gebruikersprofiel vanuit de ranglijst';
$jLang['_ADMIN_CONF_SNS'] = 'Start nieuw seizoen';
$jLang['_ADMIN_CONF_SNS_DEF'] = 'Leeg alle speelrondes en wedstrijd informatie en reset gebruikers, om een nieuw seizoen op te starten';
$jLang['_ADMIN_CONF_UPDATE'] = 'Database bijwerken';
$jLang['_ADMIN_CONF_UPDATE_DEF'] = 'Werk database tabellen bij met de meest recente jTips versie';

//Team Manager
$jLang['_ADMIN_TEAM_TITLE'] = 'Team Manager';
$jLang['_ADMIN_TEAM_TEAM'] = 'Team';
$jLang['_ADMIN_TEAM_LOCATION'] = 'Locatie';
$jLang['_ADMIN_TEAM_NAME'] = 'Teamnaam';
$jLang['_ADMIN_TEAM_LOCAREA'] = 'Locatie';
$jLang['_ADMIN_TEAM_ABOUT'] = 'Overige info';

//Round Manager
$jLang['_ADMIN_ROUND_TITLE'] = 'Spelronde Manager';
$jLang['_ADMIN_ROUND_START'] = 'Aanvang';
$jLang['_ADMIN_ROUND_END'] = 'Einde';
$jLang['_ADMIN_ROUND_ADDRESULT'] = 'Uitslag toevoegen';
$jLang['_ADMIN_ROUND_ROUND'] = 'Spelronde';
$jLang['_ADMIN_ROUND_DATE'] = 'Datum';
$jLang['_ADMIN_ROUND_TIME'] = 'Tijd';
$jLang['_ADMIN_ROUND_GAMES'] = 'Wedstrijden';
$jLang['_ADMIN_ROUND_USE'] = 'Use';
$jLang['_ADMIN_ROUND_HOME'] = 'Thuis';
$jLang['_ADMIN_ROUND_AWAY'] = 'Uit';
$jLang['_ADMIN_ROUND_ORDER'] = 'Volgorde';
$jLang['_ADMIN_ROUND_WINNER'] = 'Winnaar';
$jLang['_ADMIN_ROUND_DRAW'] = 'Gelijkspel';
$jLang['_ADMIN_ROUND_NOTEAMS'] = 'Geen teams beschikbaar!';
$jLang['_ADMIN_ROUND_NOTSTARTED'] = 'Spelronde nog niet gestart!!!';
$jLang['_ADMIN_ROUND_INPROGRESS'] = 'Spelronde is nog bezig!!!';

//User Manager
$jLang['_ADMIN_USERS_TITLE'] = 'User Manager';
$jLang['_ADMIN_USERS_USERNAME'] = 'Gebruikersnaam';
$jLang['_ADMIN_USERS_DOUBLE'] = 'DoubleUP';
$jLang['_ADMIN_USERS_AVERAGE'] = 'Gemiddeld';
$jLang['_ADMIN_USERS_TOTAL'] = 'Totaal';
$jLang['_ADMIN_USERS_PAID'] = 'Gedoneerd';
$jLang['_ADMIN_USERS_SELECT'] = 'Selecteer gebruiker';
$jLang['_ADMIN_USERS_AVERAGE_SCORE'] = 'Gemiddelde score';
$jLang['_ADMIN_USERS_TOTAL_SCORE'] = 'Totale score';
$jLang['_ADMIN_USERS_ROUND'] = 'Spelronde';
$jLang['_ADMIN_USERS_NEWUSER'] = 'Nieuwe gebruiker';

//Admin (Other)
$jLang['_ADMIN_OTHER_EDIT'] = 'Bijwerken';
$jLang['_ADMIN_OTHER_EDITING'] = 'Wordt bijgewerkt';
$jLang['_ADMIN_OTHER_NEW'] = 'Nieuw';
?>