angular.module('adminctrl', [])
    // Admin
    .controller('dashboardController', dashboardController)
    .controller('customerController', customerController)
    .controller('pemasokController', pemasokController)
    .controller('barangController', barangController)
    .controller('userController', userController)
    .controller('hargaSewaController', hargaSewaController)
    .controller('daftarJualController', daftarJualController)
    .controller('pembelianController', pembelianController)
    .controller('barangMasukController', barangMasukController)
    .controller('penyewaanController', penyewaanController)
    .controller('pengembalianController', pengembalianController)
    .controller('laporanController', laporanController)
    ;

function dashboardController($scope, dashboardServices, helperServices) {
    setTimeout(() => {
        $scope.$emit("SendUp", "Selamat datang " + sessionStorage.getItem('namaUser'));
    }, 500);
    $scope.datas = {};
    $scope.title = "Dashboard";
    console.log(helperServices.lastPath);

    var all = [];
}

function pemasokController($scope, pemasokServices, pesan, helperServices) {
    $scope.$emit("SendUp", "Pemasok");
    $scope.datas = {};
    $scope.title = "Dashboard";
    $.LoadingOverlay('show');
    pemasokServices.get().then(res => {
        $scope.datas = res;
        $.LoadingOverlay('hide');
    })

    $scope.edit = (param) => {
        $scope.model = angular.copy(param);
        $("#add").modal('show');
    }

    $scope.save = () => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "info").then(x => {
            $.LoadingOverlay('show');
            if ($scope.model.id_pemasok) {
                pemasokServices.put($scope.model).then(res => {
                    $scope.model = {}
                    $.LoadingOverlay('hide');
                    $("#add").modal('hide');
                })
            } else {
                pemasokServices.post($scope.model).then(res => {
                    $scope.model = {}
                    $.LoadingOverlay('hide');
                    $("#add").modal('hide');
                })
            }
        })
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            pemasokServices.deleted(param).then(res => {
                $.LoadingOverlay('hide');
            })
        })
    }
    $scope.showDaftar = (param) => {
        document.location.href = helperServices.url + "pemasok/daftar/" + param;
    }

}

function customerController($scope, customerServices, pesan) {
    $scope.$emit("SendUp", "Customer");
    $scope.datas = {};
    $scope.title = "Dashboard";
    $.LoadingOverlay('show');
    customerServices.get().then(res => {
        $scope.datas = res;
        $.LoadingOverlay('hide');
    })

    $scope.edit = (param) => {
        $scope.model = angular.copy(param);
        $("#add").modal('show');
    }

    $scope.save = () => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "info").then(x => {
            $.LoadingOverlay('show');
            if ($scope.model.id_customer) {
                customerServices.put($scope.model).then(res => {
                    $scope.model = {}
                    $.LoadingOverlay('hide');
                    $("#add").modal('hide');
                })
            } else {
                customerServices.post($scope.model).then(res => {
                    $scope.model = {}
                    $.LoadingOverlay('hide');
                    $("#add").modal('hide');
                })
            }
        })
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            customerServices.deleted(param).then(res => {
                $.LoadingOverlay('hide');
            })
        })
    }

}

