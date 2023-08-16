<?php

// Query Settings
$sql_settings = mysqli_query($mysqli, "SELECT * FROM settings WHERE company_id = 1");
$row = mysqli_fetch_array($sql_settings);

// Database version
DEFINE("CURRENT_DATABASE_VERSION", $row['config_current_database_version']);

// Microsoft OAuth
$config_azure_client_id = $row['config_azure_client_id'];
$config_azure_client_secret = $row['config_azure_client_secret'];

// Mail
$config_smtp_host = $row['config_smtp_host'];
$config_smtp_port = intval($row['config_smtp_port']);
$config_smtp_encryption = $row['config_smtp_encryption'];
$config_smtp_username = $row['config_smtp_username'];
$config_smtp_password = $row['config_smtp_password'];
$config_mail_from_email = $row['config_mail_from_email'];
$config_mail_from_name = $row['config_mail_from_name'];
// Mail - IMAP
$config_imap_host = $row['config_imap_host'];
$config_imap_port = intval($row['config_imap_port']);
$config_imap_encryption = $row['config_imap_encryption'];
$config_imap_username = $row['config_imap_username'];
$config_imap_password = $row['config_imap_password'];

// Defaults
$config_start_page = $row['config_start_page'];
$config_default_transfer_from_account = intval($row['config_default_transfer_from_account']);
$config_default_transfer_to_account = intval($row['config_default_transfer_to_account']);
$config_default_payment_account = intval($row['config_default_payment_account']);
$config_default_expense_account = intval($row['config_default_expense_account']);
$config_default_payment_method = $row['config_default_payment_method'];
$config_default_expense_payment_method = $row['config_default_expense_payment_method'];
$config_default_calendar = intval($row['config_default_calendar']);
$config_default_net_terms = intval($row['config_default_net_terms']);

// Invoice
$config_invoice_prefix = $row['config_invoice_prefix'];
$config_invoice_next_number = intval($row['config_invoice_next_number']);
$config_invoice_footer = $row['config_invoice_footer'];
$config_invoice_from_name = $row['config_invoice_from_name'];
$config_invoice_from_email = $row['config_invoice_from_email'];
$config_invoice_late_fee_enable = intval($row['config_invoice_late_fee_enable']);
$config_invoice_late_fee_percent = floatval($row['config_invoice_late_fee_percent']);

// Recurring
$config_recurring_prefix = $row['config_recurring_prefix'];
$config_recurring_next_number = intval($row['config_recurring_next_number']);

// Quotes
$config_quote_prefix = $row['config_quote_prefix'];
$config_quote_next_number = intval($row['config_quote_next_number']);
$config_quote_footer = $row['config_quote_footer'];
$config_quote_from_name = $row['config_quote_from_name'];
$config_quote_from_email = $row['config_quote_from_email'];

// Tickets
$config_ticket_prefix = $row['config_ticket_prefix'];
$config_ticket_next_number = intval($row['config_ticket_next_number']);
$config_ticket_from_name = $row['config_ticket_from_name'];
$config_ticket_from_email = $row['config_ticket_from_email'];
$config_ticket_email_parse = intval($row['config_ticket_email_parse']);
$config_ticket_client_general_notifications = intval($row['config_ticket_client_general_notifications']);
$config_ticket_autoclose = intval($row['config_ticket_autoclose']);
$config_ticket_autoclose_hours = intval($row['config_ticket_autoclose_hours']);

// Cron
$config_enable_cron = intval($row['config_enable_cron']);
$config_cron_key = $row['config_cron_key'];

// Alerts & Notifications
$config_recurring_auto_send_invoice = intval($row['config_recurring_auto_send_invoice']);
$config_enable_alert_domain_expire = intval($row['config_enable_alert_domain_expire']);
$config_send_invoice_reminders = intval($row['config_send_invoice_reminders']);
$config_invoice_overdue_reminders = intval($row['config_invoice_overdue_reminders']);

// Online Payment
$config_stripe_enable = intval($row['config_stripe_enable']);
$config_stripe_publishable = $row['config_stripe_publishable'];
$config_stripe_secret = $row['config_stripe_secret'];
$config_stripe_account = $row['config_stripe_account'];

// Modules
$config_module_enable_itdoc = intval($row['config_module_enable_itdoc']);
$config_module_enable_ticketing = intval($row['config_module_enable_ticketing']);
$config_module_enable_accounting = intval($row['config_module_enable_accounting']);
$config_client_portal_enable = intval($row['config_client_portal_enable']);

// Login key
$config_login_key_required = $row['config_login_key_required'];
$config_login_key_secret = $row['config_login_key_secret'];

// Currency
$config_currency_format = "US_en";

// Theme
$config_theme = $row['config_theme'];
$config_theme_mode = "dark_mode";

// Telemetry
$config_telemetry = intval($row['config_telemetry']);

$colors_array = array(
    'blue',
    'green',
    'cyan',
    'yellow',
    'red',
    'black',
    'gray-dark',
    'gray',
    'light',
    'indigo',
    'navy',
    'purple',
    'fuchsia',
    'pink',
    'maroon',
    'orange',
    'lime',
    'teal',
    'olive'
);

