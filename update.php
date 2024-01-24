<?php 
  require "functions.php";
 
  if (!isset($_GET['id'])) {    
    header("Location: index.php");
    exit();
  }
  
  $contact = getContact($_GET['id']);
  
  if (!$contact) {    
    header("Location: index.php");
    exit();
  }
  // if (updateContact($_POST)) {
  //   header("Location: contact.php?id={$id}");
  //   exit();
  // }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    
    $id = $_POST['id'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $birthday = $_POST['birthday'] ?? '';

    $updatedData = [
      'id' => $id,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'email' => $email,
      'phone' => $phone,
      'birthday' => $birthday
    ];
  
    if (isset($_POST['delete'])) {
      $contactId = $_POST['id'];
      deleteContact($contactId);
      header('Location: index.php');
      exit();
  }

    updateContact($updatedData);
  
    header("Location: index.php");
    exit();
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $contactId = $_POST['contact_id'];
    deleteContact($contactId);
    header('Location: index.php');
    exit();
  }
  extract($contact);
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
          <div class="col-8">
            <h1 class="display-4 mb-3">Update Contact</h1>
            <form method="post" class="bg-light p-4 border border-1">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?php echo $id ?? ''; ?>">
              <?php require "inputs.php"; ?>
              <button type="submit" class="btn btn-primary">Update Contact</button>
            </form>
          </div>
        </section>
        <section class="row mt-5">
          <div class="col-8 d-flex justify-content-center">
            <form  method="post" >
              <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
              <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
            </form>
          </div>
        </section>
      </div>
    </div>
  </main>
</body>
</html>