<?php
  require "functions.php";

  if (isset($_GET['search'])) {
    $contacts = searchContacts($_GET['search']);
  } else {
    $contacts = getContacts();
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $contactId = $_POST['contact_id'];
    deleteContact($contactId);
    header('Location: index.php');
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<?php require "head.php"; ?>
<body>
  <main id="app" class="container my-5 bg-white">
    <div class="row justify-content-center">
      <div class="col-8 p-5">
        <?php require "header.php"; ?>
        <section class="row">
          <div class="col-12">
            <h1 class="display-4 mb-3">Contact Book</h1>
            <form class="form mb-3">
              <input type="search" name="search" class="form-control" placeholder="Search" value="<?php echo $search ?? '' ?>">
            </form>
            <ul class="list-group">
              <?php foreach ($contacts as $contact): ?>
              <li class="list-group-item">
                <a href="contact.php?id=<?php echo $contact['id']; ?>" class="text-decoration-none">
                  <?php echo "{$contact['first_name']} {$contact['last_name']}"; ?>
                </a>
                <!-- <form  method="post" >
                  <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                  <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                </form> -->
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </section>
      </div>
    </div>
  </main>
</body>
</html>