@extends('layouts.app')

@section('title', 'Dashboard Penyelenggara')

@section('content')
<main class="min-h-screen bg-slate-50 py-10">
<div class="mx-auto max-w-6xl px-4">

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold text-ajara-red">RUANG PENYELENGGARA</p>
            <h1 class="mt-1 text-3xl font-extrabold text-ajara-teal">
                Dashboard Publikasi
            </h1>
            <p class="mt-2 text-slate-600">
                Kelola lomba, pembayaran, dan proses verifikasi.
            </p>
        </div>

        <a href="{{ route('organizer.create') }}"
           class="btn-primary rounded-xl px-5 py-3 font-bold text-white">
            + Ajukan Lomba
        </a>
    </div>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        @foreach([
            ['Total Pengajuan', $orders->count()],
            ['Belum Bayar', $orders->where('payment_status','unpaid')->count()],
            ['Menunggu Verifikasi', $orders->where('payment_status','pending')->count()],
            ['Disetujui', $orders->where('payment_status','verified')->count()]
        ] as [$label, $value])

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-extrabold text-ajara-teal">{{ $value }}</p>
        </div>

        @endforeach

    </div>

    <section class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm">

        <div class="border-b p-5">
            <h2 class="text-lg font-extrabold text-ajara-teal">
                Riwayat Pengajuan Publikasi
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Pantau status pembayaran dan persetujuan Admin.
            </p>
        </div>

        <div class="divide-y">

            @forelse($orders as $o)

            <div class="flex flex-col justify-between gap-5 p-5 md:flex-row md:items-center">

                <div class="min-w-0">
                    <h3 class="font-bold text-slate-900">
                        {{ $o->competition->title }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-600">
                        Paket {{ $o->package }}
                        · Rp{{ number_format($o->amount,0,',','.') }}
                    </p>

                    <p class="mt-2 text-sm">
                        Status pembayaran:

                        @if($o->payment_status === 'verified')
                            <span class="font-bold text-emerald-700">Terverifikasi</span>
                        @elseif($o->payment_status === 'pending')
                            <span class="font-bold text-amber-700">Menunggu Admin</span>
                        @elseif($o->payment_status === 'rejected')
                            <span class="font-bold text-red-700">Ditolak</span>
                        @else
                            <span class="font-bold text-slate-600">Belum dibayar</span>
                        @endif
                    </p>

                    <p class="mt-1 text-sm text-slate-600">
                        Status publikasi:
                        <strong>{{ ucfirst($o->review_status) }}</strong>
                    </p>

                    @if($o->admin_note)
                        <div class="mt-3 rounded-lg bg-red-50 p-3 text-sm text-red-800">
                            <strong>Catatan Admin:</strong>
                            {{ $o->admin_note }}
                        </div>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">

                    <a href="{{ route('organizer.payment.receipt',$o) }}"
                       class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-bold text-ajara-teal hover:bg-slate-50">
                        Lihat Struk
                    </a>

                    @if(in_array($o->payment_status, ['unpaid','rejected']))
                        <a href="{{ route('organizer.payment',$o) }}"
                           class="rounded-xl bg-ajara-teal px-4 py-2 text-sm font-bold text-white hover:opacity-90">
                            Bayar Sekarang
                        </a>
                    @elseif($o->payment_status === 'pending')
                        <span class="rounded-xl bg-amber-100 px-4 py-2 text-sm font-bold text-amber-800">
                            Menunggu Verifikasi
                        </span>
                    @else
                        <span class="rounded-xl bg-emerald-100 px-4 py-2 text-sm font-bold text-emerald-800">
                            Lomba Dipublikasikan
                        </span>
                    @endif

                </div>

            </div>

            @empty

            <div class="p-12 text-center">
                <p class="text-lg font-bold text-slate-700">
                    Belum ada pengajuan lomba.
                </p>
                <p class="mt-2 text-sm text-slate-500">
                    Mulai publikasikan lombamu melalui AJARA.
                </p>
                <a href="{{ route('organizer.create') }}"
                   class="mt-5 inline-block rounded-xl bg-ajara-teal px-5 py-3 font-bold text-white">
                    Buat Pengajuan
                </a>
            </div>

            @endforelse

        </div>
    </section>
</div>
</main>
@endsection