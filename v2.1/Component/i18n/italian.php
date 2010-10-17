<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');

global $jLang, $mosConfig_absolute_path;

$jLang = array();
//Info Dash
$jLang['_COM_NO_USER'] = "Attualmente non fai parte della competizione. Contatta l'amministratore per ulteriori informazioni";
$jLang['_COM_DASH_TITLE'] = 'Bacheca Informativa';
$jLang['_COM_DASH_ROUND'] = 'Turno';
$jLang['_COM_DASH_SCORE'] = 'Punteggio';
$jLang['_COM_DASH_AVERAGE'] = 'Media';
$jLang['_COM_DASH_PROJECTED'] = 'Proiezione';
$jLang['_COM_DASH_DOUBLEUP'] = 'Doppio UP';
$jLang['_COM_DASH_PAID'] = 'Pagato';
$jLang['_COM_TIP_LADDER_TITLE'] = 'Classifica Tipster';
$jLang['_COM_TIP_LADDER_USER'] = 'User';
$jLang['_COM_TIP_LADDER_LAST'] = 'Ultimo';
$jLang['_COM_TIP_LADDER_SCORE'] = 'Punteggio';
$jLang['_COM_TIP_LADDER_VIEW_PROFILE'] = 'Vedi Profilo';
$jLang['_COM_MYTIPS_TITLE'] = 'I Miei Prono del Week-end';
$jLang['_COM_MYTIPS_UNAVAILABLE'] = 'Prossimo Turno non disponibile';
$jLang['_COM_MYTIPS_NOTIPS'] = 'Nessun dato inserito';
$jLang['_COM_MYTIPS_SUBMIT'] = 'Inserisci Pronostico';
$jLang['_COM_MYTIPS_TIPS_PANEL'] = 'Pannello Pronostici';
$jLang['_COM_TEAMS_TITLE'] = 'Classifica Squadre';
$jLang['_COM_TEAMS_UNAVAILABLE'] = 'Classifica non disponibile';

//1.0 Additions to Dashboard
$jLang['_COM_DASH_CURR_ROUND'] = 'Turno in corso..';
$jLang['_COM_DASH_TOT_ROUND'] = 'Turni Totali';
$jLang['_COM_DASH_GPER_ROUND'] = 'Partite per Turno';
$jLang['_COM_DASH_START'] = 'Inizio';
$jLang['_COM_DASH_END'] = 'Fine';
$jLang['_COM_DASH_SEASON'] = 'Stagione';
$jLang['_COM_DASH_RANK'] = 'Rank';
$jLang['_COM_DASH_LAST_WON'] = 'Ultimo Turno Vinto da';
$jLang['_COM_DASH_LAST_ROUND'] = 'Ultimo Turno';
$jLang['_COM_DASH_LAST_TOT'] = 'Totale';
$jLang['_COM_DASH_LAST_COMM'] = 'Commenti';
$jLang['_COM_DASH_NO_ROUNDS'] = 'Nessun Turno Giocato';
$jLang['_COM_DASH_PAYPAL_ALT'] = 'Paga adesso con Paypal';
$jLang['_COM_DASH_TIPPED'] = 'Pronosticato';
$jLang['_COM_DASH_NOT_TIPPED'] = 'Non Pronosticato';
$jLang['_COM_DASH_ITALICS_DEF'] = 'Italics indica che � stato pronosticato il Pareggio';
$jLang['_COM_DASH_JOIN_COMP'] = 'Iscriviti alla Competizione';
$jLang['_COM_DASH_JOIN_PEND'] = 'In Approvazione..';
$jLang['_COM_DASH_REG_REQ'] = 'Registrati per partecipae alla Competizione';
$jLang['_COM_DASH_REG_DENY'] = 'Registrazioni chiuse per questa Competizione';
$jLang['_COM_DASH_NOSEASONS'] = 'Nessuna Stagione Trovata!';
$jLang['_COM_DASH_PRECISION'] = 'Precisione';
$jLang['_COM_DASH_LAST_PREC'] = 'Ultima Precisione';
$jLang['_COM_DASH_LAST_PRECT'] = 'Precisione';
$jLang['_COM_DASH_GAMES_TIPPED'] = 'Partite Pronosticate';
$jLang['_COM_DASH_OVERLIB_DRAW'] = 'Pareggi Pronosticati';
$jLang['_COM_DASH_SUMMARY'] = 'Mio Punteggio Totale';
$jLang['_COM_DASH_TO_BEAT'] = 'vince con';
$jLang['_COM_DASH_TO_DRAW'] = 'pareggia con';
$jLang['_COM_DASH_COMMENT'] = 'Commento';
$jLang['_COM_DASH_USER'] = 'User';
$jLang['_COM_DASH_POINTS'] = 'Punteggio';
$jLang['_COM_DASH_PREC'] = 'Precisione';
$jLang['_COM_DASH_POINTST'] = 'Punteggio Totale';
$jLang['_COM_DASH_PRECT'] = 'Totale Precisione';
$jLang['_COM_DASH_NO_SEASONS'] = 'Nessuna Competizione Disponibile';
$jLang['_COM_DASH_MOVED'] = '';

//Team Ladder
$jLang['_COM_TLD_TEAM'] = 'Squadra';
$jLang['_COM_TLD_POINTS_SHORT'] = 'Punti';
$jLang['_COM_TLD_ABR_PLAYED'] = 'G';
$jLang['_COM_TLD_PLAYED'] = 'Giocate';
$jLang['_COM_TLD_ABR_WINS'] = 'V';
$jLang['_COM_TLD_WINS'] = 'Vinte';
$jLang['_COM_TLD_ABR_DRAWS'] = 'N';
$jLang['_COM_TLD_DRAWS'] = 'Nulle';
$jLang['_COM_TLD_ABR_LOSSES'] = 'P';
$jLang['_COM_TLD_LOSSES'] = 'Perse';
$jLang['_COM_TLD_ABR_POINTS_FOR'] = 'O';
$jLang['_COM_TLD_POINTS_FOR'] = 'Ottenuti (Punti Conquistati)';
$jLang['_COM_TLD_ABR_POINTS_AGAINST'] = 'C';
$jLang['_COM_TLD_POINTS_AGAINST'] = 'Concessi (Punti Concessi)';
$jLang['_COM_TLD_ABR_FOR_AGAINST'] = 'O-C';
$jLang['_COM_TLD_FOR_AGAINST'] = 'Differenza tra punti Ottenuti-Concessi';
$jLang['_COM_TLD_ABR_POINTS'] = 'P';
$jLang['_COM_TLD_POINTS'] = 'Punti (Punti Totali della Stagione)';
$jLang['_COM_TLD_LEGEND'] = 'Legenda';


//Tips Panel
$jLang['_COM_TIPS_PANEL'] = 'Pannello Pronostici';
$jLang['_COM_PREV_ROUND'] = 'Precedente';
$jLang['_COM_NEXT_ROUND'] = 'Successivo';
$jLang['_COM_CLOSE_TIME'] = 'Chiude alle';
$jLang['_COM_CLOSE_DATE'] = 'il';
$jLang['_COM_GAME_HOME'] = 'Casa';
$jLang['_COM_GAME_AWAY'] = 'Trasferta';
$jLang['_COM_GAME_DRAW'] = 'Pareggio';
$jLang['_COM_GAME_USEDOUBLE'] = 'Usa Doppio UP';
$jLang['_COM_ROUND_CLOSED'] = 'I Pronostici per questo Turno sono stati chiusi';
$jLang['_COM_ROUND_NOGAMES'] = 'Nessuna Partita trovata per questo Turno in questa Stagione';

//1.0 Additions to Tips Panel
$jLang['_COM_TIPS_HOMESCORE'] = 'Gol Casa ';
$jLang['_COM_TIPS_AWAYSCORE'] = 'Gol Trasferta';
$jLang['_COM_TIPS_MARGIN'] = 'Margine';
$jLang['_COM_TIPS_BONUS'] = 'Bonus';
$jLang['_COM_TIPS_SAVE'] = 'Salva Pronostico';
$jLang['_COM_TIPS_SHOWHIDE'] = 'Mostra/Nascondi Classifica';
$jLang['_COM_TIPS_TEAMLAD'] = 'Classifica Squadre';
$jLang['_COM_TIPS_NEVER'] = 'Mai';
$jLang['_COM_TIPS_LASTUP'] = 'Ultimo Aggiornamento';

