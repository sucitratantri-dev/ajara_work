<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Symfony\Component\HttpFoundation\Response;
class EnsureRole { public function handle(Request $request, Closure $next, string ...$roles): Response { if (!$request->user()) return redirect()->route('login'); abort_unless(in_array($request->user()->role,$roles,true),403,'Akses tidak diizinkan.'); return $next($request); } }
