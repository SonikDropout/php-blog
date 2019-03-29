<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php 
	// Get all admin users from DB
	$admins = getAdminUsers();
	$roleOptions = ['Admin', 'Author'];				
?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
	<title>Admin | Manage users</title>
</head>
<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>
	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>
		<!-- User management is only for admins  -->
		<?php if ($_SESSION['user']['role'] == 'Author'): ?>
			<h1 style="text-align: center; margin-top: 20px;">Only admins are allowed to manage users.</h1>
		<?php else: ?>
		<!-- Middle form - to create and edit  -->
			<div class="action">
				<h1 class="page-title">Create/Edit Admin User</h1>

				<form method="post" action="<?= BASE_URL . 'admin/users.php'; ?>" autocomplete="off" >

					<!-- validation errors for the form -->
					<?php include(ROOT_PATH . '/includes/errors.php') ?>

					<!-- if editing user, the id is required to identify that user -->
					<?php if ($isEditingUser === true): ?>
						<input type="hidden" name="admin_id" value="<?= $admin_id; ?>">
					<?php endif ?>

					<input type="text" name="username" value="<?= $username; ?>" placeholder="Username">
					<input type="email" name="email" value="<?= $email ?>" placeholder="Email">
					<input type="password" name="password" placeholder="Password">
					<input type="password" name="passwordConfirmation" placeholder="Password confirmation">
					<select name="role">
						<option value="" <?= $role === "" ? 'selected' : '' ?> disabled>Assign role</option>
						<?php foreach ($roleOptions as $roleOption): ?>
							<option value="<?= $roleOption; ?>" <?= $role === $roleOption ? 'selected' : '' ?>><?= $roleOption; ?></option>
						<?php endforeach ?>
					</select>

					<!-- if editing user, display the update button instead of create button -->
					<?php if ($isEditingUser === true): ?> 
						<button type="submit" class="btn" name="update_admin">UPDATE</button>
					<?php else: ?>
						<button type="submit" class="btn" name="create_admin">Save User</button>
					<?php endif ?>
				</form>
			</div>
			<!-- // Middle form - to create and edit -->

		<!-- Display records from DB-->
		<div class="table-div">
			<!-- Display notification message -->
			<?php include(ROOT_PATH . '/includes/messages.php') ?>

			<?php if (empty($admins)): ?>
				<h1>No admins in the database.</h1>
			<?php else: ?>
				<table class="table">
					<thead>
						<th>№</th>
						<th>Admin</th>
						<th>Role</th>
						<th colspan="2">Action</th>
					</thead>
					<tbody>
					<?php foreach ($admins as $key => $admin): ?>
						<tr>
							<td><?= $key + 1; ?></td>
							<td>
								<?= $admin['username']; ?>, &nbsp;
								<?= $admin['email']; ?>	
							</td>
							<td><?= $admin['role']; ?></td>
							<td>
								<a class="fa fa-pencil btn edit" title="edit admin"
									href="users.php?edit-admin=<?= $admin['id'] ?>">
								</a>
							</td>
							<td>
								<a class="fa fa-trash btn delete" title="delete admin"
								    href="users.php?delete-admin=<?= $admin['id'] ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Display records from DB -->
		<?php endif ?>
	</div>
</body>
</html>