<?php
namespace Minds\Core\Torrent;

/**
 * Torrent.
 *
 * PHP version 5.2+ (with cURL extention enabled)
 *
 * 1) Features:
 * - Decode torrent file or data from local file and distant url
 * - Build torrent from source folder/file(s) or distant url
 * - Super easy usage & syntax
 * - Silent Exception error system
 *
 * 2) Usage example
 * <code>
 * require_once 'Torrent.php';
 *
 * // get torrent infos
 * $torrent = new Torrent( './test.torrent' );
 * echo '<br>private: ', $torrent->is_private() ? 'yes' : 'no',
 * '<br>announce: ', $torrent->announce(),
 * '<br>name: ', $torrent->name(),
 * '<br>comment: ', $torrent->comment(),
 * '<br>piece_length: ', $torrent->piece_length(),
 * '<br>size: ', $torrent->size( 2 ),
 * '<br>hash info: ', $torrent->hash_info(),
 * '<br>stats: ';
 * var_dump( $torrent->scrape() );
 * echo '<br>content: ';
 * var_dump( $torrent->content() );
 * echo '<br>source: ',
 * $torrent;
 *
 * // get magnet link
 * $torrent->magnet(); // use $torrent->magnet( false ); to get non html encoded ampersand
 *
 * // create torrent
 * $torrent = new Torrent( array( 'test.mp3', 'test.jpg' ), 'http://torrent.tracker/annonce' );
 * $torrent->save('test.torrent'); // save to disk
 *
 * // modify torrent
 * $torrent->announce('http://alternate-torrent.tracker/annonce'); // add a tracker
 * $torrent->announce(false); // reset announce trackers
 * $torrent->announce(array('http://torrent.tracker/annonce', 'http://alternate-torrent.tracker/annonce')); // set tracker(s), it also works with a 'one tracker' array...
 * $torrent->announce(array(array('http://torrent.tracker/annonce', 'http://alternate-torrent.tracker/annonce'), 'http://another-torrent.tracker/annonce')); // set tiered trackers
 * $torrent->comment('hello world');
 * $torrent->name('test torrent');
 * $torrent->is_private(true);
 * $torrent->httpseeds('http://file-hosting.domain/path/'); // BitTornado implementation
 * $torrent->url_list(array('http://file-hosting.domain/path/','http://another-file-hosting.domain/path/')); //
 * GetRight implementation
 *
 * // print errors
 * if ( $errors = $torrent->errors() )
 * var_dump( $errors );
 *
 * // send to user
 * $torrent->send();
 * </code>
 *
 * @author   Adrien Gibrat <adrien.gibrat@gmail.com>
 * @tester   Jeong, Anton, dokcharlie, official testers ;) Thanks for your precious feedback
 * @copyleft 2010 - Just use it!
 *
 * @license  http://www.gnu.org/licenses/gpl.html GNU General Public License version 3
 *
 * @version  0.0.3
 */
class TorrentFile
{
    /**
     * @const float Default http timeout
     */
    const timeout = 30;

    /**
     * @var array List of error occurred
     */
    protected static $_errors = [];

    /** Read and decode torrent file/data OR build a torrent from source folder/file(s)
     * Supported signatures:
     * - Torrent(); // get an instance (useful to scrape and check errors)
     * - Torrent( string $torrent ); // analyze a torrent file
     * - Torrent( string $torrent, string $announce );
     * - Torrent( string $torrent, array $meta );
     * - Torrent( string $file_or_folder ); // create a torrent file
     * - Torrent( string $file_or_folder, string $announce_url, [int $piece_length] );
     * - Torrent( string $file_or_folder, array $meta, [int $piece_length] );
     * - Torrent( array $files_list );
     * - Torrent( array $files_list, string $announce_url, [int $piece_length] );
     * - Torrent( array $files_list, array $meta, [int $piece_length] );.
     *
     * @param string|array torrent to read or source folder/file(s) (optional, to get an instance)
     * @param string|array announce url or meta informations (optional)
     * @param int piece length (optional)
     */
    public function __construct($data = null, $meta = [], $piece_length = 256)
    {
        if (is_null($data)) {
            return false;
        }
        if ($piece_length < 32 || $piece_length > 4096) {
            return self::set_error(new Exception('Invalid piece length, must be between 32 and 4096'));
        }
        if (is_string($meta)) {
            $meta = ['announce' => $meta];
        }
        if ($this->build($data, $piece_length * 1024)) {
            $this->touch();
        } else {
            $meta = array_merge($meta, $this->decode($data));
        }
        foreach ($meta as $key => $value) {
            $this->{trim($key)} = $value;
        }
    }

