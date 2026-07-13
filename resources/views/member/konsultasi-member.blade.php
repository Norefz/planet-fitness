@extends('layouts.app')

@section('title', 'Konsultasi Mentor - Area Member')

@section('content')
<div class="pf-page-head">
    <div>
        <div class="pf-eyebrow">Penjadwalan & Interaksi</div>
        <div class="pf-page-title">Konsultasi Mentor</div>
        <div class="pf-page-sub">
            Booking sesi video konsultasi langsung dengan mentor bersertifikat —
            dari pengajuan jadwal hingga sesi selesai.
        </div>
    </div>
</div>

<div class="pf-view">
    <!-- Section Cari Mentor -->
    <div class="pf-section-block">
        <h3>Cari Mentor</h3>
        <div class="pf-mentor-grid">
            <div class="pf-mentor-card">
                <div class="pf-mentor-card-top">
                    <div class="pf-mentor-avatar-lg" style="background:var(--color-primary-light); color:var(--color-primary-dark);">RA</div>
                    <div>
                        <h4>Rini Andini</h4>
                        <div class="pf-mentor-spec">Pelatih Kebugaran & Penurunan Berat Badan</div>
                    </div>
                </div>
                <div class="pf-cert-badge"><i class="ti ti-certificate"></i> Bersertifikat</div>
                <div class="pf-mentor-rating"><i class="ti ti-star-filled"></i> <strong>4.9</strong> (212 sesi)</div>
                <button class="pf-btn pf-btn-primary" style="width:100%;" onclick="selectMentor('Rini Andini')"><i class="ti ti-calendar-plus"></i> Booking Konsultasi</button>
            </div>

            <div class="pf-mentor-card">
                <div class="pf-mentor-card-top">
                    <div class="pf-mentor-avatar-lg" style="background:#e6f1fb; color:#185fa5;">DP</div>
                    <div>
                        <h4>Dimas Pratama</h4>
                        <div class="pf-mentor-spec">Spesialis Kekuatan & Hipertrofi Otot</div>
                    </div>
                </div>
                <div class="pf-cert-badge"><i class="ti ti-certificate"></i> Bersertifikat</div>
                <div class="pf-mentor-rating"><i class="ti ti-star-filled"></i> <strong>4.8</strong> (164 sesi)</div>
                <button class="pf-btn pf-btn-primary" style="width:100%;" onclick="selectMentor('Dimas Pratama')"><i class="ti ti-calendar-plus"></i> Booking Konsultasi</button>
            </div>

            <div class="pf-mentor-card">
                <div class="pf-mentor-card-top">
                    <div class="pf-mentor-avatar-lg" style="background:#fbeaf0; color:#be185d;">SW</div>
                    <div>
                        <h4>Sari Wulandari</h4>
                        <div class="pf-mentor-spec">Ahli Gizi & Perencanaan Nutrisi</div>
                    </div>
                </div>
                <div class="pf-cert-badge"><i class="ti ti-certificate"></i> Bersertifikat</div>
                <div class="pf-mentor-rating"><i class="ti ti-star-filled"></i> <strong>5.0</strong> (98 sesi)</div>
                <button class="pf-btn pf-btn-primary" style="width:100%;" onclick="selectMentor('Sari Wulandari')"><i class="ti ti-calendar-plus"></i> Booking Konsultasi</button>
            </div>
        </div>
    </div>

    <!-- Section Jadwal Slot -->
    <div class="pf-section-block">
        <h3>Jadwalkan Sesi — <span id="selectedMentorName" style="color:var(--color-primary);">Rini Andini</span></h3>
        <div class="pf-booking-card">
            <div style="font-size:13px; font-weight:600; color:var(--color-text-secondary); margin-bottom:8px;">Pilih Tanggal</div>
            <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
                <button class="pf-slot is-active" style="width:auto; padding:10px 18px;">Sen, 22 Jun</button>
                <button class="pf-slot" style="width:auto; padding:10px 18px;">Sel, 23 Jun</button>
                <button class="pf-slot" style="width:auto; padding:10px 18px;">Rab, 24 Jun</button>
                <button class="pf-slot" style="width:auto; padding:10px 18px;">Kam, 25 Jun</button>
            </div>

            <div style="font-size:13px; font-weight:600; color:var(--color-text-secondary); margin-bottom:8px;">Pilih Waktu Tersedia</div>
            <div class="pf-slot-grid">
                <div class="pf-slot is-taken">09:00</div>
                <div class="pf-slot">10:00</div>
                <div class="pf-slot is-active">13:00</div>
                <div class="pf-slot">14:00</div>
                <div class="pf-slot">15:00</div>
                <div class="pf-slot is-taken">16:00</div>
                <div class="pf-slot">19:00</div>
                <div class="pf-slot">20:00</div>
            </div>

            <button class="pf-btn pf-btn-primary" style="width:100%;"><i class="ti ti-send"></i> Ajukan Permintaan Booking</button>
        </div>
    </div>

    <!-- Section List Sesi -->
    <div class="pf-section-block">
        <h3>Sesi Konsultasimu</h3>
        <div class="pf-booking-list">
            <div class="pf-booking-row">
                <div class="pf-mentor-avatar-lg" style="background:var(--color-primary-light); color:var(--color-primary-dark);">RA</div>
                <div class="pf-booking-info">
                    <div class="pf-bname">Rini Andini · Konsultasi Penurunan Berat Badan</div>
                    <div class="pf-bmeta"><i class="ti ti-calendar"></i> Senin, 22 Jun 2026 · 13:00 WIB</div>
                    <div class="pf-status-pill pf-status-confirmed" style="margin-top:8px;"><i class="ti ti-circle-check"></i> Dikonfirmasi</div>
                </div>
                <div class="pf-booking-actions">
                    <button class="pf-btn pf-btn-primary pf-btn-sm"><i class="ti ti-video"></i> Gabung Zoom</button>
                    <button class="pf-btn pf-btn-outline pf-btn-sm">Batalkan</button>
                </div>
            </div>

            <div class="pf-booking-row">
                <div class="pf-mentor-avatar-lg" style="background:#e6f1fb; color:#185fa5;">DP</div>
                <div class="pf-booking-info">
                    <div class="pf-bname">Dimas Pratama · Evaluasi Program Kekuatan</div>
                    <div class="pf-bmeta"><i class="ti ti-calendar"></i> Rabu, 24 Jun 2026 · 19:00 WIB</div>
                    <div class="pf-status-pill pf-status-pending" style="margin-top:8px;"><i class="ti ti-clock"></i> Menunggu Konfirmasi</div>
                </div>
                <div class="pf-booking-actions">
                    <button class="pf-btn pf-btn-outline pf-btn-sm">Batalkan</button>
                </div>
            </div>

            <div class="pf-booking-row">
                <div class="pf-mentor-avatar-lg" style="background:#fbeaf0; color:#be185d;">SW</div>
                <div class="pf-booking-info">
                    <div class="pf-bname">Sari Wulandari · Konsultasi Rencana Gizi</div>
                    <div class="pf-bmeta"><i class="ti ti-calendar"></i> Selasa, 9 Jun 2026 · 10:00 WIB</div>
                    <div class="pf-status-pill pf-status-completed" style="margin-top:8px;"><i class="ti ti-check"></i> Selesai</div>
                </div>
                <div class="pf-booking-actions">
                    <button class="pf-btn pf-btn-outline pf-btn-sm"><i class="ti ti-message-circle"></i> Lihat Catatan</button>
                </div>
            </div>
        </div>

        <div class="pf-stepper">
            <div class="pf-step is-done"><div class="pf-step-dot"><i class="ti ti-check"></i></div> Pending</div>
            <div class="pf-step-line"></div>
            <div class="pf-step is-done"><div class="pf-step-dot"><i class="ti ti-check"></i></div> Dikonfirmasi</div>
            <div class="pf-step-line"></div>
            <div class="pf-step"><div class="pf-step-dot">3</div> Selesai</div>
        </div>
        <div style="font-size:12px; color:var(--color-text-secondary);">Status sesi dengan Rini Andini saat ini.</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // JS Khusus Halaman Member
    function selectMentor(name) {
        document.getElementById('selectedMentorName').textContent = name;
        window.scrollTo({
            top: document.querySelector('.pf-booking-card').getBoundingClientRect().top + window.scrollY - 100,
            behavior: 'smooth'
        });
    }

    document.querySelectorAll('.pf-slot:not(.is-taken)').forEach(slot => {
        slot.addEventListener('click', function () {
            this.parentElement.querySelectorAll('.pf-slot').forEach(s => s.classList.remove('is-active'));
            this.classList.add('is-active');
        });
    });
</script>
@endpush