//1.0 New - Competition Ladder
$jLang['_COM_COMP_LADAT'] = 'Classifica al Turno';
$jLang['_COM_COM_OF'] = 'di';
$jLang['_COM_COMP_OVERALL'] = 'Leaderboard Totale';
$jLang['_COM_COMP_RANK'] = 'Rank';
$jLang['_COM_COMP_NAME'] = 'Nome';
$jLang['_COM_COMP_LASTR'] = 'Ultimo Turno';
$jLang['_COM_COMP_TOTAL'] = 'Totale';
$jLang['_COM_COMP_ROUNDSEL'] = 'Seleziona un Turno della Stagione';
$jLang['_COM_COMP_PRECISION'] = 'Precisione';
$jLang['_COM_TIPS_RESULT'] = 'Risultati';

//Configuration
$jLang['_ADMIN_CONF_TITLE'] = 'jTips Configurazione';
$jLang['_ADMIN_CONF_SETTING'] = 'Setting';
$jLang['_ADMIN_CONF_VARIABLE'] = 'Variabile';
$jLang['_ADMIN_CONF_DESCRIPTION'] = 'Descrizione';
$jLang['_ADMIN_CONF_GPR'] = 'Numero Partite per Turno';
$jLang['_ADMIN_CONF_GPR_DEF'] = 'Il massimo numero di partite per turno';
$jLang['_ADMIN_CONF_RPS'] = 'Turni per Stagione';
$jLang['_ADMIN_CONF_RPS_DEF'] = 'Il numero dei turni per la stagione';
$jLang['_ADMIN_CONF_BP'] = 'Punti Bonus';
$jLang['_ADMIN_CONF_BP_DEF'] = 'Se un utente centra il Turno Perfetto (il massimo dei punti), puoi premiarlo con punti Bonus';
$jLang['_ADMIN_CONF_CT'] = 'Pronostico Corretto';
$jLang['_ADMIN_CONF_CT_DEF'] = 'Il numero dei punti da assegnare ad un pronostico esatto';
$jLang['_ADMIN_CONF_MT'] = 'Pronostico non Inserito';
$jLang['_ADMIN_CONF_MT_DEF'] = "Il numero dei punti assegnato agli utenti che non inseriscono pronostici. Imposta -1 to per assegnare a questi utenti l'equivalente degli utenti che hanno ottenuto il peggior punteggio";
$jLang['_ADMIN_CONF_TD'] = 'Abilita Pareggio';
$jLang['_ADMIN_CONF_TD_DEF'] = 'Gli Utenti possono pronosticare anche il Pareggio come esito finale';
$jLang['_ADMIN_CONF_DB'] = 'Bonus Pareggio';
$jLang['_ADMIN_CONF_DB_DEF'] = 'Il numero di punti da assegnare agli utenti che hanno pronosticato il pareggio';
$jLang['_ADMIN_SEASON_TWP'] = 'Punti Squadra Vincente';
$jLang['_ADMIN_SEASON_TWP_DEF'] = 'Numero Punti da assegnare alla squadra vincente';
$jLang['_ADMIN_SEASON_TDP'] = 'Punti Pareggio';
$jLang['_ADMIN_SEASON_TDP_DEF'] = 'Numero punti da assegnare alle squadre di partite che terminano in pareggio';
$jLang['_ADMIN_SEASON_TLP'] = 'Punti Squadra Perdente';
$jLang['_ADMIN_SEASON_TLP_DEF'] = 'Numero Punti da assegnare alla squadra perdente';
$jLang['_ADMIN_SEASON_TBP'] = 'Team Bye Points';
$jLang['_ADMIN_SEASON_TBP_DEF'] = 'The number of points awarded to a team that has a bye';
$jLang['_ADMIN_SEASON_EPTB'] = "Abilita 'Seleziona il Bonus'";
$jLang['_ADMIN_SEASON_EPTB_DEF'] = 'Abilita gli utenti a selezionare a quale squadra(e) sar� assegnato il punto(i) Bonus';
$jLang['_ADMIN_SEASON_EPTD'] = "Abilita 'Seleziona il Pareggio'";
$jLang['_ADMIN_SEASON_EPTD_DEF'] = 'Abilita gli utenti a pronosticare il pareggio come risultato finale';
$jLang['_ADMIN_SEASON_EPTB_DIS'] = 'Disabilita';
$jLang['_ADMIN_SEASON_EPTB_SIN'] = 'Singola Squadra';
$jLang['_ADMIN_SEASON_EPTB_BOT'] = 'Entrambe le Squadre';
$jLang['_ADMIN_SEASON_BONUS_TEAM'] = 'Valore Punti Bonus per Squadra';
$jLang['_ADMIN_SEASON_EPTS'] = "Abilita 'Risultato Esatto'";
$jLang['_ADMIN_SEASON_EPTM'] = "Abilita 'Inserisci il  Margine'";
$jLang['_ADMIN_SEASON_UCORR'] = 'Punti Utente per Pronostici Corretti';
$jLang['_ADMIN_SEASON_UDRAW'] = 'Punti Utente per Pronostici Pareggio Corretti';
$jLang['_ADMIN_SEASON_UNONE'] = 'Punti per gli utenti che non hanno inserito pronostici';
$jLang['_ADMIN_SEASON_UPERF'] = 'Pronostici Utente per "Perfect Round Points"';
$jLang['_ADMIN_SEASON_USCOR'] = 'Utenti selezionano il Risultato Esatto Finale';
$jLang['_ADMIN_SEASON_UMARG'] = 'Utenti selezionano i Punti Margine Corretti';
$jLang['_ADMIN_SEASON_UBONU'] = 'Utenti selezionano i Punti Bonus per Squadra corretti';
$jLang['_ADMIN_SEASON_UPGRADE'] = 'Clicca qui per aggiornare JTips 1.0 Ultimate per usare multiple stagioni/competizioni!';
$jLang['_ADMIN_SEASON_NAMEONLY'] = 'Solo Nome Squadre';
$jLang['_ADMIN_SEASON_LOGOONLY'] = 'Solo Logo Squadre';
$jLang['_ADMIN_SEASON_NAMELOGO'] = 'Nome Squadre &amp; Logos';
$jLang['_ADMIN_SEASON_TIPSDISPLAY'] = 'Mostra Squadre in Inserisci Pronostici';
$jLang['_ADMIN_SEASON_LINKURL'] = 'Immagine Link URL';
$jLang['_ADMIN_SEASON_LOGO_PATH'] = 'Logo Stagione';
$jLang['_ADMIN_SEASON_MANAGER'] = 'Amministra Stagione';
$jLang['_ADMIN_GAME_BONUS'] = 'Punti Bonus';
$jLang['_ADMIN_CONF_EP'] = 'Abilita Pagamento';
$jLang['_ADMIN_CONF_EP_DEF'] = 'Abilita pagamento per la Competizione Tipster';
$jLang['_ADMIN_CONF_DN'] = 'Mostra Nome';
$jLang['_ADMIN_CONF_DN_DEF'] = 'Mostra nome completo o username nella Classifica Tipsters';
$jLang['_ADMIN_CONF_CBI'] = 'Integratione CB (Community Builder)';
$jLang['_ADMIN_CONF_CBI_DEF'] = 'Linka il Community Builder profilo  dalla classifica della competizione';
$jLang['_ADMIN_CONF_SNS'] = 'Avvia Nuova Stagione';
$jLang['_ADMIN_CONF_SNS_DEF'] = 'Svuota tutti le gare e i turni e il profilo utenti, pronti per una nuova stagione';
$jLang['_ADMIN_CONF_UPDATE'] = 'Aggiorna database';
$jLang['_ADMIN_CONF_UPDATE_DEF'] = "Aggiorna le tabelle del database per usarle con l'ultima versione di jTips";

