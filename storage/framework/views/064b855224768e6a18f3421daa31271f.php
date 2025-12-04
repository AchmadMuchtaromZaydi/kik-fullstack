<div class="container py-4">

    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php
        $jumlahMaks = $jumlahMaks ?? ($organisasi->jumlah_anggota ?? 0);
        $jumlahSaatIni = $jumlahSaatIni ?? ($organisasi->anggota()->count() ?? 0);

        $ketua = $ketua ?? ($anggota->where('jabatan', 'Ketua')->first() ?? null);
        $sekretaris = $sekretaris ?? ($anggota->where('jabatan', 'Sekretaris')->first() ?? null);

        $isKetuaAda = !empty($ketua);
        $isSekretarisAda = !empty($sekretaris);
        $isJumlahComplete = $jumlahSaatIni >= $jumlahMaks;
        $isComplete = $isJumlahComplete && $isKetuaAda && $isSekretarisAda;
    ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-3">Data Anggota</h5>

            <div class="mb-3">
                <?php if(!$isKetuaAda): ?>
                    <p class="text-danger mb-1">✦ Jabatan <strong>Ketua</strong> belum diisi.</p>
                <?php endif; ?>

                <?php if(!$isSekretarisAda): ?>
                    <p class="text-danger mb-1">✦ Jabatan <strong>Sekretaris</strong> belum diisi.</p>
                <?php endif; ?>

                <p id="alertJumlahAnggota" class="text-danger mb-1 <?php echo e($isJumlahComplete ? 'd-none' : ''); ?>">
                    ✦ Jumlah anggota belum memenuhi <?php echo e($jumlahMaks); ?> orang.
                </p>

                <p class="text-muted mb-3">
                    Masukkan data anggota dalam organisasi anda sejumlah
                    <strong id="jumlahMaksText"><?php echo e($jumlahMaks); ?> Orang</strong>.
                    <span id="statusPenuh" class="text-danger <?php echo e($jumlahSaatIni >= $jumlahMaks ? '' : 'd-none'); ?>">
                        (Jumlah anggota sudah penuh)
                    </span>
                </p>

                
                <button id="btnTambahAnggotaDynamic"
                    class="btn btn-primary mb-3 <?php if($jumlahSaatIni >= $jumlahMaks): ?> d-none <?php endif; ?>"
                    data-bs-toggle="modal" data-bs-target="#modalAnggota">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Anggota
                </button>
            </div>

            
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>L/P</th>
                            <th>Umur</th>
                            <th>Jabatan</th>
                            <th>Kontak</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center text-muted">
                        <?php $__empty_1 = true; $__currentLoopData = $anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($a->nik); ?></td>
                            <td><?php echo e($a->nama); ?></td>
                            <td><?php echo e($a->jenis_kelamin); ?></td>
                            <td>
                                <?php
                                    $umur = $a->tanggal_lahir ? \Carbon\Carbon::parse($a->tanggal_lahir)->age : null;
                                ?>
                                <?php echo e($umur !== null ? $umur . ' th' : '-'); ?>

                            </td>
                            <td><?php echo e($a->jabatan); ?></td>
                            <td><?php echo e($a->telepon ?? $a->whatsapp ?? '-'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-info me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditAnggota<?php echo e($a->id); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDeleteAnggota<?php echo e($a->id); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8">Belum ada data anggota.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-secondary prev-tab" data-prev="#tab-organisasi">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </button>

                <button
                    id="btnNextAnggota"
                    class="btn btn-primary px-4 next-tab"
                    data-next="#tab-inventaris"
                    <?php if(!$isComplete): ?> disabled <?php endif; ?>
                >
                    Selanjutnya
                </button>
            </div>

        </div>
    </div>
</div>





<div class="modal fade" id="modalAnggota" tabindex="-1" aria-labelledby="modalAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAnggotaLabel">Tambah Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('user.anggota.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>NIK</label>
                            <input type="text" name="nik" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Jabatan</label>
                            <select name="jabatan" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="Ketua">Ketua</option>
                                <option value="Wakil">Wakil</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Bendahara">Bendahara</option>
                                <option value="Anggota">Anggota</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Jenis Kelamin</label><br>
                            <input type="radio" name="jenis_kelamin" value="L" required> Laki-Laki
                            <input type="radio" name="jenis_kelamin" value="P" required> Perempuan
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control">
                        </div>
                        <div class="col-12">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Telepon</label>
                            <input type="text" name="telepon" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Whatsapp</label>
                            <input type="text" name="whatsapp" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>





<?php $__currentLoopData = $anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalEditAnggota<?php echo e($a->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Edit Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('user.anggota.update', $a->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo e($a->nama); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label>NIK</label>
                            <input type="text" name="nik" class="form-control" value="<?php echo e($a->nik); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label>Jabatan</label>
                            <select name="jabatan" class="form-select" required>
                                <option value="Ketua" <?php echo e($a->jabatan=='Ketua'?'selected':''); ?>>Ketua</option>
                                <option value="Wakil" <?php echo e($a->jabatan=='Wakil'?'selected':''); ?>>Wakil</option>
                                <option value="Sekretaris" <?php echo e($a->jabatan=='Sekretaris'?'selected':''); ?>>Sekretaris</option>
                                <option value="Bendahara" <?php echo e($a->jabatan=='Bendahara'?'selected':''); ?>>Bendahara</option>
                                <option value="Anggota" <?php echo e($a->jabatan=='Anggota'?'selected':''); ?>>Anggota</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Jenis Kelamin</label><br>
                            <input type="radio" name="jenis_kelamin" value="L" <?php echo e($a->jenis_kelamin=='L'?'checked':''); ?> required> Laki-Laki
                            <input type="radio" name="jenis_kelamin" value="P" <?php echo e($a->jenis_kelamin=='P'?'checked':''); ?> required> Perempuan
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Lahir</label>
                             <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo e($a->tanggal_lahir ? \Carbon\Carbon::parse($a->tanggal_lahir)->format('Y-m-d') : ''); ?>">

                        </div>
                        <div class="col-md-4">
                            <label>Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control" value="<?php echo e($a->pekerjaan); ?>">
                        </div>
                        <div class="col-12">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"><?php echo e($a->alamat); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="<?php echo e($a->telepon); ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Whatsapp</label>
                            <input type="text" name="whatsapp" class="form-control" value="<?php echo e($a->whatsapp); ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>




<?php $__currentLoopData = $anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalDeleteAnggota<?php echo e($a->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('user.anggota.destroy', $a->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="modal-body">
                    Apakah anda yakin ingin menghapus anggota <strong><?php echo e($a->nama); ?></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<script>
    // --- VARIABEL GLOBAL DARI PHP ---
    window.jumlahSaatIni = <?php echo e($jumlahSaatIni); ?>;
    window.jumlahMaks = <?php echo e($jumlahMaks); ?>;
    window.isKetuaAda = <?php echo e($isKetuaAda ? 'true' : 'false'); ?>;
    window.isSekretarisAda = <?php echo e($isSekretarisAda ? 'true' : 'false'); ?>;

    // Pastikan boolean di JS
    window.isKetuaAda = window.isKetuaAda === true || window.isKetuaAda === 'true';
    window.isSekretarisAda = window.isSekretarisAda === true || window.isSekretarisAda === 'true';
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {

    // --- Hanya jalankan jika elemen anggota ada
    const btnTambah = document.getElementById("btnTambahAnggotaDynamic");
    if(!btnTambah) return;

    // Variabel global
    window.jumlahSaatIni   = <?php echo e($jumlahSaatIni); ?>;
    window.jumlahMaks      = <?php echo e($jumlahMaks); ?>;
    window.isKetuaAda      = <?php echo e($isKetuaAda ? 'true' : 'false'); ?>;
    window.isSekretarisAda = <?php echo e($isSekretarisAda ? 'true' : 'false'); ?>;
    window.isKetuaAda      = window.isKetuaAda === true || window.isKetuaAda === 'true';
    window.isSekretarisAda = window.isSekretarisAda === true || window.isSekretarisAda === 'true';

    // ===============================
    // FUNCTION: Update tampilan jumlah anggota
    // ===============================
    function updateJumlahAnggota(jumlahMaksBaru) {
    // Pastikan angka
    jumlahMaksBaru = parseInt(jumlahMaksBaru);

    // Update variabel global
    window.jumlahMaks = jumlahMaksBaru;

    // Ambil elemen UI
    const btnTambah   = document.getElementById("btnTambahAnggotaDynamic");
    const alertJumlah = document.getElementById("alertJumlahAnggota");
    const statusPenuh = document.getElementById("statusPenuh");
    const jumlahText  = document.getElementById("jumlahMaksText");
    const nextBtn     = document.getElementById("btnNextAnggota");

    // Update teks jumlah maksimal
    if (jumlahText) jumlahText.innerHTML = jumlahMaksBaru + " Orang";

    // Apakah jumlah saat ini sudah penuh?
    const isPenuh = window.jumlahSaatIni >= jumlahMaksBaru;

    // Tombol Tambah muncul jika belum penuh
    if (btnTambah) btnTambah.classList.toggle("d-none", isPenuh);

    // Alert merah muncul jika jumlah belum terpenuhi
    if (alertJumlah) {
        alertJumlah.innerHTML = `✦ Jumlah anggota belum memenuhi ${jumlahMaksBaru} orang.`;
        alertJumlah.classList.toggle("d-none", isPenuh);
    }

    // Status penuh muncul jika jumlah sudah terpenuhi
    if (statusPenuh) statusPenuh.classList.toggle("d-none", !isPenuh);

    // Tombol Next aktif jika lengkap (Ketua & Sekretaris ada + jumlah terpenuhi)
    if (nextBtn) {
        const isComplete = isPenuh && window.isKetuaAda && window.isSekretarisAda;
        nextBtn.disabled = !isComplete;
    }
}

    // ===============================
    // Event listener custom: dari halaman ORGANISASI (AJAX)
    // ===============================
    document.addEventListener("jumlahAnggotaUpdated", function (e) {
        const jumlahBaru = e.detail.jumlah;
        updateJumlahAnggota(jumlahBaru);
        sessionStorage.setItem("jumlah_anggota_baru", jumlahBaru);
    });

    // ===============================
    // SessionStorage fallback
    // ===============================
    const storedJumlah = sessionStorage.getItem("jumlah_anggota_baru");
    if(storedJumlah) updateJumlahAnggota(storedJumlah);

    // ===============================
    // AUTO PINDAH KE TAB ANGGOTA JIKA SESSION('tab') = 'anggota'
    // ===============================
    if(<?php echo json_encode(session('tab'), 15, 512) ?> === 'anggota') {
        const tabButtons = document.querySelectorAll('#form-tabs button');
        const tabPanes   = document.querySelectorAll('.tab-pane');

        // Sembunyikan semua tab
        tabPanes.forEach(tab => tab.classList.add('d-none'));

        // Tampilkan tab anggota
        const tabAnggota = document.querySelector('#tab-anggota');
        if(tabAnggota) tabAnggota.classList.remove('d-none');

        // Reset active button
        tabButtons.forEach(btn => btn.classList.remove('active'));

        // Aktifkan tombol tab anggota
        const btnAnggota = document.querySelector('#form-tabs button[data-target="#tab-anggota"]');
        if(btnAnggota) btnAnggota.classList.add('active');
    }

});
</script>

<?php /**PATH D:\New Code\kik-fullstack\resources\views/user/anggota/index.blade.php ENDPATH**/ ?>