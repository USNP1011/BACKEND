angular.module('ctrl', [])
    .controller('pageController', pageController)
    .controller('homeController', homeController)
    .controller('pesananController', pesananController)
    .controller('jadwalController', jadwalController)
    ;

function pageController($scope, helperServices) {
    $scope.Title = "Page Header";
}

function homeController($scope, $http, helperServices, dashboardServices, $sce) {
    $scope.datas = [];
    dashboardServices.get().then(res=>{
        $scope.datas = res
        $scope.datas.menu.forEach(element => {
            element.harga = parseFloat(element.harga);
            $scope.$applyAsync(x => {
                element.foto = $sce.trustAsResourceUrl(helperServices.url + "assets/backend/img/makanan/" + element.foto);
            })
        });
        console.log($scope.datas);
    })
}

function pesananController($scope, helperServices, pesananServices, message, $sce) {
    $scope.$emit("SendUp", "Pegawai");
    $scope.datas = [];
    $scope.titleModal = "Tambah Data";
    $scope.model = {};
    $scope.model.detail = [];
    pesananServices.get().then(res => {
        $scope.datas = res;
        console.log($scope.datas.pesanan);
    })

    $scope.save = () => {
        message.dialog("Data setelah di simpan tidak dapat diubah!", "Ya", "Tidak").then(x=>{
            pesananServices.post($scope.model).then(res => {
                message.info("Berhasil")
                document.location.reload();
                // $("#tambah").modal("hide");
                // $scope.titleModal = "Tambah Data";
                // $scope.model = {};
            })
        })
    }

    $scope.edit = (item) => {
        $scope.titleModal = "Ubah Data";
        $scope.model = angular.copy(item);
        $("#tambah").modal("show");
    }

    $scope.nilai = 0;
    $scope.setForm = (value)=>{
        $scope.nilai += value;
        if($scope.nilai==2){
            $("#tambah").modal("hide");
            $("#invoice").modal("show");
        }
        if($scope.nilai>0){
            if(!$scope.model.orderid){
                $scope.model.orderid = Date.now();
            }
        }
    }

    $scope.check = (item) => {
        if(item.value){
            item.paket_id = item.id;
            $scope.model.detail.push(angular.copy(item));
        }else{
            var data = $scope.model.detail.find(x=>x.paket_id==item.id);
            var index = $scope.model.detail.indexOf(data)
            $scope.model.detail.splice(index, 1);
        }
        console.log($scope.model); 
    }
    $scope.total = 0;
    $scope.subTotal = 0;
    $scope.tax = 0;
    $scope.hitungTotal=()=>{
        $scope.subTotal = 0;
        $scope.model.detail.forEach(element => {
            $scope.subTotal += (element.harga*element.jumlah);
        });
        $scope.tax = $scope.subTotal*0.1;
        $scope.total = $scope.subTotal + $scope.tax;
        $scope.model.tagihan = $scope.total;
    }

    $scope.showInvoice = (item)=>{
        $scope.model = angular.copy(item);
        $scope.model.tanggal_pesan = new Date($scope.model.tanggal_pesan);
        $scope.model.waktu_acara = new Date($scope.model.waktu_acara);
        var set = [];
        $scope.model.detail.forEach(element => {
            $scope.datas.paket.forEach(paket => {
                if(element.paket_id==paket.id){
                    var a = angular.copy(paket);
                    a.jumlah = parseFloat(element.jumlah);
                    set.push(a);
                }
            });
        });
        $scope.model.detail = set;
        $scope.hitungTotal();
        $("#invoice").modal("show");
    }

    $scope.showUpload = (item)=>{
        $scope.model = item;
        console.log(item);
    }

    $scope.cekFile = (item) => {
        console.log(item);
    }

    $scope.uploadBukti = ()=>{
        pesananServices.upload($scope.model).then(res=>{
            message.info("Berhasil");
            $("#upload").modal("hide");
            $scope.model = {};
        })
    }
}

function jadwalController($scope, helperServices, jadwalServices, message, $sce) {
    $scope.$emit("SendUp", "Jadwal");
    $scope.datas = [];
    $scope.spot = [];
    $scope.pengumuman = [];
    $scope.model = {};
    $scope.jenisTanggal = "spot";
    jadwalServices.get().then(res => {
        $scope.datas = res;
        $scope.iklanspot($scope.grouptanggal($scope.datas));
    })
    $scope.jadwal = (param) => {
        $scope.infoOrder = param;
        $scope.jadwals = $scope.grouptanggal(param.jadwal);
        $("#jadwalsiaran").modal("show");
    }
    $scope.grouptanggal = (data) => {
        $scope.total = 0;
        var newArray = [];
        var dataTanggal = "";
        data.forEach(element => {
            var tgl = element.waktu_acara.split(" ");
            if (dataTanggal != tgl[0]) {
                element.tanggal = tgl[0];
                newArray.push(element);
                dataTanggal = tgl[0];
            }
        });

        newArray.forEach(element => {
            // element.display = 'background';
            element.textColor= 'white';
            element.title = "Alamat Antar: " + element.alamat;
            element.start = element.tanggal;
            element.end = element.tanggal;
            element.langth = 0;
            element.color = 'red';
            delete element.tanggal;
        });
        return newArray;
    }
    $scope.iklanspot = (datas) => {
        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');
        var items = [];
        var calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            locale: 'id',
            themeSystem: 'bootstrap',
            //Random default events
            events: items = datas,
            // dateClick: function (info) {
            //     $scope.datas.forEach(element => {
            //         var tgl = element.waktu_acara.split(" ");
            //         if(tgl[0]==info.dateStr){
            //             $scope.dataDetail = element;
            //             $("#detail").modal('show');
            //         }
            //     });
            // }
        });
        console.log(items);

        calendar.render();
        // $('#calendar').fullCalendar()

        /* ADDING EVENTS */
        var currColor = '#3c8dbc' //Red by default
        // Color chooser button
        $('#color-chooser > li > a').click(function (e) {
            e.preventDefault()
            // Save color
            currColor = $(this).css('color')
            // Add color effect to button
            $('#add-new-event').css({
                'background-color': currColor,
                'border-color': currColor
            })
        })
        $('#add-new-event').click(function (e) {
            e.preventDefault()
            // Get value and make sure it is not null
            var val = $('#new-event').val()
            if (val.length == 0) {
                return
            }

            // Create events
            var event = $('<div />')
            event.css({
                'background-color': currColor,
                'border-color': currColor,
                'color': '#fff'
            }).addClass('external-event')
            event.text(val)
            $('#external-events').prepend(event)

            // Add draggable funtionality
            ini_events(event)

            // Remove event from text input
            $('#new-event').val('')
        })
    }
    $scope.setData = (set) => {
        if (set == 'spot') {
            $scope.iklanspot($scope.grouptanggal($scope.spot));
        } else {
            $scope.iklanspot($scope.grouptanggal($scope.pengumuman));
        }
    }

    
}