//1.0 Additions
$jLang['_ADMIN_CONF_UPGRADE'] = 'Clicca qui per aggiornare!';
$jLang['_ADMIN_CONF_TAB_GENERAL'] = 'Generale';
$jLang['_ADMIN_CONF_TAB_DISPLAY'] = 'Display';
$jLang['_ADMIN_CONF_TAB_SCORING'] = 'Punteggio';
$jLang['_ADMIN_CONF_TAB_PAYPAL'] = 'PayPal';
$jLang['_ADMIN_CONF_TAB_NOTIFY'] = 'Notifiche';
$jLang['_ADMIN_CONF_TAB_IMPORT'] = 'Importa';
$jLang['_ADMIN_CONF_TAB_EXPORT'] = 'Esporta';
$jLang['_ADMIN_CONF_TAB_ACTIVATE'] = 'Attiva';
$jLang['_ADMIN_CONF_LOCATION'] = 'Joomla! Location';
$jLang['_ADMIN_CONF_LOCATION_DEF'] = 'Se Joomla! non � installato nella root principale, inserisci la directory di installazione';
$jLang['_ADMIN_CONF_ALLOWREG'] = 'Permetti Registrazioni';
$jLang['_ADMIN_CONF_ALLOWREG_DEG'] = "Permetti agli utenti di iscriversi alla competizione. Se non selezionato, l'amministratore devr� aggiungere gli utenti manualmente";
$jLang['_ADMIN_CONF_AUTOAPP'] = 'Auto-Approva Registrationi';
$jLang['_ADMIN_CONF_AUTOAPP_DEF'] = 'Gli utenti saranno iscritti automaticamente alla competizione';
$jLang['_ADMIN_CONF_DOUBLE'] = 'Abilita DoppioUP';
$jLang['_ADMIN_CONF_DOUBLE_DEF'] = 'Gli utenti possono scegliere se dupicare i punti (utilizzabile per un solo turno a stagione)';
$jLang['_ADMIN_CONF_MARGIN'] = 'Pemetti Pronostico "Margine"';
$jLang['_ADMIN_CONF_MARGIN_DEF'] = 'Permetti agli utenti di pronosticare il Margine';
$jLang['_ADMIN_CONF_PICKSC'] = "Permetti Pronostico 'Risultato Esatto'";
$jLang['_ADMIN_CONF_PICKSC_DEF'] = 'Permetti agli utenti di pronosticare il Risultato Esatto';
$jLang['_ADMIN_CONF_PAYOPT_MAN'] = 'Manuale';
$jLang['_ADMIN_CONF_NEW_CONF'] = 'Sei sicuro di voler iniziare una nuova Stagione?\\nQuesto canceller� e resettere tutti i dati registrati!';
$jLang['_ADMIN_CONF_SNS_BTN'] = 'Vai!';
$jLang['_ADMIN_CONF_COM_TITLE'] = 'Titolo Componente';
$jLang['_ADMIN_CONF_COM_TITLE_DEF'] = 'Il titolo da mostrare se non � selezionata nessuna immagine Header';
$jLang['_ADMIN_CONF_LOGO'] = 'Logo Competizione';
$jLang['_ADMIN_CONF_LOGO_DEF'] = 'Logo per la Competizione. Appare al TOP della pagina';
$jLang['_ADMIN_CONF_LOGO_POS'] = 'Logo Posizione';
$jLang['_ADMIN_CONF_LOGO_LEFT'] = 'Sinistra';
$jLang['_ADMIN_CONF_LOGO_RIGHT'] = 'Destra';
$jLang['_ADMIN_CONF_LOGO_CENTRE'] = 'Centro';
$jLang['_ADMIN_CONF_LOGO_POS_DEF'] = 'Posizione per il Logo Competizione';
$jLang['_ADMIN_CONF_LOGO_RM'] = 'Rimuovi Logo Competizione';
$jLang['_ADMIN_CONF_LOGO_RM_DEF'] = "Rimuovi l'attuale logo competizione";
$jLang['_ADMIN_CONF_LOGO_LINK'] = 'Logo Link';
$jLang['_ADMIN_CONF_LOGO_LINK_DEF'] = 'URL linkato al logo competizione (includi http://) - apre in una nuova finestra/tab';
$jLang['_ADMIN_CONF_DASH_TITLE'] = 'Titolo Bacheca';
$jLang['_ADMIN_CONF_TIPS_TITLE'] = 'Titolo Pannello Pronostici';
$jLang['_ADMIN_CONF_COMP_TITLE'] = 'Titolo Classifica Competizione';
$jLang['_ADMIN_CONF_TEAM_TITLE'] = 'Titolo Calssifica Squadre';
$jLang['_ADMIN_CONF_CENT'] = 'Posizione Centrale';
$jLang['_ADMIN_CONF_LEFT'] = 'Posizione Sinistra';
$jLang['_ADMIN_CONF_RIHT'] = 'Posizione destra';
$jLang['_ADMIN_CONF_MOD_COMP'] = 'classifica Competizione';
$jLang['_ADMIN_CONF_MOD_TEAM'] = 'Classifica Squadre';
$jLang['_ADMIN_CONF_MOD_TIPS'] = 'Sommario Pronosici';
$jLang['_ADMIN_CONF_DATEF'] = 'Formato Data';
$jLang['_ADMIN_CONF_DATEF_DEF'] = 'Il formato Data usato in jTips. Formati utilizzabili disponibili qui: ';
$jLang['_ADMIN_CONF_TIMEF'] = 'Formato Ora';
$jLang['_ADMIN_CONF_CUSTLAYOUT'] = 'Permetti Layout personalizzato';
$jLang['_ADMIN_CONF_CUSTLAYOUT_DEF'] = 'Permetti agli utenti di modificare il Layout';
$jLang['_ADMIN_CONF_LADSTYLE'] = 'Stile Classifica Squadre';
$jLang['_ADMIN_CONF_LADSTYLE_DEF'] = 'Seleziona lo stile per la classifica Squadre (cambiamento nel Pannello Pronostici)';
$jLang['_ADMIN_CONF_SHOWLAST'] = "Mostra il vincitore dell'ultimo Turno";
$jLang['_ADMIN_CONF_SHOWLAST_DEF'] = "Mostra l'utente(i)che hanno totalizzato il punteggio pi� alto nell'ultimo Turno";
$jLang['_ADMIN_CONF_SHOWLAST_SEAS'] = "Mostra vincitore dell'ultimo Turno(Per Stagione)";
$jLang['_ADMIN_CONF_SHOWLAST_SEAS_DEF'] = ". Se lasciato non selezionato, il vincitore dell'ultimo turno di tutte le stagioni sar� mostrato";
$jLang['_ADMIN_CONF_SHOWDEF'] = 'Default Utenti da mostrare';
$jLang['_ADMIN_CONF_SHOWDEF_DEF'] = 'Il numero di Utenti da mostrare di default nella classifica della competizione';
$jLang['_ADMIN_CONF_SHOWMAX'] = 'Massimo Utenti da mostrare';
$jLang['_ADMIN_CONF_SHOWMAX_DEF'] = 'Il numero massimo di utenti da mastrare per pagina nella classifica della competizione';
$jLang['_ADMIN_CONF_NAMER'] = 'Nome Reale';
$jLang['_ADMIN_CONF_NAMEU'] = 'Username';
$jLang['_ADMIN_CONF_MARGIN_SCORE'] = 'Bonus Margine Corretto';
$jLang['_ADMIN_CONF_MARGIN_SCORE_DEF'] = 'Il numero di punti bonus da assegnare per gli utenti che hanno correttamente pronosticato il pronostico Margine';
$jLang['_ADMIN_CONF_PICKSC_SCORE'] = "Punti Risultato Esatto";
$jLang['_ADMIN_CONF_PICKSC_SCORE_DEF'] = 'Il numero di punti Bonus da assegnare agli utenti che hanno pronosticato correttamente il risultato esatto';
$jLang['_ADMIN_CONF_PAYPALACC'] = 'Il suo Paypal Account (Email)';
$jLang['_ADMIN_CONF_PAYPALITEM'] = 'Nome Competizione';
$jLang['_ADMIN_CONF_PAYPALITEM_DEF'] = 'Esempio: Registazione Competizione';
$jLang['_ADMIN_CONF_PAYPALCURR'] = 'Valuta';
$jLang['_ADMIN_CONF_PAYPALCURR_DEF'] = 'Esempio: AUD, USD, GBP';
$jLang['_ADMIN_CONF_PAYPALAMNT'] = 'Ammontare';
$jLang['_ADMIN_CONF_PAYPALAMNT_DEF'] = 'Esempio: 20.00 (Non inserire alcun simbolo di valuta!)';
$jLang['_ADMIN_CONF_PAYPALBTN'] = 'Immagine Pulsante';
$jLang['_ADMIN_CONF_PAYPALBTN_DEF'] = "Default: <img src='https://www.paypal.com/en_US/i/btn/x-click-but6.gif' align='absmiddle' />";
$jLang['_ADMIN_CONF_PAYPALTEST'] = 'Abilita test per PayPal';
$jLang['_ADMIN_CONF_PAYPALTEST_DEF'] = '';
$jLang['_ADMIN_CONF_PAYPALTYPE'] = 'Usa Paypal sottoscrizioni?';
$jLang['_ADMIN_CONF_PAYPALTYPE_DEF'] = "Se stai usando le sottoscrizioni Paypal, inserisci il codice di sottoscrizione rilasciato dal tuo account Paypal nell'apposito campo";
$jLang['_ADMIN_CONF_PAYPAL'] = 'PayPal Codice:';
$jLang['_ADMIN_CONF_PAYPAL_DEF'] = 'Codice HTML generato da Paypal';
$jLang['_ADMIN_CONF_PAYPALUNSUB'] = 'PayPal UnSubscribe Code:';
$jLang['_ADMIN_CONF_PAYPALUNSUB_DEF'] = 'The HTML code provided by PayPal to UnSubscribe - only required if the above code corresponds to a subscription in your PayPal account';
$jLang['_ADMIN_CONF_EXP_HIST'] = 'Esporta Storia';
$jLang['_ADMIN_CONF_EXP_TYPE'] = 'Seleziona Tipo Data';
$jLang['_ADMIN_CONF_EXP_TYPE_ERR'] = 'Prima seleziona un Tipo Data';
$jLang['_ADMIN_CONF_IMP_FILE'] = 'Seleziona File File';
$jLang['_ADMIN_CONF_IMP_PENDING'] = 'Importazione in attesa';
$jLang['_ADMIN_CONF_IMP_INTO'] = 'Importa nel';
$jLang['_ADMIN_CONF_IMP_MATCH'] = 'Match Up The Fields';
$jLang['_ADMIN_CONF_ACT_STATUS'] = 'Stato Attivazione';
$jLang['_ADMIN_CONF_ACT_DONE'] = 'Attivato';
$jLang['_ADMIN_CONF_JTIP_VER'] = 'jTips Versione';
$jLang['_ADMIN_CONF_ACT_EMAIL'] = 'Indirizzo Email di Attivazione';
$jLang['_ADMIN_CONF_ACT_KEY'] = 'Chiave di Attivazione';
$jLang['_ADMIN_CONF_EXPBTN'] = 'Esperta Data';
$jLang['_ADMIN_CONF_EXPERR'] = 'Nessuna Selezione';
$jLang['_ADMIN_CONF_NOTIFY_UOR'] = 'Notifica Utente Alla Registrazione';
$jLang['_ADMIN_CONF_NOTIFY_UOR_DEF'] = 'Seleziona il box per abilitare la notifica email quando un utente si registra alla competizione';
$jLang['_ADMIN_CONF_NOTOFY_FROMN'] = 'Da Nome';
$jLang['_ADMIN_CONF_NOTIFY_FROMN_DEF'] = 'Nome del mittente';
$jLang['_ADMIN_CONF_NOTIFY_FROMA'] = 'From Address';
$jLang['_ADMIN_CONF_NOTIFY_FROMA_DEF'] = 'Indirizzo Email del mittente';
$jLang['_ADMIN_CONF_NOTIFY_SUBJ'] = 'Soggetto del Messaggio';
$jLang['_ADMIN_CONF_NOTIFY_SUBJU_DEF'] = 'Il titolo della email di notifica che verr� inviata agli utenti';
$jLang['_ADMIN_CONF_NOTIFY_MSG'] = 'Corpo Messaggio';
$jLang['_ADMIN_CONF_NOTIFY_MSGU_DEF'] = "Il testo della email di notifica che sar� inviata agli utenti.<br />Potresti aggiungere valori segnaposto che saranno rimpiazzati dai dettagli dell'utente registrato. Validi segnaposto sono:<ul><li>{name}</li><li>{username}</li><li>{email}</li></ul>";
$jLang['_ADMIN_CONF_NOTIFY_AOR'] = 'Notify Admin On Registration';
$jLang['_ADMIN_CONF_NOTIFY_TO'] = "All'indirizzo";
$jLang['_ADMIN_CONF_NOTIFY_TO_DEF'] = "L'indirizzo email a cui sar� inviato";
$jLang['_ADMIN_CONF_NOTIFY_SUBJA_DEF'] = "Soggetto della notifica che sar� inviata all'amministratore";
$jLang['_ADMIN_CONF_NOTIFY_MSGA_DEF'] = "Il corpo della email di notifica che sar� inviata all'amministratore.<br />Potresti aggiungere valori segnaposto che saranno rimpiazzati dai dettagli dell'utente registrato.Validi segnaposto sono:<ul><li>{name}</li><li>{username}</li><li>{email}</li></ul>";
$jLang['_ADMIN_NOTIFY_APPSUB'] = 'Soggetto Messaggio di Approvazione';
$jLang['_ADMIN_NOTIFY_APPSUB_DEF'] = 'The subject of the notification email to be sent to users when they are manually approved from the list of users';
$jLang['_ADMIN_NOTIFY_APPMSG'] = 'Corpo Mssaggio di Approvazione';
$jLang['_ADMIN_NOTIFY_APPMSG_DEF'] = 'The body of the notification email to be sent to users when they are manually approved from the list of users.<br />You may add replaceable values that will get replaced with details of the registering user. Valid placeholders are:<ul><li>{name}</li><li>{username}</li><li>{email}</li></ul>';
$jLang['_ADMIN_NOTIFY_UAPPSUB'] = 'Soggetto Messaggio di non Approvazione';
$jLang['_ADMIN_NOTIFY_UAPPSUB_DEF'] = 'The subject of the notification email to be sent to users when they are manually unapproved from the list of users';
$jLang['_ADMIN_NOTIFY_UAPPMSG'] = 'Corpo Messaggio di non Approvazione';
$jLang['_ADMIN_NOTIFY_UAPPMSG_DEF'] = 'The body of the notification email to be sent to users when they are manually unapproved from the list of users.<br />You may add replaceable values that will get replaced with details of the registering user. Valid placeholders are:<ul><li>{name}</li><li>{username}</li><li>{email}</li></ul>';
$jLang['_ADMIN_CONF_RES_DIS'] = 'Registratione Disabilta Messaggio';
$jLang['_ADMIN_CONF_REF_DIS_DEF'] = 'Messaggio da mostrare se la registrazione � disabilitata';
$jLang['_ADMIN_CONF_JS_ROLL'] = 'Roll Up/Down';
$jLang['_ADMIN_CONF_JS_SLIDE'] = 'Slide Up/Down';
$jLang['_ADMIN_CONF_JS_FADE'] = 'Fade In/Out';
$jLang['_ADMIN_CONF_JS_GROW'] = 'Grow / Shrink';
$jLang['_ADMIN_CONF_SUMM_SEL'] = 'Colonna Sommaria Punteggi';
$jLang['_ADMIN_CONF_SUMM_SEL_DEF'] = 'Seleziona la colonna che apparir� nella Area Somma Punteggi nella bacheca. Sposta la colonna su o gi� per determinare il loro ordine di apparizione da sinistra a destra. Per selezioni multiple, tieni premuto il tasto CTRL e clicca sulle opzioni.';
$jLang['_ADMIN_CONF_LAST_SUMM_SEL'] = 'Dettagli Vincitore Ultimo Turno';
$jLang['_ADMIN_CONF_LAST_SUMM_SEL_DEF'] = "Seleziona la colonna che apparir� nell'area Vincitore Ultimo Turno della Bacheca. Sposta la colonna su o gi� per determinare il loro ordine di apparizione";
$jLang['_ADMIN_CONF_NONE'] = '--None--';
$jLang['_ADMIN_CONF_JS_STATIC'] = 'Static (Sempre visibile)';
$jLang['_ADMIN_CONF_JS_NONE'] = 'Niente / Nascondi';
$jLang['_ADMIN_CONF_LICENCE'] = 'Mostra informazioni Licenza';
$jLang['_ADMIN_CONF_LICENCE_DEF'] = 'Mostra il jTips Logo, copyright e licenza';
$jLang['_ADMIN_CONF_GO'] = 'Vai!';
$jLang['_ADMIN_CONF_UPLOAD'] = 'Carica';
$jLang['_ADMIN_CONF_ACT_NA'] = "N / A";
$jLang['_ADMIN_CONF_UPGRADE_LINK'] = '<a href="http://demo.jobbiespics.com.au/index.php?option=com_content&task=view&id=28&Itemid=38" target="_blank">' .$jLang['_ADMIN_CONF_UPGRADE']. '</a>';
$jLang['_ADMIN_CONF_BUTTON_UP'] = 'Su';
$jLang['_ADMIN_CONF_BUTTON_DOWN'] = 'Gi�';
$jLang['_ADMIN_CONF_UPDATE_NOW'] = 'Aggiorna Ora';
$jLang['_ADMIN_CONF_UPDATE_NA'] = 'Non disponibile';
$jLang['_ADMIN_CONF_JSTIME'] = 'Mostra Conto alla rovescia';
$jLang['_ADMIN_CONF_JSTIME_DEF'] = "Mostro il conto alla rovescia fino alla chiusura dell'inserimento pronostici";
$jLang['_ADMIN_CONF_COMMENTS'] = 'Abilita commenti';
$jLang['_ADMIN_CONF_COMMENTS_DEF'] = 'Permetti agli utenti di aggiungere commenti visibili agli altri utenti per ogni turno.';
$jLang['_ADMIN_CONF_COMMENTSFILTER'] = 'Abilita filtro Bad-Words';
$jLang['_ADMIN_CONF_COMMENTSFILTER_DEF'] = '';
$jLang['_ADMIN_CONF_COMMENTSACTION_REPLACE'] = 'Sostituisci';
$jLang['_ADMIN_CONF_COMMENTSACTION_DELETE'] = 'Cancella';
$jLang['_ADMIN_CONF_COMMENTSFILTERACTION'] = 'Comments Filter Action';
$jLang['_ADMIN_CONF_COMMENTSFILTERACTION_DEF'] = "L'azione da intraprendere in presenza di un commento con Bad-Word all'interno";
$jLang['_ADMIN_CONF_TEAM_LADD_SEL'] = 'Colonna Classifica Squadre';
$jLang['_ADMIN_CONF_TEAM_LADD_SEL_DEF'] = "Seleziona la colonna che apparir� nell'area Classifica Squadre della Bacheca. Sposta la colonna su o gi� per determinare il loro ordine di apparizione";
$jLang['_ADMIN_CONF_SHOWTIPS'] = 'Abilita "Mostra Pronostici"';
$jLang['_ADMIN_CONF_SHOWTIPS_DEF'] = 'I pronostici degli utenti saranno visibili a turno concluso';
$jLang['_ADMIN_CONF_SHOWTIPS_WIDTH'] = 'ShowTips Window Larghezza';
$jLang['_ADMIN_CONF_SHOWTIPS_WIDTH_DEF'] = 'Window width in pixels - default 640';
$jLang['_ADMIN_CONF_SHOWTIPS_HEIGHT'] = 'ShowTips Window Altezza';
$jLang['_ADMIN_CONF_SHOWTIPS_HEIGHT_DEF'] = 'Window height in pixels - default 480';
$jLang['_ADMIN_CONF_COMP_LADD_SEL'] = 'Colonna Classifica Competizione';
$jLang['_ADMIN_CONF_COMP_LADD_SEL_DEF'] = 'Select the columns that will appear in the Competition Ladder page. Move the columns up and and down to determine their order of appearance from left to right. To select multiple options, hold down the CTRL key and click the options.';
$jLang['_ADMIN_REMIND_ENABLE'] = 'Abilita Promemoria Email';
$jLang['_ADMIN_REMIND_ENABLE_DEF'] = 'Permetti gli utenti di ricevere opzionalmente una email automatica di promemoria per l\'inserimento pronostici. Per abilitare questa funzione aggiungi la seguente lina al tuo cron tab: <br /><strong><pre>0 10 * * 5 cd ' .$mosConfig_absolute_path. '/components/com_jtips; php -f emailman.php > /dev/null 2>&amp;1</pre></strong>This will run the email reminder mailout every Friday at 10am.<br />For more information on setting up your own schedule, take a look at <a href="http://en.wikipedia.org/wiki/Cron">Wikipedia</a>';
$jLang['_ADMIN_REMIND_FROMNAME'] = 'Da Nome';
$jLang['_ADMIN_REMIND_FROMADDRESS'] = 'Da Indirizzo Email';
$jLang['_ADMIN_REMIND_SUBJECT'] = 'Soggetto Email Promemoria';
$jLang['_ADMIN_REMIND_BODY'] = 'Testo email Promemoria';
$jLang['_ADMIN_REMIND_BODY_DEF'] = 'The body of the reminder email to be automatically sent to users to remind them to submit their tips.<br />You may add replaceable values that will get replaced with details of the registering user. Valid placeholders are:<ul><li>{name}</li><li>{username}</li><li>{email}</li></ul>';
$jLang['_ADMIN_CONF_TEAM_LADD_BTNS'] = 'Classifica Squadre - Ordinamento';
$jLang['_ADMIN_CONF_TEAM_LADD_BTNS_DEF'] = 'Select one or more items in the Team Ladder Columns list and use the Up and Down buttons to change their order';
$jLang['_ADMIN_CONF_COMP_LADD_BTNS'] = 'Classifica Competizione - Ordinamento';
$jLang['_ADMIN_CONF_COMP_LADD_BTNS_DEF'] = 'Select one or more items in the Competition Ladder Columns list and use the Up and Down buttons to change their order';
$jLang['_ADMIN_CONF_LAST_SUMM_BTNS'] = 'Ultimo Turno - Ordinamento';
$jLang['_ADMIN_CONF_LAST_SUMM_BTNS_DEF'] = 'Select one or more items in the Last Round Summary list and use the Up and Down buttons to change their order';
$jLang['_ADMIN_CONF_SUMM_BTNS'] = 'Sommatoria Punteggi - Ordinamento';
$jLang['_ADMIN_CONF_SUMM_BTNS_DEF'] = 'Select one or more items in the Summary Score Columns list and use the Up and Down buttons to change their order';
$jLang['_ADMIN_CONF_LADSTYLE2'] = 'Usa Effetti per:';
$jLang['_ADMIN_CONF_LADSTYLE2_DEF'] = "Dtermina quando l'effetto deve essere applicato";
$jLang['_ADMIN_CONF_LADSTYLE3'] = 'Durata Effetto:';
$jLang['_ADMIN_CONF_LADSTYLE3_DEF'] = "Il numero di secondo che l'effetto dovr� durare";

