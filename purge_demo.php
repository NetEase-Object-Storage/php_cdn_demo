<?php
/**
 * Created by IntelliJ IDEA.
 * User: future
 * Date: 2018/9/30
 * Time: 10:00 AM
 */


date_default_timezone_set('PRC');
header("Content-Type:text/html; charset=UTF-8");


function curl_post_https($domainName)
{
    $data = <<<EOF
{
    "file-url": [
        "http://$domainName/aa",
        "http://$domainName/bb"
    ],
    "dir-url": [
    ]
}

EOF;


    $date = gmdate('D, d M Y H:i:s') . ' GMT';
    $Authorization = get_Authorization($date, $domainName);


    $url = 'ncdn-eastchina1.126.net/domain/' . $domainName . '/purge';


    $headers = array();
    $headers[] = 'host: ncdn-eastchina1.126.net';
    $headers[] = 'Date: ' . $date;
    $headers[] = 'Accept: application/json';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: ' . $Authorization;


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $tmpInfo = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Errno' . curl_error($curl);
    }
    curl_close($curl);
    return $tmpInfo;
}


function get_Authorization($date, $domainName)
{
    // 用户ak
    $AccessKey = "";
    // 用户sk
    $AccessSecret = "";


    $Content_MD5 = "";
    $Content_Type = "application/json";
    $CanonicalizedResource = "/domain/" . $domainName . "/purge";
    $s = "POST\n" . $Content_MD5 . "\n" . $Content_Type . "\n" . $date . "\n" . $CanonicalizedResource;
    echo $s;
    $Signature = base64_encode(hash_hmac('sha256', $s, $AccessSecret, true));
    $Authorization = "NCDN " . $AccessKey . ":" . $Signature;

    return $Authorization;
}

// 用于想要purge的域名
$purge_domain = "aa.bb";
echo curl_post_https($purge_domain);

