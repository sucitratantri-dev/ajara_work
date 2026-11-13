<?php
namespace App\Http\Controllers;
use App\Models\Competition; use App\Models\PublicationOrder; use App\Models\User; use Illuminate\Http\Request; use Illuminate\Support\Facades\Mail;
class AdminController extends Controller {
 public function index(){$stats=['users'=>User::where('role','mahasiswa')->count(),'organizers'=>User::where('role','penyelenggara')->count(),'competitions'=>Competition::count(),'pending'=>PublicationOrder::where('payment_status','pending')->count()];$orders=PublicationOrder::with(['user','competition'])->latest()->take(50)->get();return view('admin.dashboard',compact('stats','orders'));}
 public function review(Request $r,PublicationOrder $order){$d=$r->validate(['decision'=>'required|in:approve,reject','admin_note'=>'nullable|string|max:2000']);if($d['decision']==='approve'){abort_unless($order->proof_path,422,'Bukti transfer belum tersedia.');$order->update(['payment_status'=>'verified','review_status'=>'approved','admin_note'=>$d['admin_note']??'Disetujui Admin']);$order->competition->update(['status'=>'published']);$msg='Pembayaran diverifikasi dan lomba dipublikasikan.';}else{$order->update(['payment_status'=>'rejected','review_status'=>'rejected','admin_note'=>$d['admin_note']??'Mohon perbaiki pengajuan']);$order->competition->update(['status'=>'draft']);$msg='Pengajuan ditolak.';}try {
   $recipient = $order->user->email;
   $subject = $d['decision'] === 'approve' ? 'Pengajuan AJARA disetujui' : 'Pengajuan AJARA perlu diperbaiki';
   $body = "Halo {$order->user->name},\n\n{$msg}\nLomba: {$order->competition->title}\nPaket: {$order->package}\nCatatan Admin: ".($order->admin_note ?? '-')."\n\nSilakan masuk ke dashboard AJARA untuk melihat status terbaru.\nTim AJARA";
   Mail::raw($body, function ($mail) use ($recipient, $subject) { $mail->to($recipient)->subject($subject); });
 } catch (\Throwable $e) {
   report($e); // Email tidak boleh menggagalkan proses verifikasi di dashboard.
 }
 return back()->with('success',$msg . ' Status email akan dikirim jika SMTP sudah dikonfigurasi.');}
}