//Team Manager
$jLang['_ADMIN_TEAM_TITLE'] = 'Amministra Squadre';
$jLang['_ADMIN_TEAM_TEAM'] = 'Squadra';
$jLang['_ADMIN_TEAM_LOCATION'] = 'Locazione';
$jLang['_ADMIN_TEAM_NAME'] = 'Nome Squadra';
$jLang['_ADMIN_TEAM_LOCAREA'] = 'Locazione/Area';
$jLang['_ADMIN_TEAM_ABOUT'] = 'Informazioni su..';

//1.0 Additions
$jLang['_ADMIN_TEAM_INSEASON'] = 'Nella Stagione';
$jLang['_ADMIN_TEAM_POINTS'] = 'Punti';
$jLang['_ADMIN_TEAM_SEASON'] = 'Stagione / Competizione';
$jLang['_ADMIN_TEAM_ADJUST'] = 'Correggi Punti Squadra';
$jLang['_ADMIN_TEAM_ADJUST_WINS'] = 'Correggi Vittorie Squadra';
$jLang['_ADMIN_TEAM_ADJUST_LOSSES'] = 'Correggi Sconfitte Squadra';
$jLang['_ADMIN_TEAM_ADJUST_FOR'] = 'Imposta Punti Ottenuti';
$jLang['_ADMIN_TEAM_ADJUST_AGAINST'] = 'Imposta Punti concessi';
$jLang['_ADMIN_TEAM_LOGO'] = 'Aggiorna Logo Squadra';
$jLang['_ADMIN_TEAM_LOGO_CURR'] = 'Attuale Logo Squadra';
$jLang['_ADMIN_TEAM_LOGO_ERR'] = 'Nessuna Immagine';
$jLang['_ADMIN_TEAM_LOGO_RM'] = 'Rimuovi Immagine';
$jLang['_ADMIN_TEAM_URL'] = 'Squadra Website';

