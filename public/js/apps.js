angular.module('apps', [
    'adminctrl',
    'helper.service',
    'admin.service',
    'auth.service',
    'message.service'
])
    .controller('indexController', indexController)

function indexController($scope, helperServices, dashboardServices) {
    $scope.titleHeader = "Booking Foto";
    $scope.header = "";
    $scope.proses = false;
    $scope.breadcrumb = "";
    $scope.title;
    $scope.hasil = {};
    $scope.hasil.nilai_peserta_kelas_berhasil = 0;
    $scope.hasil.nilai_peserta_kelas_gagal = 0;
    $scope.warning = 0;
    dashboardServices.get().then(res => {
        console.log(res);
        $scope.datas = res;
    })

    $scope.sync = async (data, set) => {
        var cek = [];
        $scope.proses = true;
        const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));
        $.LoadingOverlay('show');
        const batches = $scope.batchArray(data, 10);
        for (let i = 0; i < batches.length; i++) {
            const element = batches[i];
            try {
                // Menunggu hasil dari fungsi sync untuk setiap batch
                const res = await dashboardServices.sync(element, set);

                // Memproses hasil
                $scope.hasil.nilai_peserta_kelas_berhasil += res.berhasil.length;
                $scope.hasil.nilai_peserta_kelas_gagal += res.gagal.length;
                console.log(res);

                // Menyimpan data dari setiap batch ke cek
                element.forEach(elementData => {
                    cek.push(elementData);
                });

                // Menunggu 1 detik sebelum memproses batch berikutnya
                await delay(1000); // Ganti 1000 dengan nilai sesuai kebutuhan jeda (dalam ms)

            } catch (err) {
                console.error("Error processing batch", i, err);
            }
        }
        $.LoadingOverlay('hide');
        $scope.proses = false;  // Pastikan proses selesai

        // batches.forEach((element, index) => {
        //     dashboardServices.sync(element, set).then(res => {
        //         $scope.hasil.nilai_peserta_kelas_berhasil += res.berhasil.length
        //         $scope.hasil.nilai_peserta_kelas_gagal += res.gagal.length
        //         console.log(res);
        //         element.forEach(elementData => {
        //             cek.push(elementData);
        //         });
        //         if (cek.length == data.length) {
        //             $.LoadingOverlay('hide');
        //             $scope.proses = true;
        //         }
        //     })
        // });
    }

    $scope.batchArray = (data, batchSize) => {
        const batches = [];
        for (let i = 0; i < data.length; i += batchSize) {
            const batch = data.slice(i, i + batchSize);
            batches.push(batch);
        }
        return batches;
    }
}
