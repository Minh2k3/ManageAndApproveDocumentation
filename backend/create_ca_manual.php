<?php
// create_ca_manual.php - đặt ở root project
require_once 'vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\File\X509;

try {
    echo "Đang tạo chứng chỉ CA thủ công...\n";

    // Tạo cặp khóa RSA
    $caPrivate = RSA::createKey(2048);
    echo "Đã tạo khóa riêng\n";

    $caPublic = $caPrivate->getPublicKey();
    echo "Đã lấy khóa công khai\n";

    // Tạo đối tượng X509
    $x509 = new X509();
    $x509->setPrivateKey($caPrivate);
    $x509->setPublicKey($caPublic);
    echo "Đã khởi tạo đối tượng X509\n";

    // Thiết lập thông tin subject
    $x509->setDN([
        'id-at-commonName' => 'TLU Test CA',
        'id-at-organizationName' => 'Dai hoc Thuy loi',
        'id-at-countryName' => 'VN'
    ]);
    echo "Đã thiết lập subject\n";

    // Thiết lập thời hạn hiệu lực và serial number
    $x509->setStartDate('-1 day');
    $x509->setEndDate('+10 years'); // CA thường có thời hạn dài, ví dụ 10 năm
    $x509->setSerialNumber('1', 10);
    echo "Đã thiết lập thời hạn hiệu lực\n";

    // Thiết lập thuộc tính CA
    $x509->makeCA(); // Đánh dấu đây là chứng chỉ CA
    echo "Đã thiết lập thuộc tính CA\n";

    // Tự ký chứng chỉ CA
    $caCert = $x509->sign($x509, $caPrivate, ['digest_alg' => 'sha256']);
    echo "Đã ký chứng chỉ\n";

    // Lưu chứng chỉ và khóa riêng
    $certPem = $x509->saveX509($caCert);
    echo "Độ dài chứng chỉ PEM: " . strlen($certPem) . " bytes\n";

    // Tạo thư mục lưu trữ nếu chưa tồn tại
    if (!is_dir('storage/certificates')) {
        mkdir('storage/certificates', 0755, true);
    }

    // Lưu chứng chỉ và khóa riêng
    file_put_contents('storage/certificates/ca.pem', $certPem);
    file_put_contents('storage/certificates/ca-key.pem', $caPrivate->toString('PKCS8'));

    echo "✅ Tạo chứng chỉ CA thành công!\n";
    echo "Kích thước chứng chỉ CA: " . filesize('storage/certificates/ca.pem') . " bytes\n";
    echo "Kích thước khóa riêng CA: " . filesize('storage/certificates/ca-key.pem') . " bytes\n";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}