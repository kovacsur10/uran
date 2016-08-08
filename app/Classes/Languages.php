<?php

namespace App\Classes;

class Languages{
	
	public static function getDefault(){
		return Languages::hungarian();
	}
	
	public static function hungarian(){
		return
		[
		//basic
			'activate' => 'Aktiválás',
			'admin_panel' => 'Admin panel',
			'allow_request' => 'Kérelem elfogadása',
			'approve' => 'Jóváhagy',
			'back_to_last_page' => 'Vissza az előző oldalra',
			'balance' => 'Egyenleg',
			'count' => 'darab',
			'deactivate' => 'Deaktiválás',
			'delete_filter' => 'Szűrés törlése',
			'deny_request' => 'Kérelem elutasítása',
			'ejc' => 'EJC',
			'error' => 'Hiba',
			'error_at_finding_the_user' => 'A felhasználó nem található, vagy nem publikus az oldala!',
			'find' => 'Keres',
			'find_user' => 'Felhasználó keresése',
			'help' => 'Segítség',
			'insufficient_permissions' => 'Nem rendelkezel a szükséges jogokkal!',
			'list' => 'Listázás',
			'list_users' => 'felhasználók listázása',
			'login' => 'Belépés',
			'logout' => 'Kilépés',
			'modify' => 'Módosít',
			'next_page' => 'Következő oldal',
			'note' => 'Megjegyzés',
			'oops_something_went_wrong' => 'Ajajj, valami baj van!',
			'password' => 'Jelszó',
			'permission' => 'Jog',
			'previous_page' => 'Előző oldal',
			'request' => 'Kérelem',
			'start_of_modification' => 'Módosítás kezdése',
			'success' => 'Siker',
			'uran' => 'Urán',
			'validate' => 'Érvényesít',
			'validation' => 'érvényesítés',
			'users_list' => 'Felhasználók listája',
			'identifier' => 'Azonosító',
			'user_data' => 'Személyes adatok',
			'administration' => 'Ügyintézés',
			'not_specified_value' => 'Nem érvényes érték!',
			'empty_value_is_forbidden' => 'A mező értéke nem lehet üres!',
		//permissions
			'assign_permissions' => 'Jogok hozzárendelése',
			'error_at_setting_the_permissions' => 'Hiba a jogosultságok beállításakor!',
			'list_permissions' => 'Jogok listázása',
			'permissions_handling' => 'Jogok kezelése',
			'set_permissions' => 'Jogosultságok beállítása',
		//modules
			'error_at_module_activation' => 'Hiba a modul aktiválásakor!',
			'module' => 'Modul',
			'module_activate' => 'Modul aktiválása',
			'module_deactivate' => 'Modul deaktiválása',
			'modules' => 'Modulok',
			'modules_handling' => 'Modulok kezelése',
		//notifications
			'error_notification_view_insufficient_permission' => 'Nincsen jogod ezt az értesítést megtekinteni!',
			'newer_notifications' => 'Frissebb értesítések',
			'no_notification_to_show' => 'Nincsen megjeleníthető értesítés!',
			'notifications' => 'Értesítések',
			'older_notifications' => 'Régebbi értesítések',
			'show_all' => 'Mutasd mindet',
			'system_no_problem' => 'Rendszer: Semmi probléma',
			'unread_notifications' => 'Olvasatlan értesítések',
		//rooms
			'back_to_the_rooms_list' => 'Vissza a szobák listájához',
			'basement' => 'Alagsor',
			'cellar' => 'Pince',
			'error_already_lives_somewhere' => 'Már másik szobában lakik ez a személy!',
			'error_floor_not_found' => 'Nincsen ilyen emelet a Collegiumban!',
			'floor' => 'emelet',
			'free_spot' => 'Üres hely',
			'free_spots' => 'Szabad helyek',
			'ground_floor' => 'Földszint',
			'resident' => 'Lakó',
			'room_assignment' => 'Szobabeosztás',
		//userdata
			'address' => 'Lakcím',
			'email_address' => 'E-mail cím',
			'name' => 'Név',
			'registration_date' => 'Regisztráció időpontja',
			'user' => 'Felhasználó',
			'username' => 'Felhasználói név',
		//menues
			'admin' => 'ADMIN',
			'ecnet' => 'ECnet',
			'home' => 'Kezdőoldal',
			'internet_access' => 'Internet hozzáférés',
			'mac_slot_ordering' => 'MAC slot igénylése',
			'my_data' => 'Adataim',
			'my_profile' => 'Profilom',
			'printing_account' => 'Nyomtatószámla',
			'registration' => 'Regisztráció',
			'user_administration' => 'Felhasználók kezelése',
		//home
			'logged_in_home_message' => 'TODO',
			'not_logged_in_home_message' => 'TODO',
		//ecnet
			'available_money' => 'Rendelkezésre álló összeg',
			'balance_was_modified' => 'Egyenleg módosítva!',
			'balance_was_modified_description' => 'A nyomtatószámlád egyenlege megváltozott',
			'custom_validation_date' => 'Egyedi érvényességi dátum',
			'default_time' => 'alapértelmezett időpont',
			'default_time_now_description' => 'Az alapértelmezett dátum jelenleg',
			'default_time_set_note_description' => 'Hajnali 5 óra állítódik be időként!',
			'error_at_allowing_mac_slot_order' => 'Valami probléma merült fel a slot jóváhagyásánál!',
			'error_at_money_adding' => 'Valami probléma merült fel a pénz hozzáadásánál!',
			'error_no_default_time_description' => 'Hiba! Nincsen beállított alapértelmezett idő!',
			'error_no_default_time_set' => 'Nem találtunk alapértelmezett időt!',
			'error_page_not_found' => 'Nincsen ilyen oldal!',
			'expiration_date' => 'Lejárati dátum',
			'from_forint' => 'forintról',
			'internet_access_was_modified' => 'Internethozzáférés módosítva!',
			'internet_access_was_modified_to_description' => 'Az internethozzáférésed lejárati ideje módosítva lett erre a dátumra: ',
			'internet_in_not_active' => 'Az interneted nem aktív!',
			'internet_is_active' => 'Az interneted aktív!',
			'internet_registartion_description' => 'Az internet regisztrációról érdeklődj egy rendszergazdánál!',
			'list_internet_users' => 'Internettel rendelkezők listázása',
			'list_internet_users_both' => 'Mindkettő adat',
			'list_internet_users_only_name' => 'Csak nevek',
			'list_internet_users_only_username' => 'Csak felhasználói nevek',
			'low_mac_slot_usage' => 'KEVESEBB SLOTOT HASZNÁL',
			'mac_address' => 'MAC cím',
			'mac_addresses' => 'MAC címek',
			'mac_slot_order' => 'SLOT kérelem',
			'mac_slot_order_was_accepted_description' => 'MAC slot igénylésed el lett fogadva! Kérelem: ',
			'mac_slot_order_was_denied_description' => 'MAC slot igénylésed el lett utasítva! Kérelem: ',
			'mac_slot_ordering_description' => 'Vezetékes internet regisztrációhoz lehet igényelni még további számítógép MAC cím helyeket. Az okot kérjük írjad le, az elfogadásához egy rendszergazda szükséges, erről értesítést fogsz kapni.',
			'mac_slot_was_ordered_description' => 'MAC slot lett igényelve! Kérelem: ',
			'mac_slots_count' => 'MAC slotok száma',
			'money_add_admin_note_description' => 'Ha a hozzáadandó mező 0-val egyenlő, akkor a "Pénz a számlán" értéke lesz figyelembe véve, különben a hozzáadandó érték!',
			'money_on_account' => 'Pénz a számlán',
			'money_to_add' => 'Hozzáadandó pénz',
			'money_upload_note_description' => 'A számlára pénzt feltölteni egy rendszergazdánál tudsz.',
			'order_slot' => 'Slot igénylése',
			'reason_of_ordering' => 'Igénylés oka',
			'registrated_mac_addresses' => 'Regisztrált MAC címek',
			'set_mac_address' => 'MAC címek beállítása',
			'success_at_allowing_mac_slot_order' => 'Sikeresen jóvá lett hagyva a slot igénylés!',
			'success_at_sending_mac_slot_order' => 'A MAC slot igénylésed le lett adva!',
			'success_at_setting_the_default_time_to' => 'Sikeresen át lett állítva az alapértelmezett idő erre: ',
			'success_at_setting_users_internet_access_time' => 'Sikeresen módosítottuk a felhasználó internethozzáférésének idejét!',
			'success_at_updating_mac_addresses' => 'A MAC címek sikeresen frissítve!',
			'success_set_money' => 'Sikeresen átállítottad a célszámla pénzösszegét!',
			'to_forint' => 'forintra',
			'user_administration_LC' => 'felhasználók kezelése',
			'validation_date' => 'Érvényességi dátum',
			'validation_time_set_admin_description' => 'Ha a dátum nincsen kitöltve, akkor az alapértelmezett dátum lesz az érvényesség vége!',
		//login
			'forget_password' => 'Elfelejtetted a jelszavad?',
			'log_in' => 'Belépek',
			'unsuccessful_login' => 'Sikertelen a bejelentkezés!',
		//registration
			'accept_user_registration' => 'Regisztráció elfogadása',
			'city' => 'Város',
			'collegist_registration' => 'Collegista regisztráció',
			'collegist_username_advice_description' => 'Javasoljuk a Neptun azonosító használatát, így sosem fogod elfelejteni! ',
			'confirm_password' => 'Jelszó megerősítése',
			'confirm_registration' => 'Regisztráció megerősítése',
			'country' => 'Ország',
			'error_at_reseting_password' => 'Egy hiba miatt nem sikerült módosítani a jelszót!',
			'error_at_verifying_the_registration' => 'Hiba lépett fel a megerősítésnél! Probléma esetén keresse fel az oldal üzemeltetőjét.',
			'forgotten_password' => 'Elfelejtett jelszó',
			'hungary' => 'Magyarország',
			'modify_password' => 'Jelszó módosítása',
			'new_password' => 'Új jelszó',
			'password_again' => 'Jelszó újra',
			'phone_number' => 'Telefonszám',
			'postalcode' => 'Irányítószám',
			'reason_of_registration' => 'Regisztráció oka',
			'register' => 'Regisztrálok',
			'reset_password' => 'Jelszó beállítása',
			'send_password_reset_link' => 'Jelszó visszaállításának kezdése',
			'shire' => 'Megye',
			'success_at_reset_password' => 'Sikeresen módosítottad a jelszót!',
			'success_at_sending_registration_verification_email' => 'Kérjük erősítse meg e-mail címét, ehhez kiküldtünk egy levelet a megadott e-mail címre.',
			'error_at_sending_registration_verification_email' => 'Adatbázis hiba lépett fel a regisztráció során! Kérjük próbálkozzon később!',
			'success_send_email_about_what_to_do' => 'A további teendőkről elküldünk egy e-mailt a megadott címre.',
			'success_at_verifying_the_registration' => 'Sikeresen megerősítette az e-mail címét! A regisztráció elfogadásakor e-mailben értesítjük!',
			'registration_for_collegists' => 'Regisztráció collegistáknak',
			'registration_for_guests' => 'Regisztráció vendégeknek',
			'from_year' => 'Felvétel éve',
			'workshop' => 'Műhely',
			'faculty' => 'Kar',
			'neptun' => 'Neptun kód',
			'place_of_birth' => 'Születési hely',
			'date_of_birth' => 'Születési idő',
			'date_of_birth_with_format' => 'Születési idő (éééé.hh.nn.)',
			'name_of_mother' => 'Anyja leánykori neve',
			'year_of_leaving_exam' => 'Érettségi éve',
			'high_school' => 'Középiskola/gimnázium neve',
			'guest_registration' => 'Vendég regisztráció',
			'email_address_was_verified_at_this_date' => 'Az e-mail cím megerősítve ekkor:',
			'email_address_not_yet_verified' => 'Az e-mail cím még nincsen megerősítve',
			'accept_user_registration_needed_fields_description' => 'Az ilyen színnel jelzett mezők értékének a beállítása kötelező az ilyen típusú regisztrációhoz!',
			'accept_user_registration_not_needed_fields_description' => 'Az ilyen színnel jelzett mezők értékének a beállítása NEM lehetséges az ilyen típusú regisztrációhoz!',
			'accept_user_registration_informations' => 'Regisztrációt csak akkor érdemes elfogadni, ha az e-mail címét a felhasználó megerősítette előzőleg. Ez az információ a lista legelején megtalálható! Csak valós adatokat érdemes elfogadni!',
			'accept_user_registration_success' => 'A regisztrációs kérelem sikeresen el lett fogadva!',
			'accept_user_registration_failure' => 'A regisztrációs kérelem elfogadásakor hiba történt!',
			'registration_accepted' => 'Sikeres regisztráció',
			'reject_user_registration' => 'Regisztráció elutasítása',
			'reject_user_registration_success' => 'Sikeresen elutasítottad a regisztrációs kérelmet!',
			'reject_user_registration_failure' => 'A regisztrációs kérelem elutasítása sikertelen!',
			'register_password_can_contain_description' => 'A jelszó legalább 8 karakteres kell, hogy legyen. A maximális hossz 64 karakter. A jelszó a következő karaktereket tartalmazhatja: az angol ábécé betűit, számokat és ezeket a speciális karaktereket (- _ / . ? :) tartalmazhatják.',
			'register_email_advice_description' => 'Kérjük, létező e-mail címet adjon meg! Ezen keresztül fog a Collegium kommunikálni Önnel!',
			'register_username_can_contain_description' => 'Felhasználói neved legalább 6, de nem több, mint 32 karaktert tartalmazhat. A következő karakterek engedélyezettek: az angol ábécé betűi, számok, illetve a következő speciális karakterek (- _).',
			'register_reason_advice_description' => 'Kérjük minél kifejezőbb regisztrációs indokot adjon.',
			'accept_rules_with_submit_description' => 'A Regisztrálok gomb megnyomásával a szabályzat tartalmát elfogadom.',
			'new_user_registered' => 'Új regisztráció',
			'new_user_registered_description' => 'Kérem bírálja el a regisztrációs kérelmet!',
		//countries
			'HUN' => 'Magyarország',
			'AFG' => 'Afganisztán',
			'ALA' => 'Åland Islands',
			'ALB' => 'Albánia',
			'DZA' => 'Algéria',
			'ASM' => 'American Samoa',
			'AND' => 'Andorra',
			'AGO' => 'Angola',
			'AIA' => 'Anguilla',
			'ATA' => 'Antarctica',
			'ATG' => 'Antigua and Barbuda',
			'ARG' => 'Argentina',
			'ARM' => 'Armenia',
			'ABW' => 'Aruba',
			'AUS' => 'Ausztrália',
			'AUT' => 'Ausztria',
			'AZE' => 'Azerbajdzsán',
			'BHS' => 'Bahamas',
			'BHR' => 'Bahrain',
			'BGD' => 'Bangladesh',
			'BRB' => 'Barbados',
			'BLR' => 'Belarus',
			'BEL' => 'Belgium',
			'BLZ' => 'Belize',
			'BEN' => 'Benin',
			'BMU' => 'Bermuda',
			'BTN' => 'Bhutan',
			'BOL' => 'Bolivia, Plurinational State of',
			'BES' => 'Bonaire, Sint Eustatius and Saba',
			'BIH' => 'Bosnia and Herzegovina',
			'BWA' => 'Botswana',
			'BVT' => 'Bouvet Island',
			'BRA' => 'Brazil',
			'IOT' => 'British Indian Ocean Territory',
			'BRN' => 'Brunei Darussalam',
			'BGR' => 'Bulgaria',
			'BFA' => 'Burkina Faso',
			'BDI' => 'Burundi',
			'KHM' => 'Cambodia',
			'CMR' => 'Cameroon',
			'CAN' => 'Canada',
			'CPV' => 'Cape Verde',
			'CYM' => 'Cayman Islands',
			'CAF' => 'Central African Republic',
			'TCD' => 'Chad',
			'CHL' => 'Chile',
			'CHN' => 'China',
			'CXR' => 'Christmas Island',
			'CCK' => 'Cocos (Keeling) Islands',
			'COL' => 'Colombia',
			'COM' => 'Comoros',
			'COG' => 'Congo',
			'COD' => 'Congo, the Democratic Republic of the',
			'COK' => 'Cook Islands',
			'CRI' => 'Costa Rica',
			'CIV' => 'Côte d\'Ivoire',
			'HRV' => 'Croatia',
			'CUB' => 'Cuba',
			'CUW' => 'Curaçao',
			'CYP' => 'Cyprus',
			'CZE' => 'Czech Republic',
			'DNK' => 'Denmark',
			'DJI' => 'Djibouti',
			'DMA' => 'Dominica',
			'DOM' => 'Dominican Republic',
			'ECU' => 'Ecuador',
			'EGY' => 'Egypt',
			'SLV' => 'El Salvador',
			'GNQ' => 'Equatorial Guinea',
			'ERI' => 'Eritrea',
			'EST' => 'Estonia',
			'ETH' => 'Ethiopia',
			'FLK' => 'Falkland Islands (Malvinas)',
			'FRO' => 'Faroe Islands',
			'FJI' => 'Fiji',
			'FIN' => 'Finland',
			'FRA' => 'France',
			'GUF' => 'French Guiana',
			'PYF' => 'French Polynesia',
			'ATF' => 'French Southern Territories',
			'GAB' => 'Gabon',
			'GMB' => 'Gambia',
			'GEO' => 'Georgia',
			'DEU' => 'Germany',
			'GHA' => 'Ghana',
			'GIB' => 'Gibraltar',
			'GRC' => 'Greece',
			'GRL' => 'Greenland',
			'GRD' => 'Grenada',
			'GLP' => 'Guadeloupe',
			'GUM' => 'Guam',
			'GTM' => 'Guatemala',
			'GGY' => 'Guernsey',
			'GIN' => 'Guinea',
			'GNB' => 'Guinea-Bissau',
			'GUY' => 'Guyana',
			'HTI' => 'Haiti',
			'HMD' => 'Heard Island and McDonald Islands',
			'VAT' => 'Holy See (Vatican City State)',
			'HND' => 'Honduras',
			'HKG' => 'Hong Kong',
			'ISL' => 'Iceland',
			'IND' => 'India',
			'IDN' => 'Indonesia',
			'IRN' => 'Iran, Islamic Republic of',
			'IRQ' => 'Iraq',
			'IRL' => 'Ireland',
			'IMN' => 'Isle of Man',
			'ISR' => 'Israel',
			'ITA' => 'Italy',
			'JAM' => 'Jamaica',
			'JPN' => 'Japan',
			'JEY' => 'Jersey',
			'JOR' => 'Jordan',
			'KAZ' => 'Kazakhstan',
			'KEN' => 'Kenya',
			'KIR' => 'Kiribati',
			'PRK' => 'Korea, Democratic People\'s Republic of',
			'KOR' => 'Korea, Republic of',
			'KWT' => 'Kuwait',
			'KGZ' => 'Kyrgyzstan',
			'LAO' => 'Lao People\'s Democratic Republic',
			'LVA' => 'Latvia',
			'LBN' => 'Lebanon',
			'LSO' => 'Lesotho',
			'LBR' => 'Liberia',
			'LBY' => 'Libya',
			'LIE' => 'Liechtenstein',
			'LTU' => 'Lithuania',
			'LUX' => 'Luxembourg',
			'MAC' => 'Macao',
			'MKD' => 'Macedonia, the former Yugoslav Republic of',
			'MDG' => 'Madagascar',
			'MWI' => 'Malawi',
			'MYS' => 'Malaysia',
			'MDV' => 'Maldives',
			'MLI' => 'Mali',
			'MLT' => 'Málta',
			'MHL' => 'Marshall Islands',
			'MTQ' => 'Martinique',
			'MRT' => 'Mauritania',
			'MUS' => 'Mauritius',
			'MYT' => 'Mayotte',
			'MEX' => 'Mexico',
			'FSM' => 'Micronesia, Federated States of',
			'MDA' => 'Moldova, Republic of',
			'MCO' => 'Monaco',
			'MNG' => 'Mongolia',
			'MNE' => 'Montenegro',
			'MSR' => 'Montserrat',
			'MAR' => 'Morocco',
			'MOZ' => 'Mozambique',
			'MMR' => 'Myanmar',
			'NAM' => 'Namibia',
			'NRU' => 'Nauru',
			'NPL' => 'Nepál',
			'NLD' => 'Netherlands',
			'NCL' => 'New Caledonia',
			'NZL' => 'New Zealand',
			'NIC' => 'Nicaragua',
			'NER' => 'Niger',
			'NGA' => 'Nigeria',
			'NIU' => 'Niue',
			'NFK' => 'Norfolk Island',
			'MNP' => 'Northern Mariana Islands',
			'NOR' => 'Norway',
			'OMN' => 'Oman',
			'PAK' => 'Pakistan',
			'PLW' => 'Palau',
			'PSE' => 'Palestinian Territory, Occupied',
			'PAN' => 'Panama',
			'PNG' => 'Papua New Guinea',
			'PRY' => 'Paraguay',
			'PER' => 'Peru',
			'PHL' => 'Philippines',
			'PCN' => 'Pitcairn',
			'POL' => 'Lengyelország',
			'PRT' => 'Portugália',
			'PRI' => 'Puerto Rico',
			'QAT' => 'Qatar',
			'REU' => 'Réunion',
			'ROU' => 'Románia',
			'RUS' => 'Russian Federation',
			'RWA' => 'Rwanda',
			'BLM' => 'Saint Barthélemy',
			'SHN' => 'Saint Helena, Ascension and Tristan da Cunha',
			'KNA' => 'Saint Kitts and Nevis',
			'LCA' => 'Saint Lucia',
			'MAF' => 'Saint Martin (French part)',
			'SPM' => 'Saint Pierre and Miquelon',
			'VCT' => 'Saint Vincent and the Grenadines',
			'WSM' => 'Samoa',
			'SMR' => 'San Marino',
			'STP' => 'Sao Tome and Principe',
			'SAU' => 'Saudi Arabia',
			'SEN' => 'Senegal',
			'SRB' => 'Szerbia',
			'SYC' => 'Seychelles',
			'SLE' => 'Sierra Leone',
			'SGP' => 'Szingapúr',
			'SXM' => 'Sint Maarten (Dutch part)',
			'SVK' => 'Szlovákia',
			'SVN' => 'Szlovénia',
			'SLB' => 'Solomon Islands',
			'SOM' => 'Szomália',
			'ZAF' => 'South Africa',
			'SGS' => 'South Georgia and the South Sandwich Islands',
			'SSD' => 'South Sudan',
			'ESP' => 'Spanyolország',
			'LKA' => 'Sri Lanka',
			'SDN' => 'Szudán',
			'SUR' => 'Suriname',
			'SJM' => 'Svalbard and Jan Mayen',
			'SWZ' => 'Swaziland',
			'SWE' => 'Sweden',
			'CHE' => 'Switzerland',
			'SYR' => 'Syrian Arab Republic',
			'TWN' => 'Taiwan, Province of China',
			'TJK' => 'Tajikistan',
			'TZA' => 'Tanzania, United Republic of',
			'THA' => 'Thailand',
			'TLS' => 'Timor-Leste',
			'TGO' => 'Togo',
			'TKL' => 'Tokelau',
			'TON' => 'Tonga',
			'TTO' => 'Trinidad and Tobago',
			'TUN' => 'Tunisia',
			'TUR' => 'Turkey',
			'TKM' => 'Turkmenistan',
			'TCA' => 'Turks and Caicos Islands',
			'TUV' => 'Tuvalu',
			'UGA' => 'Uganda',
			'UKR' => 'Ukraine',
			'ARE' => 'United Arab Emirates',
			'GBR' => 'United Kingdom',
			'USA' => 'Amerikai Egyesült Államok',
			'UMI' => 'United States Minor Outlying Islands',
			'URY' => 'Uruguay',
			'UZB' => 'Uzbekistan',
			'VUT' => 'Vanuatu',
			'VEN' => 'Venezuela, Bolivarian Republic of',
			'VNM' => 'Viet Nam',
			'VGB' => 'Virgin Islands, British',
			'VIR' => 'Virgin Islands, U.S.',
			'WLF' => 'Wallis and Futuna',
			'ESH' => 'Western Sahara',
			'YEM' => 'Yemen',
			'ZMB' => 'Zambia',
			'ZWE' => 'Zimbabwe',
		//tasks
			'task' => 'Feladat',
			'task_manager' => 'Feladat központ',
			'status' => 'Státusz',
			'date' => 'Dátum',
			'caption' => 'Röviden',
			'create_new_task' => 'Új feladat hozzáadása',
			'add_task' => 'Feladat hozzáadása',
			'deadline' => 'Határidő',
			'type' => 'Típus',
			'priority' => 'Prioritás',
			'write_task_caption_description' => 'Feladat címe',
			'write_task_text_description' => 'Írd ide a feladat leírását!',
			'bug report' => 'Hiba bejelentés',
			'request' => 'Kérés',
			'question' => 'Kérdés',
			'low' => 'Alacsony',
			'normal' => 'Normál',
			'high' => 'Magas',
			'highest' => 'Legmagasabb',
			'assigned_to' => 'Hozzárendelve',
			'working_hour' => 'Munkaóra',
			'write_new_comment' => 'Új komment írása',
			'write_new_comment_description' => 'Írjad ide a komment szövegét!',
			'new' => 'Új',
			'open' => 'Nyitva',
			'closed' => 'Lezárva',
			'pending' => 'Várakozó',
			'ongoing' => 'Folyamatban',
			'send' => 'Elküld',
			'not_set' => 'nincsen beállítva',
			'comment_not_exists' => 'Nem létezik ilyen komment!',
			'task_not_found' => 'Ilyen feladat nem létezik!',
			'no_one_is_assigned' => 'Senkihez sincs hozzárendelve!',
			'closed_on_that_date' => 'Lezárás dátuma',
		
			'' => '',
		];
	}
	
//	{{ $layout->language('') }}
	
