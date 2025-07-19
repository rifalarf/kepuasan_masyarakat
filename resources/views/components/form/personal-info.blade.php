<div
    class="flex basis-full flex-col space-y-5 rounded-lg border border-gray-200 bg-white px-5 py-5 shadow dark:border-gray-700 dark:bg-gray-800">
    <h5 class="mb-5 text-center text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
        Data Diri
    </h5>
    {{-- Pastikan form memiliki id="personal-info-form" --}}
    <form action="{{ route('kuesioner') }}" method="GET" id="personal-info-form">
        <input type="hidden" name="step" value="2">
        <input type="hidden" name="question" value="1">
        <div class="mb-5">
            <label for="genders" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Jenis
                Kelamin</label>
            <select id="genders" name="gender"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                <option value="" hidden>-Pilih-</option>
                @foreach ($genders as $item)
                    <option value="{{ $item->value }}" {{ old('gender') == $item->value ? 'selected' : '' }}>
                        {{ $item->label }}</option>
                @endforeach
            </select>
            @error('gender')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="age" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Umur</label>
            <input type="text" id="age" name="age" value="{{ old('age') }}"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
            @error('age')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="educations"
                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Pendidikan</label>
            <select id="educations" name="education"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                <option value="" hidden>-Pilih-</option>
                @foreach ($educations as $item)
                    <option value="{{ $item->value }}" {{ old('education') == $item->value ? 'selected' : '' }}>
                        {{ $item->label }}</option>
                @endforeach
            </select>
            @error('education')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="jobs" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Pekerjaan</label>
            <select id="jobs" name="job"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                <option value="" hidden>-Pilih-</option>
                @foreach ($jobs as $item)
                    <option value="{{ $item->value }}" {{ old('job') == $item->value ? 'selected' : '' }}>
                        {{ $item->label }}</option>
                @endforeach
            </select>
            @error('job')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="villages" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan
                Kerja</label>
            <select id="villages" name="village"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                <option value="" hidden>-Pilih-</option>
                @foreach ($villages as $item)
                    <option value="{{ $item->id }}" {{ old('village') == $item->id ? 'selected' : '' }}>
                        {{ $item->village }}</option>
                @endforeach
            </select>
            @error('village')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- TAMBAHKAN BLOK INI --}}
        <div class="mb-5">
            <label for="domiciles" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tempat
                Tinggal</label>
            <select id="domiciles" name="domicile"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                <option value="" hidden>-Pilih-</option>
                @foreach ($domiciles as $item)
                    <option value="{{ $item->value }}" {{ old('domicile') == $item->value ? 'selected' : '' }}>
                        {{ $item->label }}</option>
                @endforeach
            </select>
            @error('domicile')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
        {{-- AKHIR BLOK --}}

        <div class="mb-5">
            {{-- Pastikan label for dan input id adalah "survey-email" --}}
            <label for="survey-email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Alamat
                Email</label>
            <div class="flex items-center">
                <input type="email" id="survey-email" name="email" value="{{ old('email') }}"
                    class="block w-full rounded-l-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                    placeholder="nama@email.com" required>
                {{-- Pastikan tombol memiliki id="send-otp-btn" dan type="button" --}}
                <button type="button" id="send-otp-btn"
                    class="whitespace-nowrap rounded-r-lg border border-blue-700 bg-blue-700 p-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:cursor-not-allowed disabled:bg-blue-400 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Kirim OTP
                </button>
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div id="otp-container" class="mb-5 hidden">
            <label for="otp" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kode
                Verifikasi</label>
            <div class="flex items-center space-x-2" id="otp-inputs">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" name="otp[]" maxlength="1"
                        class="otp-input h-12 w-12 rounded-lg border border-gray-300 bg-gray-50 text-center text-2xl uppercase"
                        disabled />
                @endfor
            </div>
            <div id="otp-status" class="mt-2 text-sm font-medium"></div>
        </div>

        <div class="mb-5 text-right">
            <x-button.submit text="Selanjutnya" id="submit-personal-info" disabled />
        </div>
    </form>
</div>
