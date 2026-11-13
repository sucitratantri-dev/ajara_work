@extends('layouts.app')
@section('title','Struk Pembayaran AJARA')
@section('content')
<main class="min-h-screen bg-slate-50 px-4 py-10"><article class="mx-auto max-w-2xl rounded-3xl bg-white p-7 shadow-xl print:shadow-none">
<div class="flex items-start justify-between gap-4 border-b border-dashed border-slate-300 pb-6"><div><p class="text-xs font-extrabold tracking-[.2em] text-ajara-gold">BUKTI PENGAJUAN</p><h1 class="mt-2 text-3xl font-extrabold text-ajara-teal">AJARA</h1><p class="text-sm text-slate-500">Ajang & Jejaring Prestasi Mahasiswa</p></div><span class="rounded-full bg-amber-100 px-3 py-2 text-xs font-bold text-amber-900">{{ strtoupper($order->payment_status) }}</span></div>
<h2 class="mt-6 text-xl font-extrabold text-slate-900">Struk pembayaran publikasi</h2>
<dl class="mt-5 space-y-3 text-sm">
<div class="flex justify-between gap-4"><dt class="text-slate-500">Nomor transaksi</dt><dd class="font-bold text-slate-900">AJR-{{ str_pad($order->id,6,'0',STR_PAD_LEFT) }}</dd></div>
<div class="flex justify-between gap-4"><dt class="text-slate-500">Penyelenggara</dt><dd class="font-semibold text-slate-900">{{ $order->user->name }}</dd></div>
<div class="flex justify-between gap-4"><dt class="text-slate-500">Nama lomba</dt><dd class="text-right font-semibold text-slate-900">{{ $order->competition->title }}</dd></div>
<div class="flex justify-between gap-4"><dt class="text-slate-500">Paket publikasi</dt><dd class="font-semibold text-slate-900">{{ $order->package }}</dd></div>
<div class="flex justify-between gap-4"><dt class="text-slate-500">Tanggal pengajuan</dt><dd class="font-semibold text-slate-900">{{ $order->created_at?->format('d M Y H:i') }}</dd></div>
<div class="flex justify-between gap-4 border-t border-dashed pt-4"><dt class="font-bold text-slate-700">Total</dt><dd class="text-xl font-extrabold text-ajara-red">Rp{{ number_format($order->amount,0,',','.') }}</dd></div></dl>
<p class="mt-6 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">Struk ini merupakan bukti pengajuan pembayaran, bukan bukti bahwa pembayaran telah diverifikasi. Status resmi mengikuti pemeriksaan Admin AJARA.</p>
<div class="mt-6 flex flex-wrap gap-3 print:hidden"><button onclick="window.print()" class="btn-primary rounded-xl px-5 py-3 font-bold">Cetak / Simpan PDF</button><a href="{{ route('organizer.dashboard') }}" class="rounded-xl border px-5 py-3 font-bold text-ajara-teal">Kembali</a></div>
</article></main>
@endsection
