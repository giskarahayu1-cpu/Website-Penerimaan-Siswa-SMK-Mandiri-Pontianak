/**
 * Address Synchronization & Dynamic Cascading Dropdowns
 * SMK Mandiri Pontianak PPDB
 */

const PROVINCES_MAP = {
    "Aceh": "11",
    "Sumatera Utara": "12",
    "Sumatera Barat": "13",
    "Riau": "14",
    "Kepulauan Riau": "21",
    "Jambi": "15",
    "Sumatera Selatan": "16",
    "Kepulauan Bangka Belitung": "19",
    "Bengkulu": "17",
    "Lampung": "18",
    "DKI Jakarta": "31",
    "Jawa Barat": "32",
    "Jawa Tengah": "33",
    "DI Yogyakarta": "34",
    "Jawa Timur": "35",
    "Banten": "36",
    "Bali": "51",
    "Nusa Tenggara Barat": "52",
    "Nusa Tenggara Timur": "53",
    "Kalimantan Barat": "61",
    "Kalimantan Tengah": "62",
    "Kalimantan Selatan": "63",
    "Kalimantan Timur": "64",
    "Kalimantan Utara": "65",
    "Sulawesi Utara": "71",
    "Gorontalo": "75",
    "Sulawesi Tengah": "72",
    "Sulawesi Barat": "76",
    "Sulawesi Selatan": "73",
    "Sulawesi Tenggara": "74",
    "Maluku": "81",
    "Maluku Utara": "82",
    "Papua": "91",
    "Papua Barat": "92",
    "Papua Selatan": "93",
    "Papua Tengah": "94",
    "Papua Pegunungan": "95",
    "Papua Barat Daya": "96"
};

const LOCAL_DATA = {
    "Kalimantan Barat": {
        "id": "61",
        "regencies": {
            "Kabupaten Sambas": {
                "id": "6101",
                "districts": {}
            },
            "Kabupaten Bengkayang": {
                "id": "6107",
                "districts": {}
            },
            "Kabupaten Landak": {
                "id": "6108",
                "districts": {}
            },
            "Kabupaten Mempawah": {
                "id": "6102",
                "districts": {
                    "Mempawah Hilir": ["Mempawah Hilir"],
                    "Mempawah Timur": ["Mempawah Timur"],
                    "Siantan": ["Siantan"]
                }
            },
            "Kabupaten Sanggau": {
                "id": "6103",
                "districts": {}
            },
            "Kabupaten Ketapang": {
                "id": "6104",
                "districts": {}
            },
            "Kabupaten Sintang": {
                "id": "6105",
                "districts": {}
            },
            "Kabupaten Kapuas Hulu": {
                "id": "6106",
                "districts": {}
            },
            "Kabupaten Sekadau": {
                "id": "6109",
                "districts": {}
            },
            "Kabupaten Melawi": {
                "id": "6110",
                "districts": {}
            },
            "Kabupaten Kayong Utara": {
                "id": "6111",
                "districts": {}
            },
            "Kabupaten Kubu Raya": {
                "id": "6112",
                "districts": {
                    "Kecamatan Sungai Raya": {
                        "id": "611201",
                        "villages": [
                            "Sungai Raya", "Sungai Ambangah", "Arang Limbung", "Kuala Dua", "Tebang Kacang", 
                            "Sungai Asam", "Pulau Limbung", "Desa Kapur", "Gunung Tamang", "Sungai Bulan", 
                            "Limbung", "Teluk Kapuas", "Madu Sari", "Mekar Sari", "Mekar Baru", 
                            "Sungai Raya Dalam", "Parit Baru", "Pulau Jambu", "Kalibandung", "Muara Baru", 
                            "Suku Lanting", "Permata Jaya"
                        ]
                    },
                    "Kecamatan Sungai Kakap": {
                        "id": "611204",
                        "villages": [
                            "Jeruju Besar", "Kalimas", "Pal Sembilan (Pal IX)", "Punggur Besar", 
                            "Punggur Kapuas", "Punggur Kecil", "Sungai Belidak", "Sungai Itik", 
                            "Sungai Kakap", "Sungai Kupah", "Sungai Rengas", "Sepuk Laut", "Tanjung Saleh"
                        ]
                    },
                    "Kecamatan Sungai Ambawang": {
                        "id": "611207",
                        "villages": [
                            "Ampera Raya", "Bengkarek", "Durian", "Jawa Tengah", "Korek", 
                            "Lingga", "Mega Timur", "Pancaroba", "Pasak", "Pasak Piang", 
                            "Puguk", "Simpang Kanan", "Simpang Raya", "Sungai Ambawang Kuala", 
                            "Sungai Malaya", "Teluk Bakung"
                        ]
                    },
                    "Kecamatan Kubu": {
                        "id": "611202",
                        "villages": [
                            "Air Putih", "Ambarawa", "Dabong", "Jangkang Dua", "Jangkang Satu", 
                            "Kampung Baru", "Kubu", "Mengkalang", "Mengkalang Jambu", "Olak-Olak", 
                            "Pelita Jaya", "Pinang Dalam", "Pinang Luar", "Sungai Bemban", "Sungai Selamat", 
                            "Sungai Terus", "Sepakat Baru", "Seruat Dua", "Seruat Tiga", "Teluk Nangka"
                        ]
                    },
                    "Kecamatan Rasau Jaya": {
                        "id": "611208",
                        "villages": ["Rasau Jaya"]
                    },
                    "Kecamatan Teluk Pakedai": {
                        "id": "611203"
                    },
                    "Kecamatan Batu Ampar": {
                        "id": "611206"
                    },
                    "Kecamatan Terentang": {
                        "id": "611205"
                    },
                    "Kecamatan Kuala Mandor-B": {
                        "id": "611209"
                    }
                }
            },
            "Kota Pontianak": {
                "id": "6171",
                "districts": {
                    "Pontianak Kota": ["Darat Sekip", "Mariana", "Sungai Bangkong", "Sungai Jawi", "Tengah"],
                    "Pontianak Barat": ["Pal Lima", "Sungai Beliung", "Sungaijawi Dalam", "Sungaijawi Luar"],
                    "Pontianak Selatan": ["Akcaya", "Benua Melayu Darat", "Benua Melayu Laut", "Kota Baru", "Parit Tokaya"],
                    "Pontianak Tenggara": ["Bangka Belitung Darat", "Bangka Belitung Laut", "Bansir Darat", "Bansir Laut"],
                    "Pontianak Timur": ["Banjar Serasan", "Dalam Bugis", "Parit Mayor", "Saigon", "Tambelan Sampit", "Tanjung Hulu", "Tanjung Hilir"],
                    "Pontianak Utara": ["Batu Layang", "Siantan Hilir", "Siantan Hulu", "Siantan Tengah"]
                }
            },
            "Kota Singkawang": {
                "id": "6172",
                "districts": {}
            }
        }
    }
};