    /** Convert the current Torrent instance in torrent format
     *
     * @return string encoded torrent data
     */
    public function __toString()
    {
        return $this->encode($this);
    }

    /** Return last error message
     *
     * @return string|bool last error message or false if none
     */
    public function error()
    {
        return empty(self::$_errors) ?
            false :
            self::$_errors[0]->getMessage();
    }

    /** Return Errors
     *
     * @return array|bool error list or false if none
     */
    public function errors()
    {
        return empty(self::$_errors) ?
            false :
            self::$_errors;
    }

    /**** Getters and setters ****/

    /** Getter and setter of torrent announce url / list
     * If the argument is a string, announce url is added to announce list (or set as announce if announce is not set)
     * If the argument is an array/object, set announce url (with first url) and list (if array has more than one url), tiered list supported
     * If the argument is false announce url & list are unset.
     *
     * @param null|false|string|array announce url / list, reset all if false (optional, if omitted it's a getter)
     *
     * @return string|array|null announce url / list or null if not set
     */
    public function announce($announce = null)
    {
        if (is_null($announce)) {
            return !isset($this->{'announce-list'}) ?
                isset($this->announce) ? $this->announce : null :
                $this->{'announce-list'};
        }
        $this->touch();
        if (is_string($announce) && isset($this->announce)) {
            return $this->{'announce-list'} = self::announce_list(isset($this->{'announce-list'}) ? $this->{'announce-list'} : $this->announce, $announce);
        }
        unset($this->{'announce-list'});
        if (is_array($announce) || is_object($announce)) {
            if (($this->announce = self::first_announce($announce)) && count($announce) > 1) {
                return $this->{'announce-list'} = self::announce_list($announce);
            } else {
                return $this->announce;
            }
        }
        if (!isset($this->announce) && $announce) {
            return $this->announce = (string) $announce;
        }
        unset($this->announce);
    }

    /** Getter and setter of torrent creation date
     *
     * @param null|int timestamp (optional, if omitted it's a getter)
     *
     * @return int|null timestamp or null if not set
     */
    public function creation_date($timestamp = null)
    {
        return is_null($timestamp) ?
            isset($this->{'creation date'}) ? $this->{'creation date'} : null :
            $this->touch($this->{'creation date'} = (int) $timestamp);
    }

    /** Getter and setter of torrent comment
     *
     * @param null|string comment (optional, if omitted it's a getter)
     *
     * @return string|null comment or null if not set
     */
    public function comment($comment = null)
    {
        return is_null($comment) ?
            isset($this->comment) ? $this->comment : null :
            $this->touch($this->comment = (string) $comment);
    }

    /** Getter and setter of torrent name
     *
     * @param null|string name (optional, if omitted it's a getter)
     *
     * @return string|null name or null if not set
     */
    public function name($name = null)
    {
        return is_null($name) ?
            isset($this->info['name']) ? $this->info['name'] : null :
            $this->touch($this->info['name'] = (string) $name);
    }

    /** Getter and setter of private flag
     *
     * @param null|bool is private or not (optional, if omitted it's a getter)
     *
     * @return bool private flag
     */
    public function is_private($private = null)
    {
        return is_null($private) ?
            !empty($this->info['private']) :
            $this->touch($this->info['private'] = $private ? 1 : 0);
    }

    /** Getter and setter of torrent source
     *
     * @param null|string source (optional, if omitted it's a getter)
     *
     * @return string|null source or null if not set
     */
    public function source($source = null)
    {
        return is_null($source) ?
            isset($this->info['source']) ? $this->info['source'] : null :
            $this->touch($this->info['source'] = (string) $source);
    }

