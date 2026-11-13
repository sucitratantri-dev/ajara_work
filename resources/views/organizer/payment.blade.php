@extends('layouts.app')

@section('title', 'Pembayaran Publikasi AJARA')

@section('content')
<main class="min-h-screen bg-slate-50 px-4 py-10">

<div class="mx-auto max-w-3xl">

    <a href="{{ route('organizer.dashboard') }}"
       class="text-sm font-bold text-ajara-teal">
        ← Kembali ke Dashboard
    </a>

    <div class="mt-6 overflow-hidden rounded-3xl bg-white shadow-xl">

        <div class="bg-ajara-teal p-7 text-white">
            <p class="text-sm font-bold tracking-widest text-white/80">
                AJARA PAYMENT CENTER
            </p>
            <h1 class="mt-2 text-3xl font-extrabold">
                Pembayaran Publikasi
            </h1>
            <p class="mt-2 text-sm text-white/80">
                Selesaikan pembayaran untuk mengajukan publikasi lomba.
            </p>
        </div>

        <div class="p-6 sm:p-8">

            @if(session('success'))
                <div class="mb-5 rounded-xl bg-emerald-50 p-4 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 p-4 text-red-700">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-sm text-slate-500">Nama lomba</p>
                <h2 class="mt-1 text-lg font-extrabold text-slate-900">
                    {{ $order->competition->title }}
                </h2>

                <div class="mt-4 flex justify-between gap-3">
                    <span class="text-sm text-slate-500">Paket publikasi</span>
                    <strong class="text-slate-900">{{ $order->package }}</strong>
                </div>

                <div class="mt-3 flex justify-between gap-3 border-t pt-4">
                    <span class="font-bold text-slate-700">Total pembayaran</span>
                    <strong class="text-2xl font-extrabold text-ajara-teal">
                        Rp{{ number_format($order->amount,0,',','.') }}
                    </strong>
                </div>
            </div>

            <div class="mt-8 text-center">
                <h2 class="text-xl font-extrabold text-slate-900">
                    Scan QRIS AJARA
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Gunakan aplikasi pembayaran yang mendukung QRIS.
                </p>

                <div class="mx-auto mt-5 max-w-sm rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <img
                        src="{{ asset('images/ajara/QRIS.jpg') }}"
                        alt="QRIS resmi pembayaran AJARA"
                        class="mx-auto h-auto w-full rounded-xl object-contain"
                    >
                </div>

                <p class="mt-4 text-xs leading-6 text-slate-500">
                    Pastikan nominal pembayaran sesuai dengan paket yang dipilih.
                    Simpan bukti transaksi untuk diunggah di bawah.
                </p>
            </div>

            <div class="my-8 border-t border-slate-200"></div>

            <form
                method="POST"
                action="{{ route('organizer.payment.submit',$order) }}"
                enctype="multipart/form-data"
                class="space-y-5"
            >
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-800">
                        Upload Bukti Pembayaran
                    </label>

                    <input
                        type="file"
                        name="proof"
                        accept=".jpg,.jpeg,.png,.webp"
                        required
                        class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800 file:mr-4 file:rounded-lg file:border-0 file:bg-ajara-teal file:px-4 file:py-2 file:font-bold file:text-white"
                    >

                    <p class="mt-2 text-xs text-slate-500">
                        Format JPG, JPEG, PNG, WEBP. Maksimal 5 MB.
                    </p>

                    @error('proof')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                    <strong>Informasi penting:</strong>
                    Setelah bukti dikirim, Admin AJARA akan memeriksa pembayaran.
                    Lomba baru akan dipublikasikan setelah pembayaran disetujui.
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-ajara-teal px-5 py-4 font-extrabold text-white shadow-lg transition hover:-translate-y-0.5 hover:opacity-90"
                >
                    Kirim Bukti Pembayaran
                </button>

            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('organizer.payment.receipt',$order) }}"
                   class="text-sm font-bold text-ajara-teal underline">
                    Lihat Struk Pengajuan
                </a>
            </div>

        </div>
    </div>

    <p class="mt-6 text-center text-sm text-slate-500">
        Bantuan pembayaran:
        <a href="mailto:ajangjejaringprestasi@gmail.com"
           class="font-bold text-ajara-teal">
            ajangjejaringprestasi@gmail.com
        </a>
    </p>

</div>
</main>
@endsection