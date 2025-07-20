import "./bootstrap";
import "flowbite";
import TomSelect from "tom-select";

// Fungsi untuk menangani toggle password (versi yang diperbaiki)
function setupPasswordToggle() {
    const toggleButtons = document.querySelectorAll("[data-toggle-password]");

    toggleButtons.forEach((button) => {
        // Cek jika listener sudah ada untuk menghindari duplikasi
        if (button.dataset.listenerAttached) {
            return;
        }

        button.addEventListener("click", function () {
            const targetInput = document.getElementById(
                this.dataset.togglePassword
            );
            if (!targetInput) return;

            const eyeOpen = this.querySelector("[data-eye-open]");
            const eyeClosed = this.querySelector("[data-eye-closed]");

            if (targetInput.type === "password") {
                targetInput.type = "text";
                eyeOpen.classList.add("hidden");
                eyeClosed.classList.remove("hidden");
            } else {
                targetInput.type = "password";
                eyeOpen.classList.remove("hidden");
                eyeClosed.classList.add("hidden");
            }
        });

        // Tandai tombol bahwa listener sudah terpasang
        button.dataset.listenerAttached = "true";
    });
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".searchable-select").forEach((el) => {
        new TomSelect(el, {
            create: false,
            sortField: {
                field: "text",
                direction: "asc",
            },
        });
    });

    // Panggil fungsi saat halaman dimuat
    setupPasswordToggle();
});