    /** Getter and setter of webseed(s) url list ( GetRight implementation )
     *
     * @param null|string|array webseed or webseeds mirror list (optional, if omitted it's a getter)
     *
     * @return string|array|null webseed(s) or null if not set
     */
    public function url_list($urls = null)
    {
        return is_null($urls) ?
            isset($this->{'url-list'}) ? $this->{'url-list'} : null :
            $this->touch($this->{'url-list'} = is_string($urls) ? $urls : (array) $urls);
    }

    /** Getter and setter of httpseed(s) url list ( BitTornado implementation )
     *
     * @param null|string|array httpseed or httpseeds mirror list (optional, if omitted it's a getter)
     *
     * @return array|null httpseed(s) or null if not set
     */
    public function httpseeds($urls = null)
    {
        return is_null($urls) ?
            isset($this->httpseeds) ? $this->httpseeds : null :
            $this->touch($this->httpseeds = (array) $urls);
    }

    /**** Analyze BitTorrent ****/

    /** Get piece length
     *
     * @return int piece length or null if not set
     */
    public function piece_length()
    {
        return isset($this->info['piece length']) ?
            $this->info['piece length'] :
            null;
    }

    /** Compute hash info
     *
     * @return string hash info or null if info not set
     */
    public function hash_info()
    {
        return isset($this->info) ?
            sha1(self::encode($this->info)) :
            null;
    }

    /** List torrent content
     *
     * @param int|null size precision (optional, if omitted returns sizes in bytes)
     *
     * @return array file(s) and size(s) list, files as keys and sizes as values
     */
    public function content($precision = null)
    {
        $files = [];
        if (isset($this->info['files']) && is_array($this->info['files'])) {
            foreach ($this->info['files'] as $file) {
                $files[self::path($file['path'], $this->info['name'])] = $precision ?
                    self::format($file['length'], $precision) :
                    $file['length'];
            }
        } elseif (isset($this->info['name'])) {
            $files[$this->info['name']] = $precision ?
                self::format($this->info['length'], $precision) :
                $this->info['length'];
        }

        return $files;
    }

    /** List torrent content pieces and offset(s)
     *
     * @return array file(s) and pieces/offset(s) list, file(s) as keys and pieces/offset(s) as values
     */
    public function offset()
    {
        $files = [];
        $size  = 0;
        if (isset($this->info['files']) && is_array($this->info['files'])) {
            foreach ($this->info['files'] as $file) {
                $files[self::path($file['path'], $this->info['name'])] = [
                    'startpiece' => floor($size / $this->info['piece length']),
                    'offset'     => fmod($size, $this->info['piece length']),
                    'size'       => $size += $file['length'],
                    'endpiece'   => floor($size / $this->info['piece length']),
                ];
            }
        } elseif (isset($this->info['name'])) {
            $files[$this->info['name']] = [
                'startpiece' => 0,
                'offset'     => 0,
                'size'       => $this->info['length'],
                'endpiece'   => floor($this->info['length'] / $this->info['piece length']),
            ];
        }

        return $files;
    }

    /** Sum torrent content size
     *
     * @param int|null size precision (optional, if omitted returns size in bytes)
     *
     * @return int|string file(s) size
     */
    public function size($precision = null)
    {
        $size = 0;
        if (isset($this->info['files']) && is_array($this->info['files'])) {
            foreach ($this->info['files'] as $file) {
                $size += $file['length'];
            }
        } elseif (isset($this->info['name'])) {
            $size = $this->info['length'];
        }

        return is_null($precision) ?
            $size :
            self::format($size, $precision);
    }

