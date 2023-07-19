<?php

$apiUrl = 'https://file.io';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    $filename = uniqid() . '_' . $file['name'];

    $filePath = $file['tmp_name'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'file' => new CURLFile($filePath, $file['type'], $filename)
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['success']) && $result['success']) {
        $downloadLink = $result['link'];
        $expiry = $result['expiry'];

        $expiryDate = date('Y-m-d H:i:s', $expiry / 1000);

        $message = "Dosya başarıyla yüklendi.";
        $downloadLinkText = "İndirme Bağlantısı: " . $downloadLink;
        $expiryText = "Dosya Geçerlilik Süresi: " . $expiryDate;
    } else {
        $message = "Dosya yükleme başarısız.";
    }
}

include 'index.html';
?>
