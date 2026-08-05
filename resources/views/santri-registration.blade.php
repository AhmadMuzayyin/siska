<x-layouts::public :title="__('Pendaftaran Santri Baru')">
    <div class="flex flex-col w-full overflow-hidden">
        {{-- Hero Banner --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30">
            <img
                src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1400&q=80&auto=format&fit=crop"
                alt="Pendaftaran Santri Al-Hikmah"
                class="absolute inset-0 size-full object-cover opacity-20"
                loading="eager"
                width="1400" height="400"
            >
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('PPDB Online 2026/2027') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!">
                    {{ __('Formulir Pendaftaran Santri Baru') }}
                </flux:heading>
                <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                    {{ __('Lengkapi formulir pendaftaran di bawah ini. Data calon santri akan ditinjau dan dikonfirmasi oleh pengurus lembaga.') }}
                </p>
            </div>
        </section>

        {{-- Form Registration Section (Soft Jade Mist Theme #edf7f4) --}}
        <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
            <div class="mx-auto max-w-4xl px-6">
                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-xl backdrop-blur-sm">
                    @if (session('status'))
                        <flux:callout variant="success" class="mb-6" icon="check-circle" text="{{ session('status') }}" />
                    @endif

                    <form method="POST" action="{{ route('santri.register') }}" class="flex flex-col gap-6">
                        @csrf

                        <div class="border-b border-emerald-100 pb-4">
                            <flux:heading size="sm" class="text-emerald-950 text-base font-bold">{{ __('Data Pokok Calon Santri') }}</flux:heading>
                            <p class="text-xs text-zinc-500 mt-0.5">{{ __('Informasi identitas dan kelas yang dituju.') }}</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:select name="lembaga_id" :label="__('Unit Lembaga Tujuan')">
                                    @foreach ($lembagas as $lembaga)
                                        <flux:select.option value="{{ $lembaga->id }}" :selected="old('lembaga_id') == $lembaga->id">{{ $lembaga->nama }} ({{ $lembaga->jenjang }})</flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="lembaga_id" />
                            </flux:field>

                            <flux:field>
                                <flux:select name="kelas_id" :label="__('Kelas Tujuan')">
                                    @foreach ($kelasList as $kelas)
                                        <flux:select.option value="{{ $kelas->id }}" :selected="old('kelas_id') == $kelas->id">{{ $kelas->nama }} @if($kelas->lembaga) ({{ $kelas->lembaga->jenjang }}) @endif</flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="kelas_id" />
                            </flux:field>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:input name="noinduk" :label="__('No. Induk')" :value="old('noinduk')" />
                                <flux:error name="noinduk" />
                            </flux:field>
                            <flux:field>
                                <flux:input name="nama_lengkap" :label="__('Nama Lengkap')" :value="old('nama_lengkap')" />
                                <flux:error name="nama_lengkap" />
                            </flux:field>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:input name="nama_panggilan" :label="__('Nama Panggilan')" :value="old('nama_panggilan')" />
                                <flux:error name="nama_panggilan" />
                            </flux:field>
                            <flux:field>
                                <flux:select name="jenis_kelamin" :label="__('Jenis Kelamin')">
                                    @foreach ($genders as $gender)
                                        <flux:select.option value="{{ $gender->value }}" :selected="old('jenis_kelamin') === $gender->value">
                                            {{ $gender->value === 'laki_laki' ? __('Laki-laki') : __('Perempuan') }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="jenis_kelamin" />
                            </flux:field>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:input name="tempat_lahir" :label="__('Tempat Lahir')" :value="old('tempat_lahir')" />
                                <flux:error name="tempat_lahir" />
                            </flux:field>
                            <flux:field>
                                <flux:input name="tanggal_lahir" type="date" :label="__('Tanggal Lahir')" :value="old('tanggal_lahir')" />
                                <flux:error name="tanggal_lahir" />
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:input name="anak_ke" type="number" min="1" :label="__('Anak Ke-')" :value="old('anak_ke', 1)" />
                            <flux:error name="anak_ke" />
                        </flux:field>

                        <flux:field>
                            <flux:textarea name="alamat" :label="__('Alamat')" rows="2">{{ old('alamat') }}</flux:textarea>
                            <flux:error name="alamat" />
                        </flux:field>

                        <flux:field>
                            <flux:input name="telepon_wali" :label="__('No. WhatsApp Wali')" placeholder="08xxxxxxxxxx" :value="old('telepon_wali')" />
                            <flux:error name="telepon_wali" />
                        </flux:field>

                        <div class="border-t border-b border-emerald-100 py-4 my-2">
                            <flux:heading size="sm" class="text-emerald-950 text-base font-bold">{{ __('Data Orang Tua / Wali') }}</flux:heading>
                            <p class="text-xs text-zinc-500 mt-0.5">{{ __('Informasi identitas ayah dan ibu santri.') }}</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <flux:field>
                                <flux:input name="nama_ayah" :label="__('Nama Ayah')" :value="old('nama_ayah')" />
                                <flux:error name="nama_ayah" />
                            </flux:field>
                            <flux:field>
                                <flux:input name="pendidikan_ayah" :label="__('Pendidikan')" :value="old('pendidikan_ayah')" />
                                <flux:error name="pendidikan_ayah" />
                            </flux:field>
                            <flux:field>
                                <flux:input name="pekerjaan_ayah" :label="__('Pekerjaan')" :value="old('pekerjaan_ayah')" />
                                <flux:error name="pekerjaan_ayah" />
                            </flux:field>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <flux:field>
                                <flux:input name="nama_ibu" :label="__('Nama Ibu')" :value="old('nama_ibu')" />
                                <flux:error name="nama_ibu" />
                            </flux:field>
                            <flux:field>
                                <flux:input name="pendidikan_ibu" :label="__('Pendidikan')" :value="old('pendidikan_ibu')" />
                                <flux:error name="pendidikan_ibu" />
                            </flux:field>
                            <flux:field>
                                <flux:input name="pekerjaan_ibu" :label="__('Pekerjaan')" :value="old('pekerjaan_ibu')" />
                                <flux:error name="pekerjaan_ibu" />
                            </flux:field>
                        </div>

                        <div class="flex justify-end pt-4">
                            <flux:button type="submit" variant="primary" class="bg-emerald-700! hover:bg-emerald-800! text-white! font-extrabold px-8 py-3.5 shadow-xl text-sm">
                                <flux:icon name="user-plus" class="size-4 me-2" />
                                {{ __('Kirim Pendaftaran Santri Baru') }}
                            </flux:button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-layouts::public>