    /** Request torrent statistics from scrape page USING CURL!!
     *
     * @param string|array announce or scrape page url (optional, to request an alternative tracker BUT required for static call)
     * @param string torrent hash info (optional, required ONLY for static call)
     * @param float read timeout in seconds (optional, default to self::timeout 30s)
     *
     * @return array tracker torrent statistics
     */
    /* static */
    public function scrape($announce = null, $hash_info = null, $timeout = self::timeout)
    {
        $packed_hash = urlencode(pack('H*', $hash_info ? $hash_info : $this->hash_info()));
        $handles     = $scrape     = [];
        if (!function_exists('curl_multi_init')) {
            return self::set_error(new Exception('Install CURL with "curl_multi_init" enabled'));
        }
        $curl = curl_multi_init();
        foreach ((array) ($announce ? $announce : $this->announce()) as $tier) {
            foreach ((array) $tier as $tracker) {
                $tracker = str_ireplace([
                    'udp://',
                    '/announce',
                    ':80/',
                ], [
                    'http://',
                    '/scrape',
                    '/',
                ], $tracker);
                if (isset($handles[$tracker])) {
                    continue;
                }
                $handles[$tracker] = curl_init($tracker . '?info_hash=' . $packed_hash);
                curl_setopt($handles[$tracker], CURLOPT_RETURNTRANSFER, true);
                curl_setopt($handles[$tracker], CURLOPT_TIMEOUT, $timeout);
                curl_multi_add_handle($curl, $handles[$tracker]);
            }
        }
        do {
            while (CURLM_CALL_MULTI_PERFORM == ($state = curl_multi_exec($curl, $running)));
            if (CURLM_OK != $state) {
                continue;
            }
            while ($done = curl_multi_info_read($curl)) {
                $info    = curl_getinfo($done['handle']);
                $tracker = explode('?', $info['url'], 2);
                $tracker = array_shift($tracker);
                if (empty($info['http_code'])) {
                    $scrape[$tracker] = self::set_error(new Exception('Tracker request timeout (' . $timeout . 's)'), true);
                    continue;
                } elseif (200 != $info['http_code']) {
                    $scrape[$tracker] = self::set_error(new Exception('Tracker request failed (' . $info['http_code'] . ' code)'), true);
                    continue;
                }
                $data  = curl_multi_getcontent($done['handle']);
                $stats = self::decode_data($data);
                curl_multi_remove_handle($curl, $done['handle']);
                $scrape[$tracker] = empty($stats['files']) ?
                    self::set_error(new Exception('Empty scrape data'), true) :
                    array_shift($stats['files']) + (empty($stats['flags']) ? [] : $stats['flags']);
            }
        } while ($running);
        curl_multi_close($curl);

        return $scrape;
    }

    /**** Save and Send ****/

    /** Save torrent file to disk
     *
     * @param null|string name of the file (optional)
     *
     * @return bool file has been saved or not
     */
    public function save($filename = null)
    {
        return file_put_contents(is_null($filename) ? $this->info['name'] . '.torrent' : $filename, $this->encode($this));
    }

    /** Send torrent file to client
     *
     * @param null|string name of the file (optional)
     */
    public function send($filename = null)
    {
        $data = $this->encode($this);
        header('Content-type: application/x-bittorrent');
        header('Content-Length: ' . strlen($data));
        header('Content-Disposition: attachment; filename="' . (is_null($filename) ? $this->info['name'] . '.torrent' : $filename) . '"');
        exit($data);
    }

    /** Get magnet link
     *
     * @param bool html encode ampersand, default true (optional)
     *
     * @return string magnet link
     */
    public function magnet($html = true)
    {
        $ampersand = $html ? '&amp;' : '&';

        return sprintf('magnet:?xt=urn:btih:%2$s%1$sdn=%3$s%1$sxl=%4$d%1$str=%5$s', $ampersand, $this->hash_info(), urlencode($this->name()), $this->size(), implode($ampersand . 'tr=', self::untier($this->announce())));
    }

    /**** Encode BitTorrent ****/

    /** Encode torrent data
     *
     * @param mixed data to encode
     *
     * @return string torrent encoded data
     */
    public static function encode($mixed)
    {
        switch (gettype($mixed)) {
            case 'integer':
            case 'double':
                return self::encode_integer($mixed);
            case 'object':
                $mixed = get_object_vars($mixed);
            // no break
            case 'array':
                return self::encode_array($mixed);
            default:
                return self::encode_string((string) $mixed);
        }
    }

