<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<script src="https://code.jquery.com/jquery-3.2.1.min.js">
		</script>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
		</script>
		<title>HE API</title>
	</head>
	<body>
		<div class="container">
			<div class="span10 offset1">
				<div class="row">
					<h3><?= (count($user) > 0) ? 'Edit user' : 'Create a User'; ?></h3>
				</div>
				<form action="<?= (count($user) > 0) ? '/updateUser' : '/createUser'; ?>" class="form-horizontal" method="post">
					<input type="hidden" name="_METHOD" value="<?= (count($user) > 0) ? 'POST' : 'PUT'; ?>"/>
					<input type="hidden" name="user_id" value="<?= ($user->user_id) ? $user->user_id : '0'; ?>"/>
					<div class="control-group">
						<label class="control-label">Forename</label>
						<div class="controls">
							<input name="forename" placeholder="Forename" type="text" value="<?= ($user->forename) ? $user->forename : ''; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Surname</label>
						<div class="controls">
							<input name="surname" placeholder="Surname" type="text" value="<?= ($user->surname) ? $user->surname : ''; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Email Address</label>
						<div class="controls">
							<input name="email" placeholder="Email Address" type="text" value="<?= ($user->email) ? $user->email : ''; ?>">
						</div>
					</div>
					<div class="form-actions">
						<button class="btn btn-success" type="submit"><?= (count($user) > 0) ? 'Update' : 'Create'; ?></button> <a class="btn" href="index.php">Back</a>
					</div>
				</form>
			</div>
		</div><!-- /container -->
	</body>
</html>