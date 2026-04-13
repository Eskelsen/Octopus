<?php

function vd($in){
	echo '<pre>';
	if (func_num_args()==1) {
		var_dump($in);
		echo '</pre>';
		return;
	}
	foreach (func_get_args() as $in) {
		var_dump($in);
	}
    echo '</pre>';
}

function cli($msg, $savelog = false){
    if (PHP_SAPI=='cli') {
        echo $msg . PHP_EOL;
        if ($savelog) {
            error_log($msg);
        }
    }
}

function mrk(){
	$_ENV['mrk'] = empty($_ENV['mrk']) ? 1 : ($_ENV['mrk'] + 1);
	echo PHP_EOL . $_ENV['mrk'] . PHP_EOL;
}

function redirect($in = '/'){
	header('Location: /' . ltrim($in, '/'));
	exit;
}

function refresh($in = '/', $time = 3){
	header("Refresh: $time; /" . ltrim($in, '/'));
}

function img($in){
	return url($in) . '?t=' . time();
}

function url($path = ''){
	return rtrim(SITE,'/') . '/' . ltrim($path,'/');
}

function url_query($path, $add_query){
	$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '';
	parse_str($query, $old_data);
	$data = array_merge($old_data, $add_query);
	return url($path) . '?' . http_build_query($data);
}

function rel($path){
    $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $levels = $uri === '' ? 0 : substr_count($uri, '/') + 1;
    return str_repeat('../', $levels) . ltrim($path, '/');
}

function complete_request(){
    return urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

function dinamicUrl($path = ''){
    return $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/' . ltrim($path,'/');
}

function ip(): string {
    foreach (['HTTP_CF_CONNECTING_IP','HTTP_X_REAL_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR'] as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = $_SERVER[$k];
            if ($k === 'HTTP_X_FORWARDED_FOR' && str_contains($ip, ',')) {
                $ip = trim(explode(',', $ip)[0]);
            }
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return '0.0.0.0';
}

function get($in){
	$out = $_GET[$in] ?? null;
	return filter($out);
}

function post($in){
	$out = $_POST[$in] ?? null;
	return filter($out);
}

function session($key, $value = false){
	if ($value) {
		$_SESSION[$key] = filter($value);
	}
	return $_SESSION[$key] ?? null;
}

function filter($in){
	$in = is_string($in) ? trim($in) : $in;
	return $in ? htmlspecialchars($in, ENT_QUOTES, 'UTF-8') : $in;
}

function slugify(string $text, string $sep = '-', ?int $limit = null): string{
    $text = trim($text);
    if (function_exists('transliterator_transliterate')) {
        $text = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; Lower()',
            $text
        );
    } else {
        $text = strtolower(html_entity_decode(
            preg_replace(
                '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i',
                '$1',
                htmlentities($text, ENT_QUOTES, 'UTF-8')
            ),
            ENT_QUOTES,
            'UTF-8'
        ));
    }
    $sepRegex = preg_quote($sep, '~');
    $text = preg_replace('~[^a-z0-9]+~i', $sep, $text);
    $text = preg_replace("~{$sepRegex}{2,}~", $sep, $text);
    $text = trim($text, $sep);
    if ($limit !== null) {
        $text = substr($text, 0, $limit);
        $text = rtrim($text, $sep);
    }
    return strtolower($text);
}

function utf8_filter($in){
    $regex = '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u';
    return preg_replace($regex,' ', $in);
}

function distance($in, $list){
    foreach ($list as $line) {
        $n[$line] = levenshtein($in, $line);
    }
    return $n ?? [];
}

function relevant_terms($terms) {
    $terms = array_filter(array_map(function($a) { 
        return (strlen($a)>3) ? $a : null;
    },$terms));
    return $terms;
}

function string_filter($in){
    $in = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $in); # Emoticons
    $in = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $in); # Symbols & Pictographs
    $in = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $in); # Transport & Map Symbols
    $in = preg_replace('/[\x{1F700}-\x{1F77F}]/u', '', $in); # Alphabetic Presentation Forms
    $in = preg_replace('/[\x{1F780}-\x{1F7FF}]/u', '', $in); # Geometric Shapes Extended
    return $in;
}

