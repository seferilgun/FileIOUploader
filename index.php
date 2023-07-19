<?php

// File.io API URL'si
$apiUrl = 'https://file.io';

// Dosya yükleme işlemini işleyin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Rastgele bir dosya adı oluşturun
    $filename = uniqid() . '_' . $file['name'];

    // Dosya yolunu belirleyin
    $filePath = $file['tmp_name'];

    // Dosyayı API'ye yükleyin
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'file' => new CURLFile($filePath, $file['type'], $filename)
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    // API yanıtını işleyin
    $result = json_decode($response, true);

    if (isset($result['success']) && $result['success']) {
        // Dosya yükleme başarılı
        $downloadLink = $result['link'];
        $expiry = $result['expiry'];

        // Unix zaman damgasını dönüştürün
        $expiryDate = date('Y-m-d H:i:s', $expiry / 1000);

        $message = "Dosya başarıyla yüklendi.";
        $downloadLinkText = "İndirme Bağlantısı: " . $downloadLink;
        $expiryText = "Dosya Geçerlilik Süresi: " . $expiryDate;
    } else {
        // Dosya yükleme başarısız
        $message = "Dosya yükleme başarısız.";
    }
}

include 'index.html';
?>
