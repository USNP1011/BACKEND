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

    $scope.sync = (data, set) => {
        var cek = [];
        var controller = helperServices.url + 'sync/';
        $scope.proses = true;
        $.LoadingOverlay('show');
        const batches = $scope.batchArray(data, 10);
        batches.forEach((element, index) => {
            setTimeout(() => {
                $http({
                    method: 'post',
                    url: controller + set,
                    data: param,
                    headers: AuthService.getHeader()
                }).then(
                    (res) => {
                        $scope.hasil.nilai_peserta_kelas_berhasil += res.berhasil.length
                        $scope.hasil.nilai_peserta_kelas_gagal += res.gagal.length
                        console.log(res);
                        element.forEach(elementData => {
                            cek.push(elementData);
                        });
                        if(cek.length==data.length){
                            $.LoadingOverlay('hide');
                            $scope.proses = true;
                        }
                        def.resolve(res.data);
                    },
                    (err) => {
                        def.reject(err);
                    }
                );
            }, 1000);
        });
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
