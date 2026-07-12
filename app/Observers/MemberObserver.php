<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Member;

class MemberObserver
{
    public function created(Member $member): void
    {
        AuditLog::record(
            action:      'member_created',
            details:     "Profil member dibuat untuk: {$member->full_name}.",
            targetTable: 'members',
            targetId:    $member->id,
        );
    }

    public function updated(Member $member): void
    {
        // Perubahan subscription type
        if ($member->wasChanged('subscription_type')) {
            $old = $member->getOriginal('subscription_type');
            $new = $member->subscription_type;
            AuditLog::record(
                action:      'member_subscription_changed',
                details:     "Langganan Member {$member->full_name} berubah dari {$old} → {$new}.",
                targetTable: 'members',
                targetId:    $member->id,
            );
            return;
        }

        AuditLog::record(
            action:      'member_profile_updated',
            details:     "Member {$member->full_name} memperbarui profilnya.",
            targetTable: 'members',
            targetId:    $member->id,
        );
    }

    public function deleted(Member $member): void
    {
        AuditLog::record(
            action:      'member_deleted',
            details:     "Akun Member {$member->full_name} dihapus dari sistem.",
            targetTable: 'members',
            targetId:    $member->id,
        );
    }
}