//Season Manager
$jLang['_ADMIN_SEASON_TITLE'] = 'Amministra Stragione';
$jLang['_ADMIN_SEASON_NAME'] = 'Nome Stagione';
$jLang['_ADMIN_SEASON_START'] = 'Avvio Stagione';
$jLang['_ADMIN_SEASON_END'] = 'Termine Stagione';
$jLang['_ADMIN_SEASON_START_DATE'] = 'Data di partenza';
$jLang['_ADMIN_SEASON_START_DATE_DEF'] = 'Data di partenza della competizione';
$jLang['_ADMIN_SEASON_END_DATE'] = 'Data di conclusione';
$jLang['_ADMIN_SEASON_END_DATE_DEF'] = 'La data di conclusione della competizione';
$jLang['_ADMIN_SEASON_ROUNDS'] = 'Turni Totali';
$jLang['_ADMIN_SEASON_GPR'] = 'Numero Massimo Partite per Turno';
$jLang['_ADMIN_SEASON_DESCR'] = 'Descrizione';
$jLang['_ADMIN_SEASON_PRECISION'] = "Abilita 'Precision Score'<sup>&copy;</sup>";
$jLang['_ADMIN_SEASON_PRECISION_DEF'] = "Da usare con 'Abilita Risultato esatto', o 'Abilita punti margine' al fine di ottenere con accuratezza un singolo vincitore a fine stagione. Per maggiori informazioni a riguardo sul 'Precision Score'<sup>&copy;</sup>";

