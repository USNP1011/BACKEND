<!DOCTYPE html>
<html lang="en" ng-app="apps" ng-controller="indexController">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <h3>Data Sync</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Jumlah Data</th>
                    <th>Jumlah Berhasil</th>
                    <th>Jumlah Gagal</th>
                    <th><i class="fas fa-cogs"></i></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-if="datas.mahasiswa.length > 0">
                    <td>Mahasiswa</td>
                    <td>{{datas.mahasiswa.length}}</td>
                    <td>{{hasil.mahasiswa_berhasil}}</td>
                    <td>{{hasil.mahasiswa_gagal}}</td>
                    <td><button class="btn btn-primary btn-sm" ng-click="sync(datas.mahasiswa, 'mahasiswa')"><i class="fas fa-sync"></i></button></td>
                </tr>
                <tr ng-if="datas.riwayat_pendidikan.length > 0">
                    <td>Riwayat Pendidikan</td>
                    <td>{{datas.riwayat_pendidikan.length}}</td>
                    <td>{{hasil.pendidikan_berhasil}}</td>
                    <td>{{hasil.pendidikan_gagal}}</td>
                    <td><button class="btn btn-primary btn-sm" ng-click="sync(datas.mahasiswa, 'pendidikan')"><i class="fas fa-sync"></i></button></td>
                </tr>
                <tr ng-if="datas.nilai_transfer.length > 0">
                    <td>Nilai Transfer</td>
                    <td>{{datas.nilai_transfer.length}}</td>
                    <td>{{hasil.nilai_transfer_berhasil}}</td>
                    <td>{{hasil.nilai_transfer_gagal}}</td>
                    <td><button class="btn btn-primary btn-sm" ng-click="sync(datas.nilai_transfer, 'nilai_transfer')"><i class="fas fa-sync"></i></button></td>
                </tr>
                <tr ng-if="datas.kelas_kuliah.length > 0">
                    <td>Kelas Kuliah</td>
                    <td>{{datas.kelas_kuliah.length}}</td>
                    <td>{{hasil.kelas_kuliah_berhasil}}</td>
                    <td>{{hasil.kelas_kuliah_gagal}}</td>
                    <td><button class="btn btn-primary btn-sm" ng-click="sync(datas.kelas_kuliah, 'kelas_kuliah')"><i class="fas fa-sync"></i></button></td>
                </tr>
                <tr ng-if="datas.peserta_kelas.length > 0">
                    <td>Peserta Kelas</td>
                    <td>{{datas.peserta_kelas.length}}</td>
                    <td>{{hasil.kelas_kuliah_berhasil}}</td>
                    <td>{{hasil.kelas_kuliah_gagal}}</td>
                    <td><button class="btn btn-primary btn-sm" ng-click="sync(datas.kelas_kuliah, 'kelas_kuliah')"><i class="fas fa-sync"></i></button></td>
                </tr>
                <tr ng-if="datas.dosen_pengajar_kelas.length > 0">
                    <td>Dosen Pengajar Kelas</td>
                    <td>{{datas.dosen_pengajar_kelas.length}}</td>
                    <td>{{hasil.kelas_kuliah_berhasil}}</td>
                    <td>{{hasil.kelas_kuliah_gagal}}</td>
                    <td><button class="btn btn-primary btn-sm" ng-click="sync(datas.kelas_kuliah, 'kelas_kuliah')"><i class="fas fa-sync"></i></button></td>
                </tr>
                <tr ng-if="datas.nilai_peserta_kelas.length > 0">
                    <td>Nilai Peserta Kelas</td>
                    <td>{{datas.nilai_peserta_kelas.length}}</td>
                    <td>{{hasil.nilai_peserta_kelas_berhasil}}</td>
                    <td>{{hasil.nilai_peserta_kelas_gagal}}</td>
                    <td><button class="btn btn-primary btn-sm" ng-click="sync(datas.nilai_peserta_kelas, 'nilai_peserta_kelas')" ng-disabled="proses"><i class="fas fa-sync"></i></button></td>
                </tr>
                <tr ng-if="datas.aktivitas_mahasiswa.length > 0">
                    <td>Aktivitas Mahasiswa</td>
                    <td>{{datas.aktivitas_mahasiswa.length}}</td>
                </tr>
                <tr ng-if="datas.anggota_aktivitas_mahasiswa.length > 0">
                    <td>Anggota Aktivitas Mahasiswa</td>
                    <td>{{datas.anggota_aktivitas_mahasiswa.length}}</td>
                </tr>
                <tr ng-if="datas.bimbing_mahasiswa.length > 0">
                    <td>Bimbing Mahasiswa</td>
                    <td>{{datas.bimbing_mahasiswa.length}}</td>
                </tr>
                <tr ng-if="datas.uji_mahasiswa.length > 0">
                    <td>Uji Mahasiswa</td>
                    <td>{{datas.uji_mahasiswa.length}}</td>
                </tr>
                <tr ng-if="datas.mahasiswa_lulus_do.length > 0">
                    <td>Mahasiswa Lulus DO</td>
                    <td>{{datas.mahasiswa_lulus_do.length}}</td>
                </tr>
                <tr ng-if="datas.perkuliahan_mahasiswa.length > 0">
                    <td>Perkuliahan Mahasiswa</td>
                    <td>{{datas.perkuliahan_mahasiswa.length}}</td>
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="libs/jquery/dist/jquery.min.js"></script>
    <script src="libs/angular/angular.min.js"></script>
    <script src="js/apps.js"></script>
    <script src="js/services/helper.services.js"></script>
    <script src="js/services/auth.services.js"></script>
    <script src="js/services/admin.services.js"></script>
    <script src="js/services/pesan.services.js"></script>
    <script src="js/controllers/admin.controllers.js"></script>
    <script src="libs/loading/dist/loadingoverlay.min.js"></script>
</body>

</html>