function barangController($scope, barangServices, pesan, helperServices) {
    $scope.$emit("SendUp", "Barang");
    $scope.datas = {};
    $scope.title = "Dashboard";
    $.LoadingOverlay('show');
    barangServices.get().then(res => {
        $scope.datas = res;
        $.LoadingOverlay('hide');
    })

    $scope.edit = (param) => {
        $scope.model = angular.copy(param);
        $('#add').modal('show');
    }
    var kondisi_baik = 0;
    $scope.setStok = () => {
        if (kondisi_baik) {
            $scope.model.kondisi_baik = kondisi_baik;
        }
        if ($scope.model.kondisi_baik - $scope.model.kondisi_rusak >= 0) {
            $scope.model.kondisi_baik -= $scope.model.kondisi_rusak;
            kondisi_baik = $scope.model.kondisi_baik + $scope.model.kondisi_rusak;
        } else {
            pesan.dialog('Stok tidak boleh kurang dari 0', "Ok", null, 'error').then(x => {
                $scope.model.kondisi_rusak = 0;
                document.getElementById("kondisi_rusak").focus();
            });
        }
    }
    $scope.counter = 0;

    $scope.cekStok = () => {
        kondisi_baik = $scope.model.kondisi_baik;
        if ($scope.model.kondisi_baik <= $scope.model.minimal_stok) {
            if ($scope.counter <= 2) {
                pesan.dialog('Stok harus lebih besar dari Minimum Stok', "Ok", null, 'error').then(x => {
                    $scope.model.kondisi_baik = $scope.model.kondisi_baik + 1;
                    document.getElementById("kondisi_baik").focus();
                });
                $scope.counter += 1;
            }
        }
    }

    $scope.save = () => {
        if ($scope.model.kondisi_baik >= $scope.model.minimal_stok) {
            pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "info").then(x => {
                $.LoadingOverlay('show');
                if ($scope.model.id_barang) {
                    barangServices.put($scope.model).then(res => {
                        $scope.model = {}
                        $.LoadingOverlay('hide');
                        $('#add').modal('hide');
                        pesan.Success("Berhasil Mengubah");
                    })
                } else {
                    barangServices.post($scope.model).then(res => {
                        $scope.model = {}
                        $.LoadingOverlay('hide');
                        $('#add').modal('hide');
                        $scope.counter = 0;
                        pesan.Success("Berhasil Menambah");
                    })
                }
            })
        } else {
            pesan.dialog('Stok harus lebih besar dari Minimum Stok', "Ok", null, 'error').then(x => {
                $scope.model.kondisi_baik = 0;
            });
        }
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            barangServices.deleted(param).then(res => {
                pesan.Success("Berhasil Menghapus");
                $.LoadingOverlay('hide');
            })
        })
    }

    $scope.showHarga = (param) => {
        document.location.href = helperServices.url + "barang/harga/" + param;
    }

}

function hargaSewaController($scope, hargaSewaServices, pesan, barangServices, helperServices) {

    $scope.datas = {};
    $.LoadingOverlay('show');
    hargaSewaServices.get(helperServices.lastPath).then(res => {
        $scope.datas = res;
        console.log(res);
        $.LoadingOverlay('hide');
    })

    barangServices.getId(helperServices.lastPath).then(res => {
        // $scope.namaBarang = res.nama_barang;
        $scope.$emit("SendUp", "Harga Sewa " + res.nama_barang);
    })

    $scope.edit = (param) => {
        $scope.model = angular.copy(param);
        $('#add').modal('show');
    }

    $scope.save = () => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "info").then(x => {
            $.LoadingOverlay('show');
            $scope.model.id_barang = helperServices.lastPath;
            if ($scope.model.id_harga_sewa) {
                hargaSewaServices.put($scope.model).then(res => {
                    $scope.model = {}
                    $.LoadingOverlay('hide');
                    $('#add').modal('hide');
                })
            } else {
                hargaSewaServices.post($scope.model).then(res => {
                    $scope.model = {}
                    $.LoadingOverlay('hide');
                    $('#add').modal('hide');
                })
            }
        })
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            hargaSewaServices.deleted(param).then(res => {
                $.LoadingOverlay('hide');
            })
        })
    }
}

function userController($scope, userServices, pesan) {
    $scope.$emit("SendUp", "Manajemen User");
    $scope.datas = {};
    $.LoadingOverlay('show');
    userServices.get().then(res => {
        $scope.datas = res;
        console.log(res);

        $.LoadingOverlay('hide');
    })

    $scope.edit = (param) => {
        $scope.model = angular.copy(param);
    }

    $scope.save = () => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "info").then(x => {
            $.LoadingOverlay('show');
            if ($scope.model.id) {
                userServices.put($scope.model).then(res => {
                    $scope.model = {}
                    $.LoadingOverlay('hide');
                })
            } else {
                userServices.post($scope.model).then(res => {
                    $scope.model = {}
                    $.LoadingOverlay('hide');
                })
            }
        })
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            userServices.deleted(param).then(res => {
                $.LoadingOverlay('hide');
            })
        })
    }
}

