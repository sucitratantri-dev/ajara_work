<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\PublicationOrder;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::where('role', 'mahasiswa')->count(),
            'organizers' => User::where('role', 'penyelenggara')->count(),
            'competitions' => Competition::count(),
            'pending' => PublicationOrder::where('payment_status', 'pending')->count(),
        ];

        $orders = PublicationOrder::with(['user', 'competition'])
            ->latest()
            ->take(100)
            ->get();

        return view('admin.dashboard', compact('stats', 'orders'));
    }

    public function review(Request $r, PublicationOrder $order)
    {
        $d = $r->validate([
            'decision' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $order->load(['user', 'competition']);

        abort_unless(
            $order->payment_status === 'pending' &&
            $order->review_status === 'pending',
            422,
            'Pengajuan ini sudah diproses atau belum menunggu verifikasi.'
        );

        abort_unless(
            $order->proof_path &&
            Storage::disk('public')->exists($order->proof_path),
            422,
            'Bukti transfer belum tersedia.'
        );

        $approved = $d['decision'] === 'approve';

        $message = $approved
            ? 'Pembayaran telah diverifikasi. Lomba berhasil dipublikasikan.'
            : 'Pengajuan pembayaran belum disetujui. Silakan periksa catatan Admin.';

        DB::transaction(function () use ($order, $d, $approved, $message) {

            $order->update([
                'payment_status' => $approved ? 'verified' : 'rejected',
                'review_status' => $approved ? 'approved' : 'rejected',
                'admin_note' => $d['admin_note'] ??
                    ($approved ? 'Disetujui Admin AJARA' : 'Mohon perbaiki pengajuan.'),
            ]);

            $order->competition->update([
                'status' => $approved ? 'published' : 'draft',
            ]);

            Notification::create([
                'user_id' => $order->user_id,
                'title' => $approved
                    ? 'Pembayaran berhasil diverifikasi'
                    : 'Pengajuan perlu diperbaiki',
                'message' => $message . ' Lomba: ' . $order->competition->title,
                'type' => $approved ? 'success' : 'warning',
            ]);
        });

        $emailSent = false;

        try {
            $recipient = $order->user->email;

            $subject = $approved
                ? 'Pembayaran AJARA berhasil diverifikasi'
                : 'Informasi revisi pengajuan AJARA';

            $body =
                "Halo {$order->user->name},\n\n" .
                "{$message}\n\n" .
                "Nama lomba: {$order->competition->title}\n" .
                "Paket: {$order->package}\n" .
                "Nominal: Rp" . number_format($order->amount, 0, ',', '.') . "\n" .
                "Catatan Admin: " . ($order->admin_note ?? '-') . "\n\n" .
                "Silakan masuk ke dashboard AJARA untuk melihat informasi terbaru.\n\n" .
                "Salam,\nTim AJARA\n" .
                "ajangjejaringprestasi@gmail.com";

            Mail::raw($body, function ($mail) use ($recipient, $subject) {
                $mail->to($recipient)->subject($subject);
            });

            $emailSent = true;

        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with(
            'success',
            $message . ($emailSent
                ? ' Email pemberitahuan berhasil dikirim.'
                : ' Namun email belum berhasil dikirim. Periksa konfigurasi SMTP.')
        );
    }
}