$jLang['_ADMIN_GAME_HOMESCORE'] = 'Punteggio Squadra Casa';
$jLang['_ADMIN_GAME_AWAYSCORE'] = 'Punteggio Squadra Trasferta';
$jLang['_ADMIN_GAME_HAS_BONUS'] = 'Abilita Punti Bonus';
$jLang['_ADMIN_GAME_HAS_MARGIN'] = 'Abilita Punti Margine';
$jLang['_ADMIN_GAME_HAS_SCORE'] = 'Abilita Risultato Esatto';

//Round Manager
$jLang['_ADMIN_ROUND_TITLE'] = 'amministra Turno';
$jLang['_ADMIN_ROUND_START'] = 'Avvio';
$jLang['_ADMIN_ROUND_END'] = 'Fine';
$jLang['_ADMIN_ROUND_ADDRESULT'] = 'Aggiungi Risultato';
$jLang['_ADMIN_ROUND_ROUND'] = 'Turno';
$jLang['_ADMIN_ROUND_DATE'] = 'Data';
$jLang['_ADMIN_ROUND_TIME'] = 'Ora';
$jLang['_ADMIN_ROUND_GAMES'] = 'Partite';
$jLang['_ADMIN_ROUND_USE'] = 'Uso';
$jLang['_ADMIN_ROUND_HOME'] = 'Casa';
$jLang['_ADMIN_ROUND_AWAY'] = 'Trasferta';
$jLang['_ADMIN_ROUND_ORDER'] = 'Ordine';
$jLang['_ADMIN_ROUND_WINNER'] = 'Vincente';
$jLang['_ADMIN_ROUND_DRAW'] = 'Pareggio';
$jLang['_ADMIN_ROUND_NOTEAMS'] = 'Nessuna Squadra Disponibile!';
$jLang['_ADMIN_ROUND_NOTSTARTED'] = 'Turno non ancora Iniziato';
$jLang['_ADMIN_ROUND_INPROGRESS'] = 'Turno in gioco';

//1.0 Additions
$jLang['_ADMIN_ROUND_SEASON'] = 'Stagione';
$jLang['_ADMIN_ROUND_STATUS'] = 'Stato';
$jLang['_ADMIN_ROUND_EDITGAMES'] = 'Edita Partite';
$jLang['_ADMIN_ROUND_STATUS_NS'] = 'Non Cominciate';
$jLang['_ADMIN_ROUND_STATUS_C'] = 'Completo';
$jLang['_ADMIN_ROUND_STATUS_P'] = 'In attesa di risultati';
$jLang['_ADMIN_ROUND_STATUS_IP'] = 'In Gioco';
$jLang['_ADMIN_ROUND_CURRTIME'] = 'Ora Attuale';

//User Manager
$jLang['_ADMIN_USERS_TITLE'] = 'Amministra Utenti';
$jLang['_ADMIN_USERS_USERNAME'] = 'Username';
$jLang['_ADMIN_USERS_DOUBLE'] = 'Doppio UP';
$jLang['_ADMIN_USERS_AVERAGE'] = 'Media';
$jLang['_ADMIN_USERS_TOTAL'] = 'Totale';
$jLang['_ADMIN_USERS_PAID'] = 'Pagato';
$jLang['_ADMIN_USERS_SELECT'] = 'Seleziona Utente';
$jLang['_ADMIN_USERS_AVERAGE_SCORE'] = 'Punteggio Medio';
$jLang['_ADMIN_USERS_TOTAL_SCORE'] = 'Punteggio totale';
$jLang['_ADMIN_USERS_ROUND'] = 'Turno';
$jLang['_ADMIN_USERS_NEWUSER'] = 'Nuovo Utente';

//1.0 Additions
$jLang['_ADMIN_USERS_APPROVED'] = 'Approvato';
$jLang['_ADMIN_USERS_NAME'] = 'Nome';
$jLang['_ADMIN_USERS_FULLNAME'] = 'Nome completo';
$jLang['_ADMIN_USERS_EMAIL'] = 'Email';
$jLang['_ADMIN_USERS_LASTROUND'] = 'Ultimo Turno';
$jLang['_ADMIN_USERS_DOUBLEUP_RESET'] = 'Reset Doppio UP';
$jLang['_ADMIN_USERS_DOUBLEUP_RESET_DEF'] = 'Seleziona questo box per far riutilizzare il Doublu Up agli Utenti';
$jLang['_ADMIN_USERS_SEASON'] = 'Aggiungi alla Stagione';

//Admin (Other)
$jLang['_ADMIN_OTHER_EDIT'] = 'Modifica';
$jLang['_ADMIN_OTHER_EDITING'] = 'In Modifica';
$jLang['_ADMIN_OTHER_NEW'] = 'Nuovo';

//CSS
$jLang['_ADMIN_CSS_TITLE'] = 'Style Sheet Editor';





