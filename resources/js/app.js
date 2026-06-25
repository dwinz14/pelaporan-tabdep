import "./bootstrap";

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * Auto-format input NIK: huruf otomatis kapital, hanya angka setelah 2 karakter awal,
 * dibatasi 9 digit. Mencegah user perlu menekan capslock atau salah input format.
 */
window.maskNik = function (input) {
    let value = input.value.toUpperCase();
    let prefix = value.substring(0, 2).replace(/[^A-Z]/g, "");
    let suffix = value
        .substring(2)
        .replace(/[^0-9]/g, "")
        .substring(0, 9);
    input.value = prefix + suffix;
};
