<!DOCTYPE html>
<html>
<head>
	<title>WhatsApp API Project</title>	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css" />
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>Enter list of names and Phone Number / Facebook UserId</h1>
				<p>
					Enter list of names and phone number/UserId e.g <br/>
					Joe; 917563564867 <br/> 
					Zuckerberg; zuck
				</p>
				<p>First two digits of phone number must be country code</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<form name="contact-form" method="post" action="save.php">
					<input type="hidden" name="action" value="POST" />
					<div class="form-group">
						<label for="contactList">Enter Name and Phone/UserId</label>
						<textarea required name="contactList" class="form-control" id="contactList" rows="3" placeholder="Joe; 917563564867"></textarea>
					</div>
					<div class="form-group">
						<label for="message">Message</label>
						<textarea required name="message" class="form-control" id="message" rows="3"></textarea>
					</div>

					<button type="submit" class="btn btn-primary">Add to List</button>
				</form>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<h3 style="margin-top: 20px">Contact List</h3>
				<table id="contact-list" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Name</th>
							<th>Phone/UserId</th>
							<th style="max-width: 450px;">Message</th>
							<th>Contact</th>
							<th>Status</th>
							<th>Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Name</th>
							<th>Phone</th>
							<th style="max-width: 450px;">Message</th>
							<th>Contact</th>
							<th>Status</th>
							<th>Date</th>
							<th>Actions</th>
						</tr>
					</tfoot>
					<tbody id="tableBody">
					</tbody>
				</table>
			</div>
		</div>
		<div>
			<form id="deleteForm" name="delete-form" method="post" action="save.php">
				<input type="hidden" name="id" value="" />
				<input type="hidden" name="action" value="DELETE" />
			</form>
		</div>
		<div id="editModal" class="modal" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="updateForm" name="update-form" method="post" action="save.php">
							<input type="hidden" name="id" value="" />
							<input type="hidden" name="action" value="PATCH" />
							<input type="hidden" name="date" value="PATCH" />
							<div class="form-group">
								<label for="contactName">Name</label>
								<input type="text" required name="name" class="form-control" id="contactName"/>
							</div>
							<div class="form-group">
								<label for="contactPhone">Phone</label>
								<input type="text" required name="phone" class="form-control" id="contactPhone"/>
							</div>

							<div class="form-group">
								<label for="message">Message</label>
								<textarea required name="message" class="form-control" id="message" rows="3"></textarea>
							</div>

							<div class="form-group">
								<label for="status">Status</label>
								<select name="status" class="form-control" id="status">
									<option value="pending">Pending</option>
									<option value="contacted">Contacted</option>
								</select>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button id="updateRecord" type="button" class="btn btn-primary">Save changes</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!-- set date modal -->
		<div id="setDateModal" class="modal" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Set Date</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="updateDateForm" name="update-form" method="post" action="save.php">
							<input type="hidden" name="id" value="" />
							<input type="hidden" name="action" value="PATCH" />
							<div class="form-group">
								<label for="contactName">Date</label>
								<div class="input-append date form_datetime">
    								<input name="date" class="form-control" type="text" value="">
    								<span class="add-on"><i class="icon-th"></i></span>
								</div>
							</div>
							<input type="hidden" name="name" value=""/>
							<input type="hidden" name="phone" value=""/>
							<input type="hidden" name="message" value=""/>
							<input type="hidden" name="status" value=""/>
						</form>
					</div>
					<div class="modal-footer">
						<button id="updateDateRecord" type="button" class="btn btn-primary">Save changes</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /set date modal -->
	</div>

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="custom/js/index.js"></script>
</body>
</html>