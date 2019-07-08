<?php

if (! function_exists('removeSpace')) {
    function removeSpace($string = '')
    {
        $string = preg_replace('/\s+/', '', $string);
        return $string;
    }
}

if (! function_exists('random')) {
    function random($length = 16)
    {
        if (! function_exists('openssl_random_pseudo_bytes')) {
            throw new RuntimeException('OpenSSL extension is required.');
        }

        $bytes = openssl_random_pseudo_bytes($length * 2);

        if ($bytes === false) {
            throw new RuntimeException('Unable to generate random string.');
        }

        return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    }
}

if (! function_exists('center')) {
    function center($text)
    {
        return "<div style='text-align:center'>$text</div>";
    }
}

if (! function_exists('gender')) {
    function gender($text)
    {
        if (empty($text)) {
            return '-';
        }
        return ($text == 'male') ? 'Laki-laki' : 'Perempuan';
    }
}

if (! function_exists('dateformat')) {
    function dateformat($date, $format = 'd-F-Y')
    {
        if (empty($date)) {
            return '';
        }
        return date($format, strtotime($date));
    }
}

if (! function_exists('intendedroute')) {
    function intendedroute()
    {
        session(['url.intended' => \Route::currentRouteName()]);
    }
}

if (! function_exists('addzeroprefix')) {
    function addzeroprefix($zero)
    {
        $zero = strval($zero);
        if ($zero[0] == '8') {
            $zero = '0'.$zero;
        }

        return $zero;
    }
}

if (! function_exists('convertBatas')) {
    function convertBatas($tglmulai, $tglakhir)
    {
        $start = date('d', strtotime($tglmulai));
        $end   = date('d F Y', strtotime($tglakhir));

        return $start . ' - ' . $end;
    }
}

if (! function_exists('convert_underscore')) {
    function convert_underscore($string)
    {
        return ucwords(str_replace("_", " ", $string));
    }
}

if (! function_exists('rupiah')) {
    function rupiah($angka)
    {
        $hasil_rupiah = number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }
}

if (! function_exists('get_web_page')) {
    function get_web_page($url)
    {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
}

if (! function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'tahun',
            'm' => 'bulan',
            'w' => 'minggu',
            'd' => 'hari',
            'h' => 'jam',
            'i' => 'menit',
            's' => 'detik',
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }
        return $string ? implode(', ', $string) . ' lalu' : 'baru saja';
    }
}


if (! function_exists('getUser')) {
    function getUser($key = array())
    {
        return \DB::table('users')->where($key)->first();
    }
}
