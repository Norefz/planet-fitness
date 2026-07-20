<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionPaymentController extends Controller
{
    public function __construct(private MidtransService $midtrans)
    {
    }

    public function show(): View
    {
        $member = Auth::user()->member;

        if ($member->hasActiveSubscription()) {
            return view('member.subscription-payment', ['member' => $member, 'payment' => null, 'error' => null]);
        }

        $payment = $member->subscriptionPayments()
            ->where('status', 'pending')
            ->whereNotNull('snap_token')
            ->latest()
            ->first();

        $error = null;
        if (! $payment) {
            try {
                $payment = $this->midtrans->createMonthlySubscription($member);
            } catch (\Throwable $exception) {
                report($exception);
                $error = $exception->getMessage();
            }
        }

        return view('member.subscription-payment', compact('member', 'payment', 'error'));
    }

    public function retry(): RedirectResponse
    {
        $member = Auth::user()->member;

        $member->subscriptionPayments()->where('status', 'pending')->update(['status' => 'expired']);

        return redirect()->route('member.payment.show');
    }

    public function notification(Request $request): JsonResponse
    {
        $payment = $this->midtrans->handleNotification($request->all());

        if (! $payment) {
            return response()->json(['message' => 'Invalid payment notification.'], 403);
        }

        return response()->json(['message' => 'Notification processed.']);
    }
}