function daftarJualController($scope, daftarJualServices, pesan, barangServices, helperServices) {
    $scope.$emit("SendUp", "Daftar Barang Jual");
    $scope.datas = {};
    $scope.model = {};
    $.LoadingOverlay('show');
    daftarJualServices.get(helperServices.lastPath).then(res => {
        $scope.datas = res;
        console.log(res);
        barangServices.get().then(rest => {
            $scope.barangs = [];
            rest.forEach(element => {
                var check = $scope.datas.find(x => x.id_barang == element.id_barang);
                if (!check) {
                    $scope.barangs.push(element);
                }
            });
            console.log(res);

        })
        $.LoadingOverlay('hide');
    })


    $scope.edit = (param) => {
        $scope.model = angular.copy(param);
    }

    $scope.save = () => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "info").then(x => {
            $.LoadingOverlay('show');
            $scope.model.id_pemasok = helperServices.lastPath;
            if ($scope.model.id_daftar_jual) {
                daftarJualServices.put($scope.model).then(res => {
                    $scope.model = {}
                    $scope.barang = undefined;
                    $.LoadingOverlay('hide');
                })
            } else {
                daftarJualServices.post($scope.model).then(res => {
                    $scope.model = {}
                    $scope.barang = undefined;
                    barangServices.get().then(rest => {
                        $scope.barangs = [];
                        rest.forEach(element => {
                            var check = $scope.datas.find(x => x.id_barang == element.id_barang);
                            if (!check) {
                                $scope.barangs.push(element);
                            }
                        });
                        console.log(res);
                        $.LoadingOverlay('hide');

                    })
                })
            }
        })
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            daftarJualServices.deleted(param).then(res => {
                $.LoadingOverlay('hide');
            })
        })
    }
}