/////////////////////////////////////////////////
///
///	jSeason Field Defintion Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JSEASON_ID'] = 'Primary Key';
$jLang['_ADMIN_JSEASON_NAME'] = 'Nome Stagione';
$jLang['_ADMIN_JSEASON_DESCRIPTION'] = 'Descrizione';
$jLang['_ADMIN_JSEASON_START'] = 'Data di Partenza';
$jLang['_ADMIN_JSEASON_END'] = 'Data di Conclusione';
$jLang['_ADMIN_JSEASON_ROUNDS'] = 'Turni Totali';
$jLang['_ADMIN_JSEASON_GAME_PER_ROUND'] = 'Partite per Turno';
$jLang['_ADMIN_JSEASON_PICK_SCORE'] = 'Abilita Risultato Esatto';
$jLang['_ADMIN_JSEASON_PICK_MARGIN'] = 'Abilita Punti Margine';
$jLang['_ADMIN_JSEASON_PICK_BONUS'] = 'Abilita Punti Bonus Squadra';
$jLang['_ADMIN_JSEASON_PICK_DRAW'] = 'Abilita Pronostico Pareggio';
$jLang['_ADMIN_JSEASON_TEAM_BONUS'] = 'Punti Bonus Squadra';
$jLang['_ADMIN_JSEASON_TEAM_WIN'] = 'Punti Squadra Vincente';
$jLang['_ADMIN_JSEASON_TEAM_LOSE'] = 'Punti Squadra Perdente';
$jLang['_ADMIN_JSEASON_TEAM_DRAW'] = 'Punti squadra per partita conclusa in pareggio';
$jLang['_ADMIN_JSEASON_TEAM_BYE'] = 'Team Bye Points';
$jLang['_ADMIN_JSEASON_USER_CORRECT'] = 'Punti Utente per pronostico corretto';
$jLang['_ADMIN_JSEASON_USER_DRAW'] = 'Punti Utente per pronostico corretto sul pareggio';
$jLang['_ADMIN_JSEASON_USER_BONUS'] = 'Punti Bonus per utenti che hanno pronosticato correttamente il completo turno';
$jLang['_ADMIN_JSEASON_USER_NONE'] = 'Punti Utenti che non hanno inserito pronostico';
$jLang['_ADMIN_JSEASON_USER_PICK_SCORE'] = 'Punti Utenti che hanno Pronosticato correttamente il Risultato Esatto';
$jLang['_ADMIN_JSEASON_USER_PICK_MARGIN'] = 'Punti utenti che hanno correttamente pronosticato i Punti Margine';
$jLang['_ADMIN_JSEASON_USER_PICK_BONUS'] = 'Punti utenti che hann pronosticato correttamente i Bonus Points';
$jLang['_ADMIN_JSEASON_URL'] = 'Stagione Website';
$jLang['_ADMIN_JSEASON_IMAGE'] = 'Logo Header della Stagione';
$jLang['_ADMIN_JSEASON_PRECISION_SCORE'] = 'Ablita il Risultato Esatto';
$jLang['_ADMIN_JSEASON_TIP_DISPLAY'] = 'Mostra impostazioni Squadra';
$jLang['_ADMIN_JSEASON_UPDATED'] = 'Ultima Modifica';

/////////////////////////////////////////////////
///
/// jBadWord Feild Definition Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JBADWORD_ID'] = 'Primary Key';
$jLang['_ADMIN_JBADWORD_WORD'] = 'Bad Word';
$jLang['_ADMIN_JBADWORD_MATCH_CASE'] = 'Maiuscole e Minuscole';
$jLang['_ADMIN_JBADWORD_ACTION'] = 'Azione';
$jLang['_ADMIN_JBADWORD_REPALCE'] = 'Sostituisci';
$jLang['_ADMIN_JBADOWRD_HITS'] = 'Hits';
$jLang['_ADMIN_JBADWORD_UPDATED'] = 'Ultima Modifica';

/////////////////////////////////////////////////
///
/// jGame Feild Definition Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JGAME_ID'] = 'Primary Key';
$jLang['_ADMIN_JGAME_ROUND_ID'] = 'Numero Turni';
$jLang['_ADMIN_JGAME_HOME_ID'] = 'Squadra in Casa';
$jLang['_ADMIN_JGAME_AWAY_ID'] = 'Sqaudra in Trasferta';
$jLang['_ADMIN_JGAME_POSITION'] = 'Ordine Partite';
$jLang['_ADMIN_JGAME_WINNER_ID'] = 'Squadra Vincente';
$jLang['_ADMIN_JGAME_DRAW'] = 'Pareggio';
$jLang['_ADMIN_JGAME_HOME_SCORE'] = 'Punteggio Squadra in Casa';
$jLang['_ADMIN_JGAME_AWAY_SCORE'] = 'Punteggio Squada in Trasferta';
$jLang['_ADMIN_JGAME_BONUS_ID'] = 'Punti Bonus Assegnati a:';
$jLang['_ADMIN_JGAME_HAS_BONUS'] = 'Permetti Bonus Point Seleziona Squadra';
$jLang['_ADMIN_JGAME_HAS_MARGIN'] = 'Permetti Punti Margine Selectione';
$jLang['_ADMIN_JGAME_HAS_SCORE'] = 'Permetti Risultato Esato Selezione';
$jLang['_ADMIN_JGAME_UPDATED'] = 'Ultima Modifica';
$jLang['_ADMIN_JGAME_SEASON_ID'] = 'Stagione';

/////////////////////////////////////////////////
///
/// jRound Feild Definition Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JROUND_ID'] = 'Primary Key';
$jLang['_ADMIN_JROUND_ROUND'] = 'Numero Turni';
$jLang['_ADMIN_JROUND_SEASON_ID'] = 'Stagione';
$jLang['_ADMIN_JROUND_START_TIME'] = 'Data e Ora di Partenza Competizione';
$jLang['_ADMIN_JROUND_END_TIME'] = 'Data e Ora di Termine Competizione';
$jLang['_ADMIN_JROUND_SCORED'] = 'Punteggi & Completo';
$jLang['_ADMIN_JROUND_UPDATED'] = 'Ultima Modifica';

/////////////////////////////////////////////////
///
/// jTeam Feild Definition Labels
///
/////////////////////////////////////////////////
$jLang['_ADMIN_JTEAM_ID'] = 'Primary Key';
$jLang['_ADMIN_JTEAM_SEASON_ID'] = 'Nome Stagione';
$jLang['_ADMIN_JTEAM_NAME'] = 'Nome';
$jLang['_ADMIN_JTEAM_LOCATION'] = 'Locazione';
$jLang['_ADMIN_JTEAM_ABOUT'] = 'Informazione su:';
$jLang['_ADMIN_JTEAM_LOGO'] = 'Logo';
$jLang['_ADMIN_JTEAM_URL'] = 'Website';
$jLang['_ADMIN_JTEAM_WINS'] = 'Vinte';
$jLang['_ADMIN_JTEAM_DRAWS'] = 'Pareggiate';
$jLang['_ADMIN_JTEAM_LOSSES'] = 'Perse';
$jLang['_ADMIN_JTEAM_POINTS_FOR'] = 'Punti Ottenuti';
$jLang['_ADMIN_JTEAM_POINTS_AGAINST'] = 'Points Concessi';
$jLang['_ADMIN_JTEAM_POINTS'] = 'Punti Totali';
$jLang['_ADMIN_JTEAM_UPDATED'] = 'Ultima Modifica';