    /** Encode torrent string
     *
     * @param string string to encode
     *
     * @return string encoded string
     */
    private static function encode_string($string)
    {
        return strlen($string) . ':' . $string;
    }

    /** Encode torrent integer
     *
     * @param int integer to encode
     *
     * @return string encoded integer
     */
    private static function encode_integer($integer)
    {
        return 'i' . $integer . 'e';
    }

    /** Encode torrent dictionary or list
     *
     * @param array array to encode
     *
     * @return string encoded dictionary or list
     */
    private static function encode_array($array)
    {
        if (self::is_list($array)) {
            $return = 'l';
            foreach ($array as $value) {
                $return .= self::encode($value);
            }
        } else {
            ksort($array, SORT_STRING);
            $return = 'd';
            foreach ($array as $key => $value) {
                $return .= self::encode(strval($key)) . self::encode($value);
            }
        }

        return $return . 'e';
    }

    /**** Decode BitTorrent ****/

    /** Decode torrent data or file
     *
     * @param string data or file path to decode
     *
     * @return array decoded torrent data
     */
    protected static function decode($string)
    {
        $stringIsFile = is_string($string) && strpos($string, '/') === 0;

        $data = ($stringIsFile && is_file($string)) || self::url_exists($string) ?
            self::file_get_contents($string) :
            $string;

        return (array) self::decode_data($data);
    }

    /** Decode torrent data
     *
     * @param string data to decode
     *
     * @return array decoded torrent data
     */
    private static function decode_data(&$data)
    {
        switch (self::char($data)) {
            case 'i':
                $data = substr($data, 1);

                return self::decode_integer($data);
            case 'l':
                $data = substr($data, 1);

                return self::decode_list($data);
            case 'd':
                $data = substr($data, 1);

                return self::decode_dictionary($data);
            default:
                return self::decode_string($data);
        }
    }

    /** Decode torrent dictionary
     *
     * @param string data to decode
     *
     * @return array decoded dictionary
     */
    private static function decode_dictionary(&$data)
    {
        $dictionary = [];
        $previous   = null;
        while ('e' != ($char = self::char($data))) {
            if (false === $char) {
                return self::set_error(new Exception('Unterminated dictionary'));
            }
            if (!ctype_digit($char)) {
                return self::set_error(new Exception('Invalid dictionary key'));
            }
            $key = self::decode_string($data);
            if (isset($dictionary[$key])) {
                return self::set_error(new Exception('Duplicate dictionary key'));
            }
            if ($key < $previous) {
                self::set_error(new Exception('Missorted dictionary key'));
            }
            $dictionary[$key] = self::decode_data($data);
            $previous         = $key;
        }
        $data = substr($data, 1);

        return $dictionary;
    }

    /** Decode torrent list
     *
     * @param string data to decode
     *
     * @return array decoded list
     */
    private static function decode_list(&$data)
    {
        $list = [];
        while ('e' != ($char = self::char($data))) {
            if (false === $char) {
                return self::set_error(new Exception('Unterminated list'));
            }
            $list[] = self::decode_data($data);
        }
        $data = substr($data, 1);

        return $list;
    }

    /** Decode torrent string
     *
     * @param string data to decode
     *
     * @return string decoded string
     */
    private static function decode_string(&$data)
    {
        if ('0' === self::char($data) && ':' != substr($data, 1, 1)) {
            self::set_error(new Exception('Invalid string length, leading zero'));
        }
        if (!$colon = @strpos($data, ':')) {
            return self::set_error(new Exception('Invalid string length, colon not found'));
        }
        $length = intval(substr($data, 0, $colon));
        if ($length + $colon + 1 > strlen($data)) {
            return self::set_error(new Exception('Invalid string, input too short for string length'));
        }
        $string = substr($data, $colon + 1, $length);
        $data   = substr($data, $colon + $length + 1);

        return $string;
    }