	public static function english(){
		return
		[
		//basic
			'activate' => 'Activate',
			'admin_panel' => 'Admin panel',
			'allow_request' => 'Allow request',
			'approve' => 'Approve',
			'back_to_last_page' => 'Back to the previous page',
			'balance' => 'Balance',
			'count' => 'darab',
			'deactivate' => 'Deactivate',
			'delete_filter' => 'Remove filter',
			'deny_request' => 'Deny request',
			'ejc' => 'EJC',
			'error' => 'Error',
			'error_at_finding_the_user' => 'Could not find the user, or the profile is hidden!',
			'find' => 'Find',
			'find_user' => 'Find user',
			'help' => 'Help',
			'insufficient_permissions' => 'You do not have the needed permissions!',
			'list' => 'List',
			'list_users' => 'list users',
			'login' => 'Login',
			'logout' => 'Log out',
			'modify' => 'Modify',
			'next_page' => 'Next page',
			'note' => 'Note',
			'oops_something_went_wrong' => 'Oops, something went wrong!',
			'password' => 'Password',
			'permission' => 'Permission',
			'previous_page' => 'Previous page',
			'request' => 'Request',
			'start_of_modification' => 'Start modifying',
			'success' => 'Success',
			'uran' => 'Urán',
			'validate' => 'Validate',
			'validation' => 'validation',
			'users_list' => 'Users list',
			'identifier' => 'Identifier',
			'user_data' => 'Personal data',
			'administration' => 'Administration',
			'not_specified_value' => 'This value in not valid!',
			'empty_value_is_forbidden' => 'Empty value is forbidden!',
		//permissions
			'assign_permissions' => 'Assign permissions',
			'error_at_setting_the_permissions' => 'Error at setting the permissions!',
			'list_permissions' => 'List permissions',
			'permissions_handling' => 'Permission handler',
			'set_permissions' => 'Set permissions',
		//modules
			'error_at_module_activation' => 'Error at activating the modul!',
			'module' => 'Module',
			'module_activate' => 'Module activation',
			'module_deactivate' => 'Module deactivation',
			'modules' => 'Modules',
			'modules_handling' => 'Module handler',
		//notifications	
			'error_notification_view_insufficient_permission' => 'You do not have the permissions to view this notification!',
			'newer_notifications' => 'Newer notifications',
			'no_notification_to_show' => 'No notifications to show!',
			'notifications' => 'Notifications',
			'older_notifications' => 'Older notifications',
			'show_all' => 'Show all',
			'system_no_problem' => 'System: No problem',
			'unread_notifications' => 'Unread notifications',
		//rooms
			'back_to_the_rooms_list' => 'Back to the rooms list',
			'basement' => 'Basement',
			'cellar' => 'Cellar',
			'error_already_lives_somewhere' => 'This person lives in an other room!',
			'error_floor_not_found' => 'Floor not found!',
			'floor' => 'floor',
			'free_spot' => 'Free spot',
			'free_spots' => 'Free spots',
			'ground_floor' => 'Ground floor',
			'resident' => 'Resident',
			'room_assignment' => 'Room assignment',
		//userdata
			'address' => 'Address',
			'email_address' => 'E-mail address',
			'name' => 'Name',
			'registration_date' => 'Registration date',
			'user' => 'User',
			'username' => 'Username',
		//menues
			'admin' => 'ADMIN',
			'ecnet' => 'ECnet',
			'home' => 'Home',
			'internet_access' => 'Internet access',
			'mac_slot_ordering' => 'MAC slot ordering',
			'my_data' => 'My data',
			'my_profile' => 'My profile',
			'printing_account' => 'Printing account',
			'registration' => 'Registration',
			'user_administration' => 'Manage users',
		//home
			'logged_in_home_message' => 'TODO',
			'not_logged_in_home_message' => 'TODO',
		//ecnet
			'available_money' => 'Available money',
			'balance_was_modified' => 'Balance was modified!',
			'balance_was_modified_description' => 'The printing balance was modified from',			
			'custom_validation_date' => 'Custom validation date',
			'default_time' => 'default time',
			'default_time_now_description' => 'Default time currently',
			'default_time_set_note_description' => 'Time is set to 5 a.m.!',
			'error_at_allowing_mac_slot_order' => 'Error at allowing/denying MAC slot order!',
			'error_at_money_adding' => 'Error at balance modifying!',
			'error_no_default_time_description' => 'Error! Default time is not set!',
			'error_no_default_time_set' => 'Default time is not set!',
			'error_page_not_found' => 'Page not found!',
			'expiration_date' => 'Expiration date',
			'from_forint' => 'forints to',
			'internet_access_was_modified' => 'Internet access was modified!',
			'internet_access_was_modified_to_description' => 'The internet access expiration time was modified to: ',			
			'internet_in_not_active' => 'Your internet connection is not active!',
			'internet_is_active' => 'Your internet connection is active!',
			'internet_registartion_description' => 'Contact a system administrator, if you have questions about the internet registration!',
			'list_internet_users' => 'List users with active internet connection',
			'list_internet_users_both' => 'Both of them',
			'list_internet_users_only_name' => 'Only names',
			'list_internet_users_only_username' => 'Only usernames',
			'low_mac_slot_usage' => 'LOW SLOT USAGE',
			'mac_address' => 'MAC address',
			'mac_addresses' => 'MAC addresses',
			'mac_slot_order' => 'SLOT order',
			'mac_slot_order_was_accepted_description' => 'Your MAC slot order was accepted! Request: ',
			'mac_slot_order_was_denied_description' => 'Your MAC slot order was denied! Request: ',			
			'mac_slot_ordering_description' => 'You can order more MAC slots for the wired network. Please, give the reason of the order. After the ordering, we will accept or deny it (we send you a notification).',
			'mac_slot_was_ordered_description' => 'You ordered a MAC slot! Request: ',			
			'mac_slots_count' => 'MAC slot count',
			'money_add_admin_note_description' => 'If the "money to add" field is zero, then the "Money on the account" field is used!',
			'money_on_account' => 'Money on the account',
			'money_to_add' => 'Money to add to account',
			'money_upload_note_description' => 'You can refill your printing account at a system administrator.',
			'order_slot' => 'Order slot',
			'reason_of_ordering' => 'Reason',
			'registrated_mac_addresses' => 'Registered MAC addresses',
			'set_mac_address' => 'Set MAC addresses',
			'success_at_allowing_mac_slot_order' => 'The MAC slot order allowing/denying was successfully done!',
			'success_at_sending_mac_slot_order' => 'The MAC slot order was successfully sent!',
			'success_at_setting_the_default_time_to' => 'The new default time is now: ',
			'success_at_setting_users_internet_access_time' => 'The internet access was successfully updated!',
			'success_at_updating_mac_addresses' => 'The MAC addresses were successfully updated!',
			'success_set_money' => 'The balance is successfully modified!',
			'to_forint' => 'forints',			
			'user_administration_LC' => 'manage users',
			'user_administration_LC' => 'manage users',
			'validation_date' => 'Validation date',
			'validation_time_set_admin_description' => 'If the date is not set, then the default time is set as expiration time!',
		//login
			'forget_password' => 'Forgot your password?',
			'log_in' => 'Log in',
			'unsuccessful_login' => 'Could not log in with the given credentials!',
		//registration
			'accept_user_registration' => 'Accept registrations',
			'city' => 'City',
			'collegist_registration' => 'Collegist registration',
			'collegist_username_advice_description' => 'Neptun identifier is recommended as a username.',
			'confirm_password' => 'Confirm password',
			'confirm_registration' => 'Confirm registration',
			'country' => 'Country',
			'error_at_reseting_password' => 'Could not reset the password!',
			'error_at_verifying_the_registration' => 'Error at e-mail verification! Please contact a system administrator.',
			'forgotten_password' => 'Forgotten password',
			'hungary' => 'Hungary',
			'modify_password' => 'Password modification',
			'new_password' => 'New password',
			'password_again' => 'Password again',
			'phone_number' => 'Telefon number',
			'postalcode' => 'Postal code',
			'reason_of_registration' => 'Reason',
			'register' => 'Register',
			'reset_password' => 'Reset password',
			'send_password_reset_link' => 'Send password reset link',
			'shire' => 'Region',
			'success_at_reset_password' => 'You reset the password successfully!',
			'success_at_sending_registration_verification_email' => 'Please verify your e-mail address! We have sent you an e-mail with the details.',
			'error_at_sending_registration_verification_email' => 'A database error has occured during the registration! Please try again later!',
			'success_send_email_about_what_to_do' => 'We sent an e-mail about what to do now.',
			'success_at_verifying_the_registration' => 'You have successfully verified your e-mail address! When your registration will be accepted, we will inform you via e-mail!',
			'registration_for_collegists' => 'Registration for collegists',
			'registration_for_guests' => 'Registration for guests',
			'from_year' => 'Enrollment year',
			'workshop' => 'Workshop',
			'faculty' => 'Faculty',
			'neptun' => 'Neptun identifier',
			'place_of_birth' => 'Place of birth',
			'date_of_birth' => 'Date of birth',
			'date_of_birth_with_format' => 'Date of birth (yyyy.mm.dd.)',
			'name_of_mother' => 'Mother\'s maiden name',
			'year_of_leaving_exam' => 'Year of the leaving exam',
			'high_school' => 'Name of secondary school',
			'guest_registration' => 'Guest registration',
			'email_address_was_verified_at_this_date' => 'E-mail address was verified at',
			'email_address_not_yet_verified' => 'E-mail address is not yet verified',
			'accept_user_registration_needed_fields_description' => 'This color indicates that these values must be set for this type of registration.',
			'accept_user_registration_not_needed_fields_description' => 'This color indicates that these values cannot be set for this type of registration.',
			'accept_user_registration_informations' => 'Registrations should be accepted only in case the user has already verified the e-mail address. This information can be found at the top of the list! Only valid data should be accepted!',
			'accept_user_registration_success' => 'The registration order was successfully accepted!',
			'accept_user_registration_failure' => 'An error occured during the acceptance of the registration order!',
			'registration_accepted' => 'Registration was accepted',
			'reject_user_registration' => 'Reject registration',
			'reject_user_registration_success' => 'The registration order was successfully rejected!',
			'reject_user_registration_failure' => 'An error occured during the rejection of the registration order!',
			'register_password_can_contain_description' => 'The password must be between 8 and 64 characters. It can contain the following characters: english letters, numbers and these special characters (- _ / . ? :).',
			'register_email_advice_description' => 'A valid e-mail address is required! We\'ll contact you via this.',
			'register_username_can_contain_description' => 'The username must be between 6 and 32 characters. It can contain the following characters: english letters, numbers and these special characters (- _).',
			'register_reason_advice_description' => 'Please give us a clear reason.',
			'accept_rules_with_submit_description' => 'I accept the terms and conditions.',
			'new_user_registered' => 'New registration',
			'new_user_registered_description' => 'Please accept or reject the registration order!',
		//countries
			'HUN' => 'Hungary',
			'AFG' => 'Afghanistan',
			'ALA' => 'Åland Islands',
			'ALB' => 'Albania',
			'DZA' => 'Algeria',
			'ASM' => 'American Samoa',
			'AND' => 'Andorra',
			'AGO' => 'Angola',
			'AIA' => 'Anguilla',
			'ATA' => 'Antarctica',
			'ATG' => 'Antigua and Barbuda',
			'ARG' => 'Argentina',
			'ARM' => 'Armenia',
			'ABW' => 'Aruba',
			'AUS' => 'Australia',
			'AUT' => 'Austria',
			'AZE' => 'Azerbaijan',
			'BHS' => 'Bahamas',
			'BHR' => 'Bahrain',
			'BGD' => 'Bangladesh',
			'BRB' => 'Barbados',
			'BLR' => 'Belarus',
			'BEL' => 'Belgium',
			'BLZ' => 'Belize',
			'BEN' => 'Benin',
			'BMU' => 'Bermuda',
			'BTN' => 'Bhutan',
			'BOL' => 'Bolivia, Plurinational State of',
			'BES' => 'Bonaire, Sint Eustatius and Saba',
			'BIH' => 'Bosnia and Herzegovina',
			'BWA' => 'Botswana',
			'BVT' => 'Bouvet Island',
			'BRA' => 'Brazil',
			'IOT' => 'British Indian Ocean Territory',
			'BRN' => 'Brunei Darussalam',
			'BGR' => 'Bulgaria',
			'BFA' => 'Burkina Faso',
			'BDI' => 'Burundi',
			'KHM' => 'Cambodia',
			'CMR' => 'Cameroon',
			'CAN' => 'Canada',
			'CPV' => 'Cape Verde',
			'CYM' => 'Cayman Islands',
			'CAF' => 'Central African Republic',
			'TCD' => 'Chad',
			'CHL' => 'Chile',
			'CHN' => 'China',
			'CXR' => 'Christmas Island',
			'CCK' => 'Cocos (Keeling) Islands',
			'COL' => 'Colombia',
			'COM' => 'Comoros',
			'COG' => 'Congo',
			'COD' => 'Congo, the Democratic Republic of the',
			'COK' => 'Cook Islands',
			'CRI' => 'Costa Rica',
			'CIV' => 'Côte d\'Ivoire',
			'HRV' => 'Croatia',
			'CUB' => 'Cuba',
			'CUW' => 'Curaçao',
			'CYP' => 'Cyprus',
			'CZE' => 'Czech Republic',
			'DNK' => 'Denmark',
			'DJI' => 'Djibouti',
			'DMA' => 'Dominica',
			'DOM' => 'Dominican Republic',
			'ECU' => 'Ecuador',
			'EGY' => 'Egypt',
			'SLV' => 'El Salvador',
			'GNQ' => 'Equatorial Guinea',
			'ERI' => 'Eritrea',
			'EST' => 'Estonia',
			'ETH' => 'Ethiopia',
			'FLK' => 'Falkland Islands (Malvinas)',
			'FRO' => 'Faroe Islands',
			'FJI' => 'Fiji',
			'FIN' => 'Finland',
			'FRA' => 'France',
			'GUF' => 'French Guiana',
			'PYF' => 'French Polynesia',
			'ATF' => 'French Southern Territories',
			'GAB' => 'Gabon',
			'GMB' => 'Gambia',
			'GEO' => 'Georgia',
			'DEU' => 'Germany',
			'GHA' => 'Ghana',
			'GIB' => 'Gibraltar',
			'GRC' => 'Greece',
			'GRL' => 'Greenland',
			'GRD' => 'Grenada',
			'GLP' => 'Guadeloupe',
			'GUM' => 'Guam',
			'GTM' => 'Guatemala',
			'GGY' => 'Guernsey',
			'GIN' => 'Guinea',
			'GNB' => 'Guinea-Bissau',
			'GUY' => 'Guyana',
			'HTI' => 'Haiti',
			'HMD' => 'Heard Island and McDonald Islands',
			'VAT' => 'Holy See (Vatican City State)',
			'HND' => 'Honduras',
			'HKG' => 'Hong Kong',
			'ISL' => 'Iceland',
			'IND' => 'India',
			'IDN' => 'Indonesia',
			'IRN' => 'Iran, Islamic Republic of',
			'IRQ' => 'Iraq',
			'IRL' => 'Ireland',
			'IMN' => 'Isle of Man',
			'ISR' => 'Israel',
			'ITA' => 'Italy',
			'JAM' => 'Jamaica',
			'JPN' => 'Japan',
			'JEY' => 'Jersey',
			'JOR' => 'Jordan',
			'KAZ' => 'Kazakhstan',
			'KEN' => 'Kenya',
			'KIR' => 'Kiribati',
			'PRK' => 'Korea, Democratic People\'s Republic of',
			'KOR' => 'Korea, Republic of',
			'KWT' => 'Kuwait',
			'KGZ' => 'Kyrgyzstan',
			'LAO' => 'Lao People\'s Democratic Republic',
			'LVA' => 'Latvia',
			'LBN' => 'Lebanon',
			'LSO' => 'Lesotho',
			'LBR' => 'Liberia',
			'LBY' => 'Libya',
			'LIE' => 'Liechtenstein',
			'LTU' => 'Lithuania',
			'LUX' => 'Luxembourg',
			'MAC' => 'Macao',
			'MKD' => 'Macedonia, the former Yugoslav Republic of',
			'MDG' => 'Madagascar',
			'MWI' => 'Malawi',
			'MYS' => 'Malaysia',
			'MDV' => 'Maldives',
			'MLI' => 'Mali',
			'MLT' => 'Malta',
			'MHL' => 'Marshall Islands',
			'MTQ' => 'Martinique',
			'MRT' => 'Mauritania',
			'MUS' => 'Mauritius',
			'MYT' => 'Mayotte',
			'MEX' => 'Mexico',
			'FSM' => 'Micronesia, Federated States of',
			'MDA' => 'Moldova, Republic of',
			'MCO' => 'Monaco',
			'MNG' => 'Mongolia',
			'MNE' => 'Montenegro',
			'MSR' => 'Montserrat',
			'MAR' => 'Morocco',
			'MOZ' => 'Mozambique',
			'MMR' => 'Myanmar',
			'NAM' => 'Namibia',
			'NRU' => 'Nauru',
			'NPL' => 'Nepal',
			'NLD' => 'Netherlands',
			'NCL' => 'New Caledonia',
			'NZL' => 'New Zealand',
			'NIC' => 'Nicaragua',
			'NER' => 'Niger',
			'NGA' => 'Nigeria',
			'NIU' => 'Niue',
			'NFK' => 'Norfolk Island',
			'MNP' => 'Northern Mariana Islands',
			'NOR' => 'Norway',
			'OMN' => 'Oman',
			'PAK' => 'Pakistan',
			'PLW' => 'Palau',
			'PSE' => 'Palestinian Territory, Occupied',
			'PAN' => 'Panama',
			'PNG' => 'Papua New Guinea',
			'PRY' => 'Paraguay',
			'PER' => 'Peru',
			'PHL' => 'Philippines',
			'PCN' => 'Pitcairn',
			'POL' => 'Poland',
			'PRT' => 'Portugal',
			'PRI' => 'Puerto Rico',
			'QAT' => 'Qatar',
			'REU' => 'Réunion',
			'ROU' => 'Romania',
			'RUS' => 'Russian Federation',
			'RWA' => 'Rwanda',
			'BLM' => 'Saint Barthélemy',
			'SHN' => 'Saint Helena, Ascension and Tristan da Cunha',
			'KNA' => 'Saint Kitts and Nevis',
			'LCA' => 'Saint Lucia',
			'MAF' => 'Saint Martin (French part)',
			'SPM' => 'Saint Pierre and Miquelon',
			'VCT' => 'Saint Vincent and the Grenadines',
			'WSM' => 'Samoa',
			'SMR' => 'San Marino',
			'STP' => 'Sao Tome and Principe',
			'SAU' => 'Saudi Arabia',
			'SEN' => 'Senegal',
			'SRB' => 'Serbia',
			'SYC' => 'Seychelles',
			'SLE' => 'Sierra Leone',
			'SGP' => 'Singapore',
			'SXM' => 'Sint Maarten (Dutch part)',
			'SVK' => 'Slovakia',
			'SVN' => 'Slovenia',
			'SLB' => 'Solomon Islands',
			'SOM' => 'Somalia',
			'ZAF' => 'South Africa',
			'SGS' => 'South Georgia and the South Sandwich Islands',
			'SSD' => 'South Sudan',
			'ESP' => 'Spain',
			'LKA' => 'Sri Lanka',
			'SDN' => 'Sudan',
			'SUR' => 'Suriname',
			'SJM' => 'Svalbard and Jan Mayen',
			'SWZ' => 'Swaziland',
			'SWE' => 'Sweden',
			'CHE' => 'Switzerland',
			'SYR' => 'Syrian Arab Republic',
			'TWN' => 'Taiwan, Province of China',
			'TJK' => 'Tajikistan',
			'TZA' => 'Tanzania, United Republic of',
			'THA' => 'Thailand',
			'TLS' => 'Timor-Leste',
			'TGO' => 'Togo',
			'TKL' => 'Tokelau',
			'TON' => 'Tonga',
			'TTO' => 'Trinidad and Tobago',
			'TUN' => 'Tunisia',
			'TUR' => 'Turkey',
			'TKM' => 'Turkmenistan',
			'TCA' => 'Turks and Caicos Islands',
			'TUV' => 'Tuvalu',
			'UGA' => 'Uganda',
			'UKR' => 'Ukraine',
			'ARE' => 'United Arab Emirates',
			'GBR' => 'United Kingdom',
			'USA' => 'United States',
			'UMI' => 'United States Minor Outlying Islands',
			'URY' => 'Uruguay',
			'UZB' => 'Uzbekistan',
			'VUT' => 'Vanuatu',
			'VEN' => 'Venezuela, Bolivarian Republic of',
			'VNM' => 'Viet Nam',
			'VGB' => 'Virgin Islands, British',
			'VIR' => 'Virgin Islands, U.S.',
			'WLF' => 'Wallis and Futuna',
			'ESH' => 'Western Sahara',
			'YEM' => 'Yemen',
			'ZMB' => 'Zambia',
			'ZWE' => 'Zimbabwe',
		//tasks
			'task' => 'Task',
			'task_manager' => 'Task manager',
			'status' => 'Status',
			'date' => 'Date',
			'caption' => 'Caption',
			'create_new_task' => 'Create new task',
			'add_task' => 'Add task',
			'deadline' => 'Deadline',
			'type' => 'Type',
			'priority' => 'Priority',
			'write_task_caption_description' => 'Task caption',
			'write_task_text_description' => 'Write here the description of the task!',
			'bug report' => 'Bug report',
			'request' => 'Request',
			'question' => 'Question',
			'low' => 'Low',
			'normal' => 'Normal',
			'high' => 'High',
			'highest' => 'Highest',
			'assigned_to' => 'Assigned to',
			'working_hour' => 'Working hours',
			'write_new_comment' => 'Write a new comment',
			'write_new_comment_description' => 'Write the comment here!',
			'new' => 'New',
			'open' => 'Open',
			'closed' => 'Closed',
			'pending' => 'Pending',
			'ongoing' => 'Ongoing',
			'send' => 'Send',
			'not_set' => 'not set',
			'comment_not_exists' => 'Comment not exists!',
			'task_not_found' => 'Task was not found!',
			'no_one_is_assigned' => 'Nobody is assigned!',
			'closed_on_that_date' => 'Closing date',
		];
	}
	
}

