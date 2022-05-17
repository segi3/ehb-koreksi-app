const LoadUjianSiswaTableData = (data) => {
    data.forEach(element => {
        var row = document.createElement("tr");
        var nisn = document.createElement('td');
        var nama_siswa = document.createElement('td');
        var nama_sekolah = document.createElement('td');
        var jurusan = document.createElement('td');
        var nilai = document.createElement('td');
        var predikat = document.createElement('td');
        var status_koreksi = document.createElement('td');

        nisn.innerHTML = element.nisn;
        nama_siswa.innerHTML = element.nama_siswa;
        nama_sekolah.innerHTML = element.nama_sekolah;
        jurusan.innerHTML = element.jurusan;

        let jumlah_benar = parseInt(element.jumlah_benar);

        if (jumlah_benar == -2) {
            predikat.innerHTML = '-';
            nilai.innerHTML = '-';
            status_koreksi.innerHTML = 'BELUM DIKOREKSI';
        } else {
            predikat.innerHTML = element.predikat;
            nilai.innerHTML = element.jumlah_benar;
            status_koreksi.innerHTML = 'SUDAH DIKOREKSI';
        }



        row.appendChild(nisn);
        row.appendChild(nama_siswa);
        row.appendChild(nama_sekolah);
        row.appendChild(jurusan);
        row.appendChild(nilai);
        row.appendChild(predikat);
        row.appendChild(status_koreksi);

        $('#ujian_siswa_table').append(row);

    });

    $('#ujian_siswa_table').DataTable().ajax().reload();
}
