<?php
namespace App\Http\Controllers;
use App\Models\ChatMessage; use App\Models\User; use Illuminate\Http\Request;
class ChatController extends Controller {
 public function index(Request $r){$admin=$r->user()->role==='admin';$target=$admin&&$r->filled('user')?User::findOrFail($r->integer('user')):$r->user();$users=$admin?User::whereHas('chatMessages')->orderBy('name')->get():collect();$messages=$target->chatMessages()->latest()->take(100)->get()->reverse();return view('chat.index',['messages'=>$messages,'users'=>$users,'selectedUser'=>$target,'isAdmin'=>$admin]);}
 public function store(Request $r){$d=$r->validate(['message'=>'required|string|max:3000']);$admin=$r->user()->role==='admin';$id=$admin?$r->integer('user_id'):$r->user()->id;abort_unless($id&&User::whereKey($id)->exists(),422);ChatMessage::create(['user_id'=>$id,'sender_role'=>$admin?'admin':'user','message'=>$d['message']]);return redirect()->route('chat.index',$admin?['user'=>$id]:[]);}
}
