// Skrip untuk mencari data di dalam tabel berdasarkan query pada elemen input search
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const table = document.getElementById('karyawan-table');
    const rows = table.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();

        rows.forEach(row => {
            // Gabungkan semua teks dalam satu baris menjadi satu string
            const rowText = Array.from(row.querySelectorAll('td, th'))
                .map(cell => cell.textContent.toLowerCase())
                .join(' ');

            // Tampilkan atau sembunyikan baris berdasarkan apakah query ditemukan
            if (rowText.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});