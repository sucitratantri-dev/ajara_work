<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\PublicationOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizerController extends Controller
{
    private const PACKAGES = [
        'Basic' => 150000,
        'Premium' => 300000,
        'Featured' => 500000,
    ];

    public function index()
    {
        $orders = auth()->user()
            ->publicationOrders()
            ->with('competition')
            ->latest()
            ->get();

        return view('organizer.dashboard', compact('orders'));
    }

    public function create()
    {
        return view('organizer.create', [
            'packages' => self::PACKAGES
        ]);
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'title' => 'required|string|max:180',
            'organizer' => 'required|string|max:180',
            'competition_category' => 'required|string|max:100',
            'competition_level' => 'required|string|max:50',
            'delivery_mode' => 'required|in:Online,Offline,Hybrid',
            'location' => 'nullable|string|max:180',
            'description' => 'required|string|max:10000',
            'requirements' => 'required|string|max:10000',
            'benefits' => 'nullable|string|max:5000',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after_or_equal:registration_start',
            'event_start' => 'nullable|date',
            'event_end' => 'nullable|date|after_or_equal:event_start',
            'contact' => 'required|string|max:180',
            'banner' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'package' => 'required|in:Basic,Premium,Featured',
        ]);

        $mode = $d['delivery_mode'];

        $banner = $r->file('banner')
            ->store('competition-posters', 'public');

        $competition = Competition::create([
            'title' => $d['title'],
            'organizer' => $d['organizer'],
            'category' => $d['competition_level'] === 'Internasional'
                ? 'Internasional'
                : ($d['competition_level'] === 'Nasional'
                    ? 'Nasional' : 'Kampus'),
            'competition_category' => $d['competition_category'],
            'competition_level' => $d['competition_level'],
            'mode' => $mode === 'Offline' ? 'Offline' : 'Online',
            'delivery_mode' => $mode,
            'location' => $d['location'] ?? ($mode === 'Online' ? 'Online' : null),
            'description' => $d['description'],
            'requirements' => $d['requirements'],
            'benefits' => $d['benefits'] ?? null,
            'registration_start' => $d['registration_start'],
            'registration_end' => $d['registration_end'],
            'event_start' => $d['event_start'] ?? null,
            'event_end' => $d['event_end'] ?? null,
            'contact' => $d['contact'],
            'banner' => $banner,
            'status' => 'draft',
        ]);

        PublicationOrder::create([
            'user_id' => auth()->id(),
            'competition_id' => $competition->id,
            'package' => $d['package'],
            'amount' => self::PACKAGES[$d['package']],
            'payment_status' => 'unpaid',
            'review_status' => 'pending',
        ]);

        return redirect()
            ->route('organizer.dashboard')
            ->with('success', 'Pengajuan berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    public function payment(PublicationOrder $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        abort_if(
            $order->payment_status === 'verified',
            403,
            'Pembayaran sudah diverifikasi.'
        );

        return view('organizer.payment', compact('order'));
    }

    public function receipt(PublicationOrder $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['user', 'competition']);

        return view('organizer.receipt', compact('order'));
    }

    public function submitPayment(Request $r, PublicationOrder $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        abort_if(
            $order->payment_status === 'verified',
            403,
            'Pembayaran sudah diverifikasi dan tidak dapat diubah.'
        );

        $d = $r->validate([
            'proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $oldPath = $order->proof_path;

        $path = $r->file('proof')
            ->store('payment-proofs', 'public');

        $order->update([
            'proof_path' => $path,
            'payment_status' => 'pending',
            'review_status' => 'pending',
            'paid_at' => now(),
            'admin_note' => null,
        ]);

        if ($oldPath && $oldPath !== $path) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()
            ->route('organizer.dashboard')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Tunggu verifikasi Admin AJARA.');
    }
}