function format_name($name){
    $name = preg_replace('/[^\p{L}\p{N}\s\'-]/u', '', $name);
    $name = mb_strtolower($name);
    $prepositions = ['de', 'da', 'do', 'dos', 'das', 'e', 'del', 'la', 'las', 'los', 'di', 'du', 'der', 'den', 'des', 'von', 'van', 'of', 'af'];
    $words = explode(' ', $name);
    $capitalized = array_map(function($word) use ($prepositions) {
        return in_array($word, $prepositions) ? $word : cap_name($word);
    }, $words);
    $name = implode(' ', $capitalized);
    return preg_replace_callback('/(?:^|[-\'])(\p{L})/u', function ($matches) {
        return mb_strtoupper($matches[0]);
    }, $name);
}

function cap_name($name){
    return mb_strtoupper(mb_substr($name, 0, 1, 'utf-8'), 'utf-8') . mb_substr($name, 1, null, 'utf-8');
}

function verify_size($in,$min_size){
    return is_string($in) ? mb_strlen($in) >= $min_size : false;
}

# Refresh Control
function rc_url(){
    return '?rc=' . IDEMPOTENCY;
}

function rc_form(){
    return  '<input type="hidden" name="rc" value="' . IDEMPOTENCY . '">' . PHP_EOL;
}

function rc_get(){
    return App\Core\Access::request_get();
}

function rc_set(){
    App\Core\Access::request_set() ;
}

# Time Control
function tc_set($time){
    $_SESSION['tc'] = time() + $time;
}

function tc_get(){
    if (empty($_SESSION['tc'])) {
        return false;
    }
    if ($_SESSION['tc']<=time()) {
        unset($_SESSION['tc']);
        return false;
    }
    return true;
}

function csrf(){
    return  '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">' . PHP_EOL;
}

function csrf_check(){
    return hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '');
}

function response($msgdata, $code = 200){
	$response_key = ($code<400) ? 'response' : 'error';
	$response[$response_key] = $msgdata;
	http($code);
	exit(json_pretty($response));
}

function http($in){
		$n[100] = '100 Continue';
		$n[101] = '101 Switching Protocols';
		$n[102] = '102 Processing';

		$n[200] = '200 OK';
		$n[201] = '201 Created';
		$n[202] = '202 Accepted';
		$n[203] = '203 Non-Authoritative Information';
		$n[204] = '204 No Content';
		$n[205] = '205 Reset Content';
		$n[206] = '206 Partial Content';
		$n[207] = '207 Multi-Status';
		$n[208] = '208 Already Reported';
		$n[226] = '226 IM Used';

		$n[300] = '300 Multiple Choices';
		$n[301] = '301 Moved Permanently';
		$n[302] = '302 Found';
		$n[303] = '303 See Other';
		$n[304] = '304 Not Modified';
		$n[307] = '307 Temporary Redirect';
		$n[308] = '308 Permanent Redirect';

		$n[400] = '400 Bad Request';
		$n[401] = '401 Unauthorized';
		$n[402] = '402 Payment Required';
		$n[403] = '403 Forbidden';
		$n[404] = '404 Not Found';
		$n[405] = '405 Method Not Allowed';
		$n[406] = '406 Not Acceptable';
		$n[407] = '407 Proxy Authentication Required';
		$n[408] = '408 Request Timeout';
		$n[409] = '409 Conflict';
		$n[410] = '410 Gone';
		$n[411] = '411 Length Required';
		$n[412] = '412 Precondition Failed';
		$n[413] = '413 Payload Too Large';
		$n[414] = '414 URI Too Long';
		$n[415] = '415 Unsupported Media Type';
		$n[416] = '416 Range Not Satisfiable';
		$n[417] = '417 Expectation Failed';
		$n[418] = "418 I'm a teapot";
		$n[421] = '421 Misdirected Request';
		$n[422] = '422 Unprocessable Entity';
		$n[423] = '423 Locked';
		$n[424] = '424 Failed Dependency';
		$n[425] = '425 Too Early';
		$n[426] = '426 Upgrade Required';
		$n[428] = '428 Precondition Required';
		$n[429] = '429 Too Many Requests';
		$n[431] = '431 Request Header Fields Too Large';
		$n[451] = '451 Unavailable For Legal Reasons';

		$n[500] = '500 Internal Server Error';
		$n[501] = '501 Not Implemented';
		$n[502] = '502 Bad Gateway';
		$n[503] = '503 Service Unavailable';
		$n[504] = '504 Gateway Timeout';
		$n[505] = '505 HTTP Version Not Supported';
		$n[506] = '506 Variant Also Negotiates';
		$n[507] = '507 Insufficient Storage';
		$n[508] = '508 Loop Detected';
		$n[510] = '510 Not Extended';
		$n[511] = '511 Network Authentication Required';

		$code = $n[$in] ?? $n[500];

        header("HTTP/1.0 $code");
}

