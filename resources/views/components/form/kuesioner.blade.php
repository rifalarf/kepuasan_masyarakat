<div class="flex basis-full flex-col items-center space-y-12 rounded-lg border border-gray-200 bg-white px-5 py-20 text-center shadow dark:border-gray-700 dark:bg-gray-800">
	<div class="flex w-full justify-center">
		<x-link.button :href="$previous" icon="chevron-left" :disabled="$previous === '#' ? true : false" />
		<x-link.button href="" :text="$question . ' / ' . $totalKuesioner" class="px-4" />
		<x-link.button :href="$next" icon="chevron-right" :disabled="$next === '#' ? true : false" />
	</div>
	<h5 class="max-w-3xl text-2xl font-medium tracking-tight text-gray-900 dark:text-white">{{ $kuesioner->question }}
	</h5>
	@php
		for ($i=0; $i < $totalKuesioner; $i++) { 
      if (!isset($data['question'.$i+1])) {
        $selected[$i+1] = 0;
      } else {
        $selected[$i+1] = $data['question'.$i+1];
      }
    }
	@endphp
	<div class="flex space-x-5">
		@for ($i = 1; $i <= 4; $i++)
			<?php
			  $opacityClass = $selected[$question] == $i ? '' : 'opacity-100';
			?>
			<a href="{{ route('kuesioner', [...$data, ...['question' . $question => $i]]) }}" data-tooltip-target="rate{{ $i }}" data-tooltip-style="light" data-tooltip-placement="bottom" class="{{ $opacityClass }} transform transition duration-100 hover:scale-125 hover:opacity-100">
				<img src="{{ asset('assets/' . $i . '.svg') }}" class="h-20 w-20">
			</a>
			<div id="rate{{ $i }}" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 opacity-0 shadow-sm">
				{{ rateLabel($i) }}
				<div class="tooltip-arrow" data-popper-arrow></div>
			</div>
		@endfor
	</div>
</div>

<?php
// ...existing code...
        <div class="mb-5">
            <label for="villages" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan
                Kerja</label>
            <select id="villages" name="village"
                class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                required>
                <option value="" hidden>-Pilih-</option>
                @foreach ($villages as $item)
                    <option value="{{ $item->id }}" {{ old('village') == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}</option>
                @endforeach
            </select>
            {{-- Elemen untuk menampilkan status ketersediaan --}}
            <div id="village-status" class="mt-2 text-sm"></div>
            @error('village')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama
                Lengkap</label>
            <input type="text" id="name" name="name"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
            @error('name')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="phone" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nomor
                Telepon</label>
            <input type="tel" id="phone" name="phone"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                placeholder="Masukkan nomor telepon" value="{{ old('phone') }}" required>
            @error('phone')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Alamat
                Email</label>
            <input type="email" id="email" name="email"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                placeholder="Masukkan alamat email" value="{{ old('email') }}" required>
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kata
                Sandi</label>
            <input type="password" id="password" name="password"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                placeholder="Masukkan kata sandi" required>
            @error('password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Konfirmasi
                Kata Sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                placeholder="Konfirmasi kata sandi" required>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="terms" aria-describedby="terms" type="checkbox" name="terms"
                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                    required>
                <label for="terms" class="ml-2 block text-sm text-gray-900 dark:text-white">
                    Saya setuju dengan <a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">syarat
                        dan ketentuan</a>
                </label>
            </div>
        </div>

        <button type="submit"
            class="mt-4 w-full rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white shadow-md transition-all duration-200 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-700">
            Daftar
        </button>

        <p class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Masuk
            </a>
        </p>
    </form>
</div>