    /** Decode torrent integer
     *
     * @param string data to decode
     *
     * @return int decoded integer
     */
    private static function decode_integer(&$data)
    {
        $start = 0;
        $end   = strpos($data, 'e');
        if (0 === $end) {
            self::set_error(new Exception('Empty integer'));
        }
        if ('-' == self::char($data)) {
            ++$start;
        }
        if ('0' == substr($data, $start, 1) && $end > $start + 1) {
            self::set_error(new Exception('Leading zero in integer'));
        }
        if (!ctype_digit(substr($data, $start, $start ? $end - 1 : $end))) {
            self::set_error(new Exception('Non-digit characters in integer'));
        }
        $integer = substr($data, 0, $end);
        $data    = substr($data, $end + 1);

        return 0 + $integer;
    }

    /**** Internal Helpers ****/

    /** Build torrent info
     *
     * @param string|array source folder/file(s) path
     * @param int piece length
     *
     * @return array|bool torrent info or false if data isn't folder/file(s)
     */
    protected function build($data, $piece_length)
    {
        $dataIsFile = is_string($data) && strpos($data, '/') === 0;

        if (is_null($data)) {
            return false;
        } elseif (is_array($data) && self::is_list($data)) {
            return $this->info = $this->files($data, $piece_length);
        } elseif ($dataIsFile && is_dir($data)) {
            return $this->info = $this->folder($data, $piece_length);
        } elseif ((($dataIsFile && is_file($data)) || self::url_exists($data)) && !self::is_torrent($data)) {
            return $this->info = $this->file($data, $piece_length);
        } else {
            return false;
        }
    }

    /** Set torrent creator and creation date
     *
     * @param any param
     *
     * @return any param
     */
    protected function touch($void = null)
    {
        $this->{'created by'}    = 'Torrent RW PHP Class - http://github.com/adriengibrat/torrent-rw';
        $this->{'creation date'} = time();

        return $void;
    }

    /** Add an error to errors stack
     *
     * @param Exception error to add
     * @param bool return error message or not (optional, default to false)
     *
     * @return bool|string return false or error message if requested
     */
    protected static function set_error($exception, $message = false)
    {
        return (array_unshift(self::$_errors, $exception) && $message) ? $exception->getMessage() : false;
    }

    /** Build announce list
     *
     * @param string|array announce url / list
     * @param string|array announce url / list to add (optionnal)
     *
     * @return array announce list (array of arrays)
     */
    protected static function announce_list($announce, $merge = [])
    {
        return array_map(function($a) {return (array) $a;}, array_merge((array) $announce, (array) $merge));
    }

    /** Get the first announce url in a list
     *
     * @param array announce list (array of arrays if tiered trackers)
     *
     * @return string first announce url
     */
    protected static function first_announce($announce)
    {
        while (is_array($announce)) {
            $announce = reset($announce);
        }

        return $announce;
    }

    /** Helper to pack data hash
     *
     * @param string data
     *
     * @return string packed data hash
     */
    protected static function pack(&$data)
    {
        return pack('H*', sha1($data)) . ($data = null);
    }

    /** Helper to build file path
     *
     * @param array file path
     * @param string base folder
     *
     * @return string real file path
     */
    protected static function path($path, $folder)
    {
        array_unshift($path, $folder);

        return join(DIRECTORY_SEPARATOR, $path);
    }

    /** Helper to explode file path
     *
     * @param string file path
     *
     * @return array file path
     */
    protected static function path_explode($path)
    {
        return explode(DIRECTORY_SEPARATOR, $path);
    }

    /** Helper to test if an array is a list
     *
     * @param array array to test
     *
     * @return bool is the array a list or not
     */
    protected static function is_list($array)
    {
        foreach (array_keys($array) as $key) {
            if (!is_int($key)) {
                return false;
            }
        }

        return true;
    }