function pembelianController($scope, pembelianServices, pesan, helperServices, pemasokServices, daftarJualServices) {
    $scope.$emit("SendUp", "Pengajuan Pembelian");
    $scope.datas = {};
    $scope.model = {};
    $scope.datas.detail = [];
    $scope.detail = undefined;
    $scope.datas.tanggal_beli = new Date();
    $scope.title = "Dashboard";
    $.LoadingOverlay('show');
    $scope.init = (param) => {
        $scope.akses = param
    }
    $scope.setDataPembelian = (param) => {
        if (param == '1')
            $scope.dataPembelian = $scope.datas.filter(x => x.status == '1' || x.status == '2');
        else
            $scope.dataPembelian = $scope.datas.filter(x => x.status == '0');

    }
    if (helperServices.lastPath == 'pembelian') {
        pembelianServices.get().then(res => {
            res.forEach(element => {
                element.tanggal_beli = new Date(element.tanggal_beli);
            });
            $scope.datas = res;
            if ($scope.akses == 'Owner') {
                $scope.dataPembelian = $scope.datas.filter(x => x.status == '0');

            }
            $.LoadingOverlay('hide');
        })
    } else if (helperServices.lastPath == 'tambah') {
        pembelianServices.getAdd().then(res => {
            $scope.datas.no_pembelian = res;
            console.log(res);
            $.LoadingOverlay('hide');
        })
    } else {
        pembelianServices.getId(helperServices.lastPath).then(res => {
            res.tanggal_beli = new Date(res.tanggal_beli);
            $scope.datas = res;
            console.log(res);
            $.LoadingOverlay('hide');
        })
    }
    pemasokServices.get().then(res => {
        $scope.pemasoks = res
    })

    $scope.showDaftar = (param) => {
        if (param) {
            $scope.model.id_pemasok = param.id_pemasok;
            $scope.model.nama_pemasok = param.nama_pemasok;
            $scope.model.status = "0";
            daftarJualServices.get(param.id_pemasok).then(res => {
                if (res.length == 0) {
                    pesan.info("Pemasok belum memiliki daftar jual");
                    $scope.pemasok = undefined;
                }
                param.detail = res;
                console.log(res);
            })
        }
    }

    $scope.checkJumlah = () => {
        if ($scope.detail.minimal_stok >= ($scope.detail.minimal_stok + $scope.detail.kondisi_baik)) {
            pesan.error('Jumlah yang anda masukkan lebih kecil atau sama dengan minimum stok \nJumlah Minimum Stok: ' + $scope.detail.minimal_stok);
            $scope.model.jumlah = parseInt($scope.detail.minimal_stok) + 1;
        }
    }

    $scope.addItem = () => {
        $scope.datas.total_pembelian = 0;
        $scope.datas.detail.push(angular.copy($scope.model));
        $scope.datas.detail.forEach(element => {
            $scope.datas.total_pembelian += (element.harga * element.jumlah);
            $scope.pemasok = undefined
            $scope.detail = undefined
            $scope.model.jumlah = 0
        });
    }

    $scope.edit = (param) => {
        $scope.pemasok = $scope.pemasoks.find(x => x.id_pemasok == param.id_pemasok);
        if ($scope.pemasok) {
            daftarJualServices.get(param.id_pemasok).then(res => {
                $scope.pemasok.detail = res;
                $scope.detail = $scope.pemasok.detail.find(x => x.id_barang == param.id_barang)
                console.log(res);
            })
        }
        $scope.model = angular.copy(param);
    }

    $scope.editPembelian = (param) => {
        document.location.href = helperServices.url + "pembelian/ubah/" + param.id_pembelian
    }

    $scope.tambah = () => {
        document.location.href = helperServices.url + "pembelian/tambah"
    }

    $scope.save = () => {
        if ($scope.datas.detail.length > 0) {
            pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "info").then(x => {
                $scope.datas.tanggal_beli = helperServices.dateTimeToString($scope.datas.tanggal_beli)
                $.LoadingOverlay('show');
                if ($scope.datas.id_pembelian) {
                    pembelianServices.put($scope.datas).then(res => {
                        pesan.Success("Berhasil");
                        $.LoadingOverlay('hide');
                        pesan.dialog("Berhasil mengubah data").then(x => {
                            document.location.href = helperServices.url + "pembelian"
                        });
                    })
                } else {
                    pembelianServices.post($scope.datas).then(res => {
                        pesan.Success("Berhasil");
                        $.LoadingOverlay('hide');
                        pesan.dialog("Berhasil menambah data").then(x => {
                            document.location.href = helperServices.url + "pembelian"
                        });
                    })
                }
            })
        } else {
            pesan.error("Belum ada daftar barang yang ditambahkan");
        }
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            pembelianServices.deleted(param).then(res => {
                pesan.Success("Berhasil Menghapus");
                $.LoadingOverlay('hide');
            })
        })
    }

    $scope.deleteItem = (param) => {
        if (param.id_detail_pembelian) {
            pemasokServices.deleteDetail(param).then(res => {
                pesan.Success('Berhasil');
            });
        } else {
            var index = $scope.datas.detail.indexOf(param);
            $scope.datas.detail.splice(index, 1);
        }
        $scope.datas.detail.forEach(element => {
            $scope.datas.total_pembelian += (element.harga * element.jumlah);
        });
    }

    $scope.showDetail = (param) => {
        $scope.detailPemasok = param.detail;
        $("#tampilDetail").modal('show');
        $scope.totalPembelian = param.detail.reduce((sum, item) => sum + (parseFloat(item.harga) * parseInt(item.jumlah)), 0);

    }

    $scope.showItemPengajuan = (param) => {
        $scope.itemPengajuan = param.detail;
        $("#add").modal('show');
    }
    $scope.updateDetail = (param, status) => {
        var set = angular.copy(param);
        set.status = status;
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "info").then(x => {
            $.LoadingOverlay('show');
            pembelianServices.putDetail(set).then(res => {
                $.LoadingOverlay('hide');
                $scope.dataPembelian = $scope.datas.filter(x => x.status == '0');
                pesan.Success("Berhasil");
                param.status = status;
            })
        })
    }

    $scope.tutup = (set) => {
        $('#' + set).modal('hide');
    }


}


function barangMasukController($scope, barangMasukServices, pesan, pembelianServices, helperServices) {
    $scope.$emit("SendUp", "Barang Masuk");
    $scope.datas = {};
    $.LoadingOverlay('show');
    $scope.show = 'daftar';
    barangMasukServices.get().then(res => {
        $scope.datas = res;
        $.LoadingOverlay('hide');
    })

    pembelianServices.getMasuk().then(res => {
        $scope.pembelians = res;
    })

    $scope.validasi = (param) => {
        $scope.model = angular.copy(param);
        $scope.model.tagihan = parseFloat($scope.model.tagihan);
        $scope.model.bayar = parseFloat($scope.model.bayar);
        $("#pembayaran").modal('show');
        console.log(param);
    }

    $scope.save = (param) => {
        pesan.dialog('Yakin yakin?', "Ya", "Tidak", "info").then(x => {
            $.LoadingOverlay('show');
            param.tanggal_masuk = helperServices.dateToString($scope.tanggal_masuk)
            barangMasukServices.post(param).then(res => {
                $scope.show = 'daftar';
                $.LoadingOverlay('hide');
            })
        })
    }

    $scope.setShow = (param) => {
        $scope.show = param;
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            barangMasukServices.deleted(param).then(res => {
                $.LoadingOverlay('hide');
            })
        })
    }
}