$jLang['_ADMIN_DASH_CONFIGURATION'] = 'Configurazione';
$jLang['_ADMIN_DASH_ROUND_MANAGER'] = 'Amministra Turni e Partite';
$jLang['_ADMIN_DASH_TEAM_MANAGER'] = 'Amministra Squadre';
$jLang['_ADMIN_DASH_SEASON_MANAGER'] = 'Amministra Stagione';
$jLang['_ADMIN_DASH_USER_MANAGER'] = 'Amministra Utenti';
$jLang['_ADMIN_DASH_TIPS_MANAGER'] = 'Amministra Tipsters';
$jLang['_ADMIN_DASH_NEW_ROUND'] = 'Nuovo Turno';
$jLang['_ADMIN_DASH_NEW_TEAM'] = 'Nuova squadra';
$jLang['_ADMIN_DASH_NEW_SEASON'] = 'Nuova Stagione';
$jLang['_ADMIN_DASH_EXPORT_MANAGER'] = 'Data Export Manager';
$jLang['_ADMIN_DASH_IMPORT_MANAGER'] = 'Data Import Manager';
$jLang['_ADMIN_DASH_COMMENT_MANAGER'] = 'Amministra Commenti';
$jLang['_ADMIN_DASH_BADWORD_MANAGER'] = 'Amministra Bad Word';
$jLang['_ADMIN_DASH_STYLE_EDITOR'] = 'Style Editor';
$jLang['_ADMIN_DASH_SUPPORT'] = 'Help &amp; Support';
//July 14 2008. v2.0.8
$jLang['_ADMIN_SEASON_ETS'] = 'Abilita Handicap';
$jLang['_ADMIN_SEASON_ETS_DEF'] = 'Allows the administrator to give a team a head start. Users must select the winner while taking thie head start into consideration. This feature is also known as a Team Handicap.';
$jLang['_ADMIN_JSEASON_TEAM_STARTS'] = 'Handicap';
$jLang['_ADMIN_GAME_HOME_START'] = 'Home Handicap';
$jLang['_ADMIN_GAME_AWAY_START'] = 'Away Handicap';
$jLang['_ADMIN_JGAME_HOME_START'] = 'Home Handicap';
$jLang['_ADMIN_JGAME_AWAY_START'] = 'Away Handicap';
$jLang['_COM_TIPS_HOMESTART'] = 'Home Handicap';
$jLang['_COM_TIPS_AWAYSTART'] = 'Away Handicap';
$jLang['_COM_MY_SUB_SEASONS'] = 'Iscritto alla Competizione';
$jLang['_COM_MY_UNSUB_SEASONS'] = 'Non iscritto alla Competizione';
$jLang['_COM_UNSUBLINK_PART1'] = 'Sei sicuro di volerti disiscrivere dalla';
$jLang['_COM_UNSUBLINK_PART2'] = 'competitione?\nTutti i tuoi punti saranno resettati.';
$jLang['_COM_ONE_ROUND_REQUIRED'] = 'Almeno un Round deve essere completato';
$jLang['_COM_SCORE'] = 'Punteggio';
$jLang['_COM_MARGIN'] = 'Margine';
$jLang['_COM_BONUS'] = 'Bonus';
$jLang['_COM_COMMENT_VALIDATED'] = 'Commento validato.';
$jLang['_COM_COMMENT_NOT_ALLOWED'] = 'Il commento contiene parole non ammesse';
$jLang['_COM_COMMENT_REPLACED'] = 'Il commento contiene parole non ammesse. Queste parole saranno sostituite.';
//August 11 2008. v2.0.9
$jLang['_ADMIN_DASH_TOTAL_USERS'] = 'Utenti Totali';
$jLang['_ADMIN_DASH_PENDING_TIPS'] = 'Pronostici in attesa';
$jLang['_ADMIN_DASH_PENDING_PAYMENT'] = 'Pagamenti in attesa';
$jLang['_ADMIN_DASH_REVALIDATE'] = 'Rivalida';
$jLang['_ADMIN_DASH_LOGGING'] = 'Logging';
$jLang['_ADMIN_DASH_FILE_SIZE'] = 'File Size';
$jLang['_ADMIN_DASH_DOWNLOAD'] = 'Download';
$jLang['_ADMIN_DASH_PURGE'] = 'Spurgo';
$jLang['_ADMIN_DASH_LOG_ROTATED'] = 'Log file automatically rotated after 10MB';
$jLang['_ADMIN_DASH_ABOUT_UPDATES'] = 'Stay up-to-date on the latest jTips development and downloads at';
$jLang['_ADMIN_DASH_ABOUT_SALES'] = 'For sales and licencing enquiries, contact';
$jLang['_ADMIN_DASH_ABOUT_SUPPORT'] = 'For support, please visit';
$jLang['_ADMIN_DASH_ABOUT_REBUILD'] = 'If you need to rebuild the database tables for jTips,';
$jLang['_COMMON_CLICK_HERE'] = 'click here';
$jLang['_ADMIN_DASH_CREDITS'] = 'jTips uses of the following packages';
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
$jLang['_ADMIN_DASH_CPANEL'] = 'jTips Control Panel';
$jLang['_ADMIN_CONF_SHOWTIPS_PROCESSED'] = 'After Round is Processed';
$jLang['_ADMIN_CONF_SHOWTIPS_TIPPED'] = 'After User has Submitted Tips';
$jLang['_ADMIN_CONF_SHOWTIPS_ANY'] = 'At Any Time';
$jLang['_ADMIN_CONF_SHOWTIPS_ACCESS'] = 'ShowTips Access Level';
$jLang['_ADMIN_CONF_SHOWTIPS_ACCESS_DEF'] = 'Controls when users will be able to view the tips of other participants for the current round.';
$jLang['_COM_TIPS_MORE'] = 'Dettagli';
$jLang['_ADMIN_CONF_TIP_LOCKOUT'] = 'Disable Tips Edit';
$jLang['_ADMIN_CONF_TIP_LOCKOUT_DEF'] = 'Once a user has submitted their tips, their are unable to edit them';
$jLang['_POPUP_TIPS_PEEK'] = 'Pronostici Giornata Successiva';
$jLang['_ADMIN_TIPSMAN_SCORE_MARGIN'] = 'Score Margin';
$jLang['_ADMIN_SEASON_SELECT'] = 'Seleziona Competizione';
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
$jLang['_COM_MENU_JTIPS_TITLE'] = 'Tipster Competition';
$jLang['_COM_TEAM_AWAY'] = 'Trasferta';
$jLang['_COM_TEAM_HOME'] = 'Casa';
$jLang['_COM_TEAM_HOME_PAGE'] = 'Home Page';
$jLang['_COM_TEAM_HISTORY'] = 'Storia';
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
$jLang['_COM_UNSUBSCRIBE'] = 'Disiscriviti';
$jLang['_COM_CLOSED_TIME'] = 'Chiude alle';
$jLang['_ADMIN_CONF_PAYPAL_DEF'] = 'The HTML code generated from PayPal.<br /><strong>IMPORTANT: </strong>Add the following line just above the &lt;/form&gt; line at the bottom:<br /><strong><pre>&lt;input type="hidden" name="return" value="{RETURN_URL}"&gt;</pre></strong>';
$jLang['_COM_EDIT_PREFERENCES'] = 'Modifica Preferenze';
$jLang['_ADMIN_CONF_DISABLE_MENU'] = 'Nascondi/Mostra Menu';
$jLang['_ADMIN_CONF_DISABLE_MENU_DEF'] = 'Completely hides the main jTips navigation bar. Useful if you have Joomla menu items directly linked to a competition.';
$jLang['_ADMIN_CONF_DISABLE_SEASON_SELECT'] = 'Nascondi/Mostra seleziona Stagione';
$jLang['_ADMIN_CONF_DISABLE_SEASON_SELECT_DEF'] = 'Completely hides the season/competition select field. Useful if you have Joomla menu items directly linked to a competition.';
$jLang['_ADMIN_CONF_NOTIFY_TIPS'] = 'Invia Email di conferma di inserimento pronostici';
$jLang['_ADMIN_CONF_NOTIFY_TIPS_DEF'] = 'Send an email when a user submits their tips. The email contains their selected tips.';
//version 2.0.13
$jLang['_ADMIN_STYLE_SAVED'] = 'Style Saved';
$jLang['_ADMIN_STYLE_SAVING_ERROR'] = 'Error Writing Stylesheet';
$jLang['_ADMIN_FILE_DELETED'] = 'File Deleted';
$jLang['_ADMIN_FILE_DELETE_FAILED'] = 'Failed to Delete File';
$jLang['_ADMIN_FILE_DELETE_NOFILE'] = 'No File to Delete';
$jLang['_ADMIN_FILE_UPLOAD_SUCCESS'] = 'Upload Successful';
$jLang['_ADMIN_FILE_UPLOAD_FAILED'] = 'Upload Failed';
$jLang['_MOD_LAST_ROUND_POINTS'] = 'Punti Ultimo Round';
$jLang['_MOD_TOTAL_POINTS'] = 'Punti Totali';
$jLang['_MOD_LAST_ROUND_PRECISION'] = 'Ultimo Round Precision';
$jLang['_MOD_PRECISION_SCORE'] = 'Risultato Esatto';
$jLang['_MOD_LAST_ROUND_COMMENT'] = 'Commenti Ultimo Round';
$jLang['_MOD_LADDER_MOVEMENT'] = 'Andamento Classifica';
$jLang['_LADDER_VIEW_TIPS_FOR'] = 'Vedi pronostici per';
$jLang['_MOD_VIEW_MORE'] = 'Vedi Tutto';
$jLang['_MOD_INVALID_SEASON_ERROR'] = 'Missing or Invalid Competition Specified';
?>