    /** Build pieces depending on piece length from a file handler
     *
     * @param ressource file handle
     * @param int piece length
     * @param bool is last piece
     *
     * @return string pieces
     */
    private function pieces($handle, $piece_length, $last = true)
    {
        static $piece, $length;
        if (empty($length)) {
            $length = $piece_length;
        }
        $pieces = null;
        while (!feof($handle)) {
            if (($length = strlen($piece .= fread($handle, $length))) == $piece_length) {
                $pieces .= self::pack($piece);
            } elseif (($length = $piece_length - $length) < 0) {
                return self::set_error(new Exception('Invalid piece length!'));
            }
        }
        fclose($handle);

        return $pieces . ($last && $piece ? self::pack($piece) : null);
    }

    /** Build torrent info from single file
     *
     * @param string file path
     * @param int piece length
     *
     * @return array torrent info
     */
    private function file($file, $piece_length)
    {
        if (!$handle = self::fopen($file, $size = self::filesize($file))) {
            return self::set_error(new Exception('Failed to open file: "' . $file . '"'));
        }
        if (self::is_url($file)) {
            $this->url_list($file);
        }
        $path = self::path_explode($file);

        return [
            'length'       => $size,
            'name'         => end($path),
            'piece length' => $piece_length,
            'pieces'       => $this->pieces($handle, $piece_length),
        ];
    }

    /** Build torrent info from files
     *
     * @param array file list
     * @param int piece length
     *
     * @return array torrent info
     */
    private function files($files, $piece_length)
    {
        sort($files);
        usort($files, function($a, $b) {
            return strrpos($a,DIRECTORY_SEPARATOR)-strrpos($b,DIRECTORY_SEPARATOR);
        });
        $first = current($files);
        if (!self::is_url($first)) {
            $files = array_map('realpath', $files);
        } else {
            $this->url_list(dirname($first) . DIRECTORY_SEPARATOR);
        }
        $files_path = array_map('self::path_explode', $files);
        $root       = call_user_func_array('array_intersect_assoc', $files_path);
        $pieces     = null;
        $info_files = [];
        $count      = count($files) - 1;
        foreach ($files as $i => $file) {
            if (!$handle = self::fopen($file, $filesize = self::filesize($file))) {
                self::set_error(new Exception('Failed to open file: "' . $file . '" discarded'));
                continue;
            }
            $pieces .= $this->pieces($handle, $piece_length, $count == $i);
            $info_files[] = [
                'length' => $filesize,
                'path'   => array_diff_assoc($files_path[$i], $root),
            ];
        }

        return [
            'files'        => $info_files,
            'name'         => end($root),
            'piece length' => $piece_length,
            'pieces'       => $pieces,
        ];
    }

    /** Build torrent info from folder content
     *
     * @param string folder path
     * @param int piece length
     *
     * @return array torrent info
     */
    private function folder($dir, $piece_length)
    {
        return $this->files(self::scandir($dir), $piece_length);
    }

    /** Helper to return the first char of encoded data
     *
     * @param string encoded data
     *
     * @return string|bool first char of encoded data or false if empty data
     */
    private static function char($data)
    {
        return empty($data) ?
            false :
            substr($data, 0, 1);
    }

    /**** Public Helpers ****/

    /** Helper to format size in bytes to human readable
     *
     * @param int size in bytes
     * @param int precision after coma
     *
     * @return string formated size in appropriate unit
     */
    public static function format($size, $precision = 2)
    {
        $units = [
            'octets',
            'Ko',
            'Mo',
            'Go',
            'To',
        ];
        while (($next = next($units)) && $size > 1024) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . ($next ? prev($units) : end($units));
    }

    /** Helper to return filesize (even bigger than 2Gb -linux only- and distant files size)
     *
     * @param string file path
     *
     * @return float|bool filesize or false if error
     */
    public static function filesize($file)
    {
        if (is_file($file)) {
            return (float) sprintf('%u', @filesize($file));
        } elseif ($content_length = preg_grep($pattern = '#^Content-Length:\s+(\d+)$#i', (array) @get_headers($file))) {
            return (int) preg_replace($pattern, '$1', reset($content_length));
        }
    }

