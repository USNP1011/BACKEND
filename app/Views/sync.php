<!DOCTYPE html>
<html lang="en" ng-app="apps" ng-controller="indexController">
  <head>
    <title>Sync Data</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
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
                </tr>
            </thead>
            <tbody>
                <tr ng-if="datas.mahasiswa.length > 0">
                    <td>Mahasiswa</td>
                    <td>{{datas.mahasiswa.length}}</td>
                </tr>
                <tr ng-if="datas.riwayat_pendidikan.length > 0">
                    <td>Riwayat Pendidikan</td>
                    <td>{{datas.riwayat_pendidikan.length}}</td>
                </tr>
                <tr ng-if="datas.nilai_transfer.length > 0">
                    <td>Nilai Transfer</td>
                    <td>{{datas.nilai_transfer.length}}</td>
                </tr>
                <tr ng-if="datas.kelas_kuliah.length > 0">
                    <td>Kelas Kuliah</td>
                    <td>{{datas.kelas_kuliah.length}}</td>
                </tr>
                <tr ng-if="datas.peserta_kelas.length > 0">
                    <td>Peserta Kelas</td>
                    <td>{{datas.peserta_kelas.length}}</td>
                </tr>
                <tr ng-if="datas.dosen_pengajar_kelas.length > 0">
                    <td>Dosen Pengajar Kelas</td>
                    <td>{{datas.dosen_pengajar_kelas.length}}</td>
                </tr>
                <tr ng-if="datas.nilai_peserta_kelas.length > 0">
                    <td>Nilai Peserta Kelas</td>
                    <td>{{datas.nilai_peserta_kelas.length}}</td>
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
    <!-- Bootstrap JavaScript Libraries -->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
      integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
      crossorigin="anonymous"
    ></script>
    <script src="libs/angular/angular.min.js"></script>
    <script src="js/apps.js"></script>
    <script src="js/services/helper.services.js"></script>
    <script src="js/services/auth.services.js"></script>
    <script src="js/services/admin.services.js"></script>
    <script src="js/services/pesan.services.js"></script>
    <script src="js/controllers/admin.controllers.js"></script>
  </body>
</html>
