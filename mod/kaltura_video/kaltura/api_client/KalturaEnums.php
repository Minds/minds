<?php
require_once("KalturaClientBase.php");

class KalturaAccessControlOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaAdminUserOrderBy
{
	const ID_ASC = "+id";
	const ID_DESC = "-id";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaApiActionPermissionItemOrderBy
{
	const ID_ASC = "+id";
	const ID_DESC = "-id";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaApiParameterPermissionItemAction
{
	const READ = "read";
	const UPDATE = "update";
	const INSERT = "insert";
	const USEAGE = "all";
}

class KalturaApiParameterPermissionItemOrderBy
{
	const ID_ASC = "+id";
	const ID_DESC = "-id";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaAssetOrderBy
{
	const SIZE_ASC = "+size";
	const SIZE_DESC = "-size";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const DELETED_AT_ASC = "+deletedAt";
	const DELETED_AT_DESC = "-deletedAt";
}

class KalturaAssetParamsOrderBy
{
}

class KalturaAssetParamsOrigin
{
	const CONVERT = 0;
	const INGEST = 1;
	const CONVERT_WHEN_MISSING = 2;
}

class KalturaAssetParamsOutputOrderBy
{
}

class KalturaAssetStatus
{
	const ERROR = -1;
	const QUEUED = 0;
	const READY = 2;
	const DELETED = 3;
	const IMPORTING = 7;
}

class KalturaAudioCodec
{
	const NONE = "";
	const MP3 = "mp3";
	const AAC = "aac";
	const VORBIS = "vorbis";
	const WMA = "wma";
	const WMAPRO = "wmapro";
	const AMRNB = "amrnb";
	const MPEG2 = "mpeg2";
	const COPY = "copy";
}

class KalturaBaseEntryOrderBy
{
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const MODERATION_COUNT_ASC = "+moderationCount";
	const MODERATION_COUNT_DESC = "-moderationCount";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const RANK_ASC = "+rank";
	const RANK_DESC = "-rank";
	const START_DATE_ASC = "+startDate";
	const START_DATE_DESC = "-startDate";
	const END_DATE_ASC = "+endDate";
	const END_DATE_DESC = "-endDate";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaBaseJobOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const PROCESSOR_EXPIRATION_ASC = "+processorExpiration";
	const PROCESSOR_EXPIRATION_DESC = "-processorExpiration";
	const EXECUTION_ATTEMPTS_ASC = "+executionAttempts";
	const EXECUTION_ATTEMPTS_DESC = "-executionAttempts";
	const LOCK_VERSION_ASC = "+lockVersion";
	const LOCK_VERSION_DESC = "-lockVersion";
}

class KalturaBaseSyndicationFeedOrderBy
{
	const PLAYLIST_ID_ASC = "+playlistId";
	const PLAYLIST_ID_DESC = "-playlistId";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const TYPE_ASC = "+type";
	const TYPE_DESC = "-type";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaBatchJobErrorTypes
{
	const APP = 0;
	const RUNTIME = 1;
	const HTTP = 2;
	const CURL = 3;
	const KALTURA_API = 4;
	const KALTURA_CLIENT = 5;
}

class KalturaBatchJobOrderBy
{
	const STATUS_ASC = "+status";
	const STATUS_DESC = "-status";
	const CHECK_AGAIN_TIMEOUT_ASC = "+checkAgainTimeout";
	const CHECK_AGAIN_TIMEOUT_DESC = "-checkAgainTimeout";
	const PROGRESS_ASC = "+progress";
	const PROGRESS_DESC = "-progress";
	const UPDATES_COUNT_ASC = "+updatesCount";
	const UPDATES_COUNT_DESC = "-updatesCount";
	const PRIORITY_ASC = "+priority";
	const PRIORITY_DESC = "-priority";
	const QUEUE_TIME_ASC = "+queueTime";
	const QUEUE_TIME_DESC = "-queueTime";
	const FINISH_TIME_ASC = "+finishTime";
	const FINISH_TIME_DESC = "-finishTime";
	const FILE_SIZE_ASC = "+fileSize";
	const FILE_SIZE_DESC = "-fileSize";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const PROCESSOR_EXPIRATION_ASC = "+processorExpiration";
	const PROCESSOR_EXPIRATION_DESC = "-processorExpiration";
	const EXECUTION_ATTEMPTS_ASC = "+executionAttempts";
	const EXECUTION_ATTEMPTS_DESC = "-executionAttempts";
	const LOCK_VERSION_ASC = "+lockVersion";
	const LOCK_VERSION_DESC = "-lockVersion";
}

class KalturaBatchJobStatus
{
	const PENDING = 0;
	const QUEUED = 1;
	const PROCESSING = 2;
	const PROCESSED = 3;
	const MOVEFILE = 4;
	const FINISHED = 5;
	const FAILED = 6;
	const ABORTED = 7;
	const ALMOST_DONE = 8;
	const RETRY = 9;
	const FATAL = 10;
	const DONT_PROCESS = 11;
}

class KalturaBatchJobType
{
	const CONVERT = "0";
	const IMPORT = "1";
	const DELETE = "2";
	const FLATTEN = "3";
	const BULKUPLOAD = "4";
	const DVDCREATOR = "5";
	const DOWNLOAD = "6";
	const OOCONVERT = "7";
	const CONVERT_PROFILE = "10";
	const POSTCONVERT = "11";
	const PULL = "12";
	const REMOTE_CONVERT = "13";
	const EXTRACT_MEDIA = "14";
	const MAIL = "15";
	const NOTIFICATION = "16";
	const CLEANUP = "17";
	const SCHEDULER_HELPER = "18";
	const BULKDOWNLOAD = "19";
	const DB_CLEANUP = "20";
	const PROVISION_PROVIDE = "21";
	const CONVERT_COLLECTION = "22";
	const STORAGE_EXPORT = "23";
	const PROVISION_DELETE = "24";
	const STORAGE_DELETE = "25";
	const EMAIL_INGESTION = "26";
	const METADATA_IMPORT = "27";
	const METADATA_TRANSFORM = "28";
	const FILESYNC_IMPORT = "29";
	const CAPTURE_THUMB = "30";
	const VIRUS_SCAN = "virusScan.VirusScan";
	const DISTRIBUTION_SUBMIT = "contentDistribution.DistributionSubmit";
	const DISTRIBUTION_UPDATE = "contentDistribution.DistributionUpdate";
	const DISTRIBUTION_DELETE = "contentDistribution.DistributionDelete";
	const DISTRIBUTION_FETCH_REPORT = "contentDistribution.DistributionFetchReport";
	const DISTRIBUTION_ENABLE = "contentDistribution.DistributionEnable";
	const DISTRIBUTION_DISABLE = "contentDistribution.DistributionDisable";
	const DISTRIBUTION_SYNC = "contentDistribution.DistributionSync";
	const DROP_FOLDER_WATCHER = "dropFolder.DropFolderWatcher";
	const DROP_FOLDER_HANDLER = "dropFolder.DropFolderHandler";
	const PARSE_CAPTION_ASSET = "captionSearch.parseCaptionAsset";
}

class KalturaBitRateMode
{
	const CBR = 1;
	const VBR = 2;
}

class KalturaBulkUploadAction
{
	const ADD = 1;
	const UPDATE = 2;
	const DELETE = 3;
	const REPLACE = 4;
	const TRANSFORM_XSLT = 5;
}

class KalturaBulkUploadResultObjectType
{
	const ENTRY = "1";
}

class KalturaBulkUploadType
{
	const CSV = "bulkUploadCsv.CSV";
	const XML = "bulkUploadXml.XML";
	const DROP_FOLDER_XML = "dropFolderXmlBulkUpload.DROP_FOLDER_XML";
}

class KalturaCategoryOrderBy
{
	const DEPTH_ASC = "+depth";
	const DEPTH_DESC = "-depth";
	const FULL_NAME_ASC = "+fullName";
	const FULL_NAME_DESC = "-fullName";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaCommercialUseType
{
	const COMMERCIAL_USE = 1;
	const NON_COMMERCIAL_USE = 0;
}

class KalturaContainerFormat
{
	const FLV = "flv";
	const MP4 = "mp4";
	const AVI = "avi";
	const MOV = "mov";
	const MP3 = "mp3";
	const _3GP = "3gp";
	const OGG = "ogg";
	const WMV = "wmv";
	const WMA = "wma";
	const ISMV = "ismv";
	const MKV = "mkv";
	const WEBM = "webm";
	const MPEG = "mpeg";
	const MPEGTS = "mpegts";
	const APPLEHTTP = "applehttp";
	const SWF = "swf";
	const PDF = "pdf";
	const JPG = "jpg";
}

class KalturaControlPanelCommandOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaControlPanelCommandStatus
{
	const PENDING = 1;
	const HANDLED = 2;
	const DONE = 3;
	const FAILED = 4;
}

class KalturaControlPanelCommandTargetType
{
	const DATA_CENTER = 1;
	const SCHEDULER = 2;
	const JOB_TYPE = 3;
	const JOB = 4;
	const BATCH = 5;
}

class KalturaControlPanelCommandType
{
	const STOP = 1;
	const START = 2;
	const CONFIG = 3;
	const KILL = 4;
}

class KalturaConversionProfileAssetParamsOrderBy
{
}

class KalturaConversionProfileOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaConversionProfileStatus
{
	const DISABLED = "1";
	const ENABLED = "2";
	const DELETED = "3";
}

class KalturaCountryRestrictionType
{
	const RESTRICT_COUNTRY_LIST = 0;
	const ALLOW_COUNTRY_LIST = 1;
}

class KalturaDataEntryOrderBy
{
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const MODERATION_COUNT_ASC = "+moderationCount";
	const MODERATION_COUNT_DESC = "-moderationCount";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const RANK_ASC = "+rank";
	const RANK_DESC = "-rank";
	const START_DATE_ASC = "+startDate";
	const START_DATE_DESC = "-startDate";
	const END_DATE_ASC = "+endDate";
	const END_DATE_DESC = "-endDate";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaDirectoryRestrictionType
{
	const DONT_DISPLAY = 0;
	const DISPLAY_WITH_LINK = 1;
}

class KalturaDurationType
{
	const NOT_AVAILABLE = "notavailable";
	const SHORT = "short";
	const MEDIUM = "medium";
	const LONG = "long";
}

class KalturaEditorType
{
	const SIMPLE = 1;
	const ADVANCED = 2;
}

class KalturaEmailIngestionProfileStatus
{
	const INACTIVE = 0;
	const ACTIVE = 1;
}

class KalturaEntryModerationStatus
{
	const PENDING_MODERATION = 1;
	const APPROVED = 2;
	const REJECTED = 3;
	const FLAGGED_FOR_REVIEW = 5;
	const AUTO_APPROVED = 6;
}

class KalturaEntryReplacementStatus
{
	const NONE = "0";
	const APPROVED_BUT_NOT_READY = "1";
	const READY_BUT_NOT_APPROVED = "2";
	const NOT_READY_AND_NOT_APPROVED = "3";
}

class KalturaEntryStatus
{
	const ERROR_IMPORTING = "-2";
	const ERROR_CONVERTING = "-1";
	const IMPORT = "0";
	const PRECONVERT = "1";
	const READY = "2";
	const DELETED = "3";
	const PENDING = "4";
	const MODERATE = "5";
	const BLOCKED = "6";
	const NO_CONTENT = "7";
	const INFECTED = "virusScan.Infected";
	const SCAN_FAILURE = "virusScan.ScanFailure";
}

class KalturaEntryType
{
	const AUTOMATIC = "-1";
	const MEDIA_CLIP = "1";
	const MIX = "2";
	const PLAYLIST = "5";
	const DATA = "6";
	const LIVE_STREAM = "7";
	const DOCUMENT = "10";
}

class KalturaFileSyncObjectType
{
	const ENTRY = "1";
	const UICONF = "2";
	const BATCHJOB = "3";
	const ASSET = "4";
	const METADATA = "5";
	const METADATA_PROFILE = "6";
	const SYNDICATION_FEED = "7";
	const CONVERSION_PROFILE = "8";
	const FLAVOR_ASSET = "4";
	const GENERIC_DISTRIBUTION_ACTION = "contentDistribution.GenericDistributionAction";
	const ENTRY_DISTRIBUTION = "contentDistribution.EntryDistribution";
	const DISTRIBUTION_PROFILE = "contentDistribution.DistributionProfile";
}

class KalturaFlavorAssetOrderBy
{
	const SIZE_ASC = "+size";
	const SIZE_DESC = "-size";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const DELETED_AT_ASC = "+deletedAt";
	const DELETED_AT_DESC = "-deletedAt";
}

class KalturaFlavorAssetStatus
{
	const CONVERTING = 1;
	const NOT_APPLICABLE = 4;
	const TEMP = 5;
	const WAIT_FOR_CONVERT = 6;
	const VALIDATING = 8;
	const ERROR = -1;
	const QUEUED = 0;
	const READY = 2;
	const DELETED = 3;
	const IMPORTING = 7;
}

class KalturaFlavorParamsOrderBy
{
}

class KalturaFlavorParamsOutputOrderBy
{
}

class KalturaFlavorReadyBehaviorType
{
	const INHERIT_FLAVOR_PARAMS = 0;
	const REQUIRED = 1;
	const OPTIONAL = 2;
}

class KalturaGender
{
	const UNKNOWN = 0;
	const MALE = 1;
	const FEMALE = 2;
}

class KalturaGenericSyndicationFeedOrderBy
{
	const PLAYLIST_ID_ASC = "+playlistId";
	const PLAYLIST_ID_DESC = "-playlistId";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const TYPE_ASC = "+type";
	const TYPE_DESC = "-type";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaGenericXsltSyndicationFeedOrderBy
{
	const PLAYLIST_ID_ASC = "+playlistId";
	const PLAYLIST_ID_DESC = "-playlistId";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const TYPE_ASC = "+type";
	const TYPE_DESC = "-type";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaGoogleSyndicationFeedAdultValues
{
	const YES = "Yes";
	const NO = "No";
}

class KalturaGoogleVideoSyndicationFeedOrderBy
{
	const PLAYLIST_ID_ASC = "+playlistId";
	const PLAYLIST_ID_DESC = "-playlistId";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const TYPE_ASC = "+type";
	const TYPE_DESC = "-type";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaITunesSyndicationFeedAdultValues
{
	const YES = "yes";
	const NO = "no";
	const CLEAN = "clean";
}

class KalturaITunesSyndicationFeedCategories
{
	const ARTS = "Arts";
	const ARTS_DESIGN = "Arts/Design";
	const ARTS_FASHION_BEAUTY = "Arts/Fashion &amp; Beauty";
	const ARTS_FOOD = "Arts/Food";
	const ARTS_LITERATURE = "Arts/Literature";
	const ARTS_PERFORMING_ARTS = "Arts/Performing Arts";
	const ARTS_VISUAL_ARTS = "Arts/Visual Arts";
	const BUSINESS = "Business";
	const BUSINESS_BUSINESS_NEWS = "Business/Business News";
	const BUSINESS_CAREERS = "Business/Careers";
	const BUSINESS_INVESTING = "Business/Investing";
	const BUSINESS_MANAGEMENT_MARKETING = "Business/Management &amp; Marketing";
	const BUSINESS_SHOPPING = "Business/Shopping";
	const COMEDY = "Comedy";
	const EDUCATION = "Education";
	const EDUCATION_TECHNOLOGY = "Education/Education Technology";
	const EDUCATION_HIGHER_EDUCATION = "Education/Higher Education";
	const EDUCATION_K_12 = "Education/K-12";
	const EDUCATION_LANGUAGE_COURSES = "Education/Language Courses";
	const EDUCATION_TRAINING = "Education/Training";
	const GAMES_HOBBIES = "Games &amp; Hobbies";
	const GAMES_HOBBIES_AUTOMOTIVE = "Games &amp; Hobbies/Automotive";
	const GAMES_HOBBIES_AVIATION = "Games &amp; Hobbies/Aviation";
	const GAMES_HOBBIES_HOBBIES = "Games &amp; Hobbies/Hobbies";
	const GAMES_HOBBIES_OTHER_GAMES = "Games &amp; Hobbies/Other Games";
	const GAMES_HOBBIES_VIDEO_GAMES = "Games &amp; Hobbies/Video Games";
	const GOVERNMENT_ORGANIZATIONS = "Government &amp; Organizations";
	const GOVERNMENT_ORGANIZATIONS_LOCAL = "Government &amp; Organizations/Local";
	const GOVERNMENT_ORGANIZATIONS_NATIONAL = "Government &amp; Organizations/National";
	const GOVERNMENT_ORGANIZATIONS_NON_PROFIT = "Government &amp; Organizations/Non-Profit";
	const GOVERNMENT_ORGANIZATIONS_REGIONAL = "Government &amp; Organizations/Regional";
	const HEALTH = "Health";
	const HEALTH_ALTERNATIVE_HEALTH = "Health/Alternative Health";
	const HEALTH_FITNESS_NUTRITION = "Health/Fitness &amp; Nutrition";
	const HEALTH_SELF_HELP = "Health/Self-Help";
	const HEALTH_SEXUALITY = "Health/Sexuality";
	const KIDS_FAMILY = "Kids &amp; Family";
	const MUSIC = "Music";
	const NEWS_POLITICS = "News &amp; Politics";
	const RELIGION_SPIRITUALITY = "Religion &amp; Spirituality";
	const RELIGION_SPIRITUALITY_BUDDHISM = "Religion &amp; Spirituality/Buddhism";
	const RELIGION_SPIRITUALITY_CHRISTIANITY = "Religion &amp; Spirituality/Christianity";
	const RELIGION_SPIRITUALITY_HINDUISM = "Religion &amp; Spirituality/Hinduism";
	const RELIGION_SPIRITUALITY_ISLAM = "Religion &amp; Spirituality/Islam";
	const RELIGION_SPIRITUALITY_JUDAISM = "Religion &amp; Spirituality/Judaism";
	const RELIGION_SPIRITUALITY_OTHER = "Religion &amp; Spirituality/Other";
	const RELIGION_SPIRITUALITY_SPIRITUALITY = "Religion &amp; Spirituality/Spirituality";
	const SCIENCE_MEDICINE = "Science &amp; Medicine";
	const SCIENCE_MEDICINE_MEDICINE = "Science &amp; Medicine/Medicine";
	const SCIENCE_MEDICINE_NATURAL_SCIENCES = "Science &amp; Medicine/Natural Sciences";
	const SCIENCE_MEDICINE_SOCIAL_SCIENCES = "Science &amp; Medicine/Social Sciences";
	const SOCIETY_CULTURE = "Society &amp; Culture";
	const SOCIETY_CULTURE_HISTORY = "Society &amp; Culture/History";
	const SOCIETY_CULTURE_PERSONAL_JOURNALS = "Society &amp; Culture/Personal Journals";
	const SOCIETY_CULTURE_PHILOSOPHY = "Society &amp; Culture/Philosophy";
	const SOCIETY_CULTURE_PLACES_TRAVEL = "Society &amp; Culture/Places &amp; Travel";
	const SPORTS_RECREATION = "Sports &amp; Recreation";
	const SPORTS_RECREATION_AMATEUR = "Sports &amp; Recreation/Amateur";
	const SPORTS_RECREATION_COLLEGE_HIGH_SCHOOL = "Sports &amp; Recreation/College &amp; High School";
	const SPORTS_RECREATION_OUTDOOR = "Sports &amp; Recreation/Outdoor";
	const SPORTS_RECREATION_PROFESSIONAL = "Sports &amp; Recreation/Professional";
	const TECHNOLOGY = "Technology";
	const TECHNOLOGY_GADGETS = "Technology/Gadgets";
	const TECHNOLOGY_TECH_NEWS = "Technology/Tech News";
	const TECHNOLOGY_PODCASTING = "Technology/Podcasting";
	const TECHNOLOGY_SOFTWARE_HOW_TO = "Technology/Software How-To";
	const TV_FILM = "TV &amp; Film";
}

class KalturaITunesSyndicationFeedOrderBy
{
	const PLAYLIST_ID_ASC = "+playlistId";
	const PLAYLIST_ID_DESC = "-playlistId";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const TYPE_ASC = "+type";
	const TYPE_DESC = "-type";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaIpAddressRestrictionType
{
	const RESTRICT_LIST = 0;
	const ALLOW_LIST = 1;
}

class KalturaLanguage
{
	const AB = "Abkhazian";
	const AA = "Afar";
	const AF = "Afrikaans";
	const SQ = "Albanian";
	const AM = "Amharic";
	const AR = "Arabic";
	const HY = "Armenian";
	const AS_ = "Assamese";
	const AY = "Aymara";
	const AZ = "Azerbaijani";
	const BA = "Bashkir";
	const EU = "Basque";
	const BN = "Bengali (Bangla)";
	const DZ = "Bhutani";
	const BH = "Bihari";
	const BI = "Bislama";
	const BR = "Breton";
	const BG = "Bulgarian";
	const MY = "Burmese";
	const BE = "Byelorussian (Belarusian)";
	const KM = "Cambodian";
	const CA = "Catalan";
	const ZH = "Chinese";
	const CO = "Corsican";
	const HR = "Croatian";
	const CS = "Czech";
	const DA = "Danish";
	const NL = "Dutch";
	const EN = "English";
	const EO = "Esperanto";
	const ET = "Estonian";
	const FO = "Faeroese";
	const FA = "Farsi";
	const FJ = "Fiji";
	const FI = "Finnish";
	const FR = "French";
	const FY = "Frisian";
	const GL = "Galician";
	const GD = "Gaelic (Scottish)";
	const GV = "Gaelic (Manx)";
	const KA = "Georgian";
	const DE = "German";
	const EL = "Greek";
	const KL = "Greenlandic";
	const GN = "Guarani";
	const GU = "Gujarati";
	const HA = "Hausa";
	const HE = "Hebrew";
	const IW = "Hebrew";
	const HI = "Hindi";
	const HU = "Hungarian";
	const IS = "Icelandic";
	const ID = "Indonesian";
	const IN = "Indonesian";
	const IA = "Interlingua";
	const IE = "Interlingue";
	const IU = "Inuktitut";
	const IK = "Inupiak";
	const GA = "Irish";
	const IT = "Italian";
	const JA = "Japanese";
	const JV = "Javanese";
	const KN = "Kannada";
	const KS = "Kashmiri";
	const KK = "Kazakh";
	const RW = "Kinyarwanda (Ruanda)";
	const KY = "Kirghiz";
	const RN = "Kirundi (Rundi)";
	const KO = "Korean";
	const KU = "Kurdish";
	const LO = "Laothian";
	const LA = "Latin";
	const LV = "Latvian (Lettish)";
	const LI = "Limburgish ( Limburger)";
	const LN = "Lingala";
	const LT = "Lithuanian";
	const MK = "Macedonian";
	const MG = "Malagasy";
	const MS = "Malay";
	const ML = "Malayalam";
	const MT = "Maltese";
	const MI = "Maori";
	const MR = "Marathi";
	const MO = "Moldavian";
	const MN = "Mongolian";
	const NA = "Nauru";
	const NE = "Nepali";
	const NO = "Norwegian";
	const OC = "Occitan";
	const OR_ = "Oriya";
	const OM = "Oromo (Afan, Galla)";
	const PS = "Pashto (Pushto)";
	const PL = "Polish";
	const PT = "Portuguese";
	const PA = "Punjabi";
	const QU = "Quechua";
	const RM = "Rhaeto-Romance";
	const RO = "Romanian";
	const RU = "Russian";
	const SM = "Samoan";
	const SG = "Sangro";
	const SA = "Sanskrit";
	const SR = "Serbian";
	const SH = "Serbo-Croatian";
	const ST = "Sesotho";
	const TN = "Setswana";
	const SN = "Shona";
	const SD = "Sindhi";
	const SI = "Sinhalese";
	const SS = "Siswati";
	const SK = "Slovak";
	const SL = "Slovenian";
	const SO = "Somali";
	const ES = "Spanish";
	const SU = "Sundanese";
	const SW = "Swahili (Kiswahili)";
	const SV = "Swedish";
	const TL = "Tagalog";
	const TG = "Tajik";
	const TA = "Tamil";
	const TT = "Tatar";
	const TE = "Telugu";
	const TH = "Thai";
	const BO = "Tibetan";
	const TI = "Tigrinya";
	const TO = "Tonga";
	const TS = "Tsonga";
	const TR = "Turkish";
	const TK = "Turkmen";
	const TW = "Twi";
	const UG = "Uighur";
	const UK = "Ukrainian";
	const UR = "Urdu";
	const UZ = "Uzbek";
	const VI = "Vietnamese";
	const VO = "Volapuk";
	const CY = "Welsh";
	const WO = "Wolof";
	const XH = "Xhosa";
	const YI = "Yiddish";
	const JI = "Yiddish";
	const YO = "Yoruba";
	const ZU = "Zulu";
}

class KalturaLanguageCode
{
	const AB = "ab";
	const AA = "aa";
	const AF = "af";
	const SQ = "sq";
	const AM = "am";
	const AR = "ar";
	const HY = "hy";
	const AS_ = "as";
	const AY = "ay";
	const AZ = "az";
	const BA = "ba";
	const EU = "eu";
	const BN = "bn";
	const DZ = "dz";
	const BH = "bh";
	const BI = "bi";
	const BR = "br";
	const BG = "bg";
	const MY = "my";
	const BE = "be";
	const KM = "km";
	const CA = "ca";
	const ZH = "zh";
	const CO = "co";
	const HR = "hr";
	const CS = "cs";
	const DA = "da";
	const NL = "nl";
	const EN = "en";
	const EO = "eo";
	const ET = "et";
	const FO = "fo";
	const FA = "fa";
	const FJ = "fj";
	const FI = "fi";
	const FR = "fr";
	const FY = "fy";
	const GL = "gl";
	const GD = "gd";
	const GV = "gv";
	const KA = "ka";
	const DE = "de";
	const EL = "el";
	const KL = "kl";
	const GN = "gn";
	const GU = "gu";
	const HA = "ha";
	const HE = "he";
	const IW = "iw";
	const HI = "hi";
	const HU = "hu";
	const IS = "is";
	const ID = "id";
	const IN = "in";
	const IA = "ia";
	const IE = "ie";
	const IU = "iu";
	const IK = "ik";
	const GA = "ga";
	const IT = "it";
	const JA = "ja";
	const JV = "jv";
	const KN = "kn";
	const KS = "ks";
	const KK = "kk";
	const RW = "rw";
	const KY = "ky";
	const RN = "rn";
	const KO = "ko";
	const KU = "ku";
	const LO = "lo";
	const LA = "la";
	const LV = "lv";
	const LI = "li";
	const LN = "ln";
	const LT = "lt";
	const MK = "mk";
	const MG = "mg";
	const MS = "ms";
	const ML = "ml";
	const MT = "mt";
	const MI = "mi";
	const MR = "mr";
	const MO = "mo";
	const MN = "mn";
	const NA = "na";
	const NE = "ne";
	const NO = "no";
	const OC = "oc";
	const OR_ = "or";
	const OM = "om";
	const PS = "ps";
	const PL = "pl";
	const PT = "pt";
	const PA = "pa";
	const QU = "qu";
	const RM = "rm";
	const RO = "ro";
	const RU = "ru";
	const SM = "sm";
	const SG = "sg";
	const SA = "sa";
	const SR = "sr";
	const SH = "sh";
	const ST = "st";
	const TN = "tn";
	const SN = "sn";
	const SD = "sd";
	const SI = "si";
	const SS = "ss";
	const SK = "sk";
	const SL = "sl";
	const SO = "so";
	const ES = "es";
	const SU = "su";
	const SW = "sw";
	const SV = "sv";
	const TL = "tl";
	const TG = "tg";
	const TA = "ta";
	const TT = "tt";
	const TE = "te";
	const TH = "th";
	const BO = "bo";
	const TI = "ti";
	const TO = "to";
	const TS = "ts";
	const TR = "tr";
	const TK = "tk";
	const TW = "tw";
	const UG = "ug";
	const UK = "uk";
	const UR = "ur";
	const UZ = "uz";
	const VI = "vi";
	const VO = "vo";
	const CY = "cy";
	const WO = "wo";
	const XH = "xh";
	const YI = "yi";
	const JI = "ji";
	const YO = "yo";
	const ZU = "zu";
}

class KalturaLicenseType
{
	const UNKNOWN = -1;
	const NONE = 0;
	const COPYRIGHTED = 1;
	const PUBLIC_DOMAIN = 2;
	const CREATIVECOMMONS_ATTRIBUTION = 3;
	const CREATIVECOMMONS_ATTRIBUTION_SHARE_ALIKE = 4;
	const CREATIVECOMMONS_ATTRIBUTION_NO_DERIVATIVES = 5;
	const CREATIVECOMMONS_ATTRIBUTION_NON_COMMERCIAL = 6;
	const CREATIVECOMMONS_ATTRIBUTION_NON_COMMERCIAL_SHARE_ALIKE = 7;
	const CREATIVECOMMONS_ATTRIBUTION_NON_COMMERCIAL_NO_DERIVATIVES = 8;
	const GFDL = 9;
	const GPL = 10;
	const AFFERO_GPL = 11;
	const LGPL = 12;
	const BSD = 13;
	const APACHE = 14;
	const MOZILLA = 15;
}

class KalturaLiveStreamAdminEntryOrderBy
{
	const MEDIA_TYPE_ASC = "+mediaType";
	const MEDIA_TYPE_DESC = "-mediaType";
	const PLAYS_ASC = "+plays";
	const PLAYS_DESC = "-plays";
	const VIEWS_ASC = "+views";
	const VIEWS_DESC = "-views";
	const DURATION_ASC = "+duration";
	const DURATION_DESC = "-duration";
	const MS_DURATION_ASC = "+msDuration";
	const MS_DURATION_DESC = "-msDuration";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const MODERATION_COUNT_ASC = "+moderationCount";
	const MODERATION_COUNT_DESC = "-moderationCount";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const RANK_ASC = "+rank";
	const RANK_DESC = "-rank";
	const START_DATE_ASC = "+startDate";
	const START_DATE_DESC = "-startDate";
	const END_DATE_ASC = "+endDate";
	const END_DATE_DESC = "-endDate";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaLiveStreamEntryOrderBy
{
	const MEDIA_TYPE_ASC = "+mediaType";
	const MEDIA_TYPE_DESC = "-mediaType";
	const PLAYS_ASC = "+plays";
	const PLAYS_DESC = "-plays";
	const VIEWS_ASC = "+views";
	const VIEWS_DESC = "-views";
	const DURATION_ASC = "+duration";
	const DURATION_DESC = "-duration";
	const MS_DURATION_ASC = "+msDuration";
	const MS_DURATION_DESC = "-msDuration";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const MODERATION_COUNT_ASC = "+moderationCount";
	const MODERATION_COUNT_DESC = "-moderationCount";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const RANK_ASC = "+rank";
	const RANK_DESC = "-rank";
	const START_DATE_ASC = "+startDate";
	const START_DATE_DESC = "-startDate";
	const END_DATE_ASC = "+endDate";
	const END_DATE_DESC = "-endDate";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaMailJobOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const PROCESSOR_EXPIRATION_ASC = "+processorExpiration";
	const PROCESSOR_EXPIRATION_DESC = "-processorExpiration";
	const EXECUTION_ATTEMPTS_ASC = "+executionAttempts";
	const EXECUTION_ATTEMPTS_DESC = "-executionAttempts";
	const LOCK_VERSION_ASC = "+lockVersion";
	const LOCK_VERSION_DESC = "-lockVersion";
}

class KalturaMediaEntryOrderBy
{
	const MEDIA_TYPE_ASC = "+mediaType";
	const MEDIA_TYPE_DESC = "-mediaType";
	const PLAYS_ASC = "+plays";
	const PLAYS_DESC = "-plays";
	const VIEWS_ASC = "+views";
	const VIEWS_DESC = "-views";
	const DURATION_ASC = "+duration";
	const DURATION_DESC = "-duration";
	const MS_DURATION_ASC = "+msDuration";
	const MS_DURATION_DESC = "-msDuration";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const MODERATION_COUNT_ASC = "+moderationCount";
	const MODERATION_COUNT_DESC = "-moderationCount";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const RANK_ASC = "+rank";
	const RANK_DESC = "-rank";
	const START_DATE_ASC = "+startDate";
	const START_DATE_DESC = "-startDate";
	const END_DATE_ASC = "+endDate";
	const END_DATE_DESC = "-endDate";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaMediaFlavorParamsOrderBy
{
}

class KalturaMediaFlavorParamsOutputOrderBy
{
}

class KalturaMediaInfoOrderBy
{
}

class KalturaMediaType
{
	const VIDEO = 1;
	const IMAGE = 2;
	const AUDIO = 5;
	const LIVE_STREAM_FLASH = 201;
	const LIVE_STREAM_WINDOWS_MEDIA = 202;
	const LIVE_STREAM_REAL_MEDIA = 203;
	const LIVE_STREAM_QUICKTIME = 204;
}

class KalturaMixEntryOrderBy
{
	const PLAYS_ASC = "+plays";
	const PLAYS_DESC = "-plays";
	const VIEWS_ASC = "+views";
	const VIEWS_DESC = "-views";
	const DURATION_ASC = "+duration";
	const DURATION_DESC = "-duration";
	const MS_DURATION_ASC = "+msDuration";
	const MS_DURATION_DESC = "-msDuration";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const MODERATION_COUNT_ASC = "+moderationCount";
	const MODERATION_COUNT_DESC = "-moderationCount";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const RANK_ASC = "+rank";
	const RANK_DESC = "-rank";
	const START_DATE_ASC = "+startDate";
	const START_DATE_DESC = "-startDate";
	const END_DATE_ASC = "+endDate";
	const END_DATE_DESC = "-endDate";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaModerationFlagStatus
{
	const PENDING = 1;
	const MODERATED = 2;
}

class KalturaModerationFlagType
{
	const SEXUAL_CONTENT = 1;
	const VIOLENT_REPULSIVE = 2;
	const HARMFUL_DANGEROUS = 3;
	const SPAM_COMMERCIALS = 4;
}

class KalturaModerationObjectType
{
	const ENTRY = 2;
	const USER = 3;
}

class KalturaNotificationOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const PROCESSOR_EXPIRATION_ASC = "+processorExpiration";
	const PROCESSOR_EXPIRATION_DESC = "-processorExpiration";
	const EXECUTION_ATTEMPTS_ASC = "+executionAttempts";
	const EXECUTION_ATTEMPTS_DESC = "-executionAttempts";
	const LOCK_VERSION_ASC = "+lockVersion";
	const LOCK_VERSION_DESC = "-lockVersion";
}

class KalturaNotificationType
{
	const ENTRY_ADD = 1;
	const ENTR_UPDATE_PERMISSIONS = 2;
	const ENTRY_DELETE = 3;
	const ENTRY_BLOCK = 4;
	const ENTRY_UPDATE = 5;
	const ENTRY_UPDATE_THUMBNAIL = 6;
	const ENTRY_UPDATE_MODERATION = 7;
	const USER_ADD = 21;
	const USER_BANNED = 26;
}

class KalturaNullableBoolean
{
	const NULL_VALUE = -1;
	const FALSE_VALUE = 0;
	const TRUE_VALUE = 1;
}

class KalturaPartnerGroupType
{
	const PUBLISHER = 1;
	const VAR_GROUP = 2;
	const GROUP = 3;
}

class KalturaPartnerOrderBy
{
	const ID_ASC = "+id";
	const ID_DESC = "-id";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const WEBSITE_ASC = "+website";
	const WEBSITE_DESC = "-website";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const ADMIN_NAME_ASC = "+adminName";
	const ADMIN_NAME_DESC = "-adminName";
	const ADMIN_EMAIL_ASC = "+adminEmail";
	const ADMIN_EMAIL_DESC = "-adminEmail";
	const STATUS_ASC = "+status";
	const STATUS_DESC = "-status";
}

class KalturaPartnerStatus
{
	const ACTIVE = 1;
	const BLOCKED = 2;
	const FULL_BLOCK = 3;
}

class KalturaPartnerType
{
	const KMC = 1;
	const WIKI = 100;
	const WORDPRESS = 101;
	const DRUPAL = 102;
	const DEKIWIKI = 103;
	const MOODLE = 104;
	const COMMUNITY_EDITION = 105;
	const JOOMLA = 106;
	const BLACKBOARD = 107;
	const SAKAI = 108;
}

class KalturaPermissionItemOrderBy
{
	const ID_ASC = "+id";
	const ID_DESC = "-id";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaPermissionItemType
{
	const API_ACTION_ITEM = "kApiActionPermissionItem";
	const API_PARAMETER_ITEM = "kApiParameterPermissionItem";
}

class KalturaPermissionOrderBy
{
	const ID_ASC = "+id";
	const ID_DESC = "-id";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaPermissionStatus
{
	const ACTIVE = 1;
	const BLOCKED = 2;
	const DELETED = 3;
}

class KalturaPermissionType
{
	const NORMAL = 1;
	const SPECIAL_FEATURE = 2;
	const PLUGIN = 3;
	const PARTNER_GROUP = 4;
}

class KalturaPlayableEntryOrderBy
{
	const PLAYS_ASC = "+plays";
	const PLAYS_DESC = "-plays";
	const VIEWS_ASC = "+views";
	const VIEWS_DESC = "-views";
	const DURATION_ASC = "+duration";
	const DURATION_DESC = "-duration";
	const MS_DURATION_ASC = "+msDuration";
	const MS_DURATION_DESC = "-msDuration";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const MODERATION_COUNT_ASC = "+moderationCount";
	const MODERATION_COUNT_DESC = "-moderationCount";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const RANK_ASC = "+rank";
	const RANK_DESC = "-rank";
	const START_DATE_ASC = "+startDate";
	const START_DATE_DESC = "-startDate";
	const END_DATE_ASC = "+endDate";
	const END_DATE_DESC = "-endDate";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaPlaylistOrderBy
{
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const MODERATION_COUNT_ASC = "+moderationCount";
	const MODERATION_COUNT_DESC = "-moderationCount";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const RANK_ASC = "+rank";
	const RANK_DESC = "-rank";
	const START_DATE_ASC = "+startDate";
	const START_DATE_DESC = "-startDate";
	const END_DATE_ASC = "+endDate";
	const END_DATE_DESC = "-endDate";
	const PARTNER_SORT_VALUE_ASC = "+partnerSortValue";
	const PARTNER_SORT_VALUE_DESC = "-partnerSortValue";
}

class KalturaPlaylistType
{
	const DYNAMIC = 10;
	const STATIC_LIST = 3;
	const EXTERNAL = 101;
}

class KalturaReportOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaReportType
{
	const TOP_CONTENT = 1;
	const CONTENT_DROPOFF = 2;
	const CONTENT_INTERACTIONS = 3;
	const MAP_OVERLAY = 4;
	const TOP_CONTRIBUTORS = 5;
	const TOP_SYNDICATION = 6;
	const CONTENT_CONTRIBUTIONS = 7;
	const WIDGETS_STATS = 8;
}

class KalturaSchemaType
{
	const SYNDICATION = "syndication";
	const SERVE_API = "cuePoint.serveAPI";
	const INGEST_API = "cuePoint.ingestAPI";
	const BULK_UPLOAD_XML = "bulkUploadXml.bulkUploadXML";
	const BULK_UPLOAD_RESULT_XML = "bulkUploadXml.bulkUploadResultXML";
	const DROP_FOLDER_XML = "dropFolderXmlBulkUpload.dropFolderXml";
}

class KalturaSearchConditionComparison
{
	const EQUEL = 1;
	const GREATER_THAN = 2;
	const GREATER_THAN_OR_EQUEL = 3;
	const LESS_THAN = 4;
	const LESS_THAN_OR_EQUEL = 5;
}

class KalturaSearchOperatorType
{
	const SEARCH_AND = 1;
	const SEARCH_OR = 2;
}

class KalturaSearchProviderType
{
	const FLICKR = 3;
	const YOUTUBE = 4;
	const MYSPACE = 7;
	const PHOTOBUCKET = 8;
	const JAMENDO = 9;
	const CCMIXTER = 10;
	const NYPL = 11;
	const CURRENT = 12;
	const MEDIA_COMMONS = 13;
	const KALTURA = 20;
	const KALTURA_USER_CLIPS = 21;
	const ARCHIVE_ORG = 22;
	const KALTURA_PARTNER = 23;
	const METACAFE = 24;
	const SEARCH_PROXY = 28;
	const PARTNER_SPECIFIC = 100;
}

class KalturaSessionType
{
	const USER = 0;
	const ADMIN = 2;
}

class KalturaSiteRestrictionType
{
	const RESTRICT_SITE_LIST = 0;
	const ALLOW_SITE_LIST = 1;
}

class KalturaSourceType
{
	const FILE = "1";
	const WEBCAM = "2";
	const URL = "5";
	const SEARCH_PROVIDER = "6";
	const AKAMAI_LIVE = "29";
	const MANUAL_LIVE_STREAM = "30";
}

class KalturaStatsEventType
{
	const WIDGET_LOADED = 1;
	const MEDIA_LOADED = 2;
	const PLAY = 3;
	const PLAY_REACHED_25 = 4;
	const PLAY_REACHED_50 = 5;
	const PLAY_REACHED_75 = 6;
	const PLAY_REACHED_100 = 7;
	const OPEN_EDIT = 8;
	const OPEN_VIRAL = 9;
	const OPEN_DOWNLOAD = 10;
	const OPEN_REPORT = 11;
	const BUFFER_START = 12;
	const BUFFER_END = 13;
	const OPEN_FULL_SCREEN = 14;
	const CLOSE_FULL_SCREEN = 15;
	const REPLAY = 16;
	const SEEK = 17;
	const OPEN_UPLOAD = 18;
	const SAVE_PUBLISH = 19;
	const CLOSE_EDITOR = 20;
	const PRE_BUMPER_PLAYED = 21;
	const POST_BUMPER_PLAYED = 22;
	const BUMPER_CLICKED = 23;
	const PREROLL_STARTED = 24;
	const MIDROLL_STARTED = 25;
	const POSTROLL_STARTED = 26;
	const OVERLAY_STARTED = 27;
	const PREROLL_CLICKED = 28;
	const MIDROLL_CLICKED = 29;
	const POSTROLL_CLICKED = 30;
	const OVERLAY_CLICKED = 31;
	const PREROLL_25 = 32;
	const PREROLL_50 = 33;
	const PREROLL_75 = 34;
	const MIDROLL_25 = 35;
	const MIDROLL_50 = 36;
	const MIDROLL_75 = 37;
	const POSTROLL_25 = 38;
	const POSTROLL_50 = 39;
	const POSTROLL_75 = 40;
}

class KalturaStatsKmcEventType
{
	const CONTENT_PAGE_VIEW = 1001;
	const CONTENT_ADD_PLAYLIST = 1010;
	const CONTENT_EDIT_PLAYLIST = 1011;
	const CONTENT_DELETE_PLAYLIST = 1012;
	const CONTENT_DELETE_ITEM = 1058;
	const CONTENT_DELETE_MIX = 1059;
	const CONTENT_EDIT_ENTRY = 1013;
	const CONTENT_CHANGE_THUMBNAIL = 1014;
	const CONTENT_ADD_TAGS = 1015;
	const CONTENT_REMOVE_TAGS = 1016;
	const CONTENT_ADD_ADMIN_TAGS = 1017;
	const CONTENT_REMOVE_ADMIN_TAGS = 1018;
	const CONTENT_DOWNLOAD = 1019;
	const CONTENT_APPROVE_MODERATION = 1020;
	const CONTENT_REJECT_MODERATION = 1021;
	const CONTENT_BULK_UPLOAD = 1022;
	const CONTENT_ADMIN_KCW_UPLOAD = 1023;
	const CONTENT_CONTENT_GO_TO_PAGE = 1057;
	const CONTENT_ENTRY_DRILLDOWN = 1088;
	const CONTENT_OPEN_PREVIEW_AND_EMBED = 1089;
	const ACCOUNT_CHANGE_PARTNER_INFO = 1030;
	const ACCOUNT_CHANGE_LOGIN_INFO = 1031;
	const ACCOUNT_CONTACT_US_USAGE = 1032;
	const ACCOUNT_UPDATE_SERVER_SETTINGS = 1033;
	const ACCOUNT_ACCOUNT_OVERVIEW = 1034;
	const ACCOUNT_ACCESS_CONTROL = 1035;
	const ACCOUNT_TRANSCODING_SETTINGS = 1036;
	const ACCOUNT_ACCOUNT_UPGRADE = 1037;
	const ACCOUNT_SAVE_SERVER_SETTINGS = 1038;
	const ACCOUNT_ACCESS_CONTROL_DELETE = 1039;
	const ACCOUNT_SAVE_TRANSCODING_SETTINGS = 1040;
	const LOGIN = 1041;
	const DASHBOARD_IMPORT_CONTENT = 1042;
	const DASHBOARD_UPDATE_CONTENT = 1043;
	const DASHBOARD_ACCOUNT_CONTACT_US = 1044;
	const DASHBOARD_VIEW_REPORTS = 1045;
	const DASHBOARD_EMBED_PLAYER = 1046;
	const DASHBOARD_EMBED_PLAYLIST = 1047;
	const DASHBOARD_CUSTOMIZE_PLAYERS = 1048;
	const APP_STUDIO_NEW_PLAYER_SINGLE_VIDEO = 1050;
	const APP_STUDIO_NEW_PLAYER_PLAYLIST = 1051;
	const APP_STUDIO_NEW_PLAYER_MULTI_TAB_PLAYLIST = 1052;
	const APP_STUDIO_EDIT_PLAYER_SINGLE_VIDEO = 1053;
	const APP_STUDIO_EDIT_PLAYER_PLAYLIST = 1054;
	const APP_STUDIO_EDIT_PLAYER_MULTI_TAB_PLAYLIST = 1055;
	const APP_STUDIO_DUPLICATE_PLAYER = 1056;
	const REPORTS_AND_ANALYTICS_BANDWIDTH_USAGE_TAB = 1070;
	const REPORTS_AND_ANALYTICS_CONTENT_REPORTS_TAB = 1071;
	const REPORTS_AND_ANALYTICS_USERS_AND_COMMUNITY_REPORTS_TAB = 1072;
	const REPORTS_AND_ANALYTICS_TOP_CONTRIBUTORS = 1073;
	const REPORTS_AND_ANALYTICS_MAP_OVERLAYS = 1074;
	const REPORTS_AND_ANALYTICS_TOP_SYNDICATIONS = 1075;
	const REPORTS_AND_ANALYTICS_TOP_CONTENT = 1076;
	const REPORTS_AND_ANALYTICS_CONTENT_DROPOFF = 1077;
	const REPORTS_AND_ANALYTICS_CONTENT_INTERACTIONS = 1078;
	const REPORTS_AND_ANALYTICS_CONTENT_CONTRIBUTIONS = 1079;
	const REPORTS_AND_ANALYTICS_VIDEO_DRILL_DOWN = 1080;
	const REPORTS_AND_ANALYTICS_CONTENT_DRILL_DOWN_INTERACTION = 1081;
	const REPORTS_AND_ANALYTICS_CONTENT_CONTRIBUTIONS_DRILLDOWN = 1082;
	const REPORTS_AND_ANALYTICS_VIDEO_DRILL_DOWN_DROPOFF = 1083;
	const REPORTS_AND_ANALYTICS_MAP_OVERLAYS_DRILLDOWN = 1084;
	const REPORTS_AND_ANALYTICS_TOP_SYNDICATIONS_DRILL_DOWN = 1085;
	const REPORTS_AND_ANALYTICS_BANDWIDTH_USAGE_VIEW_MONTHLY = 1086;
	const REPORTS_AND_ANALYTICS_BANDWIDTH_USAGE_VIEW_YEARLY = 1087;
}

class KalturaStorageProfileDeliveryStatus
{
	const ACTIVE = 1;
	const BLOCKED = 2;
}

class KalturaStorageProfileOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaStorageProfileProtocol
{
	const KALTURA_DC = 0;
	const FTP = 1;
	const SCP = 2;
	const SFTP = 3;
	const S3 = 6;
}

class KalturaStorageProfileStatus
{
	const DISABLED = 1;
	const AUTOMATIC = 2;
	const MANUAL = 3;
}

class KalturaStorageServePriority
{
	const KALTURA_ONLY = 1;
	const KALTURA_FIRST = 2;
	const EXTERNAL_FIRST = 3;
	const EXTERNAL_ONLY = 4;
}

class KalturaSyndicationFeedStatus
{
	const DELETED = -1;
	const ACTIVE = 1;
}

class KalturaSyndicationFeedType
{
	const GOOGLE_VIDEO = 1;
	const YAHOO = 2;
	const ITUNES = 3;
	const TUBE_MOGUL = 4;
	const KALTURA = 5;
	const KALTURA_XSLT = 6;
}

class KalturaThumbAssetOrderBy
{
	const SIZE_ASC = "+size";
	const SIZE_DESC = "-size";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
	const DELETED_AT_ASC = "+deletedAt";
	const DELETED_AT_DESC = "-deletedAt";
}

class KalturaThumbAssetStatus
{
	const ERROR = -1;
	const QUEUED = 0;
	const READY = 2;
	const DELETED = 3;
	const IMPORTING = 7;
}

class KalturaThumbCropType
{
	const RESIZE = 1;
	const RESIZE_WITH_PADDING = 2;
	const CROP = 3;
	const CROP_FROM_TOP = 4;
}

class KalturaThumbParamsOrderBy
{
}

class KalturaThumbParamsOutputOrderBy
{
}

class KalturaTubeMogulSyndicationFeedCategories
{
	const ARTS_AND_ANIMATION = "Arts &amp; Animation";
	const COMEDY = "Comedy";
	const ENTERTAINMENT = "Entertainment";
	const MUSIC = "Music";
	const NEWS_AND_BLOGS = "News &amp; Blogs";
	const SCIENCE_AND_TECHNOLOGY = "Science &amp; Technology";
	const SPORTS = "Sports";
	const TRAVEL_AND_PLACES = "Travel &amp; Places";
	const VIDEO_GAMES = "Video Games";
	const ANIMALS_AND_PETS = "Animals &amp; Pets";
	const AUTOS = "Autos";
	const VLOGS_PEOPLE = "Vlogs &amp; People";
	const HOW_TO_INSTRUCTIONAL_DIY = "How To/Instructional/DIY";
	const COMMERCIALS_PROMOTIONAL = "Commercials/Promotional";
	const FAMILY_AND_KIDS = "Family &amp; Kids";
}

class KalturaTubeMogulSyndicationFeedOrderBy
{
	const PLAYLIST_ID_ASC = "+playlistId";
	const PLAYLIST_ID_DESC = "-playlistId";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const TYPE_ASC = "+type";
	const TYPE_DESC = "-type";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaUiConfCreationMode
{
	const WIZARD = 2;
	const ADVANCED = 3;
}

class KalturaUiConfObjType
{
	const PLAYER = 1;
	const CONTRIBUTION_WIZARD = 2;
	const SIMPLE_EDITOR = 3;
	const ADVANCED_EDITOR = 4;
	const PLAYLIST = 5;
	const APP_STUDIO = 6;
	const KRECORD = 7;
	const PLAYER_V3 = 8;
	const KMC_ACCOUNT = 9;
	const KMC_ANALYTICS = 10;
	const KMC_CONTENT = 11;
	const KMC_DASHBOARD = 12;
	const KMC_LOGIN = 13;
	const PLAYER_SL = 14;
	const CLIENTSIDE_ENCODER = 15;
	const KMC_GENERAL = 16;
	const KMC_ROLES_AND_PERMISSIONS = 17;
	const CLIPPER = 18;
}

class KalturaUiConfOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaUploadErrorCode
{
	const NO_ERROR = 0;
	const GENERAL_ERROR = 1;
	const PARTIAL_UPLOAD = 2;
}

class KalturaUploadTokenOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaUploadTokenStatus
{
	const PENDING = 0;
	const PARTIAL_UPLOAD = 1;
	const FULL_UPLOAD = 2;
	const CLOSED = 3;
	const TIMED_OUT = 4;
	const DELETED = 5;
}

class KalturaUserAgentRestrictionType
{
	const RESTRICT_LIST = 0;
	const ALLOW_LIST = 1;
}

class KalturaUserLoginDataOrderBy
{
}

class KalturaUserOrderBy
{
	const ID_ASC = "+id";
	const ID_DESC = "-id";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaUserRoleOrderBy
{
	const ID_ASC = "+id";
	const ID_DESC = "-id";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaUserRoleStatus
{
	const ACTIVE = 1;
	const BLOCKED = 2;
	const DELETED = 3;
}

class KalturaUserStatus
{
	const BLOCKED = 0;
	const ACTIVE = 1;
	const DELETED = 2;
}

class KalturaVideoCodec
{
	const NONE = "";
	const VP6 = "vp6";
	const H263 = "h263";
	const H264 = "h264";
	const H264B = "h264b";
	const H264M = "h264m";
	const H264H = "h264h";
	const FLV = "flv";
	const MPEG4 = "mpeg4";
	const THEORA = "theora";
	const WMV2 = "wmv2";
	const WMV3 = "wmv3";
	const WVC1A = "wvc1a";
	const VP8 = "vp8";
	const MPEG2 = "mpeg2";
	const COPY = "copy";
}

class KalturaWidgetOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

class KalturaWidgetSecurityType
{
	const NONE = 1;
	const TIMEHASH = 2;
}

class KalturaYahooSyndicationFeedAdultValues
{
	const ADULT = "adult";
	const NON_ADULT = "nonadult";
}

class KalturaYahooSyndicationFeedCategories
{
	const ACTION = "Action";
	const ART_AND_ANIMATION = "Art &amp; Animation";
	const ENTERTAINMENT_AND_TV = "Entertainment &amp; TV";
	const FOOD = "Food";
	const GAMES = "Games";
	const HOW_TO = "How-To";
	const MUSIC = "Music";
	const PEOPLE_AND_VLOGS = "People &amp; Vlogs";
	const SCIENCE_AND_ENVIRONMENT = "Science &amp; Environment";
	const TRANSPORTATION = "Transportation";
	const ANIMALS = "Animals";
	const COMMERCIALS = "Commercials";
	const FAMILY = "Family";
	const FUNNY_VIDEOS = "Funny Videos";
	const HEALTH_AND_BEAUTY = "Health &amp; Beauty";
	const MOVIES_AND_SHORTS = "Movies &amp; Shorts";
	const NEWS_AND_POLITICS = "News &amp; Politics";
	const PRODUCTS_AND_TECH = "Products &amp; Tech.";
	const SPORTS = "Sports";
	const TRAVEL = "Travel";
}

class KalturaYahooSyndicationFeedOrderBy
{
	const PLAYLIST_ID_ASC = "+playlistId";
	const PLAYLIST_ID_DESC = "-playlistId";
	const NAME_ASC = "+name";
	const NAME_DESC = "-name";
	const TYPE_ASC = "+type";
	const TYPE_DESC = "-type";
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
}

