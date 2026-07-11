<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Booking;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        $memberName = $booking->member?->full_name ?? 'Unknown';
        $mentorName = $booking->mentor?->full_name ?? 'Unknown';

        AuditLog::record(
            action:      'booking_created',
            details:     "Member {$memberName} membuat booking konsultasi dengan Mentor {$mentorName} pada {$booking->scheduled_at?->format('d M Y H:i')}.",
            targetTable: 'bookings',
            targetId:    $booking->id,
        );
    }

    public function updated(Booking $booking): void
    {
        if (! $booking->wasChanged('status')) {
            return;
        }

        $old        = $booking->getOriginal('status');
        $new        = $booking->status;
        $memberName = $booking->member?->full_name ?? 'Unknown';
        $mentorName = $booking->mentor?->full_name ?? 'Unknown';

        $actionMap = [
            'confirmed'  => 'booking_confirmed',
            'cancelled'  => 'booking_cancelled',
            'completed'  => 'booking_completed',
        ];

        $labelMap = [
            'confirmed'  => "Booking Member {$memberName} dengan Mentor {$mentorName} dikonfirmasi.",
            'cancelled'  => "Booking Member {$memberName} dengan Mentor {$mentorName} dibatalkan.",
            'completed'  => "Sesi konsultasi Member {$memberName} dengan Mentor {$mentorName} selesai.",
        ];

        AuditLog::record(
            action:      $actionMap[$new] ?? 'booking_status_changed',
            details:     $labelMap[$new] ?? "Status booking berubah dari {$old} → {$new}.",
            targetTable: 'bookings',
            targetId:    $booking->id,
        );
    }

    public function deleted(Booking $booking): void
    {
        $memberName = $booking->member?->full_name ?? 'Unknown';
        AuditLog::record(
            action:      'booking_deleted',
            details:     "Booking dari Member {$memberName} dihapus dari sistem.",
            targetTable: 'bookings',
            targetId:    $booking->id,
        );
    }
}
