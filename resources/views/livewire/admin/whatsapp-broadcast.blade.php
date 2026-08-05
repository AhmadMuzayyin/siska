<div class="space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="text-2xl font-bold">{{ __('WhatsApp Broadcast') }}</flux:heading>
            <flux:subheading>{{ __('Kirim pesan massal dengan template variabel otomatis') }}</flux:subheading>
        </div>

        <div class="flex items-center gap-2">
            @if ($this->isConnected())
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-300">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ __('Fonnte Terhubung') }}
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-500/30 bg-amber-500/10 px-3.5 py-1 text-xs font-semibold text-amber-700 dark:text-amber-300">
                    <span class="size-2 rounded-full bg-amber-500"></span>
                    {{ __('Belum Terhubung / Standby') }}
                </span>
            @endif
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex border-b border-zinc-200 dark:border-zinc-800">
        <button
            type="button"
            wire:click="setTab('broadcast')"
            class="border-b-2 px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'broadcast' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400' }}"
        >
            <flux:icon name="paper-airplane" class="inline-block size-4 me-1.5" />
            {{ __('Kirim Pesan Masal (Broadcast)') }}
        </button>

        <button
            type="button"
            wire:click="setTab('device')"
            class="border-b-2 px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'device' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400' }}"
        >
            <flux:icon name="device-phone-mobile" class="inline-block size-4 me-1.5" />
            {{ __('Pengaturan Perangkat WA Gateway') }}
        </button>
    </div>

    @if ($activeTab === 'broadcast')
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Form Broadcast --}}
            <div class="lg:col-span-7 space-y-6">
                <flux:card>
                    <form wire:submit.prevent="prepareBroadcast" class="space-y-5">
                        <flux:select wire:model.live="targetCategory" label="{{ __('Target Penerima') }}">
                            <option value="semua_santri">{{ __('Semua Santri / Wali Santri') }}</option>
                            <option value="semua_guru">{{ __('Semua Pengajar / Guru') }}</option>
                            <option value="per_kelas">{{ __('Filter Per Kelas') }}</option>
                            <option value="kustom">{{ __('Input Manual Nomor WhatsApp') }}</option>
                        </flux:select>

                        @if ($targetCategory === 'per_kelas')
                            <flux:select wire:model="selectedKelasId" label="{{ __('Pilih Kelas') }}">
                                <option value="">{{ __('--- Pilih Kelas ---') }}</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                                @endforeach
                            </flux:select>
                        @endif

                        @if ($targetCategory === 'kustom')
                            <flux:textarea
                                wire:model="customPhoneNumbers"
                                label="{{ __('Daftar Nomor Telepon (Satu nomor per baris)') }}"
                                placeholder="081234567890&#10;089876543210"
                                rows="4"
                            />
                        @endif

                        <div class="space-y-2">
                            <flux:textarea
                                wire:model="messageTemplate"
                                id="messageTemplateInput"
                                label="{{ __('Template Pesan Broadcast') }}"
                                placeholder="Tulis pesan..."
                                rows="7"
                            />

                            {{-- Variable Placeholder Chips --}}
                            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-800 dark:bg-zinc-900">
                                <span class="text-xs font-semibold text-zinc-700 dark:text-zinc-300 block mb-2">
                                    💡 {{ __('Klik variabel di bawah untuk menambahkan format') }} &#123;&#123; namavariabel &#125;&#125;:
                                </span>
                                <div class="flex flex-wrap gap-1.5">
                                    <button type="button" onclick="insertVariable('nama')" class="rounded-md border border-emerald-500/30 bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-300">
                                        &#123;&#123; nama &#125;&#125;
                                    </button>
                                    <button type="button" onclick="insertVariable('nis')" class="rounded-md border border-emerald-500/30 bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-300">
                                        &#123;&#123; nis &#125;&#125;
                                    </button>
                                    <button type="button" onclick="insertVariable('kelas')" class="rounded-md border border-emerald-500/30 bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-300">
                                        &#123;&#123; kelas &#125;&#125;
                                    </button>
                                    <button type="button" onclick="insertVariable('wali')" class="rounded-md border border-emerald-500/30 bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-300">
                                        &#123;&#123; wali &#125;&#125;
                                    </button>
                                    <button type="button" onclick="insertVariable('telepon')" class="rounded-md border border-emerald-500/30 bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-300">
                                        &#123;&#123; telepon &#125;&#125;
                                    </button>
                                    <button type="button" onclick="insertVariable('lembaga')" class="rounded-md border border-emerald-500/30 bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-300">
                                        &#123;&#123; lembaga &#125;&#125;
                                    </button>
                                    <button type="button" onclick="insertVariable('tanggal')" class="rounded-md border border-emerald-500/30 bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-300">
                                        &#123;&#123; tanggal &#125;&#125;
                                    </button>
                                </div>
                            </div>
                        </div>

                        <flux:input
                            wire:model="delaySeconds"
                            type="number"
                            min="1"
                            max="60"
                            label="{{ __('Jeda Pengiriman (Detik bawaan Fonnte)') }}"
                            description="{{ __('Jeda antarpesan untuk mencegah terdeteksi spam oleh sistem WhatsApp.') }}"
                        />

                        <div class="pt-2">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-teal-600/20 hover:bg-teal-700 transition"
                            >
                                <flux:icon name="bolt" class="size-4 shrink-0 text-white" />
                                <span>{{ __('Pratinjau') }}</span>
                            </button>
                        </div>
                    </form>
                </flux:card>
            </div>

            {{-- Execution & Progress Runner Card --}}
            <div class="lg:col-span-5 space-y-6">
                <flux:card>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-zinc-200 pb-3 dark:border-zinc-800">
                            <h3 class="font-bold text-zinc-900 dark:text-white text-base">{{ __('Pratinjau & Progress Async') }}</h3>
                            <span class="rounded-full bg-teal-100 px-2.5 py-0.5 text-xs font-bold text-teal-800 dark:bg-teal-950 dark:text-teal-300">
                                {{ count($broadcastPayloads) }} {{ __('Target') }}
                            </span>
                        </div>

                        @if ($isReadyToSend && !empty($broadcastPayloads))
                            <div class="space-y-4">
                                {{-- Preview Box --}}
                                <div class="rounded-xl border border-teal-500/30 bg-teal-50/50 p-4 dark:border-teal-800 dark:bg-teal-950/40 space-y-2">
                                    <span class="text-xs font-bold text-teal-800 dark:text-teal-300 block">
                                        📱 {{ __('Pratinjau Pesan (Target Pertama):') }}
                                    </span>
                                    <p class="text-xs text-zinc-700 dark:text-zinc-300 whitespace-pre-line font-mono bg-white/80 dark:bg-zinc-900/80 p-3 rounded-lg border border-zinc-200 dark:border-zinc-800">
                                        {{ $broadcastPayloads[0]['message'] }}
                                    </p>
                                    <span class="text-[11px] text-zinc-500 block">
                                        Target: {{ $broadcastPayloads[0]['nama'] }} ({{ $broadcastPayloads[0]['phone'] }})
                                    </span>
                                </div>

                                {{-- Progress Bar --}}
                                <div id="broadcastProgressContainer" class="space-y-2">
                                    <div class="flex justify-between text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                        <span id="progressText">{{ __('Status: Siap Dikirim') }}</span>
                                        <span id="progressPercent">0%</span>
                                    </div>
                                    <div class="h-3 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-800">
                                        <div id="progressBar" class="h-full bg-teal-600 transition-all duration-300 w-0"></div>
                                    </div>
                                </div>

                                {{-- Trigger Button --}}
                                <button
                                    id="btnStartAsyncBroadcast"
                                    type="button"
                                    onclick="runAsyncBroadcast()"
                                    class="w-full rounded-xl bg-teal-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition flex items-center justify-center gap-2"
                                >
                                    <flux:icon name="paper-airplane" class="size-5" />
                                    <span>{{ __('Kirim Broadcast Sekarang (JS Async)') }}</span>
                                </button>
                            </div>
                        @else
                            <div class="py-12 text-center text-zinc-500">
                                <flux:icon name="queue-list" class="mx-auto size-10 text-zinc-400 mb-2" />
                                <p class="text-xs">{{ __('Klik "Siapkan Broadcast & Pratinjau" untuk memuat daftar target penerima.') }}</p>
                            </div>
                        @endif

                        {{-- Real-Time Log Console --}}
                        <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 space-y-2">
                            <span class="text-xs font-bold text-zinc-700 dark:text-zinc-300 block">
                                📋 {{ __('Log Pengiriman Real-Time:') }}
                            </span>
                            <div id="broadcastLogs" class="h-48 overflow-y-auto rounded-xl border border-zinc-200 bg-zinc-950 p-3 font-mono text-[11px] text-zinc-300 space-y-1">
                                <p class="text-zinc-500">// Log akan muncul di sini saat proses pengiriman berjalan...</p>
                            </div>
                        </div>
                    </div>
                </flux:card>
            </div>
        </div>
    @endif

    @if ($activeTab === 'device')
        <div class="w-full space-y-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                {{-- Token Form Card --}}
                <div class="lg:col-span-6 space-y-6">
                    <flux:card>
                        <div class="space-y-6">
                            <h3 class="font-bold text-zinc-900 dark:text-white text-lg border-b border-zinc-200 pb-3 dark:border-zinc-800">
                                {{ __('Konfigurasi API Token Fonnte') }}
                            </h3>

                            <form wire:submit.prevent="saveToken" class="space-y-4">
                                <flux:input
                                    wire:model="apiKeyWhatsapp"
                                    type="password"
                                    label="{{ __('Token API Fonnte') }}"
                                    placeholder="Masukkan token dari fonnte.com"
                                    description="{{ __('Dapatkan token device Anda di https://md.fonnte.com') }}"
                                />

                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition"
                                >
                                    <flux:icon name="check" class="size-4 shrink-0 text-white" />
                                    <span>{{ __('Simpan') }}</span>
                                </button>
                            </form>

                            <div class="rounded-xl border border-emerald-500/30 bg-emerald-50/50 p-4 dark:border-emerald-800 dark:bg-emerald-950/40 text-xs text-zinc-600 dark:text-zinc-300 space-y-2">
                                <span class="font-bold text-emerald-800 dark:text-emerald-300 block">📌 {{ __('Petunjuk Penggunaan Fonnte WhatsApp Gateway:') }}</span>
                                <ol class="list-decimal list-inside space-y-1">
                                    <li>{{ __('Daftar dan buat device baru di https://md.fonnte.com/new/register.php') }}</li>
                                    <li>{{ __('Salin Token Device yang diberikan oleh Fonnte ke form di atas.') }}</li>
                                    <li>{{ __('Klik "Simpan Token", lalu klik "Ambil QR Code" di sebelah kanan.') }}</li>
                                    <li>{{ __('Pindai QR Code menggunakan aplikasi WhatsApp di HP Anda.') }}</li>
                                </ol>
                            </div>
                        </div>
                    </flux:card>
                </div>

                {{-- Device Status & QR Code Card --}}
                <div class="lg:col-span-6 space-y-6">
                    <flux:card>
                        <div class="space-y-6">
                            <h3 class="font-bold text-zinc-900 dark:text-white text-lg border-b border-zinc-200 pb-3 dark:border-zinc-800">
                                {{ __('Status & Koneksi Perangkat') }}
                            </h3>

                            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-zinc-500">{{ __('Status Device:') }}</span>
                                    <span class="text-xs font-extrabold uppercase {{ $this->isConnected() ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ $deviceStatus ?: 'Unknown' }}
                                    </span>
                                </div>

                                @if ($deviceName)
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-zinc-500">{{ __('Nama Perangkat:') }}</span>
                                        <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $deviceName }}</span>
                                    </div>
                                @endif

                                @if ($devicePhone)
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-zinc-500">{{ __('Nomor Terhubung:') }}</span>
                                        <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $devicePhone }}</span>
                                    </div>
                                @endif

                                <p class="text-xs text-zinc-600 dark:text-zinc-400 italic">
                                    {{ $deviceStatusMessage }}
                                </p>
                            </div>

                            {{-- QR Code Display --}}
                            @if ($qrCodeUrl)
                                <div class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-teal-500/40 rounded-2xl bg-teal-50/20 dark:bg-teal-950/20 text-center space-y-3">
                                    <span class="text-xs font-bold text-teal-800 dark:text-teal-300">{{ __('Pindai QR Code Ini Menggunakan WhatsApp:') }}</span>
                                    <img src="{{ $qrCodeUrl }}" alt="Fonnte QR Code" class="size-64 rounded-xl border border-zinc-200 shadow-md">
                                    <span class="text-[11px] text-zinc-500">{{ __('Buka WA -> Perangkat Tertaut -> Tautkan Perangkat') }}</span>
                                </div>
                            @endif

                            <div class="flex flex-wrap gap-3 pt-2">
                                <button
                                    type="button"
                                    wire:click="checkDeviceStatus"
                                    class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-xs font-bold text-zinc-700 shadow-xs hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
                                >
                                    <flux:icon name="arrow-path" class="size-4 shrink-0 text-zinc-500" />
                                    <span>{{ __('Cek Status') }}</span>
                                </button>

                                <button
                                    type="button"
                                    wire:click="fetchQrCode"
                                    class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-teal-600/20 hover:bg-teal-700 transition"
                                >
                                    <flux:icon name="qr-code" class="size-4 shrink-0 text-white" />
                                    <span>{{ __('Login') }}</span>
                                </button>

                                <button
                                    type="button"
                                    wire:click="disconnectDevice"
                                    wire:confirm="{{ __('Yakin ingin memutuskan koneksi perangkat?') }}"
                                    class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-rose-600/20 hover:bg-rose-700 transition"
                                >
                                    <flux:icon name="power" class="size-4 shrink-0 text-white" />
                                    <span>{{ __('Putuskan') }}</span>
                                </button>
                            </div>
                        </div>
                    </flux:card>
                </div>
            </div>
        </div>
    @endif

    {{-- JavaScript Async Broadcast Engine --}}
    <script>
        function insertVariable(variable) {
            const textarea = document.getElementById('messageTemplateInput');
            if (!textarea) return;

            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;

            const replacement = '{{ ' + variable + ' }}';
            textarea.value = text.substring(0, start) + replacement + text.substring(end);
            textarea.selectionStart = textarea.selectionEnd = start + replacement.length;
            textarea.focus();

            textarea.dispatchEvent(new Event('input'));
        }

        async function runAsyncBroadcast() {
            const payloads = @json($broadcastPayloads);
            const apiKey = @json($apiKeyWhatsapp);
            const delaySec = @json($delaySeconds);

            if (!payloads || payloads.length === 0) {
                alert('Tidak ada data broadcast untuk dikirim.');
                return;
            }

            if (!apiKey) {
                alert('Token API Fonnte belum diatur pada Pengaturan Perangkat.');
                return;
            }

            const btn = document.getElementById('btnStartAsyncBroadcast');
            const logsContainer = document.getElementById('broadcastLogs');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const progressText = document.getElementById('progressText');

            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            logsContainer.innerHTML = '';

            function appendLog(msg, isError = false) {
                const p = document.createElement('p');
                p.className = isError ? 'text-rose-400' : 'text-emerald-400';
                const time = new Date().toLocaleTimeString('id-ID');
                p.textContent = `[${time}] ${msg}`;
                logsContainer.appendChild(p);
                logsContainer.scrollTop = logsContainer.scrollHeight;
            }

            appendLog(`🚀 Memulai pengiriman massal ${payloads.length} pesan dengan jeda ${delaySec}s...`);

            let successCount = 0;
            let failCount = 0;

            for (let i = 0; i < payloads.length; i++) {
                const item = payloads[i];
                const currentIndex = i + 1;
                const percent = Math.round((currentIndex / payloads.length) * 100);

                progressBar.style.width = `${percent}%`;
                progressPercent.textContent = `${percent}%`;
                progressText.textContent = `Mengirim ${currentIndex} dari ${payloads.length}...`;

                try {
                    const formData = new FormData();
                    formData.append('target', item.phone);
                    formData.append('message', item.message);
                    formData.append('delay', delaySec);

                    const response = await fetch('https://api.fonnte.com/send', {
                        method: 'POST',
                        headers: {
                            'Authorization': apiKey
                        },
                        body: formData
                    });

                    const resData = await response.json();

                    if (resData.status) {
                        successCount++;
                        appendLog(`✅ BERHASIL [${currentIndex}/${payloads.length}] Ke ${item.nama} (${item.phone}) - Respon: ${resData.detail || resData.process || 'sent'}`);
                    } else {
                        failCount++;
                        appendLog(`❌ GAGAL [${currentIndex}/${payloads.length}] Ke ${item.nama} (${item.phone}) - Reason: ${resData.reason || resData.message || 'Error'}`, true);
                    }
                } catch (err) {
                    failCount++;
                    appendLog(`❌ ERROR [${currentIndex}/${payloads.length}] Ke ${item.nama} (${item.phone}) - ${err.message}`, true);
                }

                // Apply Fonnte Delay between requests
                if (i < payloads.length - 1) {
                    await new Promise(resolve => setTimeout(resolve, delaySec * 1000));
                }
            }

            progressText.textContent = `🎉 Broadcast Selesai! (${successCount} Berhasil, ${failCount} Gagal)`;
            appendLog(`🏁 Broadasting Selesai. Total Berhasil: ${successCount}, Total Gagal: ${failCount}`);
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    </script>
</div>
