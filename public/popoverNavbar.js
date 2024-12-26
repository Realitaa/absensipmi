document.addEventListener('DOMContentLoaded', function () {
    const icon = document.querySelector('.blink-icon');
    const popover = new bootstrap.Popover(icon, {
        trigger: 'manual', // Popover dikontrol manual
        placement: 'top',  // Letak popover
        html: true         // Izinkan HTML di dalam popover
    });

    let isPopoverVisible = false; // Status popover

    // Toggle popover saat elemen diklik
    icon.addEventListener('click', function () {
        if (isPopoverVisible) {
            popover.hide();
        } else {
            popover.show();
        }
        isPopoverVisible = !isPopoverVisible;
    });

    // Sembunyikan popover jika pengguna mengklik di luar elemen
    document.addEventListener('click', function (e) {
        if (isPopoverVisible && !e.target.closest('.blink-icon') && !e.target.closest('.popover')) {
            popover.hide();
            isPopoverVisible = false;
        }
    });
    let isDanger = true;

    setInterval(() => {
        if (isDanger) {
            icon.classList.remove('text-danger');
            icon.classList.add('text-light');
        } else {
            icon.classList.remove('text-light');
            icon.classList.add('text-danger');
        }
        isDanger = !isDanger;
    }, 1000); // Ubah warna setiap 1 detik
});