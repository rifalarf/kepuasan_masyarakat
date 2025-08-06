<?php
{{-- filepath: resources/views/pages/public/kuesioner.blade.php --}}
@extends('layouts.public')

@section('title', 'Formulir Survei Kepuasan Masyarakat')

@section('content')
    <section class="bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto grid min-h-screen max-w-screen-xl px-4 py-8 lg:grid-cols-12 lg:gap-8 lg:py-16">
            <div class="col-span-12">
                {{-- Menampilkan komponen berdasarkan langkah survei --}}
                @if ($step == 1)
                    @include('pages.public.components.personal-info')
                @elseif ($step == 2)
                    @include('pages.public.components.kuesioner-questions')
                @elseif ($step == 3)
                    @include('pages.public.components.feedback')
                @endif
            </div>
        </div>
    </section>
@endsection

