// Fungsi untuk menampilkan/tutup drawer
function toggleDrawer() {
    var drawer = document.getElementById('profile-drawer');
    if (drawer.style.left === '0px') {
        drawer.style.left = '-300px'; // Menyembunyikan drawer
    } else {
        drawer.style.left = '0px'; // Menampilkan drawer
    }
}