function penyewaanController($scope, penyewaanServices, pesan, pembelianServices, helperServices, customerServices, barangServices) {
    $scope.$emit("SendUp", "Penyewaan Alat");
    $scope.datas = {};
    $scope.datas.detail = [];
    $scope.barangs = [];
    $.LoadingOverlay('show');
    $scope.show = 'daftar';


    if (helperServices.lastPath == 'penyewaan') {
        penyewaanServices.get().then(res => {
            $scope.datas = res;
            $scope.dataPengajuan = $scope.datas.filter(x => x.status == '0' || x.status == '1');
            $scope.dataTolak = $scope.datas.filter(x => x.status == '3');
            $scope.dataPenyewaan = $scope.datas.filter(x => x.tanggal_pengembalian == null && x.status == '2');
            $scope.dataPengembalian = $scope.datas.filter(x => x.tanggal_pengembalian != null && x.status == '2');
            console.log(res);
            $.LoadingOverlay('hide');
        })
    } else if (helperServices.lastPath == 'tambah') {
        penyewaanServices.getAdd().then(res => {
            $scope.datas.no_peminjaman = res;
            console.log(res); $.LoadingOverlay('hide');
        })
        customerServices.get().then(res => {
            $scope.customers = res;
            console.log(res);

        })
        barangServices.getAll().then(res => {
            $scope.masterBarang = res;
            $scope.masterBarang.forEach(element => {
                if (parseInt(element.kondisi_baik) > parseInt(element.minimal_stok))
                    $scope.barangs.push(element);
            });
        });
    } else {
        penyewaanServices.getId(helperServices.lastPath).then(res => {
            res.tanggal_beli = new Date(res.tanggal_beli);
            $scope.datas = res;
            console.log(res);
            $.LoadingOverlay('hide');
        })
    }

    $scope.validate = (param, set) => {
        pesan.dialog('Yakin ingin melanjutkan proses', "Ya", "Tidak", "warning").then(x => {
            param.status = set;
            $.LoadingOverlay('show');
            penyewaanServices.validate(param).then(res => {
                $.LoadingOverlay('hide');
                if (set == '3')
                    $scope.dataTolak.push(param);
                // var index = $scope.dataPengajuan.indexOf(param);
                // $scope.dataPengajuan.splice(index, 1);
                $("#itemDetail").modal('hide');
                pesan.Success("Proses berhasil");
            })
        })
    }

    pembelianServices.getMasuk().then(res => {
        $scope.pembelians = res;
    })

    $scope.setBayar = (param) => {
        $scope.model = param;
        console.log(param);

        $("#setBayar").modal('show');
    }

    $scope.bayarPeminjaman = () => {
        pesan.dialog('Yakin ingin melanjutkan proses', "Ya", "Tidak", "warning").then(x => {
            $.LoadingOverlay('show');
            penyewaanServices.bayar($scope.model).then(res => {
                $.LoadingOverlay('hide');
                pesan.Success("Proses berhasil");
                $scope.model.status = '2';
            })
        })
    }

    $scope.checkStock = () => {
        var a = $scope.detail.kondisi_baik - $scope.model.jumlah;
        if ($scope.detail.kondisi_baik > $scope.model.jumlah) {
            if (a <= $scope.detail.minimal_stok) {
                pesan.info('Jumlah pinjam melebihi minimal stok');
                $scope.model.jumlah = $scope.detail.kondisi_baik - $scope.detail.minimal_stok;
            }
        } else {
            pesan.error('Maaf, stok tidak cukup maximal: ' + $scope.detail.kondisi_baik);
            $scope.model.jumlah = $scope.detail.kondisi_baik - $scope.detail.minimal_stok;
            console.log($scope.model.jumlah);
        }
    }

    $scope.hitungSelisihTanggal = () => {
        if ($scope.datas.tanggal_peminjaman && $scope.datas.tanggal_rencana_pengembalian) {
            $scope.selisih = Math.floor(($scope.datas.tanggal_rencana_pengembalian - $scope.datas.tanggal_peminjaman) / (1000 * 3600 * 24));
            console.log($scope.selisih);
        }
    };

    $scope.addItem = () => {
        $scope.datas.total_harga = 0;
        $scope.datas.detail.push(angular.copy($scope.model));
        $scope.datas.detail.forEach(element => {
            $scope.datas.total_harga += (element.harga * element.jumlah * $scope.selisih);
        });
        var index = $scope.barangs.indexOf($scope.detail);
        $scope.barangs.splice(index, 1);
        $scope.detail = undefined
        $scope.model.jumlah = 0
    }

    $scope.validasi = (param) => {
        $scope.model = angular.copy(param);
        $scope.model.tagihan = parseFloat($scope.model.tagihan);
        $scope.model.bayar = parseFloat($scope.model.bayar);
        $("#pembayaran").modal('show');
        console.log(param);
    }

    $scope.save = () => {
        pesan.dialog('Yakin yakin?', "Ya", "Tidak", "info").then(x => {
            $.LoadingOverlay('show');
            $scope.datas.tanggal_peminjaman = helperServices.dateToString($scope.datas.tanggal_peminjaman);
            $scope.datas.tanggal_rencana_pengembalian = helperServices.dateToString($scope.datas.tanggal_rencana_pengembalian);
            penyewaanServices.post($scope.datas).then(res => {

            })
        })
    }

    $scope.setShow = (param) => {
        $scope.show = param;
    }

    $scope.showItem = (param, set) => {
        $scope.model = param;
        $("#" + set).modal('show');
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            penyewaanServices.deleted(param).then(res => {
                $.LoadingOverlay('hide');
            })
        })
    }

    $scope.showPembayaran = (param) => {
        $scope.itemPembayaran = param;
        console.log(param);
        $("#bayar").modal('show');
    }

    $scope.tutup = (set) => {
        $('#' + set).modal('hide');
    }
}

