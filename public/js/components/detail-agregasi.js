var selected_pelajaran = ''
var paket_obj = {}
const base_url = window.location.origin

$('#pelajaran-selector').on('change', () => {
    selected_pelajaran = $('#select-pelajaran').find(":selected").val()

    console.log('selected ' + selected_pelajaran)

    $('#loading-alert').show()

    GetMutuKisi(selected_pelajaran)
})

$('#paket-pel-selector').on('change', () => {
    var aggkisi_datatable = $('#agregasi-kisi-table').DataTable({
        retrieve: true
    })

    selected_paket_pel = $('#select-paket-pel').find(":selected").val()

    console.log(selected_paket_pel)
    InsertPieCharts(paket_obj[selected_paket_pel])
})

const UpdatePaketSelectorOption = () => {
    $("option[value='paket_1']").remove()
    $("option[value='paket_2']").remove()
    $("option[value='paket_3']").remove()

    Object.keys(paket_obj).forEach(key => {
        var option = document.createElement("option")
        option.value = key
        option.innerHTML = key
        $('#select-paket-pel').append(option);
    });
}

// https://stackoverflow.com/questions/57645062/create-multiple-chart-js-charts-from-jsonfile-data

const GetMutuKisi = (pelajaran) => {

    $.ajax({
        type: 'GET',
        url: base_url + '/api/agregasi/kisi/' + pelajaran,
        success: (data) => {
            console.log(data.data)

            if (data.data.length == 0) {
                // TODO zero condition

                $('#loading-alert').hide()
                return
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

            InsertPieCharts(paket_obj.paket_1)

        },
        error: (err) => {
            console.log(err)
        }
    })
}

const deletePieCharts = () => {
    $('#graph-container').empty()
}

const InsertPieCharts = (paket_data) => {

    deletePieCharts()

    var canvasArray = []
    $('#loading-alert').show()

    for(let i=0; i<paket_data.length; i++) {
        let id_ = 'ctx' + i

        let canvas = document.createElement('canvas')
        canvas.id = id_

        let card = document.createElement('div')
        card.classList.add('card')
        let body = document.createElement('div')
        body.classList.add('card-body')
        let rowdiv = document.createElement('div')
        rowdiv.classList.add('row')
        let pieContainer = document.createElement('div')
        pieContainer.classList.add('graph-size')
        pieContainer.classList.add('col-lg-5')
        let desc = document.createElement('div')
        desc.classList.add('col-lg-7')
        // desc.html(
        //     '<div class="">' +
        //         '<b>Soal Nomor </b> 1' +
        //     '</div>' +
        //     '<div>' +
        //         '<b>KD</b>' +
        //         '<div>' +
        //             'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dicta ducimus inventore laborum laudantium nobis dolor quaerat velit tempore doloremque reiciendis quod porro quisquam, provident, animi neque eos quis, nesciunt nemo?' +
        //         '</div>' +
        //     '</div>' +
        //     '<div>' +
        //         '<b>IBS</b>' +
        //         '<div>' +
        //             'Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus laborum dolor veniam deleniti unde architecto quis quibusdam quam nobis laboriosam aut voluptate suscipit nisi possimus est, maiores sint ab saepe.' +
        //         '</div>' +
        //     '</div>'
        // )

        pieContainer.appendChild(canvas)
        rowdiv.appendChild(pieContainer)
        rowdiv.appendChild(desc)
        body.appendChild(rowdiv)
        card.appendChild(body)

        $('#graph-container').append(
            $('<div/>', {class: 'card col-lg-6'}).append(
                $('<div/>', {class: 'card-body'}).append(
                    $('<div/>', {class: 'row'})
                        .append(
                            $('<div/>', {class: 'col-lg-5 graph-size'})
                                .append(
                                    $('<canvas/>', {id: id_})
                                )
                        )
                        .append(
                            $('<div/>', {class: 'col-lg-7'})
                                .append($('<div/>', {class: 'mt-4'})
                                    .append($('<b/>', {text: 'Soal Nomor  '}))
                                    .append($('<span/>', {text: paket_data[i].no_soal}))
                                )
                                .append($('<div/>', {class: 'mb-3'})
                                    .append($('<b/>', {text: 'Tipe Soal  '}))
                                    .append($('<span/>', {text: paket_data[i].tipe_soal}))
                                )
                                .append($('<div/>', {})
                                    .append($('<b/>', {text: 'KD'}))
                                    .append($('<p/>', {text: paket_data[i].kd}))
                                )
                                .append($('<div/>', {})
                                    .append($('<b/>', {text: 'IBS'}))
                                    .append($('<p/>', {text: paket_data[i].ibs}))
                                )
                        )
                )
            )
        )

        // ---- create chart

        const ctx = document.getElementById('ctx' + i).getContext('2d')

        const config = {
            type: 'pie',
            data: {
                labels: [
                    'Jumlah Benar', 'Jumlah Salah', 'Jumlah Kosong'
                ],
                datasets: [{
                    label: 'perbandingan jawaban siswa',
                    data: [
                        paket_data[i].jumlah_benar, paket_data[i].jumlah_salah, paket_data[i].jumlah_kosong
                    ],
                    backgroundColor: [
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                        'rgb(255, 205, 86)',
                    ],
                    hoverOffset: 4
                }]
            }
        }

        const myChart = new Chart(ctx, config)

        // break
    }
    $('#loading-alert').hide()
}

const GenerateCardWrapper = () => {


    return card
}
