<?php
/**
 * @var $params array
 * @var $hashes array
 *
 */
?>
<table class="table">
  <thead>
  <tr>
    <th scope="col">#</th>
    <th scope="col">Hash</th>
    <th scope="col">Password</th>
    <th scope="col">User id</th>
  </tr>
  </thead>
  <tbody>
	<?php
	foreach ($hashes as $key => $hash):
		?>
    <tr>
      <td><?= $key ?></td>
      <td><?= $hash['password'] ?></td>
      <td><?= $hash['decode_password'] ?></td>
      <td><?= $hash['user_id'] ?></td>
    </tr>
	<?php
	endforeach;
	?>
  </tbody>
</table>
<div>
</div>
