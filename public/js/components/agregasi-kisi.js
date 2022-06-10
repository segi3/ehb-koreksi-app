var selected_pelajaran = ''
var paket_obj = {}
const base_url = window.location.origin

$('#pelajaran-selector').on('change', () => {
    selected_pelajaran = $('#select-pelajaran').find(":selected").val()

    $('#download-form').attr('action', base_url + '/storage/mutu_kisi/' + selected_pelajaran + '_mutusoal.xls');

    console.log('selected ' + selected_pelajaran)

    $('#loading-alert').show()

    UpdateTable(selected_pelajaran)
})

$('#paket-pel-selector').on('change', () => {
    var aggkisi_datatable = $('#agregasi-kisi-table').DataTable({
        retrieve: true
    })

    selected_paket_pel = $('#select-paket-pel').find(":selected").val()

    aggkisi_datatable.clear()
    aggkisi_datatable.rows.add(paket_obj[selected_paket_pel])
    aggkisi_datatable.draw()
})

const UpdateTable = async (pelajaran) => {
    var aggkisi_datatable = $('#agregasi-kisi-table').DataTable({
        retrieve: true
    })

    var row = document.createElement("tr");
    var msg = document.createElement('td');
    msg.innerHTML = 'Loading ///'
    msg.setAttribute("colspan", "100")
    msg.setAttribute('style', 'text-align:center;')
    row.append(msg)
    $('#ujian_siswa_table').append(row);

    // -------

    $.ajax({
        type: 'GET',
        url: base_url + '/api/agregasi/kisi/' + pelajaran,
        success: (data) => {
            console.log(data.data)

            if (data.data.length == 0) {
                aggkisi_datatable.clear()
                aggkisi_datatable.draw()
                $('#loading-alert').hide()
            }

            var paketExists = [];
            paket_obj = {};

            data.data.forEach(el => {
                let paket = 'paket_' + String(el.paket)
                if (paketExists.includes(el.paket)) {
                    paket_obj[paket].push(el)
                } else {
                    paketExists.push(el.paket)
                    paket_obj[paket] = []
                    paket_obj[paket].push(el)
                }
            });
            UpdatePaketSelectorOption()

            $('#select-paket-pel').val('paket_1')
            aggkisi_datatable.clear()
            aggkisi_datatable.rows.add(paket_obj.paket_1)
            aggkisi_datatable.draw()
            $('#loading-alert').hide()
        },
        error: (err) => {
            console.log(err)
        }
    })
}

const UpdatePaketSelectorOption = () => {
    $("option[value='paket_1']").remove();
    $("option[value='paket_2']").remove();
    $("option[value='paket_3']").remove();

    Object.keys(paket_obj).forEach(key => {
        var option = document.createElement("option")
        option.value = key
        option.innerHTML = key
        $('#select-paket-pel').append(option);
    });
}
