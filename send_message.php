<?php
header('Content-Type: application/json');

// Konfigurasi koneksi database
$host = "localhost";
$user = "root";
$password = "";
$database = "contact_form";

$response = ["success" => false, "message" => ""];

// Fungsi untuk validasi input
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

try {
    // Koneksi ke database
    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Koneksi database gagal: " . $conn->connect_error);
    }

    // Ambil dan bersihkan data dari form
    $name = isset($_POST['name']) ? clean_input($_POST['name']) : "";
    $email = isset($_POST['email']) ? clean_input($_POST['email']) : "";
    $phone = isset($_POST['phone']) ? clean_input($_POST['phone']) : "";
    $message = isset($_POST['message']) ? clean_input($_POST['message']) : "";

    // Validasi input
    if (empty($name) || empty($email) || empty($message)) {
        $response['message'] = "Harap lengkapi semua data yang wajib diisi!";
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Format email tidak valid!";
        echo json_encode($response);
        exit;
    }

    // Siapkan query SQL dengan prepared statement
    $stmt = $conn->prepare("INSERT INTO messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Pesan berhasil dikirim!";
    } else {
        throw new Exception("Error executing query: " . $stmt->error);
    }

    // Tutup statement
    $stmt->close();

} catch (Exception $e) {
    $response['message'] = "Terjadi kesalahan: " . $e->getMessage();
} finally {
    // Tutup koneksi
    if (isset($conn) && $conn) {
        $conn->close();
    }
    // Kirim response dalam format JSON
    echo json_encode($response);
}
?>
