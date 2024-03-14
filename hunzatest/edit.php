<?php
    require_once('connection.php');
    if(isset($_REQUEST['update_id'])){
        try {
            $id = $_REQUEST['update_id'];
            $select_stmt = $db->prepare('SELECT * FROM tbl_file WHERE id =:id');
            $select_stmt->bindParam(":id",$id);
            $select_stmt->execute();
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
        } catch (\PDOException $e) {
            $e->getMesseage();
        }
    }
    if (isset($_REQUEST['btn_update'])){
        try {
            $name = $_REQUEST['txt_name'];

            $image_file = $_FILES['txt_file']['name'];
            $type = $_FILES['txt_file']['type'];
            $size = $_FILES['txt_file']['size'];
            $temp = $_FILES['txt_file']['tmp_name'];
            $path = "upload/" . $image_file; // set upload folder path
            $directory = "upload/";// set upload folder path for update
            if ($image_file){
                if($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png" || $type == "image/gif" || $type == "application/pdf"){
                    if(!file_exists($path)){
                        if ($size < 20000000) { // 20MB 
                            unlink($directory.$row['image']); // move to temp upload folder
                            move_uploaded_file($temp,'upload/'.$image_file);
                        } else{
                            $errorMsg ="File too large";
                        }
                    } else{
                        $errorMsg ="File Already Exist";
                    }
                } else{
                    $errorMsg ="Upload JPG, JPEG , PNG , GIF , PDF ONLY";
                }
            } else{
                $image_file = $row['image']; // ใช้รูปเดิมถ้าไม่ได้เลือกรูปใหม่
            }
            if(!isset($errorMsg)){
                $update_stmt = $db->prepare("UPDATE tbl_file SET name = :name_up, image = :file_up WHERE id=:id");
                $update_stmt->bindParam(':name_up',$name);
                $update_stmt->bindParam(':file_up',$image_file);
                $update_stmt->bindParam(':id',$id);

                if($update_stmt->execute()){
                    $updateNsg = "File Updated";
                    header("refresh:2;filemanage.php");
                }
            }
        } catch (\PDOException $e) {
            $e->getMesseage();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add page</title>
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
    <div class="pt-5 pb-5 container text-center">
        <h1>อัพเดทไฟล์</h1>
        <?php
        if (isset($errorMsg)) {
            ?>
            <div class="alert alert-danger">
                <strong><?php echo $errorMsg;?></strong>
            </div>
        <?php } ?>
        <?php
        if (isset($updateMsg)) {
            ?>
            <div class="alert alert-success">
                <strong><?php echo $updateMsg;?></strong>
            </div>
        <?php } ?>
        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <div class="row">
            <label for="name" class="col-sm-3 control-label">Name</label>
            <div class="col-sm-6">
                <input type="text" name="txt_name" class="form-control" value="<?php echo $name; ?>" id="">
            </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
            <label for="name" class="col-sm-3 control-label">File</label>
            <div class="col-sm-6">
                <input type="file" name="txt_file" class="form-control" value="<?php echo $image; ?>" id="">
                <p>
                <?php
                        $file_extension = pathinfo($row['image'], PATHINFO_EXTENSION);
                        if($file_extension == 'pdf'): ?>
                            <embed src="upload/<?php echo $row['image']; ?>" width="500" height="300" type="application/pdf">
                        <?php else: ?>
                            <img src="upload/<?php echo $row['image']; ?>" width="500" height="300" alt="">
                        <?php endif; ?>
                </p>
            </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <input type="submit" value="Update" name="btn_update" class="btn btn-primary"> 
                <a href="filemanage.php" class="btn btn-danger">Cancel </a>
            </div>
        </div>
    </form>
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