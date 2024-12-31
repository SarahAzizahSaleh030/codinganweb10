// Tombol Shop Now
document.getElementById('btn').addEventListener('click', () => {
  // Navigasi ke bagian menu dalam halaman
  window.location.href = '#menu';
});

document.getElementById('contact-form').addEventListener('submit', function (e) {
  e.preventDefault(); // Mencegah reload halaman

  // Ambil nilai dari input form
  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const phone = document.getElementById('phone').value;
  const message = document.getElementById('message').value;

  // Validasi input
  if (!name || !email || !message) {
    alert('Harap lengkapi semua data yang wajib diisi!');
    return;
  }

  // Tampilkan notifikasi sementara
  const notification = document.getElementById('notification');
  notification.style.display = 'block';
  notification.innerHTML = 'Mengirim pesan...';
  notification.style.color = 'blue';

  // Kirim data menggunakan Fetch API
  fetch('send_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, email, phone, message }),
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        notification.innerHTML = 'Pesan berhasil dikirim!';
        notification.style.color = 'green';
        document.getElementById('contact-form').reset(); // Reset form
      } else {
        notification.innerHTML = 'Gagal mengirim pesan. Silakan coba lagi.';
        notification.style.color = 'red';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      notification.innerHTML = 'Terjadi kesalahan. Silakan coba lagi!';
      notification.style.color = 'red';
    });
});
