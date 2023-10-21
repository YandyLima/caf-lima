let table = $('#user-types-table')
table.DataTable({
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"},
    destroy: true,
    responsive: true,
    processing: true,
    dom: 'Bfrtip',
    buttons: [{
        extend: 'excelHtml5',
        title: 'Tipos de usuarios',
        filename: 'tipos_de_usuarios',
    }],
    ajax: '/user-types-list',
    columns: [
        { data: 'id'},
        { data: 'description' },
        { data: 'active' },
        { data: 'actions' },
    ],
});
function showModalEdit(id) {
    axios.get('/user-types/'+id)
        .then(function (response) {
            document.getElementById('edit-description').value = response.data.description
            document.getElementById('edit-status').value = response.data.active
            document.getElementById('form-edit').setAttribute('action', '/user-types/'+id)
        })
        .catch(function (error) {
            showAlert('error', error.data.message)
        })
    let modal = new bootstrap.Modal(document.getElementById('modal-edit'), {
        keyboard: false
    })
    modal.show()
}

function showCreate() {
    let modal = new bootstrap.Modal(document.getElementById('modal-create'), {
        keyboard: false
    })
    document.getElementById('form-create').setAttribute('action', '/user-types')
    modal.show()
}
