const password = document.getElementById("password");
const toggle = document.getElementById("eyePass");

toggle?.addEventListener("click", () => {
    if (password.type === "password") {
        password.type = "text";
        toggle.src = "https://www.svgrepo.com/show/380007/eye-password-hide.svg";
    } else {
        password.type = "password";
        toggle.src = "https://www.svgrepo.com/show/380010/eye-password-show.svg";
    }
});