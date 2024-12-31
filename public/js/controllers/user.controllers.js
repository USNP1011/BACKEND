angular.module('userctrl', [])
    // Admin
    .filter('range', function () {
        return function (input, total, awal) {
            total = parseInt(total);

            for (var i = awal; i < total; i++) {
                input.push(i);
            }

            return input;
        };
    })
    .controller('dashboardController', dashboardController)
    .controller('pengajuanController', pengajuanController)
    .controller('angsuranController', angsuranController)
    .controller('infakController', infakController)
    ;

function dashboardController($scope, dashboardServices) {
    $scope.$emit("SendUp", "Dashboard");
    $scope.datas = {};
    $scope.title = "Dashboard";
    var all = [];
    mapboxgl.accessToken = 'pk.eyJ1Ijoia3Jpc3R0MjYiLCJhIjoiY2txcWt6dHgyMTcxMzMwc3RydGFzYnM1cyJ9.FJYE8uVi-eVl_mH_DLLEmw';

    dashboardServices.get().then(res => {
        $scope.datas = res;
        $scope.$applyAsync(x => {
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/satellite-v9',
                center: [140.7052499, -2.5565586],
                zoom: 12
            });
            $scope.datas.forEach(param => {
                var item = new mapboxgl.Marker({ color: param.status == 'Diajukan' ? 'red' : param.status == 'Proses' ? 'Yellow' : '' })
                    .setLngLat([Number(param.long), Number(param.lat)])
                    .setPopup(
                        new mapboxgl.Popup({ offset: 25 }) // add popups
                            .setHTML(
                                `<h4><strong>Nomor Laporan: ${param.nomor}</strong></h4><p>Permasalahan: ${param.kerusakan}<br>Status: <strong>${param.status}</strong></p>`
                            )
                    )
                    .addTo(map);
                all.push(item);
            });
        })
    })
}

function pengajuanController($scope, pengajuanServices, helperServices, pesan) {
    $scope.$emit("SendUp", "Pengajuan");
    $scope.datas = {};
    $scope.title = "Dashboard";
    $.LoadingOverlay('show');

    if (helperServices.lastPath == "add") {
        pengajuanServices.kelengkapan().then(res => {
            $scope.datas = res;
            $.LoadingOverlay('hide');
            console.log(res);
        })
    } else if(helperServices.lastPath == "pengajuan"){
        pengajuanServices.get(helperServices.lastPath).then(res => {
            $scope.datas = res;
            $.LoadingOverlay('hide');
        })
    }else {
        pengajuanServices.by_id(helperServices.lastPath).then(res => {
            $scope.datas = res;
            $scope.model = $scope.datas.permohonan;
            console.log(res);
            $.LoadingOverlay('hide');
        })
    }

    $scope.add = () => {
        document.location.href = helperServices.url + "pengajuan/add"
    }

    $scope.edit = (param) => {
        $scope.model = angular.copy(param);
    }

    $scope.save = () => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "warning").then(x => {
            $.LoadingOverlay('show');
            $scope.model.kelengkapan = $scope.datas.kelengkapan;
            if($scope.model.id){
                pengajuanServices.put($scope.model).then(res => {
                    $.LoadingOverlay('hide');
                    pesan.dialog('Pengajuan berhasil', 'OK', false, 'success').then(x => {
                        document.location.href = helperServices.url + "pengajuan";
                    })
                })
            }else{
                pengajuanServices.post($scope.model).then(res => {
                    $.LoadingOverlay('hide');
                    pesan.dialog('Pengajuan berhasil', 'OK', false, 'success').then(x => {
                        document.location.href = helperServices.url + "pengajuan";
                    })
                })
            }
        })
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            pengajuanServices.deleted(param).then(res => {
                $.LoadingOverlay('hide');
            })
        })
    }

    $scope.rincian = () => {
        var nominal = $scope.datas.nominal.find(x => x.id == $scope.model.nominal_id);
        var lama = parseInt($scope.model.waktu);
        $scope.model.rincian = [];
        var bayar = (parseFloat(nominal.nominal)) / (lama);
        var item = { nominal: bayar }
        for (let i = 1; i <= lama; i++) {
            $scope.model.rincian.push(item)
        }
        console.log($scope.model.rincian);
        $("#rincian").modal('show');
    }
}

function angsuranController($scope, angsuranServices, helperServices, pesan) {
    $scope.$emit("SendUp", "Angsuran");
    $scope.datas = {};
    $scope.title = "Dashboard";
    $.LoadingOverlay('show');
    console.log(helperServices.lastPath);

    if (helperServices.lastPath == "angsuran") {
        angsuranServices.get().then(res => {
            $scope.datas = res;
            $.LoadingOverlay('hide');
        })
    } else {
        angsuranServices.jadwal(helperServices.lastPath).then(res => {
            $scope.datas = res;
            $.LoadingOverlay('hide');
            console.log(res);
        })
    }

    $scope.add = () => {
        document.location.href = helperServices.url + "pengajuan/add"
    }

    $scope.bayar = (param) => {
        $scope.model = angular.copy(param);
        $scope.model.tanggal_bayar = new Date();
        $("#bayar").modal('show')
    }

    $scope.save = () => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "warning").then(x => {
            $.LoadingOverlay('show');
            angsuranServices.put($scope.model).then(res => {
                $.LoadingOverlay('hide');
                pesan.Success('Bukti pembayaran berhasil di kirim');
                $("#bayar").modal('hide')
            })
        })
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            angsuranServices.deleted(param).then(res => {
                $.LoadingOverlay('hide');
            })
        })
    }

    $scope.rincian = () => {
        var nominal = $scope.datas.nominal.find(x => x.id == $scope.model.nominal_id);
        if ($scope.model.waktu == '1') {
            $scope.model.rincian = [{ nominal: nominal.nominal }]
        } else if ($scope.model.waktu == '2') {
            $scope.model.rincian = [{ nominal: 90000 }, { nominal: nominal.nominal - 90000 }]
            console.log($scope.model.rincian);
        } else {
            var lama = parseInt($scope.model.waktu);
            $scope.model.rincian = [{ nominal: 90000 }];
            var bayar = (parseFloat(nominal.nominal) - 90000) / (lama - 1);
            var item = { nominal: bayar }
            for (let i = 1; i < lama; i++) {
                $scope.model.rincian.push(item)
            }
            console.log($scope.model.rincian);
        }
        $("#rincian").modal('show');
    }
}

function infakController($scope, infakServices, tabunganServices, helperServices, pesan) {
    $scope.datas = {};
    
    $.LoadingOverlay('show');
    if(helperServices.lastPath == "info_infak"){
        $scope.judul = "Informasi Infak";
        $scope.$emit("SendUp", $scope.judul);
        infakServices.get().then(res => {
            $scope.datas = res;
            $scope.total = 0
            $scope.datas.forEach(element => {
                element.tanggal = new Date(element.tanggal_bayar);
                $scope.total += parseFloat(element.nominal);
            });
            $.LoadingOverlay('hide');
        })
    }else{
        $scope.judul = "Informasi Tabungan";
        $scope.$emit("SendUp", $scope.judul);
        tabunganServices.get().then(res => {
            $scope.datas = res;
            $scope.total = 0
            $scope.datas.forEach(element => {
                element.tanggal = new Date(element.tanggal_bayar);
                $scope.total += parseFloat(element.nominal);
            });
            $.LoadingOverlay('hide');
        })
    }
}


