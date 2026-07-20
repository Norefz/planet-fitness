<?php

namespace App\Services;

use App\Models\Member;
use App\Models\SubscriptionPayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class MidtransService
{
    public function createMonthlySubscription(Member $member): SubscriptionPayment
    {
        $this->ensureConfigured();

        $payment = SubscriptionPayment::create([
            'member_id' => $member->id,
            'order_id' => 'PF-MEM-' . Str::uuid(),
            'amount' => $this->monthlyPrice(),
            'status' => 'pending',
        ]);

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->withBasicAuth(config('services.midtrans.server_key'), '')
                ->post($this->snapUrl(), [
                    'transaction_details' => [
                        'order_id' => $payment->order_id,
                        'gross_amount' => $payment->amount,
                    ],
                    'item_details' => [[
                        'id' => 'monthly-membership',
                        'price' => $payment->amount,
                        'quantity' => 1,
                        'name' => 'Planet Fitness Membership - 1 Bulan',
                    ]],
                    'customer_details' => [
                        'first_name' => $member->full_name,
                        'email' => $member->user?->email,
                        'phone' => $member->phone,
                    ],
                    'callbacks' => [
                        'finish' => route('member.payment.show'),
                    ],
                    'notification_url' => route('midtrans.notification'),
                ]);

            if (! $response->successful() || ! $response->json('token')) {
                throw new RuntimeException('Midtrans tidak dapat membuat transaksi pembayaran.');
            }

            $payment->update(['snap_token' => $response->json('token')]);

            return $payment->fresh();
        } catch (\Throwable $exception) {
            $payment->update(['status' => 'failed']);
            throw $exception;
        }
    }

    public function handleNotification(array $payload): ?SubscriptionPayment
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = (string) ($payload['signature_key'] ?? '');
        $serverKey = (string) config('services.midtrans.server_key');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if ($orderId === '' || $serverKey === '' || ! hash_equals($expectedSignature, $signature)) {
            return null;
        }

        $payment = SubscriptionPayment::with('member')->where('order_id', $orderId)->first();
        if (! $payment) {
            return null;
        }

        // The signed notification must also match the amount created for this
        // membership order; a different amount must never activate access.
        if ((int) (float) $grossAmount !== $payment->amount) {
            return null;
        }

        $transactionStatus = (string) ($payload['transaction_status'] ?? 'pending');
        $isPaid = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && ($payload['fraud_status'] ?? 'accept') === 'accept');

        $payment->update([
            'status' => $isPaid ? 'paid' : $transactionStatus,
            'payment_type' => $payload['payment_type'] ?? null,
            'paid_at' => $isPaid ? ($payment->paid_at ?? now()) : null,
            'gateway_payload' => $payload,
        ]);

        if ($isPaid && $payment->member) {
            $member = $payment->member;
            $startsAt = $member->subscription_expires_at && $member->subscription_expires_at->isFuture()
                ? $member->subscription_expires_at
                : now();

            $member->update([
                'subscription_type' => 'premium',
                'subscription_expires_at' => $startsAt->copy()->addMonth(),
            ]);
        }

        return $payment;
    }

    public function monthlyPrice(): int
    {
        return (int) config('services.midtrans.monthly_price');
    }

    private function ensureConfigured(): void
    {
        if (! config('services.midtrans.server_key') || ! config('services.midtrans.client_key')) {
            throw new RuntimeException('Konfigurasi Midtrans belum lengkap. Hubungi administrator.');
        }
    }

    private function snapUrl(): string
    {
        return config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }
}