$net_terms_array = array(
    '0'=>'On Reciept',
    '7'=>'7 Days',
    '14'=>'14 Days',
    '30'=>'30 Days'
);

$records_per_page_array = array('5','10','15','20','30','50','100');

$countries_array = array(
    "Afghanistan",
    "Albania",
    "Algeria",
    "Andorra",
    "Angola",
    "Antigua and Barbuda",
    "Argentina",
    "Armenia",
    "Australia",
    "Austria",
    "Azerbaijan",
    "Bahamas",
    "Bahrain",
    "Bangladesh",
    "Barbados",
    "Belarus",
    "Belgium",
    "Belize",
    "Benin",
    "Bhutan",
    "Bolivia",
    "Bosnia and Herzegovina",
    "Botswana",
    "Brazil",
    "Brunei",
    "Bulgaria",
    "Burkina Faso",
    "Burundi",
    "Cambodia",
    "Cameroon",
    "Canada",
    "Cape Verde",
    "Central African Republic",
    "Chad",
    "Chile",
    "China",
    "Colombi",
    "Comoros",
    "Congo (Brazzaville)",
    "Congo",
    "Costa Rica",
    "Cote d'Ivoire",
    "Croatia",
    "Cuba",
    "Cyprus",
    "Czech Republic",
    "Denmark",
    "Djibouti",
    "Dominica",
    "Dominican Republic",
    "East Timor (Timor Timur)",
    "Ecuador",
    "Egypt",
    "El Salvador",
    "Equatorial Guinea",
    "Eritrea",
    "Estonia",
    "Ethiopia",
    "Fiji",
    "Finland",
    "France",
    "Gabon",
    "Gambia, The",
    "Georgia",
    "Germany",
    "Ghana",
    "Greece",
    "Grenada",
    "Guatemala",
    "Guinea",
    "Guinea-Bissau",
    "Guyana",
    "Haiti",
    "Honduras",
    "Hungary",
    "Iceland",
    "India",
    "Indonesia",
    "Iran",
    "Iraq",
    "Ireland",
    "Israel",
    "Italy",
    "Jamaica",
    "Japan",
    "Jordan",
    "Kazakhstan",
    "Kenya",
    "Kiribati",
    "Korea, North",
    "Korea, South",
    "Kuwait",
    "Kyrgyzstan",
    "Laos",
    "Latvia",
    "Lebanon",
    "Lesotho",
    "Liberia",
    "Libya",
    "Liechtenstein",
    "Lithuania",
    "Luxembourg",
    "Macedonia",
    "Madagascar",
    "Malawi",
    "Malaysia",
    "Maldives",
    "Mali",
    "Malta",
    "Marshall Islands",
    "Mauritania",
    "Mauritius",
    "Mexico",
    "Micronesia",
    "Moldova",
    "Monaco",
    "Mongolia",
    "Morocco",
    "Mozambique",
    "Myanmar",
    "Namibia",
    "Nauru",
    "Nepal",
    "Netherlands",
    "New Zealand",
    "Nicaragua",
    "Niger",
    "Nigeria",
    "Norway",
    "Oman",
    "Pakistan",
    "Palau",
    "Panama",
    "Papua New Guinea",
    "Paraguay",
    "Peru",
    "Philippines",
    "Poland",
    "Portugal",
    "Qatar",
    "Romania",
    "Russia",
    "Rwanda",
    "Saint Kitts and Nevis",
    "Saint Lucia",
    "Saint Vincent",
    "Samoa",
    "San Marino",
    "Sao Tome and Principe",
    "Saudi Arabia",
    "Senegal",
    "Serbia and Montenegro",
    "Seychelles",
    "Sierra Leone",
    "Singapore",
    "Slovakia",
    "Slovenia",
    "Solomon Islands",
    "Somalia",
    "South Africa",
    "Spain",
    "Sri Lanka",
    "Sudan",
    "Suriname",
    "Swaziland",
    "Sweden",
    "Switzerland",
    "Syria",
    "Taiwan",
    "Tajikistan",
    "Tanzania",
    "Thailand",
    "Togo",
    "Tonga",
    "Trinidad and Tobago",
    "Tunisia",
    "Turkey",
    "Turkmenistan",
    "Tuvalu",
    "Uganda",
    "Ukraine",
    "United Arab Emirates",
    "United Kingdom",
    "United States",
    "Uruguay",
    "Uzbekistan",
    "Vanuatu",
    "Vatican City",
    "Venezuela",
    "Vietnam",
    "Yemen",
    "Zambia",
    "Zimbabwe"
);