    /** Helper to open file to read (even bigger than 2Gb, linux only)
     *
     * @param string file path
     * @param int|float file size (optional)
     *
     * @return resource|bool file handle or false if error
     */
    public static function fopen($file, $size = null)
    {
        if ((is_null($size) ? self::filesize($file) : $size) <= 2 * pow(1024, 3)) {
            return fopen($file, 'r');
        } elseif (PHP_OS != 'Linux') {
            return self::set_error(new Exception('File size is greater than 2GB. This is only supported under Linux'));
        } elseif (!is_readable($file)) {
            return false;
        } else {
            return popen('cat ' . escapeshellarg(realpath($file)), 'r');
        }
    }

    /** Helper to scan directories files and sub directories recursively
     *
     * @param string directory path
     *
     * @return array directory content list
     */
    public static function scandir($dir)
    {
        $paths = [];
        foreach (scandir($dir) as $item) {
            if ('.' != $item && '..' != $item) {
                if (is_dir($path = realpath($dir . DIRECTORY_SEPARATOR . $item))) {
                    $paths = array_merge(self::scandir($path), $paths);
                } else {
                    $paths[] = $path;
                }
            }
        }

        return $paths;
    }

    /** Helper to check if string is an url (http)
     *
     * @param string url to check
     *
     * @return bool is string an url
     */
    public static function is_url($url)
    {
        return preg_match('#^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$#i', $url);
    }

    /** Helper to check if url exists
     *
     * @param string url to check
     *
     * @return bool does the url exist or not
     */
    public static function url_exists($url)
    {
        return self::is_url($url) ?
            (bool) self::filesize($url) :
            false;
    }

    /** Helper to check if a file is a torrent
     *
     * @param string file location
     * @param float http timeout (optional, default to self::timeout 30s)
     *
     * @return bool is the file a torrent or not
     */
    public static function is_torrent($file, $timeout = self::timeout)
    {
        return ($start = self::file_get_contents($file, $timeout, 0, 11))
            && 'd8:announce' === $start
            || 'd10:created' === $start
            || 'd13:creatio' === $start
            || 'd13:announc' === $start
            || 'd12:_info_l' === $start
            || 'd7:comment'  === substr($start, 0, 10) // @see https://github.com/adriengibrat/torrent-rw/issues/32
            || 'd4:info'     === substr($start, 0, 7)
            || 'd9:'         === substr($start, 0, 3); // @see https://github.com/adriengibrat/torrent-rw/pull/17
    }

    /** Helper to get (distant) file content
     *
     * @param string file location
     * @param float http timeout (optional, default to self::timeout 30s)
     * @param int starting offset (optional, default to null)
     * @param int content length (optional, default to null)
     *
     * @return string|bool file content or false if error
     */
    public static function file_get_contents($file, $timeout = self::timeout, $offset = null, $length = null)
    {
        if (is_file($file) || ini_get('allow_url_fopen')) {
            $context = !is_file($file) && $timeout ?
                stream_context_create(['http' => ['timeout' => $timeout]]) :
                null;

            return !is_null($offset) ? $length ?
                @file_get_contents($file, false, $context, $offset, $length) :
                @file_get_contents($file, false, $context, $offset) :
                @file_get_contents($file, false, $context);
        } elseif (!function_exists('curl_init')) {
            return self::set_error(new Exception('Install CURL or enable "allow_url_fopen"'));
        }
        $handle = curl_init($file);
        if ($timeout) {
            curl_setopt($handle, CURLOPT_TIMEOUT, $timeout);
        }
        if ($offset || $length) {
            curl_setopt($handle, CURLOPT_RANGE, $offset . '-' . ($length ? $offset + $length - 1 : null));
        }
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($handle);
        $size    = curl_getinfo($handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($handle);

        return ($offset && $size == -1) || ($length && $length != $size) ? $length ?
            substr($content, $offset, $length) :
            substr($content, $offset) :
            $content;
    }

    /** Flatten announces list
     *
     * @param array announces list
     *
     * @return array flattened announces list
     */
    public static function untier($announces)
    {
        $list = [];
        foreach ((array) $announces as $tier) {
            is_array($tier) ?
                $list = array_merge($list, self::untier($tier)) :
                array_push($list, $tier);
        }

        return $list;
    }
}
