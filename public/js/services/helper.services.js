angular.module('helper.service', []).factory('helperServices', helperServices);

function helperServices($location) {
    var service = { IsBusy: false, absUrl: $location.$$absUrl };
    service.url = $location.$$protocol + '://' + $location.$$host;
    if ($location.$$port) {
        // service.url = service.url + ':' + $location.$$port + '/wisatasorong/';
        service.url = service.url + ':' + $location.$$port + '/';
        // service.url = service.url + '/';
        // service.url = 'https://rest.usn-papua.ac.id/';
    }

    // '    http://localhost:5000';

    service.groupBy = (list, keyGetter) => {
        const map = new Map();
        list.forEach((item) => {
            const key = keyGetter(item);
            const collection = map.get(key);
            if (!collection) {
                map.set(key, [item]);
            } else {
                collection.push(item);
            }
        });
        return map;
    };
    service.romanize = (num) => {
        if (isNaN(num))
            return NaN;
        var digits = String(+num).split(""),
            key = ["", "C", "CC", "CCC", "CD", "D", "DC", "DCC", "DCCC", "CM",
                "", "X", "XX", "XXX", "XL", "L", "LX", "LXX", "LXXX", "XC",
                "", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX"],
            roman = "",
            i = 3;
        while (i--)
            roman = (key[+digits.pop() + (i * 10)] || "") + roman;
        return Array(+digits.join("") + 1).join("M") + roman;
    }
    service.encript = (item) => {
        return btoa(item + '*pendataanJemaat');
    }
    service.biaya = [{desc: "VVA",nominal:300000}, {desc: "SBU",nominal:500000}, {desc: "IT",nominal:300000}, {desc: "PJK",nominal:300000},{desc: "Form",nominal:300000},{desc: "Meterai",nominal:100000}];
    service.tahapan = [{id: 1,tahapan: "Pembayaran"}, {id: 2,tahapan: "Validasi Berkas"}, {id: 3,tahapan:"Sertifikat SBU"}, {id: 4,tahapan:"Sertifikat KTA",}, {id: 5,tahapan:"Selesai"}];
    service.decript = (item) => {
        var string = atob(item);
        var pecah = string.split('*');
        return pecah[0];
    }
    service.urlParams = (item) => {
        var url = new URLSearchParams(window.location.search);
        return url.get(item);
    }

    service.lastPath = () => {
        const a = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
        return a;
    }
    service.dateToString = (date) => {
        return date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + date.getDate();
        // let d = new Date(2010, 7, 5);
        // let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
        // let mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(d);
        // let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
        // console.log(`${da}-${mo}-${ye}`);
    }

    service.dateTimeToString = (date) => {
        return date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":00";
        // let d = new Date(2010, 7, 5);
        // let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
        // let mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(d);
        // let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
        // console.log(`${da}-${mo}-${ye}`);
    }
    service.randNumber = (length) => {
        const characters ='0123456789';
        let result = '';
        const charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
    service.enkrip = (data)=>{
        return btoa(data + '*pendataanJemaat');
    }
    service.dekrip = (data)=>{
        var dekrip = atob(data);
        var pecah = dekrip.split("*");
        return pecah[0];
    }

    service.unsur = ['PAR', 'PAM', 'PW', 'PKB']

    service.golonganDarah = ["A",
        "B",
        "AB",
        "O",
        "A+",
        "A-",
        "B+",
        "B-",
        "AB+",
        "AB-",
        "O+",
        "O-",
        "TIDAK TAHU"
    ];
    service.agama = ["ISLAM",
        "KRISTEN",
        "KATHOLIK",
        "HINDU",
        "BUDHA",
        "KHONGHUCU",
        "Lainnya"];
    service.hubungan = [
        "KEPALA KELUARGA",
        "SUAMI",
        "ISTRI",
        "ANAK",
        "MENANTU",
        "SAUDARA",
        "CUCU",
        "ORANGTUA",
        "MERTUA",
        "FAMILI LAIN",
        "PEMBANTU",
        "LAINNYA"

    ];
    service.pendidikan = [
        "TIDAK / BELUM SEKOLAH",
        "PAUD",
        "BELUM TAMAT SD/SEDERAJAT",
        "TAMAT SD / SEDERAJAT",
        "SLTP/SEDERAJAT",
        "SLTA / SEDERAJAT",
        "DIPLOMA I / II",
        "AKADEMI/ DIPLOMA III/S. MUDA",
        "DIPLOMA IV/ STRATA I",
        "STRATA II",
        "STRATA III"
    ];
    service.pekerjaan = [
        "BELUM/TIDAK BEKERJA",
        "MENGURUS RUMAH TANGGA",
        "PELAJAR",
        "MAHASISWA",
        "PENSIUNAN",
        "PEGAWAI NEGERI SIPIL (PNS)",
        "TENTARA NASIONAL INDONESIA (TNI)",
        "KEPOLISIAN RI (POLRI)",
        "PERDAGANGAN",
        "PETANI/PEKEBUN",
        "PETERNAK",
        "NELAYAN/PERIKANAN",
        "INDUSTRI",
        "KONSTRUKSI",
        "TRANSPORTASI",
        "KARYAWAN SWASTA",
        "KARYAWAN BUMN",
        "KARYAWAN BUMD",
        "KARYAWAN HONORER",
        "BURUH HARIAN LEPAS",
        "BURUH TANI/PERKEBUNAN",
        "BURUH NELAYAN/PERIKANAN",
        "BURUH PETERNAKAN",
        "PEMBANTU RUMAH TANGGA",
        "TUKANG CUKUR",
        "TUKANG LISTRIK",
        "TUKANG BATU",
        "TUKANG KAYU",
        "TUKANG SOL SEPATU",
        "TUKANG LAS/PANDAI BESI",
        "TUKANG JAHIT",
        "TUKANG GIGI",
        "PENATA RIAS",
        "PENATA BUSANA",
        "PENATA RAMBUT",
        "MEKANIK",
        "SENIMAN",
        "TABIB",
        "PARAJI",
        "PERANCANG BUSANA",
        "PENTERJEMAH",
        "IMAM MASJID",
        "PENDETA",
        "PASTOR",
        "WARTAWAN",
        "USTADZ/MUBALIGH",
        "JURU MASAK",
        "PROMOTOR ACARA",
        "ANGGOTA DPR-RI",
        "ANGGOTA DPD",
        "ANGGOTA BPK",
        "PRESIDEN",
        "WAKIL PRESIDEN",
        "ANGGOTA MAHKAMAH KONSTITUSI",
        "ANGGOTA KABINET KEMENTERIAN",
        "DUTA BESAR",
        "GUBERNUR",
        "WAKIL GUBERNUR",
        "BUPATI",
        "WAKIL BUPATI",
        "WALIKOTA",
        "WAKIL WALIKOTA",
        "ANGGOTA DPRD PROVINSI",
        "ANGGOTA DPRD KABUPATEN/KOTA",
        "DOSEN",
        "GURU",
        "PILOT",
        "PENGACARA",
        "NOTARIS",
        "ARSITEK",
        "AKUNTAN",
        "KONSULTAN",
        "DOKTER",
        "BIDAN",
        "PERAWAT",
        "APOTEKER",
        "PSIKIATER/PSIKOLOG",
        "PENYIAR TELEVISI",
        "PENYIAR RADIO",
        "PELAUT",
        "GOJEK/GRAB",
        "PENELITI",
        "SOPIR",
        "PIALANG",
        "PARANORMAL",
        "PEDAGANG",
        "PERANGKAT DESA",
        "KEPALA DESA",
        "BIARAWATI",
        "WIRASWASTA",
        "LAINNYA"
    ];
    service.lastPath = document.location.href.substring(document.location.href.lastIndexOf('/') + 1);
    return service;
}