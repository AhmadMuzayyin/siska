<form action="" method="post">
    @csrf
    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($santris as $santri)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                                name="selected_santris[]" value="{{ $santri->id }}">
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $santri->nama_lengkap }}</td>
                        <td>{{ $santri->noinduk }}</td>
                        <td>{{ $santri->kelas->nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>
