<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4">
                    <form method="GET" action="{{ route('user.index') }}" class="flex space-x-2" id="searchForm">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..."
                            class="px-4 py-2 flex-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            id="searchInput" autofocus>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Cari
                        </button>
                    </form>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Id</th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="hidden px-6 py-3 md:block">Email</th>
                                <th scope="col" class="px-6 py-3">Todo</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $data)
                                <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                                        {{ $data->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $data->name }}
                                    </td>
                                    <td class="hidden px-6 py-4 md:block">
                                        {{ $data->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p>
                                            {{ $data->todos->count() }}
                                            <span>
                                                <span class="text-green-600 dark:text-green-400">
                                                    ({{ $data->todos->where('is_done', true)->count() }})
                                                </span> /
                                                <span class="text-blue-600 dark:text-blue-400">
                                                    {{ $data->todos->where('is_done', false)->count() }}
                                                </span>
                                            </span>
                                        </p>
                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            @empty
                                <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Tambahan pagination -->
                <div class="px-6 py-5">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Submit form secara otomatis saat ada inputan
        document.getElementById('searchInput').addEventListener('input', function() {
            document.getElementById('searchForm').submit();
        });

        // Menangani masalah kursor yang kembali ke awal setelah submit
        window.addEventListener('load', function() {
            setTimeout(function() {
                const searchInput = document.getElementById('searchInput');
                searchInput.focus(); // Fokuskan input terlebih dahulu
                const length = searchInput.value.length;
                searchInput.setSelectionRange(length, length); // Set posisi kursor di akhir teks
            }, 10); // Timeout yang sangat singkat agar fokus dan posisi kursor diatur dengan cepat
        });
    </script>
</x-app-layout>
