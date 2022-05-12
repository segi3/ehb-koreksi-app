var selected_paket_id = ''

$('#paket-selector').on('change', function() {
    selected_paket_id = $(this).find(":selected").val()

    var body = {
        "test": "test text asdasd"
    }

    GetSiswa(body)
});


const GetSiswa = async (body) => {
    const response = fetch('http://127.0.0.1:8000/api/siswa-paket', {
        method: 'POST',
        body: JSON.stringify(body),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then((response) => response.json())
    .then((responseJSON) => {
       console.log(responseJSON)
    })
    .catch((err) => {
        console.log(err)
    })
}
