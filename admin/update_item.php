<!DOCTYPE html>
<html lang="en">
<?php
include '../connection/connect.php';
error_reporting(0);
session_start();

if (isset($_POST['submit'])) {

    if (empty($_POST['d_name']) || empty($_POST['about']) || $_POST['price'] == '' || $_POST['res_name'] == '' || $_POST['stock'] == '') {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>All fields must be filled!</strong>
                  </div>';
    } else {
        $fname = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
        $extension = explode('.', $fname);
        $extension = strtolower(end($extension));
        $fnew = uniqid().'.'.$extension;
        $store = 'Res_img/items/'.basename($fnew);

        // ✅ If an image is uploaded, update it. Otherwise keep old image.
        if (! empty($fname)) {
            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif') {

                // Move file to folder
                move_uploaded_file($temp, $store);

                // ✅ Update including image
                $sql = "UPDATE items 
                        SET rs_id = '{$_POST['res_name']}',
                            title = '{$_POST['d_name']}',
                            slogan = '{$_POST['about']}',
                            price = '{$_POST['price']}',
                            stock = '{$_POST['stock']}',
                            img = '$fnew'
                        WHERE d_id = '{$_GET['menu_upd']}'";
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Invalid file type!</strong> Only JPG, JPEG, PNG, or GIF are allowed.
                          </div>';
            }
        } else {
            // ✅ No new image uploaded – keep existing image
            $sql = "UPDATE items 
                    SET rs_id = '{$_POST['res_name']}',
                        title = '{$_POST['d_name']}',
                        slogan = '{$_POST['about']}',
                        price = '{$_POST['price']}',
                        stock = '{$_POST['stock']}'
                    WHERE d_id = '{$_GET['menu_upd']}'";
        }

        // ✅ Execute query if defined
        if (isset($sql)) {
            mysqli_query($db, $sql);
            $success = '<div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Item Updated Successfully!</strong>
                        </div>';
        }
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Update Item</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <div id="main-wrapper">
        <!-- Navbar omitted for brevity -->
           <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">
                        Admin Panel
                    </a>
                </div>
                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                     
                       
                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">

                        
                      
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/users/5.jpg" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                   
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End header header -->
        <!-- Left Sidebar  -->
        <div class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                   <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                
                            </ul>
                        </li>
                        <li class="nav-label">Log</li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false">  <span><i class="fa fa-user f-s-20 "></i></span><span class="hide-menu">Users</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="allusers.php">All Users</a></li>
								<li><a href="add_users.php">Add Users</a></li>
								
                               
                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Store</span></a>
                            <ul aria-expanded="false" class="collapse">
                            <li><a href="allstores.php">All Stores</a></li>
                                <li><a href="add_store.php">Add Store</a></li>
                                
                            </ul>
                        </li>
                      <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-cubes" aria-hidden="true"></i><span class="hide-menu">Item</span></a>
                            <ul aria-expanded="false" class="collapse">
                            <li><a href="all_items.php">All Items</a></li>
								<li><a href="add_item.php">Add Item</a></li>
                              
                                
                            </ul>
                        </li>
						 <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="hide-menu">Orders</span></a>
                            <ul aria-expanded="false" class="collapse">
								<li><a href="all_orders.php">All Orders</a></li>
								  
                            </ul>
                        </li>
                         
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>
        <!-- End Left Sidebar  -->
        <!-- Page wrapper  -->
         
        <div class="page-wrapper" style="height:1200px;">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3>
                </div>
            </div>

            <div class="container-fluid">
                <?php echo $error;
echo $success; ?>
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Update Item in Store</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <?php
                    $qml = "SELECT * FROM items WHERE d_id='{$_GET['menu_upd']}'";
$rest = mysqli_query($db, $qml);
$roww = mysqli_fetch_array($rest);
?>
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Item Name</label>
                                                <input type="text" name="d_name" value="<?php echo $roww['title']; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">About</label>
                                                <input type="text" name="about" value="<?php echo $roww['slogan']; ?>" class="form-control form-control-danger" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Price (KSh)</label>
                                                <input type="number" name="price" value="<?php echo $roww['price']; ?>" class="form-control" min="0" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Image</label>
                                                <input type="file" name="file" class="form-control form-control-danger">
                                                <small>Leave empty to keep current image</small>
                                            </div>
                                        </div>

                                        <!-- ✅ New Stock Field -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Available Stock</label>
                                                <input type="number" name="stock" value="<?php echo $roww['stock']; ?>" class="form-control" min="0" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Select Store</label>
                                                <select name="res_name" class="form-control custom-select" required>
                                                    <option value="">--Select Store--</option>
                                                    <?php
                $ssql = 'SELECT * FROM store';
$res = mysqli_query($db, $ssql);
while ($row = mysqli_fetch_array($res)) {
    $selected = ($row['rs_id'] == $roww['rs_id']) ? 'selected' : '';
    echo '<option value="'.$row['rs_id'].'" '.$selected.'>'.$row['title'].'</option>';
}
?> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" name="submit" class="btn btn-success" value="Save"> 
                                    <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
