<div class="flex justify-end">
    {{ $action }}
</div>
<div class="mt-4">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">No</th>
                <th scope="col" class="px-6 py-3">Nama Semester</th>
                <th scope="col" class="px-6 py-3">Status</th>
                <th scope="col" class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($semesters as $index => $semester)
                <tr class="bg-white border-b">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">{{ $semester->tipe }}</td>
                    <td class="px-6 py-4">
                        @if ($semester->is_aktif)
                            <span class="px-2 py-1 text-xs font-semibold text-success bg-success-100 rounded-full">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold text-danger bg-danger-100 rounded-full">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900">
                                Edit
                            </button>
                            <form action="{{ route('semester.destroy', $semester->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center">Tidak ada data semester</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
