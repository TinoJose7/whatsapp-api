var records;

var getLink = function(record) {
	console.log(isNaN(record['phone']));
	if(!isNaN(record['phone'])) {
	    var message = record['message'].replace('{name}', record['name']).replace('{date}', record['date']);

    	return 'https://api.whatsapp.com/send?phone=' + record['phone'] + '&text=' + message;
	} else {
		var message = record['message'].replace('{name}', record['name']);
    	return 'https://messenger.com/t/' + record['phone'];
	}
};

var getStatus = function(record) {
    var status = ''
    if (record['status'] === 'pending') {
        status = '<span class="label label-warning">Pending</span>';
    } else if (record['status'] === 'contacted') {
        status = '<span class="label label-success">Contacted</span>';
    }

    return status;
};

var getDate = function(record) {
	//3 hrs  = '10800000' milliseconds;
    var date = '';
    var recordedDate = new Date(record['date']);
    var recordedDateSeconds = recordedDate.getTime();

	var today = new Date();
	var todaySeconds = today.getTime();
	var diff = recordedDateSeconds - todaySeconds;

    if (record['date'] == 'NULL') {
        date = '<span class="label label-primary">Not Set</span>';
        if(record['status'] == 'contacted')
    		date = '<span class="label label-default">Not Set</span>';
    } else if (record['date'] != 'NULL') {
    	if(diff < 10800000) {
    		date = '<span class="label label-danger">'+record['date']+'</span>';
    	} else {
    		date = '<span class="label label-primary">'+record['date']+'</span>';
    	}
    	if(record['status'] == 'contacted')
    		date = '<span class="label label-default">'+record['date']+'</span>';
    }

    return date;
};

var renderRows = function(data) {
    var elements = '';
    data.forEach(function(record) {
        elements += `<tr>
					<td>` + record['name'] + `</td>
					<td>` + record['phone'] + `</td>
					<td>` + record['message'] + `</td>
					<td>
						<a data-id="` + record['id'] + `" target="_blank" href="` + getLink(record) + `" class="changeStatus btn btn-sm btn-primary">Contact</a>
					</td>
					<td class="status">` + getStatus(record) + `</td>
					<td class="status">` + getDate(record) + `</td>
					<td>
						<div class="btn-group">
						  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Action <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a type="button" data-toggle="modal" data-target="#editModal" data-id='` + record['id'] + `' > <span class="glyphicon glyphicon-edit"></span> Edit</a></li>
						    <li><a type="button" data-toggle="modal" data-id="` + record['id'] + `" class="deleteRecord"> <span class="glyphicon glyphicon-trash"></span> Remove</a></li>
						    <li><a type="button" data-toggle="modal" data-target="#setDateModal" data-id="` + record['id'] + `"> <span class="glyphicon glyphicon-calendar"></span> Set Date</a></li>	    
						  </ul>
						</div>
					</td>
				</tr>`;
    });

    $("#tableBody").html(elements);

    $('#contact-list').DataTable();
};

$(document).ready(function() {

    $.post('ajax.php', { action: 'GET' }, function(responce) {
        var resp = JSON.parse(responce);
        if (resp.status) {
            var data = resp.data;
            records = data;

            renderRows(records);
        }

    });

    $('#editModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var id = button.data('id') // Extract info from data-* attributes
        var record;
        records.forEach(function(r) {
            if (r['id'] == id) {
                record = r;
                return;
            }
        });
        console.log(records);
        var modal = $(this)
        modal.find('.modal-body input[name="id"]').val(record.id);
        modal.find('.modal-body input[name="name"]').val(record.name);
        modal.find('.modal-body input[name="phone"]').val(record.phone);
        modal.find('.modal-body textarea[name="message"]').val(record.message);
        modal.find('.modal-body select[name="status"]').val(record.status);
        modal.find('.modal-body input[name="date"]').val(record.date);
    });

    $('#setDateModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var id = button.data('id') // Extract info from data-* attributes
        var record;
        records.forEach(function(r) {
            if (r['id'] == id) {
                record = r;
                return;
            }
        });
        console.log(records);
        var modal = $(this)
        modal.find('.modal-body input[name="id"]').val(record.id);
        modal.find('.modal-body input[name="name"]').val(record.name);
        modal.find('.modal-body input[name="phone"]').val(record.phone);
        modal.find('.modal-body input[name="message"]').val(record.message);
        modal.find('.modal-body input[name="status"]').val(record.status);
        modal.find('.modal-body input[name="date"]').val(record.date);
    });

    $("form[name='contact-form']").submit(function(e) {
        e.preventDefault();

        var contactList = $("textarea[name='contactList']").val();
        var contacts = contactList.split('\n');
        console.log(contacts)

        contacts.forEach(function(pair) {
            var namePhonePair = pair.split(';');
            console.log(namePhonePair.length);
            if (namePhonePair.length !== 2) {
                alert('Please enter valid Name:Phone/UserId pair')
            }
        });

        var data = $(this).serializeArray();

        $.post('ajax.php', data, function(responce) {
            var resp = JSON.parse(responce);
            if (resp.status) {
                var data = resp.data;
                records = data;

                renderRows(records);
                alert(contacts.length + ' new contacts added.')
            }
        });
    });

    $(document).on('click', '#updateRecord', function() {
        var form = $("form#updateForm");
        var phone = form.find('input[name="phone"]').val();
        var modal = $("#editModal");

        var data = $(form).serializeArray();

        $.post('ajax.php', data, function(responce) {
            var resp = JSON.parse(responce);
            if (resp.status) {
                var data = resp.data;
                records = data;

                renderRows(records);
                modal.modal('hide');
            }
        });

    });

    $(document).on('click', '#updateDateRecord', function() {
        var form = $("form#updateDateForm");
        // var phone = form.find('input[name="phone"]').val();
        var modal = $("#setDateModal");

        var data = $(form).serializeArray();
        console.log(data);

        $.post('ajax.php', data, function(responce) {
            var resp = JSON.parse(responce);
            if (resp.status) {
                var data = resp.data;
                records = data;

                renderRows(records);
                modal.modal('hide');
            }
        });
    });

    $(document).on('click', '.deleteRecord', function() {
        var form = $("#deleteForm");
        var id = $(this).data('id');
        var r = confirm("Are you sure!");

        if (r == true) {
            form.find('input[name="id"]').val(id);
            var data = $(form).serializeArray();

            $.post('ajax.php', data, function(responce) {
                var resp = JSON.parse(responce);
                if (resp.status) {
                    var data = resp.data;
                    records = data;

                    renderRows(records);
                }
            });
        }
    });

    $(document).on('click', '.changeStatus', function() {
        var self = $(this).parent('td').parent('tr').find('td.status');
        var id = $(this).data('id');

        $.post('ajax.php', { id: id, action: 'STATUSPATCH' }, function(responce) {
            var resp = JSON.parse(responce);
            if (resp.status) {
                var data = resp.data;
                records = data;

                renderRows(records);
            }
        });
    });

    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        autoclose: true,
        todayBtn: false,
        pickerPosition: "bottom-right",
        startDate: new Date(),
    });

});