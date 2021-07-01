<?php
	require "layouts/header.view.php"; 
	require "components/navBar.view.php";
?>
	<table cellpadding="10" cellspacing="0" border="1" style="margin: 10px auto;">
		<thead>
			<tr>
				<th>Names</th>
				<th>actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user): ?>
				<tr>
					<td><?= $user['name']; ?></td>
					<td>
						<form action="destroy" method="POST">
							<input type="" name="id" value="<?= $user['id'] ?>" hidden>
							<input type="submit" name="" value="Delete">
						</form>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<form action="/names" method="POST">
		<div style="margin: 15px auto; display: flex; justify-content: center;">
			<input type="text" name="name" style="width: 500px; height: 50px; font-size: 20px;">
			<input type="submit" value="submit">
		</div>
	</form>

	<ul>
		<?php foreach ($tasks as $task): ?>
			<li><?= $task['description']; ?> <span>( <?= $task['complete'] ? 'completed' : 'not completed' ?> )</span></li>
		<?php endforeach ?>
	</ul>
	

	

<?php require "layouts/footer.view.php"; ?>