function pengembalianController($scope, pengembalianServices, pesan, helperServices, penyewaanServices) {
    $scope.$emit("SendUp", "Pembembalian");
    $scope.datas = {};
    $scope.title = "Dashboard";
    $.LoadingOverlay('show');
    penyewaanServices.getPengembalian().then(res => {
        $scope.penyewaans = res;
        console.log(res);
        $.LoadingOverlay('hide');

    });
    $scope.hitungDenda = () => {
        var tanggalKembali = new Date($scope.penyewaan.tanggal_rencana_pengembalian);
        $scope.selisih = Math.floor(($scope.penyewaan.tanggal_pengembalian - tanggalKembali) / (1000 * 3600 * 24)) + 1;
        var besarDenda = $scope.selisih;
        $scope.sudahDibayar = 0;
        $scope.sisaPembayaran = 0;
        if (besarDenda > 0) {
            $scope.penyewaan.total_denda = 0;
            $scope.penyewaan.detail.forEach(element => {
                element.denda_keterlambatan = parseFloat(element.nominal_denda) * besarDenda;
                var total = element.denda_keterlambatan + (element.denda_kerusakan ? parseFloat(element.denda_kerusakan) : 0)
                $scope.penyewaan.total_denda += total;
            });

        }
        $scope.penyewaan.pembayaran.forEach(element => {
            $scope.sudahDibayar += parseFloat(element.nominal);
        });
        $scope.sisaPembayaran = ($scope.penyewaan.total_harga - ($scope.sudahDibayar)) + $scope.penyewaan.total_denda;
        console.log($scope.penyewaan.total_denda);

    }

    $scope.edit = (param) => {
        $scope.model = angular.copy(param);
        $('#add').modal('show');
    }

    $scope.save = () => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak", "info").then(x => {
            $.LoadingOverlay('show');
            var dataPenyewaan = angular.copy($scope.penyewaan);
            dataPenyewaan.tanggal_pengembalian = helperServices.dateToString(dataPenyewaan.tanggal_pengembalian)
            pengembalianServices.post(dataPenyewaan).then(res => {
                $.LoadingOverlay('hide');
                $scope.penyewaan = { tanggal_pengembalian: new Date() };
                $scope.sudahDibayar = 0;
                $scope.sisaPembayaran = 0;
            })
        })
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin melanjutkan proses?', "Ya", "Tidak").then(x => {
            $.LoadingOverlay('show');
            pengembalianServices.deleted(param).then(res => {
                pesan.Success("Berhasil Menghapus");
                $.LoadingOverlay('hide');
            })
        })
    }

    $scope.showHarga = (param) => {
        document.location.href = helperServices.url + "barang/harga/" + param;
    }

    $scope.setPembayaran = (pembayaran, sisa) => {
        if (parseFloat(pembayaran) > parseFloat(sisa)) {
            pesan.dialog("Pembayaran anda melebihi total tagihan").then(res => {
                $scope.penyewaan.nominal = sisa;
            })
        }

    }
    $scope.checkData = (param) => {
        console.log(param);

    }

    $scope.checkDisable = (param) => {
        return !$scope.penyewaan || !$scope.penyewaan.tanggal_pengembalian;
    }

}

