<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Mentor;

class MentorObserver
{
    public function created(Mentor $mentor): void
    {
        AuditLog::record(
            action:      'mentor_registered',
            details:     "Mentor baru mendaftar: {$mentor->full_name} (spesialisasi: {$mentor->specialization}).",
            targetTable: 'mentors',
            targetId:    $mentor->id,
        );
    }

    public function updated(Mentor $mentor): void
    {
        // Verifikasi mentor oleh admin
        if ($mentor->wasChanged('is_verified')) {
            $status = $mentor->is_verified ? 'diverifikasi' : 'dibatalkan verifikasinya';
            AuditLog::record(
                action:      $mentor->is_verified ? 'mentor_verified' : 'mentor_unverified',
                details:     "Akun Mentor {$mentor->full_name} {$status} oleh admin.",
                targetTable: 'mentors',
                targetId:    $mentor->id,
            );
            return;
        }

        // Update profil biasa
        AuditLog::record(
            action:      'mentor_profile_updated',
            details:     "Mentor {$mentor->full_name} memperbarui profilnya.",
            targetTable: 'mentors',
            targetId:    $mentor->id,
        );
    }

    public function deleted(Mentor $mentor): void
    {
        AuditLog::record(
            action:      'mentor_deleted',
            details:     "Akun Mentor {$mentor->full_name} dihapus dari sistem.",
            targetTable: 'mentors',
            targetId:    $mentor->id,
        );
    }
}
