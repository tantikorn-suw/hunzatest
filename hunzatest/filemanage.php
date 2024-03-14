<?php
require_once('connection.php');
    if(isset($_REQUEST['delete_id'])){
        $id = $_REQUEST['delete_id'];

        $select_stmt = $db->prepare ('SELECT * FROM tbl_file WHERE id = :id');
        $select_stmt->bindParam(':id',$id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        unlink("upload/".$row['image']); //delete files from folder

        // delete from db 
        $delete_stmt = $db->prepare('DELETE FROM tbl_file WHERE id = :id');
        $delete_stmt->bindParam(':id',$id);
        $delete_stmt->execute();

        header("Location: filemanage.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files Manage page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg p-1 overflow-hidden">
        <div class="container-fluid">
            <button onclick="openNav()" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a href="index.html">HOME</a>
                <a href="#">ABOUT US</a>
                <a href="#">PROJECT</a>
                <a href="#">GALLERY</a>
                <a href="filemanage.php">FILEMANAGE</a>
              </div>
            <div class="collapse navbar-collapse justify-content-around" id="">
                <a href="index.html" class="navbar-brand d-block d-lg-block" >
                    <img src="https://www.soeasyweb.com/websitetemplate/welconstruction/images/LOGO.png" height="65" />
                </a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                      <a class="nav-link   whiteanimbtn" href="index.html">HOME</a>
                    </li>
                    <li class="nav-item"> 
                      <a class="nav-link  whiteanimbtn" href="#">ABOUT US</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link  whiteanimbtn" href="#">PROJECT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link whiteanimbtn" href="#">GALLERY</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  whiteanimbtn" href="filemanage.php">FILEMANAGE</a>
                    </li>
                  </ul>
            </div>
        </div>
    </nav>
    <div class="container table-responsive text-center pt-5 pb-5">
        <h1>จัดการไฟล์</h1>
        <a href="add.php" class="btn btn-success mb-5">Upload File</a>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>File</td>
                    <td>View</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $select_stmt = $db->prepare('SELECT * FROM tbl_file');
                $select_stmt -> execute();

                while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                
                ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php
                        $file_extension = pathinfo($row['image'], PATHINFO_EXTENSION);
                        if($file_extension == 'pdf'): ?>
                            <embed src="upload/<?php echo $row['image']; ?>" width="200" height="200" type="application/pdf">
                        <?php else: ?>
                            <img src="upload/<?php echo $row['image']; ?>" width="200" height="200" alt="">
                        <?php endif; ?></td>
                        <td> <a class="btn btn-info" href="upload/<?php echo $row['image']; ?>" target="_blank">View</a></td>
                        <td><a class="btn btn-warning" href="edit.php?update_id=<?php echo $row['id']; ?>">Edit</a></td>
                        <td><a class="btn btn-danger" href="?delete_id=<?php echo $row['id']; ?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>