$currencies_array = array(
    'ALL' => 'Albania Lek',
    'AFN' => 'Afghanistan Afghani',
    'ARS' => 'Argentina Peso',
    'AWG' => 'Aruba Guilder',
    'AUD' => 'Australia Dollar',
    'AZN' => 'Azerbaijan New Manat',
    'BSD' => 'Bahamas Dollar',
    'BBD' => 'Barbados Dollar',
    'BDT' => 'Bangladeshi taka',
    'BYR' => 'Belarus Ruble',
    'BZD' => 'Belize Dollar',
    'BMD' => 'Bermuda Dollar',
    'BOB' => 'Bolivia Boliviano',
    'BAM' => 'Bosnia and Herzegovina Convertible Marka',
    'BWP' => 'Botswana Pula',
    'BGN' => 'Bulgaria Lev',
    'BRL' => 'Brazil Real',
    'BND' => 'Brunei Darussalam Dollar',
    'KHR' => 'Cambodia Riel',
    'CAD' => 'Canada Dollar',
    'KYD' => 'Cayman Islands Dollar',
    'CLP' => 'Chile Peso',
    'CNY' => 'China Yuan Renminbi',
    'COP' => 'Colombia Peso',
    'CRC' => 'Costa Rica Colon',
    'HRK' => 'Croatia Kuna',
    'CUP' => 'Cuba Peso',
    'CZK' => 'Czech Republic Koruna',
    'DKK' => 'Denmark Krone',
    'DOP' => 'Dominican Republic Peso',
    'XCD' => 'East Caribbean Dollar',
    'EGP' => 'Egypt Pound',
    'SVC' => 'El Salvador Colon',
    'EEK' => 'Estonia Kroon',
    'EUR' => 'Euro Member Countries',
    'FKP' => 'Falkland Islands (Malvinas) Pound',
    'FJD' => 'Fiji Dollar',
    'GHC' => 'Ghana Cedis',
    'GIP' => 'Gibraltar Pound',
    'GTQ' => 'Guatemala Quetzal',
    'GGP' => 'Guernsey Pound',
    'GYD' => 'Guyana Dollar',
    'HNL' => 'Honduras Lempira',
    'HKD' => 'Hong Kong Dollar',
    'HUF' => 'Hungary Forint',
    'ISK' => 'Iceland Krona',
    'INR' => 'India Rupee',
    'IDR' => 'Indonesia Rupiah',
    'IRR' => 'Iran Rial',
    'IMP' => 'Isle of Man Pound',
    'ILS' => 'Israel Shekel',
    'JMD' => 'Jamaica Dollar',
    'JPY' => 'Japan Yen',
    'JEP' => 'Jersey Pound',
    'KZT' => 'Kazakhstan Tenge',
    'KPW' => 'Korea (North) Won',
    'KRW' => 'Korea (South) Won',
    'KGS' => 'Kyrgyzstan Som',
    'LAK' => 'Laos Kip',
    'LVL' => 'Latvia Lat',
    'LBP' => 'Lebanon Pound',
    'LRD' => 'Liberia Dollar',
    'LTL' => 'Lithuania Litas',
    'MKD' => 'Macedonia Denar',
    'MYR' => 'Malaysia Ringgit',
    'MUR' => 'Mauritius Rupee',
    'MXN' => 'Mexico Peso',
    'MNT' => 'Mongolia Tughrik',
    'MZN' => 'Mozambique Metical',
    'NAD' => 'Namibia Dollar',
    'NPR' => 'Nepal Rupee',
    'ANG' => 'Netherlands Antilles Guilder',
    'NZD' => 'New Zealand Dollar',
    'NIO' => 'Nicaragua Cordoba',
    'NGN' => 'Nigeria Naira',
    'NOK' => 'Norway Krone',
    'OMR' => 'Oman Rial',
    'PKR' => 'Pakistan Rupee',
    'PAB' => 'Panama Balboa',
    'PYG' => 'Paraguay Guarani',
    'PEN' => 'Peru Nuevo Sol',
    'PHP' => 'Philippines Peso',
    'PLN' => 'Poland Zloty',
    'QAR' => 'Qatar Riyal',
    'RON' => 'Romania New Leu',
    'RUB' => 'Russia Ruble',
    'SHP' => 'Saint Helena Pound',
    'SAR' => 'Saudi Arabia Riyal',
    'RSD' => 'Serbia Dinar',
    'SCR' => 'Seychelles Rupee',
    'SGD' => 'Singapore Dollar',
    'SBD' => 'Solomon Islands Dollar',
    'SOS' => 'Somalia Shilling',
    'ZAR' => 'South Africa Rand',
    'LKR' => 'Sri Lanka Rupee',
    'SEK' => 'Sweden Krona',
    'CHF' => 'Switzerland Franc',
    'SRD' => 'Suriname Dollar',
    'SYP' => 'Syria Pound',
    'TWD' => 'Taiwan New Dollar',
    'THB' => 'Thailand Baht',
    'TTD' => 'Trinidad and Tobago Dollar',
    'TRY' => 'Turkey Lira',
    'TRL' => 'Turkey Lira',
    'TVD' => 'Tuvalu Dollar',
    'UAH' => 'Ukraine Hryvna',
    'GBP' => 'United Kingdom Pound',
    'USD' => 'United States Dollar',
    'UYU' => 'Uruguay Peso',
    'UZS' => 'Uzbekistan Som',
    'VEF' => 'Venezuela Bolivar',
    'VND' => 'Viet Nam Dong',
    'YER' => 'Yemen Rial',
    'ZWD' => 'Zimbabwe Dollar'
);