document.addEventListener("DOMContentLoaded", function() {
    const provSelect = document.getElementById("provinsi");
    const kotaSelect = document.getElementById("kota");
    const kecSelect = document.getElementById("kecamatan");
    const kelSelect = document.getElementById("kelurahan");

    if (!provSelect || !kotaSelect || !kecSelect || !kelSelect) return;

    // Load initial saved values from window configuration
    const savedProv = window.addressConfig ? window.addressConfig.provinsi : "";
    const savedKota = window.addressConfig ? window.addressConfig.kota : "";
    const savedKec = window.addressConfig ? window.addressConfig.kecamatan : "";
    const savedKel = window.addressConfig ? window.addressConfig.kelurahan : "";

    // Setup fallback elements
    setupManualInputFallback(kotaSelect);
    setupManualInputFallback(kecSelect);
    setupManualInputFallback(kelSelect);

    // Event listener for Provinsi
    provSelect.addEventListener("change", function() {
        handleProvinsiChange(this.value);
    });

    // Event listener for Kota
    kotaSelect.addEventListener("change", function() {
        const selectedOption = this.options[this.selectedIndex];
        const id = selectedOption ? selectedOption.getAttribute("data-id") : "";
        handleKotaChange(this.value, id);
    });

    // Event listener for Kecamatan
    kecSelect.addEventListener("change", function() {
        const selectedOption = this.options[this.selectedIndex];
        const id = selectedOption ? selectedOption.getAttribute("data-id") : "";
        handleKecamatanChange(this.value, id);
    });

    // Trigger initialization
    if (savedProv) {
        provSelect.value = savedProv;
        handleProvinsiChange(savedProv, savedKota, savedKec, savedKel);
    } else {
        // Default select to Kalimantan Barat
        provSelect.value = "Kalimantan Barat";
        handleProvinsiChange("Kalimantan Barat");
    }

    // Helper functions

    function setupManualInputFallback(selectEl) {
        const fieldName = selectEl.getAttribute("name");
        
        // Check if fallback already exists
        if (document.getElementById(selectEl.id + "_manual")) return;

        // Create container
        const container = document.createElement("div");
        container.id = selectEl.id + "_manual_container";
        container.style.display = "none";
        container.style.marginTop = "0.5rem";

        // Create input field
        const input = document.createElement("input");
        input.type = "text";
        input.id = selectEl.id + "_manual";
        input.className = "form-control uppercase-input";
        input.placeholder = "Masukkan nama " + selectEl.previousElementSibling.textContent.replace("*", "").trim();
        input.disabled = true;

        // Create cancel link
        const cancelLink = document.createElement("a");
        cancelLink.href = "#";
        cancelLink.textContent = "Pilih dari daftar";
        cancelLink.style.fontSize = "0.75rem";
        cancelLink.style.display = "inline-block";
        cancelLink.style.marginTop = "0.25rem";
        cancelLink.style.color = "var(--primary)";
        cancelLink.style.textDecoration = "none";
        cancelLink.addEventListener("click", function(e) {
            e.preventDefault();
            disableManualInput(selectEl);
        });

        container.appendChild(input);
        container.appendChild(cancelLink);
        selectEl.parentNode.insertBefore(container, selectEl.nextSibling);
    }

    function enableManualInput(selectEl, value = "") {
        const manualContainer = document.getElementById(selectEl.id + "_manual_container");
        const manualInput = document.getElementById(selectEl.id + "_manual");
        const fieldName = selectEl.getAttribute("name") || selectEl.getAttribute("data-name");

        if (manualContainer && manualInput) {
            selectEl.style.display = "none";
            selectEl.removeAttribute("name");
            selectEl.removeAttribute("required");

            manualContainer.style.display = "block";
            manualInput.name = fieldName;
            manualInput.value = value;
            manualInput.disabled = false;
            manualInput.setAttribute("required", "required");
            manualInput.focus();
        }
    }

    function disableManualInput(selectEl) {
        const manualContainer = document.getElementById(selectEl.id + "_manual_container");
        const manualInput = document.getElementById(selectEl.id + "_manual");
        const fieldName = manualInput.getAttribute("name");

        if (manualContainer && manualInput) {
            manualContainer.style.display = "none";
            manualInput.removeAttribute("name");
            manualInput.disabled = true;
            manualInput.removeAttribute("required");

            selectEl.style.display = "block";
            selectEl.setAttribute("name", fieldName);
            selectEl.setAttribute("required", "required");
            selectEl.value = "";
            
            // Trigger change event to reset downstream select elements
            selectEl.dispatchEvent(new Event("change"));
        }
    }

    function clearOptions(selectEl, placeholderText) {
        selectEl.innerHTML = `<option value="" disabled selected>${placeholderText}</option>`;
        selectEl.innerHTML += `<option value="__manual__">-- Lainnya --</option>`;
    }

    function addOption(selectEl, value, text, apiId = "") {
        const option = document.createElement("option");
        option.value = value;
        option.textContent = text;
        if (apiId) {
            option.setAttribute("data-id", apiId);
        }
        // Insert before "-- Lainnya --"
        selectEl.insertBefore(option, selectEl.lastChild);
    }

    // Handles Provinsi Dropdown Change
    function handleProvinsiChange(provName, targetKota = "", targetKec = "", targetKel = "") {
        clearOptions(kotaSelect, "Pilih Kota / Kabupaten");
        clearOptions(kecSelect, "Pilih Kecamatan");
        clearOptions(kelSelect, "Pilih Kelurahan / Desa");
        
        // Hide manual inputs if open
        if (kotaSelect.style.display === "none") disableManualInput(kotaSelect);
        if (kecSelect.style.display === "none") disableManualInput(kecSelect);
        if (kelSelect.style.display === "none") disableManualInput(kelSelect);

        if (!provName) return;

        if (provName === "Kalimantan Barat") {
            // Load Local Data
            const regencies = LOCAL_DATA["Kalimantan Barat"].regencies;
            for (const name in regencies) {
                addOption(kotaSelect, name, name, regencies[name].id);
            }
            
            // Check if targetKota is in local options
            if (targetKota) {
                if (regencies[targetKota]) {
                    kotaSelect.value = targetKota;
                    handleKotaChange(targetKota, regencies[targetKota].id, targetKec, targetKel);
                } else {
                    enableManualInput(kotaSelect, targetKota);
                    if (targetKec) enableManualInput(kecSelect, targetKec);
                    if (targetKel) enableManualInput(kelSelect, targetKel);
                }
            }
        } else {
            // Load from Online API
            const provId = PROVINCES_MAP[provName];
            if (!provId) {
                // If not found in map, allow manual typing
                enableManualInput(kotaSelect, targetKota);
                return;
            }

            // Show loading
            const loadingOpt = document.createElement("option");
            loadingOpt.value = "";
            loadingOpt.textContent = "Loading...";
            kotaSelect.insertBefore(loadingOpt, kotaSelect.firstChild);
            kotaSelect.value = "";

            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
                .then(res => res.json())
                .then(data => {
                    if (loadingOpt.parentNode) kotaSelect.removeChild(loadingOpt);
                    
                    // Sort regencies alphabetically
                    data.sort((a, b) => a.name.localeCompare(b.name));
                    
                    data.forEach(item => {
                        // Title-case formatting
                        let formattedName = formatTitleCase(item.name);
                        addOption(kotaSelect, formattedName, formattedName, item.id);
                    });

                    if (targetKota) {
                        const matched = Array.from(kotaSelect.options).find(o => o.value.toLowerCase() === targetKota.toLowerCase());
                        if (matched) {
                            kotaSelect.value = matched.value;
                            handleKotaChange(matched.value, matched.getAttribute("data-id"), targetKec, targetKel);
                        } else {
                            enableManualInput(kotaSelect, targetKota);
                            if (targetKec) enableManualInput(kecSelect, targetKec);
                            if (targetKel) enableManualInput(kelSelect, targetKel);
                        }
                    }
                })
                .catch(err => {
                    console.error("Failed to fetch regencies, falling back to manual input", err);
                    if (loadingOpt.parentNode) kotaSelect.removeChild(loadingOpt);
                    enableManualInput(kotaSelect, targetKota);
                    if (targetKec) enableManualInput(kecSelect, targetKec);
                    if (targetKel) enableManualInput(kelSelect, targetKel);
                });
        }
    }

    // Handles Kota Dropdown Change
    function handleKotaChange(kotaName, apiId, targetKec = "", targetKel = "") {
        clearOptions(kecSelect, "Pilih Kecamatan");
        clearOptions(kelSelect, "Pilih Kelurahan / Desa");
        
        if (kecSelect.style.display === "none") disableManualInput(kecSelect);
        if (kelSelect.style.display === "none") disableManualInput(kelSelect);

        if (!kotaName) return;

        if (kotaName === "__manual__") {
            enableManualInput(kotaSelect);
            enableManualInput(kecSelect, targetKec);
            enableManualInput(kelSelect, targetKel);
            return;
        }

        const currentProv = provSelect.value;

        if (currentProv === "Kalimantan Barat" && 
            LOCAL_DATA["Kalimantan Barat"].regencies[kotaName] && 
            LOCAL_DATA["Kalimantan Barat"].regencies[kotaName].districts && 
            Object.keys(LOCAL_DATA["Kalimantan Barat"].regencies[kotaName].districts).length > 0) {
            // Load Local Data
            const districts = LOCAL_DATA["Kalimantan Barat"].regencies[kotaName].districts;
            for (const name in districts) {
                const districtId = (districts[name] && typeof districts[name] === 'object' && !Array.isArray(districts[name])) ? districts[name].id : "";
                addOption(kecSelect, name, name, districtId);
            }

            if (targetKec) {
                if (districts[targetKec]) {
                    kecSelect.value = targetKec;
                    const districtId = (districts[targetKec] && typeof districts[targetKec] === 'object' && !Array.isArray(districts[targetKec])) ? districts[targetKec].id : "";
                    handleKecamatanChange(targetKec, districtId, targetKel);
                } else {
                    enableManualInput(kecSelect, targetKec);
                    if (targetKel) enableManualInput(kelSelect, targetKel);
                }
            }
        } else if (apiId) {
            // Load from Online API
            const loadingOpt = document.createElement("option");
            loadingOpt.value = "";
            loadingOpt.textContent = "Loading...";
            kecSelect.insertBefore(loadingOpt, kecSelect.firstChild);
            kecSelect.value = "";

            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${apiId}.json`)
                .then(res => res.json())
                .then(data => {
                    if (loadingOpt.parentNode) kecSelect.removeChild(loadingOpt);
                    
                    data.sort((a, b) => a.name.localeCompare(b.name));
                    
                    data.forEach(item => {
                        let formattedName = formatTitleCase(item.name);
                        addOption(kecSelect, formattedName, formattedName, item.id);
                    });

                    if (targetKec) {
                        const matched = Array.from(kecSelect.options).find(o => o.value.toLowerCase() === targetKec.toLowerCase());
                        if (matched) {
                            kecSelect.value = matched.value;
                            handleKecamatanChange(matched.value, matched.getAttribute("data-id"), targetKel);
                        } else {
                            enableManualInput(kecSelect, targetKec);
                            if (targetKel) enableManualInput(kelSelect, targetKel);
                        }
                    }
                })
                .catch(err => {
                    console.error("Failed to fetch districts", err);
                    if (loadingOpt.parentNode) kecSelect.removeChild(loadingOpt);
                    enableManualInput(kecSelect, targetKec);
                    if (targetKel) enableManualInput(kelSelect, targetKel);
                });
        } else {
            enableManualInput(kecSelect, targetKec);
            if (targetKel) enableManualInput(kelSelect, targetKel);
        }
    }

    // Handles Kecamatan Dropdown Change
    function handleKecamatanChange(kecName, apiId, targetKel = "") {
        clearOptions(kelSelect, "Pilih Kelurahan / Desa");
        
        if (kelSelect.style.display === "none") disableManualInput(kelSelect);

        if (!kecName) return;

        if (kecName === "__manual__") {
            enableManualInput(kecSelect);
            enableManualInput(kelSelect, targetKel);
            return;
        }

        const currentProv = provSelect.value;
        const currentKota = kotaSelect.value;

        let localVillages = null;
        if (currentProv === "Kalimantan Barat" && 
            LOCAL_DATA["Kalimantan Barat"].regencies[currentKota] && 
            LOCAL_DATA["Kalimantan Barat"].regencies[currentKota].districts && 
            LOCAL_DATA["Kalimantan Barat"].regencies[currentKota].districts[kecName]) {
            
            const distData = LOCAL_DATA["Kalimantan Barat"].regencies[currentKota].districts[kecName];
            if (Array.isArray(distData)) {
                localVillages = distData;
            } else if (distData && typeof distData === 'object' && Array.isArray(distData.villages)) {
                localVillages = distData.villages;
            }
        }

        if (localVillages) {
            // Load Local Data
            localVillages.forEach(name => {
                addOption(kelSelect, name, name);
            });

            if (targetKel) {
                if (localVillages.includes(targetKel)) {
                    kelSelect.value = targetKel;
                } else {
                    enableManualInput(kelSelect, targetKel);
                }
            }
        } else if (apiId) {
            // Load from Online API
            const loadingOpt = document.createElement("option");
            loadingOpt.value = "";
            loadingOpt.textContent = "Loading...";
            kelSelect.insertBefore(loadingOpt, kelSelect.firstChild);
            kelSelect.value = "";

            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${apiId}.json`)
                .then(res => res.json())
                .then(data => {
                    if (loadingOpt.parentNode) kelSelect.removeChild(loadingOpt);
                    
                    data.sort((a, b) => a.name.localeCompare(b.name));
                    
                    data.forEach(item => {
                        let formattedName = formatTitleCase(item.name);
                        addOption(kelSelect, formattedName, formattedName);
                    });

                    if (targetKel) {
                        const matched = Array.from(kelSelect.options).find(o => o.value.toLowerCase() === targetKel.toLowerCase());
                        if (matched) {
                            kelSelect.value = matched.value;
                        } else {
                            enableManualInput(kelSelect, targetKel);
                        }
                    }
                })
                .catch(err => {
                    console.error("Failed to fetch villages", err);
                    if (loadingOpt.parentNode) kelSelect.removeChild(loadingOpt);
                    enableManualInput(kelSelect, targetKel);
                });
        } else {
            enableManualInput(kelSelect, targetKel);
        }
    }

    // Event listener for Kelurahan manual check
    kelSelect.addEventListener("change", function() {
        if (this.value === "__manual__") {
            enableManualInput(kelSelect);
        }
    });

    // Formatter to change KOTA PONTIANAK or PONTIANAK TIMUR to Title Case
    function formatTitleCase(str) {
        return str.toLowerCase().replace(/(?:\s|^)\w/g, function(match) {
            return match.toUpperCase();
        });
    }
});
