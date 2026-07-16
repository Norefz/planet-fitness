<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    // 1. UNTUK GUEST (Belum Login) — Batasi hanya muncul 3 program teratas
    public function guestIndex(Request $request)
    {
        $query = WorkoutProgram::where('status', 'published')->with('mentor');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Limit 3 untuk memancing pengunjung agar mendaftar
        $programs = $query->latest()->limit(3)->get();

        return view('member.program-latihan', compact('programs'));
    }


    // 2. UNTUK MEMBER (Sudah Login) — Tampilkan semua program tanpa batas
    public function index(Request $request)
    {
    $query = WorkoutProgram::where('status', 'published')->with('mentor');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $programs = $query->latest()->get();

        return view('member.program-latihan', compact('programs'));
    }

}
