var setBaptis, setSidi, wijk;
var tableSidi, tableBaptis;
var oldWijk, oldSetBaptis, oldSetSidi; 
var encript = (item) => {
    return btoa(item + '*pendataanJemaat');
}
function getParams(item) {
    var url = new URLSearchParams(window.location.search);
    return url.get(item);
}
function setValue() {
    wijk = document.getElementById("wijk").value;
    setBaptis = document.querySelector('input[name="statusBaptis"]') && document.querySelector('input[name="statusBaptis"]:checked') ? document.querySelector('input[name="statusBaptis"]:checked').value : undefined;
    setSidi = document.querySelector('input[name="statusSidi"]') && document.querySelector('input[name="statusSidi"]:checked') ? document.querySelector('input[name="statusSidi"]:checked').value : undefined;
    console.log(wijk);
    console.log(setBaptis);
    if(setBaptis && wijk){
        // $('#printBaptis').attr('href');
        // $('#cetakBaptis').attr('href');
        document.getElementById("printBaptis").href=window.location.origin+'/laporan/excel?item='+(getParams('item')?getParams('item'):encript('layakBaptis'))+'&wijk='+encript(wijk)+'&set_status='+encript(setBaptis); 
        document.getElementById("cetakBaptis").href=window.location.origin+'/laporan/print?item='+(getParams('item')?getParams('item'):encript('layakBaptis'))+'&wijk='+encript(wijk)+'&set_status='+encript(setBaptis); 
        
        if((oldWijk && oldWijk != wijk) || (oldSetBaptis && oldSetBaptis!=setBaptis)){
            tableBaptis.destroy();
            oldSetBaptis=setBaptis;
            oldWijk=wijk;
        }else{
            oldSetBaptis=setBaptis;
            oldWijk=wijk;
        }
        tableBaptis = $('#layakBaptis').DataTable({
            processing: true,
            serverSide: true,
            // data: $scope.datas,
            ajax: '/laporan/layak_baptis?set_status='+setBaptis+'&wijk='+wijk,
            order: [],
            columnDefs: [{
                targets: '_all',
                orderable: false
            }],
            scrollX: true,
            columns: [{
                    data: 'no'
                },
                {
                    data: 'wijk'
                },
                {
                    data: 'ksp'
                },
                {
                    data: 'kode_kk'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'tanggal_lahir',
                    render: function(data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return moment(data).format("DD MMMM YYYY");
                    }
                },
                {
                    data: 'sex'
                },
                {
                    data: 'golongan_darah'
                },
                {
                    data: 'status_kawin'
                },
                {
                    data: 'hubungan_keluarga'
                },
                {
                    data: 'pendidikan_terakhir'
                },
                {
                    data: 'pekerjaan'
                },
                {
                    data: 'nama_ayah'
                },
                {
                    data: 'nama_ibu'
                },
                {
                    data: 'suku'
                },
                {
                    data: 'unsur'
                },
                {
                    data: 'status_domisili'
                },
                {
                    data: 'status_baptis'
                }
            ],
            rowCallback: function(row, data) {
                if (data.status_baptis == "Sudah") {
                    $('td:eq(17)', row).css('background-color', 'green');
                } else {
                    $('td:eq(17)', row).css('background-color', 'red');
                }
            },
    
        });
    }else{
        if(tableBaptis){
            tableBaptis.clear();
            tableBaptis.destroy();
            tableBaptis = $('#layakBaptis').DataTable();
        }
    }

    if(setSidi && wijk){
        // $('#printSidi').attr('href');
        // $('#cetakSidi').attr('href');
        document.getElementById("printSidi").href=window.location.origin+'/laporan/excel?item='+getParams('item')+'&wijk='+encript(wijk)+'&set_status='+encript(setSidi); 
        document.getElementById("cetakSidi").href=window.location.origin+'/laporan/print?item='+getParams('item')+'&wijk='+encript(wijk)+'&set_status='+encript(setSidi); 
        
        if((oldWijk && oldWijk != wijk) || (oldSetSidi && oldSetSidi!=setSidi)){
            tableSidi.destroy();
            oldSetSidi=setSidi;
            oldWijk=wijk;
        }else{
            oldSetSidi=setSidi;
            oldWijk=wijk;
        }
        tableSidi = $('#layakSidi').DataTable({
            processing: true,
            serverSide: true,
            // data: $scope.datas,
            ajax: '/laporan/layak_sidi?set_status='+setSidi+'&wijk='+wijk,
            order: [],
            columnDefs: [{
                targets: '_all',
                orderable: false
            }],
            scrollX: true,
            columns: [{
                    data: 'no'
                },
                {
                    data: 'wijk'
                },
                {
                    data: 'ksp'
                },
                {
                    data: 'kode_kk'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'tanggal_lahir',
                    render: function(data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return moment(data).format("DD MMMM YYYY");
                    }
                },
                {
                    data: 'sex'
                },
                {
                    data: 'golongan_darah'
                },
                {
                    data: 'status_kawin'
                },
                {
                    data: 'hubungan_keluarga'
                },
                {
                    data: 'pendidikan_terakhir'
                },
                {
                    data: 'pekerjaan'
                },
                {
                    data: 'nama_ayah'
                },
                {
                    data: 'nama_ibu'
                },
                {
                    data: 'suku'
                },
                {
                    data: 'unsur'
                },
                {
                    data: 'status_domisili'
                },
                {
                    data: 'status_sidi'
                }
            ],
            rowCallback: function(row, data) {
                if (data.status_sidi == "Sudah") {
                    $('td:eq(17)', row).css('background-color', 'green');
                } else {
                    $('td:eq(17)', row).css('background-color', 'red');
                }
            },
    
        });
    }else{
        if(tableSidi){
            tableSidi.clear();
            tableSidi.destroy();
            tableSidi = $('#layakSidi').DataTable();
        }
    }
}
$(document).ready(function() {
    
    $('#lansia').DataTable({
        processing: true,
        serverSide: true,
        // data: $scope.datas,
        ajax: '/laporan/lansia',
        order: [],
        columnDefs: [{
            targets: '_all',
            orderable: false
        }],
        scrollX: true,
        columns: [{
                data: 'no'
            },
            {
                data: 'wijk'
            },
            {
                data: 'ksp'
            },
            {
                data: 'kode_kk'
            },
            {
                data: 'nama'
            },
            {
                data: 'tanggal_lahir',
                render: function(data, type, row) {
                    if (type === "sort" || type === "type") {
                        return data;
                    }
                    return moment(data).format("DD MMMM YYYY");
                }
            },
            {
                data: 'sex'
            },
            {
                data: 'golongan_darah'
            },
            {
                data: 'status_kawin'
            },
            {
                data: 'hubungan_keluarga'
            },
            {
                data: 'pendidikan_terakhir'
            },
            {
                data: 'pekerjaan'
            },
            {
                data: 'nama_ayah'
            },
            {
                data: 'nama_ibu'
            },
            {
                data: 'suku'
            },
            {
                data: 'status_domisili'
            }
        ]
    });
    $('#tanggal').daterangepicker({
        timePicker: false,
        startDate: moment().add(1, 'weeks').startOf('isoWeek').add(-1, "days"),
        endDate: moment().add(1, 'weeks').endOf('isoWeek').add(-1, 'days'),
        locale: {
            format: 'YYYY/MM/DD'
        }
    });
    $('#meninggal').DataTable({
        processing: true,
        serverSide: true,
        // data: $scope.datas,
        ajax: '/laporan/meninggal',
        order: [],
        columnDefs: [{
            targets: '_all',
            orderable: false
        }],
        scrollX: true,
        columns: [{
                data: 'no',
                defaultContent: "",
            },
            {
                data: 'wijk_ksp',
                defaultContent: "",
            },
            {
                data: 'kode_kk',
                defaultContent: "",
            }, 
            {
                data: 'nik',
                defaultContent: "",
            },
            {
                data: 'nama',
                defaultContent: "",
            },
            {
                data: 'tanggal_meninggal',
                defaultContent: "",
                render: function(data, type, row) {
                    if (type === "sort" || type === "type") {
                        return data;
                    }
                    return moment(data).format("DD MMMM YYYY");
                }
            },
            {
                data: 'sex',
                defaultContent: "",
            },
            {
                data: 'umur',
                defaultContent: "",
            },
            {
                data: 'penyebab',
                defaultContent: "",
            },
            {
                data: 'unsur',
                defaultContent: "",
            },
            {
                data: 'suku',
                defaultContent: "",
            }
        ]
    });

    $('#pindah').DataTable({
        processing: true,
        serverSide: true,
        // data: $scope.datas,
        ajax: '/laporan/pindah',
        order: [],
        columnDefs: [{
            targets: '_all',
            defaultContent: "",
            orderable: false
        }],
        scrollX: true,
        columns: [{
                data: 'no',
                defaultContent: "",
            },
            {
                data: 'wijk_ksp',
                defaultContent: "",
            },
            {
                data: 'kode_kk',
                defaultContent: "",
            }, 
            {
                data: 'nik',
                defaultContent: "",
            },
            {
                data: 'nama',
                defaultContent: "",
            },
            {
                data: 'tanggal_lahir',
                defaultContent: "",
                render: function(data, type, row) {
                    if (type === "sort" || type === "type") {
                        return data;
                    }
                    return moment(data).format("DD MMMM YYYY");
                }
            },
            {
                data: 'sex',
                defaultContent: "",
            },
            {
                data: 'tujuan',
                defaultContent: "",
            },
            {
                data: 'tanggal_pindah',
                defaultContent: "",
            },
            {
                data: 'unsur',
                defaultContent: "",
            },
            {
                data: 'suku',
                defaultContent: "",
            }
        ]
    });
});