<?php
require __DIR__.'/Users/users.php';

$users = getUsers();
?>
<!-- DOC -->
<?php include __DIR__.'/partials/header.php' ?>

<div class="container">
  <p style="margin-top:10px">
    <a class="btn btn-success" href="create.php">Create new User</a>
  </p>
  <table class="table">
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Website</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
        <tr>
          <td>
            <?php if(isset($user['extension'])): ?>
              <img style="width:70px" src="<?php echo "Users/images/${user['id']}.${user['extension']}" ?>">
            <?php else: ?>
              <img style="width:70px" src="Users/Images/default.png">
            <?php endif; ?>
          </td>
          <td><?php echo $user['name'] ?></td>
          <td><?php echo $user['username'] ?></td>
          <td><?php echo $user['email'] ?></td>
          <td><?php echo $user['phone'] ?></td>
          <td>
            <a target="_blank" href="https://<?php echo $user['website'] ?>">
              <?php echo $user['website'] ?></a>
          </td>
          <td>
            <a href="view.php?id=<?php echo $user['id'] ?>" class="btn btn-sm btn-outline-info">View</a>
            <a href="update.php?id=<?php echo $user['id'] ?>" class="btn btn-sm btn-outline-secondary">Update</a>
            <form action="delete.php" method="POST">
              <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__.'/partials/footer.php' ?>