function laporanController($scope, barangServices, pesan, helperServices, laporanServices) {
    // $scope.$emit("SendUp", "Barang");
    $scope.datas = {};
    $scope.title = "Dashboard";
    $.LoadingOverlay('show');
    barangServices.get().then(res => {
        $scope.datas = res;
        $.LoadingOverlay('hide');
    })
    if (helperServices.lastPath == 'lap_barang') {
        $scope.$emit("SendUp", "Laporan Data Barang Masuk");
    } else if (helperServices.lastPath == 'lap_peminjaman') {
        $scope.$emit("SendUp", "Laporan Data Peminjaman");
    } else if (helperServices.lastPath == 'lap_pengem') {
        $scope.$emit("SendUp", "Laporan Data Pengembalian");
    } else if (helperServices.lastPath == 'lap_pembe') {
        $scope.$emit("SendUp", "Laporan Data Pembelian");
    } else if (helperServices.lastPath == 'lap_barang_masuk') {
        $scope.$emit("SendUp", "Laporan Data Barang Masuk");
    } else if (helperServices.lastPath == 'pendapatan') {
        $scope.$emit("SendUp", "Laporan Pendapatan");

    }

    $scope.showDetail = (param) => {
        $.LoadingOverlay('show');
        laporanServices.getPendapatan(param).then(res => {
            $scope.pengembalian = res;
            $scope.pengembalian.detail.forEach(element => {
                element.denda_keterlambatan = parseFloat(element.denda_keterlambatan);
                element.denda_kerusakan = parseFloat(element.denda_kerusakan);
                element.jumlah = parseInt(element.jumlah);
                element.harga = parseInt(element.harga);
                $.LoadingOverlay('hide');
            });
            parseFloat($scope.pengembalian.harga_beli);
            $scope.totalBiaya = $scope.pengembalian.detail.reduce((sum, item) => sum + (item.jumlah * item.harga + item.denda_kerusakan + item.denda_keterlambatan), 0);
            console.log($scope.totalBiaya);
        })
    }

}

function laporanControllerrr($scope, pesan, helperServices, laporanServices, barangServices) {
    $scope.barangs = [];

    // if (helperServices.lastPath == 'lap_barang') {
    //     $scope.$emit("SendUp", "Laporan Data Barang Masuk");
    // } else if (helperServices.lastPath == 'lap_peminjaman') {
    //     $scope.$emit("SendUp", "Laporan Data Peminjaman");
    // } else if (helperServices.lastPath == 'lap_pengem') {
    //     $scope.$emit("SendUp", "Laporan Data Pengembalian");
    // } else if (helperServices.lastPath == 'lap_pembe') {
    //     $scope.$emit("SendUp", "Laporan Data Pembelian");
    // } else if (helperServices.lastPath == 'lap_barang_masuk') {
    //     $scope.$emit("SendUp", "Laporan Data Barang Masuk");
    // } else if (helperServices.lastPath == 'pendapatan') {
    //     $scope.$emit("SendUp", "Laporan Pendapatan");

    // }
    $scope.showDetail = () => {
        barangServices.get().then(res => {
            $scope.$applyAsync(x => {
                $scope.barangss = res.data;
                console.log(res);
            })
        })
        pesan.dialog('Berhasil').then(res => {

        })
        // laporanServices.getPendapatan().then(res => {
        //     console.log(res);
        // })
    }
}
