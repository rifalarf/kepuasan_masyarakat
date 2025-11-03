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

// --- MULAI FUNGSI BARU UNTUK KUESIONER DINAMIS ---
function setupDynamicQuestionForms() {
    const container = document.getElementById('questions-container');
    const addButton = document.getElementById('add-question-btn');

    if (!container || !addButton) {
        console.log('Dynamic question form elements not found');
        return;
    }

    const updateLabels = () => {
        const items = container.querySelectorAll('.question-item');
        items.forEach((item, index) => {
            const label = item.querySelector('label');
            if (label) {
                label.textContent = `Pertanyaan ${index + 1}`;
            }
        });
    };

    addButton.addEventListener('click', () => {
        console.log('Menambahkan pertanyaan baru');
        const newItem = document.createElement('div');
        newItem.classList.add('question-item', 'mt-4');
        newItem.innerHTML = `
            <label class="mb-2 block text-sm font-medium">Pertanyaan</label>
            <div class="flex items-center space-x-2">
                <textarea rows="2" name="question[]" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Tulis pertanyaan kuesioner..." required></textarea>
                <button type="button" class="remove-question-btn flex-shrink-0 rounded-lg bg-red-600 p-2.5 text-white hover:bg-red-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        `;
        container.appendChild(newItem);
        updateLabels();
        console.log('Pertanyaan baru berhasil ditambahkan');
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-question-btn')) {
            e.target.closest('.question-item').remove();
            updateLabels();
            console.log('Pertanyaan berhasil dihapus');
        }
    });
}
// --- AKHIR FUNGSI BARU ---


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
    setupDynamicQuestionForms(); // Panggil fungsi baru di sini

    const villageSelectEl = document.getElementById("village_id");
    const unsurSelectEl = document.getElementById("unsur_id");
    const unsurStatus = document.getElementById("unsur-status");
    const submitButton = document.getElementById("submit-button");

    // LANGKAH 1: Inisialisasi TomSelect untuk KEDUA dropdown secara manual.
    // Ini memberi kita kontrol penuh atas instance mereka.
    const villageTomSelect = new TomSelect(villageSelectEl, {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: "- Pilih Satuan Kerja -",
    });

    const unsurTomSelect = new TomSelect(unsurSelectEl, {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: "- Pilih Unsur Pelayanan -",
    });

    // LANGKAH 2: Fungsi untuk mereset dropdown kedua.
    function resetUnsurDropdown() {
        unsurTomSelect.disable();
        unsurTomSelect.clear();
        unsurTomSelect.clearOptions();
        unsurStatus.textContent = "";
        submitButton.classList.add("hidden");
    }

    // Pastikan dropdown kedua nonaktif saat halaman dimuat.
    resetUnsurDropdown();

    // LANGKAH 3: Pasang event listener ke instance TomSelect yang kita kontrol.
    villageTomSelect.on("change", async function (villageId) {
        resetUnsurDropdown();

        // Jika pilihan dikosongkan, jangan lakukan apa-apa.
        if (!villageId) return;

        unsurStatus.textContent = "Memuat unsur pelayanan...";
        try {
            // Panggil API yang logikanya sudah benar.
            const response = await fetch(
                `/api/unsurs-by-village/${villageId}`
            );
            if (!response.ok)
                throw new Error("Gagal mengambil data dari server.");

            const unsurs = await response.json();

            if (unsurs.length > 0) {
                unsurs.forEach((unsur) => {
                    unsurTomSelect.addOption({
                        value: unsur.id,
                        text: unsur.unsur,
                    });
                });
                unsurTomSelect.enable();
                unsurStatus.textContent = "Silakan pilih unsur pelayanan.";
            } else {
                unsurStatus.textContent =
                    "Tidak ada unsur pelayanan untuk satuan kerja ini.";
            }
        } catch (error) {
            unsurStatus.textContent = "Gagal memuat data unsur.";
            console.error("Error fetching unsurs:", error);
        }
    });

    // Event listener untuk mengaktifkan tombol submit.
    unsurTomSelect.on("change", function (value) {
        if (value) {
            submitButton.classList.remove("hidden");
        } else {
            submitButton.classList.add("hidden");
        }
    });
});