// List of locales
$locales_array = [
    'af_NA'       => 'Afrikaans (Namibia)',
    'af_ZA'       => 'Afrikaans (South Africa)',
    'af'          => 'Afrikaans',
    'ak_GH'       => 'Akan (Ghana)',
    'ak'          => 'Akan',
    'sq_AL'       => 'Albanian (Albania)',
    'sq'          => 'Albanian',
    'am_ET'       => 'Amharic (Ethiopia)',
    'am'          => 'Amharic',
    'ar_DZ'       => 'Arabic (Algeria)',
    'ar_BH'       => 'Arabic (Bahrain)',
    'ar_EG'       => 'Arabic (Egypt)',
    'ar_IQ'       => 'Arabic (Iraq)',
    'ar_JO'       => 'Arabic (Jordan)',
    'ar_KW'       => 'Arabic (Kuwait)',
    'ar_LB'       => 'Arabic (Lebanon)',
    'ar_LY'       => 'Arabic (Libya)',
    'ar_MA'       => 'Arabic (Morocco)',
    'ar_OM'       => 'Arabic (Oman)',
    'ar_QA'       => 'Arabic (Qatar)',
    'ar_SA'       => 'Arabic (Saudi Arabia)',
    'ar_SD'       => 'Arabic (Sudan)',
    'ar_SY'       => 'Arabic (Syria)',
    'ar_TN'       => 'Arabic (Tunisia)',
    'ar_AE'       => 'Arabic (United Arab Emirates)',
    'ar_YE'       => 'Arabic (Yemen)',
    'ar'          => 'Arabic',
    'hy_AM'       => 'Armenian (Armenia)',
    'hy'          => 'Armenian',
    'as_IN'       => 'Assamese (India)',
    'as'          => 'Assamese',
    'asa_TZ'      => 'Asu (Tanzania)',
    'asa'         => 'Asu',
    'az_Cyrl'     => 'Azerbaijani (Cyrillic)',
    'az_Cyrl_AZ'  => 'Azerbaijani (Cyrillic, Azerbaijan)',
    'az_Latn'     => 'Azerbaijani (Latin)',
    'az_Latn_AZ'  => 'Azerbaijani (Latin, Azerbaijan)',
    'az'          => 'Azerbaijani',
    'bm_ML'       => 'Bambara (Mali)',
    'bm'          => 'Bambara',
    'eu_ES'       => 'Basque (Spain)',
    'eu'          => 'Basque',
    'be_BY'       => 'Belarusian (Belarus)',
    'be'          => 'Belarusian',
    'bem_ZM'      => 'Bemba (Zambia)',
    'bem'         => 'Bemba',
    'bez_TZ'      => 'Bena (Tanzania)',
    'bez'         => 'Bena',
    'bn_BD'       => 'Bengali (Bangladesh)',
    'bn_IN'       => 'Bengali (India)',
    'bn'          => 'Bengali',
    'bs_BA'       => 'Bosnian (Bosnia and Herzegovina)',
    'bs'          => 'Bosnian',
    'bg_BG'       => 'Bulgarian (Bulgaria)',
    'bg'          => 'Bulgarian',
    'my_MM'       => 'Burmese (Myanmar [Burma])',
    'my'          => 'Burmese',
    'ca_ES'       => 'Catalan (Spain)',
    'ca'          => 'Catalan',
    'tzm_Latn'    => 'Central Morocco Tamazight (Latin)',
    'tzm_Latn_MA' => 'Central Morocco Tamazight (Latin, Morocco)',
    'tzm'         => 'Central Morocco Tamazight',
    'chr_US'      => 'Cherokee (United States)',
    'chr'         => 'Cherokee',
    'cgg_UG'      => 'Chiga (Uganda)',
    'cgg'         => 'Chiga',
    'zh_Hans'     => 'Chinese (Simplified Han)',
    'zh_Hans_CN'  => 'Chinese (Simplified Han, China)',
    'zh_Hans_HK'  => 'Chinese (Simplified Han, Hong Kong SAR China)',
    'zh_Hans_MO'  => 'Chinese (Simplified Han, Macau SAR China)',
    'zh_Hans_SG'  => 'Chinese (Simplified Han, Singapore)',
    'zh_Hant'     => 'Chinese (Traditional Han)',
    'zh_Hant_HK'  => 'Chinese (Traditional Han, Hong Kong SAR China)',
    'zh_Hant_MO'  => 'Chinese (Traditional Han, Macau SAR China)',
    'zh_Hant_TW'  => 'Chinese (Traditional Han, Taiwan)',
    'zh'          => 'Chinese',
    'kw_GB'       => 'Cornish (United Kingdom)',
    'kw'          => 'Cornish',
    'hr_HR'       => 'Croatian (Croatia)',
    'hr'          => 'Croatian',
    'cs_CZ'       => 'Czech (Czech Republic)',
    'cs'          => 'Czech',
    'da_DK'       => 'Danish (Denmark)',
    'da'          => 'Danish',
    'nl_BE'       => 'Dutch (Belgium)',
    'nl_NL'       => 'Dutch (Netherlands)',
    'nl'          => 'Dutch',
    'ebu_KE'      => 'Embu (Kenya)',
    'ebu'         => 'Embu',
    'en_AS'       => 'English (American Samoa)',
    'en_AU'       => 'English (Australia)',
    'en_BE'       => 'English (Belgium)',
    'en_BZ'       => 'English (Belize)',
    'en_BW'       => 'English (Botswana)',
    'en_CA'       => 'English (Canada)',
    'en_GU'       => 'English (Guam)',
    'en_HK'       => 'English (Hong Kong SAR China)',
    'en_IN'       => 'English (India)',
    'en_IE'       => 'English (Ireland)',
    'en_JM'       => 'English (Jamaica)',
    'en_MT'       => 'English (Malta)',
    'en_MH'       => 'English (Marshall Islands)',
    'en_MU'       => 'English (Mauritius)',
    'en_NA'       => 'English (Namibia)',
    'en_NZ'       => 'English (New Zealand)',
    'en_MP'       => 'English (Northern Mariana Islands)',
    'en_PK'       => 'English (Pakistan)',
    'en_PH'       => 'English (Philippines)',
    'en_SG'       => 'English (Singapore)',
    'en_ZA'       => 'English (South Africa)',
    'en_TT'       => 'English (Trinidad and Tobago)',
    'en_UM'       => 'English (U.S. Minor Outlying Islands)',
    'en_VI'       => 'English (U.S. Virgin Islands)',
    'en_GB'       => 'English (United Kingdom)',
    'en_US'       => 'English (United States)',
    'en_ZW'       => 'English (Zimbabwe)',
    'en'          => 'English',
    'eo'          => 'Esperanto',
    'et_EE'       => 'Estonian (Estonia)',
    'et'          => 'Estonian',
    'ee_GH'       => 'Ewe (Ghana)',
    'ee_TG'       => 'Ewe (Togo)',
    'ee'          => 'Ewe',
    'fo_FO'       => 'Faroese (Faroe Islands)',
    'fo'          => 'Faroese',
    'fil_PH'      => 'Filipino (Philippines)',
    'fil'         => 'Filipino',
    'fi_FI'       => 'Finnish (Finland)',
    'fi'          => 'Finnish',
    'fr_BE'       => 'French (Belgium)',
    'fr_BJ'       => 'French (Benin)',
    'fr_BF'       => 'French (Burkina Faso)',
    'fr_BI'       => 'French (Burundi)',
    'fr_CM'       => 'French (Cameroon)',
    'fr_CA'       => 'French (Canada)',
    'fr_CF'       => 'French (Central African Republic)',
    'fr_TD'       => 'French (Chad)',
    'fr_KM'       => 'French (Comoros)',
    'fr_CG'       => 'French (Congo - Brazzaville)',
    'fr_CD'       => 'French (Congo - Kinshasa)',
    'fr_CI'       => 'French (Côte d’Ivoire)',
    'fr_DJ'       => 'French (Djibouti)',
    'fr_GQ'       => 'French (Equatorial Guinea)',
    'fr_FR'       => 'French (France)',
    'fr_GA'       => 'French (Gabon)',
    'fr_GP'       => 'French (Guadeloupe)',
    'fr_GN'       => 'French (Guinea)',
    'fr_LU'       => 'French (Luxembourg)',
    'fr_MG'       => 'French (Madagascar)',
    'fr_ML'       => 'French (Mali)',
    'fr_MQ'       => 'French (Martinique)',
    'fr_MC'       => 'French (Monaco)',
    'fr_NE'       => 'French (Niger)',
    'fr_RW'       => 'French (Rwanda)',
    'fr_RE'       => 'French (Réunion)',
    'fr_BL'       => 'French (Saint Barthélemy)',
    'fr_MF'       => 'French (Saint Martin)',
    'fr_SN'       => 'French (Senegal)',
    'fr_CH'       => 'French (Switzerland)',
    'fr_TG'       => 'French (Togo)',
    'fr'          => 'French',
    'ff_SN'       => 'Fulah (Senegal)',
    'ff'          => 'Fulah',
    'gl_ES'       => 'Galician (Spain)',
    'gl'          => 'Galician',
    'lg_UG'       => 'Ganda (Uganda)',
    'lg'          => 'Ganda',
    'ka_GE'       => 'Georgian (Georgia)',
    'ka'          => 'Georgian',
    'de_AT'       => 'German (Austria)',
    'de_BE'       => 'German (Belgium)',
    'de_DE'       => 'German (Germany)',
    'de_LI'       => 'German (Liechtenstein)',
    'de_LU'       => 'German (Luxembourg)',
    'de_CH'       => 'German (Switzerland)',
    'de'          => 'German',
    'el_CY'       => 'Greek (Cyprus)',
    'el_GR'       => 'Greek (Greece)',
    'el'          => 'Greek',
    'gu_IN'       => 'Gujarati (India)',
    'gu'          => 'Gujarati',
    'guz_KE'      => 'Gusii (Kenya)',
    'guz'         => 'Gusii',
    'ha_Latn'     => 'Hausa (Latin)',
    'ha_Latn_GH'  => 'Hausa (Latin, Ghana)',
    'ha_Latn_NE'  => 'Hausa (Latin, Niger)',
    'ha_Latn_NG'  => 'Hausa (Latin, Nigeria)',
    'ha'          => 'Hausa',
    'haw_US'      => 'Hawaiian (United States)',
    'haw'         => 'Hawaiian',
    'he_IL'       => 'Hebrew (Israel)',
    'he'          => 'Hebrew',
    'hi_IN'       => 'Hindi (India)',
    'hi'          => 'Hindi',
    'hu_HU'       => 'Hungarian (Hungary)',
    'hu'          => 'Hungarian',
    'is_IS'       => 'Icelandic (Iceland)',
    'is'          => 'Icelandic',
    'ig_NG'       => 'Igbo (Nigeria)',
    'ig'          => 'Igbo',
    'id_ID'       => 'Indonesian (Indonesia)',
    'id'          => 'Indonesian',
    'ga_IE'       => 'Irish (Ireland)',
    'ga'          => 'Irish',
    'it_IT'       => 'Italian (Italy)',
    'it_CH'       => 'Italian (Switzerland)',
    'it'          => 'Italian',
    'ja_JP'       => 'Japanese (Japan)',
    'ja'          => 'Japanese',
    'kea_CV'      => 'Kabuverdianu (Cape Verde)',
    'kea'         => 'Kabuverdianu',
    'kab_DZ'      => 'Kabyle (Algeria)',
    'kab'         => 'Kabyle',
    'kl_GL'       => 'Kalaallisut (Greenland)',
    'kl'          => 'Kalaallisut',
    'kln_KE'      => 'Kalenjin (Kenya)',
    'kln'         => 'Kalenjin',
    'kam_KE'      => 'Kamba (Kenya)',
    'kam'         => 'Kamba',
    'kn_IN'       => 'Kannada (India)',
    'kn'          => 'Kannada',
    'kk_Cyrl'     => 'Kazakh (Cyrillic)',
    'kk_Cyrl_KZ'  => 'Kazakh (Cyrillic, Kazakhstan)',
    'kk'          => 'Kazakh',
    'km_KH'       => 'Khmer (Cambodia)',
    'km'          => 'Khmer',
    'ki_KE'       => 'Kikuyu (Kenya)',
    'ki'          => 'Kikuyu',
    'rw_RW'       => 'Kinyarwanda (Rwanda)',
    'rw'          => 'Kinyarwanda',
    'kok_IN'      => 'Konkani (India)',
    'kok'         => 'Konkani',
    'ko_KR'       => 'Korean (South Korea)',
    'ko'          => 'Korean',
    'khq_ML'      => 'Koyra Chiini (Mali)',
    'khq'         => 'Koyra Chiini',
    'ses_ML'      => 'Koyraboro Senni (Mali)',
    'ses'         => 'Koyraboro Senni',
    'lag_TZ'      => 'Langi (Tanzania)',
    'lag'         => 'Langi',
    'lv_LV'       => 'Latvian (Latvia)',
    'lv'          => 'Latvian',
    'lt_LT'       => 'Lithuanian (Lithuania)',
    'lt'          => 'Lithuanian',
    'luo_KE'      => 'Luo (Kenya)',
    'luo'         => 'Luo',
    'luy_KE'      => 'Luyia (Kenya)',
    'luy'         => 'Luyia',
    'mk_MK'       => 'Macedonian (Macedonia)',
    'mk'          => 'Macedonian',
    'jmc_TZ'      => 'Machame (Tanzania)',
    'jmc'         => 'Machame',
    'kde_TZ'      => 'Makonde (Tanzania)',
    'kde'         => 'Makonde',
    'mg_MG'       => 'Malagasy (Madagascar)',
    'mg'          => 'Malagasy',
    'ms_BN'       => 'Malay (Brunei)',
    'ms_MY'       => 'Malay (Malaysia)',
    'ms'          => 'Malay',
    'ml_IN'       => 'Malayalam (India)',
    'ml'          => 'Malayalam',
    'mt_MT'       => 'Maltese (Malta)',
    'mt'          => 'Maltese',
    'gv_GB'       => 'Manx (United Kingdom)',
    'gv'          => 'Manx',
    'mr_IN'       => 'Marathi (India)',
    'mr'          => 'Marathi',
    'mas_KE'      => 'Masai (Kenya)',
    'mas_TZ'      => 'Masai (Tanzania)',
    'mas'         => 'Masai',
    'mer_KE'      => 'Meru (Kenya)',
    'mer'         => 'Meru',
    'mfe_MU'      => 'Morisyen (Mauritius)',
    'mfe'         => 'Morisyen',
    'naq_NA'      => 'Nama (Namibia)',
    'naq'         => 'Nama',
    'ne_IN'       => 'Nepali (India)',
    'ne_NP'       => 'Nepali (Nepal)',
    'ne'          => 'Nepali',
    'nd_ZW'       => 'North Ndebele (Zimbabwe)',
    'nd'          => 'North Ndebele',
    'nb_NO'       => 'Norwegian Bokmål (Norway)',
    'nb'          => 'Norwegian Bokmål',
    'nn_NO'       => 'Norwegian Nynorsk (Norway)',
    'nn'          => 'Norwegian Nynorsk',
    'nyn_UG'      => 'Nyankole (Uganda)',
    'nyn'         => 'Nyankole',
    'or_IN'       => 'Oriya (India)',
    'or'          => 'Oriya',
    'om_ET'       => 'Oromo (Ethiopia)',
    'om_KE'       => 'Oromo (Kenya)',
    'om'          => 'Oromo',
    'ps_AF'       => 'Pashto (Afghanistan)',
    'ps'          => 'Pashto',
    'fa_AF'       => 'Persian (Afghanistan)',
    'fa_IR'       => 'Persian (Iran)',
    'fa'          => 'Persian',
    'pl_PL'       => 'Polish (Poland)',
    'pl'          => 'Polish',
    'pt_BR'       => 'Portuguese (Brazil)',
    'pt_GW'       => 'Portuguese (Guinea-Bissau)',
    'pt_MZ'       => 'Portuguese (Mozambique)',
    'pt_PT'       => 'Portuguese (Portugal)',
    'pt'          => 'Portuguese',
    'pa_Arab'     => 'Punjabi (Arabic)',
    'pa_Arab_PK'  => 'Punjabi (Arabic, Pakistan)',
    'pa_Guru'     => 'Punjabi (Gurmukhi)',
    'pa_Guru_IN'  => 'Punjabi (Gurmukhi, India)',
    'pa'          => 'Punjabi',
    'ro_MD'       => 'Romanian (Moldova)',
    'ro_RO'       => 'Romanian (Romania)',
    'ro'          => 'Romanian',
    'rm_CH'       => 'Romansh (Switzerland)',
    'rm'          => 'Romansh',
    'rof_TZ'      => 'Rombo (Tanzania)',
    'rof'         => 'Rombo',
    'ru_MD'       => 'Russian (Moldova)',
    'ru_RU'       => 'Russian (Russia)',
    'ru_UA'       => 'Russian (Ukraine)',
    'ru'          => 'Russian',
    'rwk_TZ'      => 'Rwa (Tanzania)',
    'rwk'         => 'Rwa',
    'saq_KE'      => 'Samburu (Kenya)',
    'saq'         => 'Samburu',
    'sg_CF'       => 'Sango (Central African Republic)',
    'sg'          => 'Sango',
    'seh_MZ'      => 'Sena (Mozambique)',
    'seh'         => 'Sena',
    'sr_Cyrl'     => 'Serbian (Cyrillic)',
    'sr_Cyrl_BA'  => 'Serbian (Cyrillic, Bosnia and Herzegovina)',
    'sr_Cyrl_ME'  => 'Serbian (Cyrillic, Montenegro)',
    'sr_Cyrl_RS'  => 'Serbian (Cyrillic, Serbia)',
    'sr_Latn'     => 'Serbian (Latin)',
    'sr_Latn_BA'  => 'Serbian (Latin, Bosnia and Herzegovina)',
    'sr_Latn_ME'  => 'Serbian (Latin, Montenegro)',
    'sr_Latn_RS'  => 'Serbian (Latin, Serbia)',
    'sr'          => 'Serbian',
    'sn_ZW'       => 'Shona (Zimbabwe)',
    'sn'          => 'Shona',
    'ii_CN'       => 'Sichuan Yi (China)',
    'ii'          => 'Sichuan Yi',
    'si_LK'       => 'Sinhala (Sri Lanka)',
    'si'          => 'Sinhala',
    'sk_SK'       => 'Slovak (Slovakia)',
    'sk'          => 'Slovak',
    'sl_SI'       => 'Slovenian (Slovenia)',
    'sl'          => 'Slovenian',
    'xog_UG'      => 'Soga (Uganda)',
    'xog'         => 'Soga',
    'so_DJ'       => 'Somali (Djibouti)',
    'so_ET'       => 'Somali (Ethiopia)',
    'so_KE'       => 'Somali (Kenya)',
    'so_SO'       => 'Somali (Somalia)',
    'so'          => 'Somali',
    'es_AR'       => 'Spanish (Argentina)',
    'es_BO'       => 'Spanish (Bolivia)',
    'es_CL'       => 'Spanish (Chile)',
    'es_CO'       => 'Spanish (Colombia)',
    'es_CR'       => 'Spanish (Costa Rica)',
    'es_DO'       => 'Spanish (Dominican Republic)',
    'es_EC'       => 'Spanish (Ecuador)',
    'es_SV'       => 'Spanish (El Salvador)',
    'es_GQ'       => 'Spanish (Equatorial Guinea)',
    'es_GT'       => 'Spanish (Guatemala)',
    'es_HN'       => 'Spanish (Honduras)',
    'es_419'      => 'Spanish (Latin America)',
    'es_MX'       => 'Spanish (Mexico)',
    'es_NI'       => 'Spanish (Nicaragua)',
    'es_PA'       => 'Spanish (Panama)',
    'es_PY'       => 'Spanish (Paraguay)',
    'es_PE'       => 'Spanish (Peru)',
    'es_PR'       => 'Spanish (Puerto Rico)',
    'es_ES'       => 'Spanish (Spain)',
    'es_US'       => 'Spanish (United States)',
    'es_UY'       => 'Spanish (Uruguay)',
    'es_VE'       => 'Spanish (Venezuela)',
    'es'          => 'Spanish',
    'sw_KE'       => 'Swahili (Kenya)',
    'sw_TZ'       => 'Swahili (Tanzania)',
    'sw'          => 'Swahili',
    'sv_FI'       => 'Swedish (Finland)',
    'sv_SE'       => 'Swedish (Sweden)',
    'sv'          => 'Swedish',
    'gsw_CH'      => 'Swiss German (Switzerland)',
    'gsw'         => 'Swiss German',
    'shi_Latn'    => 'Tachelhit (Latin)',
    'shi_Latn_MA' => 'Tachelhit (Latin, Morocco)',
    'shi_Tfng'    => 'Tachelhit (Tifinagh)',
    'shi_Tfng_MA' => 'Tachelhit (Tifinagh, Morocco)',
    'shi'         => 'Tachelhit',
    'dav_KE'      => 'Taita (Kenya)',
    'dav'         => 'Taita',
    'ta_IN'       => 'Tamil (India)',
    'ta_LK'       => 'Tamil (Sri Lanka)',
    'ta'          => 'Tamil',
    'te_IN'       => 'Telugu (India)',
    'te'          => 'Telugu',
    'teo_KE'      => 'Teso (Kenya)',
    'teo_UG'      => 'Teso (Uganda)',
    'teo'         => 'Teso',
    'th_TH'       => 'Thai (Thailand)',
    'th'          => 'Thai',
    'bo_CN'       => 'Tibetan (China)',
    'bo_IN'       => 'Tibetan (India)',
    'bo'          => 'Tibetan',
    'ti_ER'       => 'Tigrinya (Eritrea)',
    'ti_ET'       => 'Tigrinya (Ethiopia)',
    'ti'          => 'Tigrinya',
    'to_TO'       => 'Tonga (Tonga)',
    'to'          => 'Tonga',
    'tr_TR'       => 'Turkish (Turkey)',
    'tr'          => 'Turkish',
    'uk_UA'       => 'Ukrainian (Ukraine)',
    'uk'          => 'Ukrainian',
    'ur_IN'       => 'Urdu (India)',
    'ur_PK'       => 'Urdu (Pakistan)',
    'ur'          => 'Urdu',
    'uz_Arab'     => 'Uzbek (Arabic)',
    'uz_Arab_AF'  => 'Uzbek (Arabic, Afghanistan)',
    'uz_Cyrl'     => 'Uzbek (Cyrillic)',
    'uz_Cyrl_UZ'  => 'Uzbek (Cyrillic, Uzbekistan)',
    'uz_Latn'     => 'Uzbek (Latin)',
    'uz_Latn_UZ'  => 'Uzbek (Latin, Uzbekistan)',
    'uz'          => 'Uzbek',
    'vi_VN'       => 'Vietnamese (Vietnam)',
    'vi'          => 'Vietnamese',
    'vun_TZ'      => 'Vunjo (Tanzania)',
    'vun'         => 'Vunjo',
    'cy_GB'       => 'Welsh (United Kingdom)',
    'cy'          => 'Welsh',
    'yo_NG'       => 'Yoruba (Nigeria)',
    'yo'          => 'Yoruba',
    'zu_ZA'       => 'Zulu (South Africa)',
    'zu'          => 'Zulu',
];

$category_types_array = array(
    'Expense',
    'Income',
    'Payment Method',
    'Referral'
);

$asset_types_array = array(
    'Laptop'=>'fa-laptop',
    'Desktop'=>'fa-desktop',
    'Server'=>'fa-server',
    'Phone'=>'fa-phone',
    'Mobile Phone'=>'fa-mobile-alt',
    'Tablet'=>'fa-tablet-alt',
    'Firewall/Router'=>'fa-network-wired',
    'Switch'=>'fa-network-wired',
    'Access Point'=>'fa-wifi',
    'Printer'=>'fa-print',
    'Camera'=>'fa-video',
    'TV'=>'fa-tv',
    'Virtual Machine'=>'fa-cloud',
    'Other'=>'fa-tag'
);

$software_types_array = array(
    'SaaS',
    'Application',
    'Mobile',
    'System Software',
    'Operating System',
    'Other'
);

$license_types_array = array(
    'Device',
    'User'
);

$document_types_array = array(
    '0'=>'Document',
    '1'=>'Template',
    '2'=>'Global Template'
);

$asset_status_array = array(
    'Ready to Deploy',
    'Deployed',
    'Out for Repair',
    'Lost/Stolen',
    'Retired'
);