function good_status($in){
	$out = filter_var($in, FILTER_VALIDATE_URL) ? get_headers($in) : 'This is not a valid URL.';
	if (!empty($out[0]) AND (strpos($out[0],' 20')!==false OR strpos($out[0],' 30')!==false)) {
		return true;
	}
	echo is_array($out) ? $out[0] : $out;
	return false;
}

function get_http_code($in){
    $out = get_headers($in);
    return substr($out[0], 9, 3);
}

function request($url, $data = '', $method = 'GET', $type = true){
	
    $curl = curl_init();
	$data = is_string($data) ? $data : json_encode($data);
	
	$application = ($type) ? 'application/json' : 'application/x-www-form-urlencoded; charset=utf-8';
	
	$headers = [
		'Content-Type: ' 		 . $application,
		'Content-Length: ' 		 . strlen($data)
	];
	
    curl_setopt_array($curl, [
        CURLOPT_URL             => $url,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_ENCODING        => '',
        CURLOPT_MAXREDIRS       => 10,
        CURLOPT_TIMEOUT         => 30,
        CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST   => $method,
        CURLOPT_POSTFIELDS      => $data,
        CURLOPT_HTTPHEADER      => $headers
    ]);
	
    $response = curl_exec($curl);

    $e = curl_error($curl);
	
    unset($curl);
	
	if ($e) {
        error_log('cURL: ' . $e);
        return false;
	}
	
    return $response;
}

function json_pretty($in){
	return json_encode($in, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
}

function json_read($file){
	$ctn = is_file($file) ? file_get_contents($file) : false;
	return ($ctn) ? json_decode($ctn, 1) : false;
}

function json_write($file, $data){
	$mid = is_string($data) ? json_decode($data, 1) : $data;
	$ctn = json_pretty($mid);
	return ($ctn) ? file_put_contents($file, $ctn) : false;
}

function is_valid_json($in) {
    json_decode($in);
    return (json_last_error() == JSON_ERROR_NONE);
}

function load_env(string $path, bool $override = false): void {
    if (!is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);

        $key = trim($key);
        $value = trim($value);

        $rad = (str_starts_with($value, '"') && str_ends_with($value, '"'));
        $ras =  (str_starts_with($value, "'") && str_ends_with($value, "'"));
        
        $value = ($rad || $ras) ? substr($value, 1, -1) : $value;

        if (!$override && (isset($_ENV[$key]) || getenv($key) !== false)) {
            continue;
        }

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv("$key=$value");
    }
}

function env(string $key, $default = null){
    return $_ENV[$key] ?? getenv($key) ?? $default;
}
