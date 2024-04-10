function mostrarRegistro() {
    var registerForm = document.getElementById('register-form');
    var loginForm = document.getElementById('login-form');
    
    if (registerForm.style.display === 'none') {
        registerForm.style.display = 'block';
        loginForm.style.display = 'none';
    } else {
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
    }
}
