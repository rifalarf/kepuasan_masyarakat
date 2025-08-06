<div
    class="flex basis-full flex-col space-y-5 rounded-lg border border-gray-200 bg-white px-5 py-5 shadow dark:border-gray-700 dark:bg-gray-800">
    <h5 class="mb-5 text-center text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
        Langkah 1: Data Responden
    </h5>
    <form action="{{ route('survey.store.personal_info') }}" method="POST" id="personal-info-form">
        @csrf
        {{-- Email dan OTP --}}
        <div class="mb-5">
            <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Alamat Email</label>
            <div class="flex items-center space-x-2">
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="nama@email.com" required>
                <button type="button" id="send-otp-btn"
                    class="whitespace-nowrap rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                    Kirim OTP
                </button>
            </div>
            @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div id="otp-container" class="mb-5 hidden">
            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kode Verifikasi (OTP)</label>
            <div class="flex justify-center space-x-2" dir="ltr">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" name="otp[]" maxlength="1" disabled
                        class="otp-input block h-12 w-12 rounded-lg border border-gray-300 bg-gray-50 text-center text-2xl text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                @endfor
            </div>
            <div id="otp-status" class="mt-2 text-center"></div>
            @error('otp')<p class="mt-2 text-center text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Data Demografis --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="mb-5">
                <label for="genders" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                <select id="genders" name="gender" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm" required>
                    <option value="" hidden>-Pilih-</option>
                    @foreach ($genders as $item)
                        <option value="{{ $item->value }}" {{ old('gender') == $item->value ? 'selected' : '' }}>{{ $item->label }}</option>
                    @endforeach
                </select>
                @error('gender')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="mb-5">
                <label for="age" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Umur</label>
                <input type="number" id="age" name="age" value="{{ old('age') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm" required min="1">
                @error('age')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-5">
            <label for="educations" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Pendidikan</label>
            <select id="educations" name="education" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm" required>
                <option value="" hidden>-Pilih-</option>
                @foreach ($educations as $item)
                    <option value="{{ $item->value }}" {{ old('education') == $item->value ? 'selected' : '' }}>{{ $item->label }}</option>
                @endforeach
            </select>
            @error('education')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5">
            <label for="jobs" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Pekerjaan</label>
            <select id="jobs" name="job" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm" required>
                <option value="" hidden>-Pilih-</option>
                @foreach ($jobs as $item)
                    <option value="{{ $item->value }}" {{ old('job') == $item->value ? 'selected' : '' }}>{{ $item->label }}</option>
                @endforeach
            </select>
            @error('job')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5">
            <label for="domiciles" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tempat Tinggal</label>
            <select id="domiciles" name="domicile" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm" required>
                <option value="" hidden>-Pilih-</option>
                @foreach ($domiciles as $item)
                    <option value="{{ $item->value }}" {{ old('domicile') == $item->value ? 'selected' : '' }}>{{ $item->label }}</option>
                @endforeach
            </select>
            @error('domicile')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="pt-5 text-center">
            <x-button.submit id="submit-personal-info" text="Lanjut ke Pilih Unit Layanan" />
        </div>
    </form>
</div>
