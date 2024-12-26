// Menampilkan pesan dengan animasi
window.addEventListener('DOMContentLoaded', (event) => {
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        // Tambahkan kelas show untuk animasi masuk
        flashMessage.classList.add('show');

        // Hapus pesan setelah beberapa detik
        setTimeout(() => {
            flashMessage.classList.remove('show');
            setTimeout(() => flashMessage.remove(), 500); // Hapus elemen setelah animasi keluar
        }, 3000); // Durasi 3 detik
    }
});