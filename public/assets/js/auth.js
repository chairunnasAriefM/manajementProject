const wrapper = document.querySelector('.wrapper');
const registerLink = document.querySelector('.register-link');
const loginLink = document.querySelector('.login-link');

registerLink.onclick = () => {
    wrapper.classList.add('active'); // Tambah kelas untuk animasi ke Register
}

loginLink.onclick = () => {
    wrapper.classList.remove('active'); // Hapus kelas untuk kembali ke Login
}
