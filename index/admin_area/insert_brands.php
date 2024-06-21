<?php 
include('../includes/connect.php');
    if(isset($_POST['insert_brand'])){
        $brand_name = mysqli_real_escape_string($con, $_POST['brand_name']);

        //select data from database
        $select_query = "SELECT * FROM brands WHERE brand_name = '$brand_name'";
        $result_select= mysqli_query($con, $select_query);
        $number=mysqli_num_rows($result_select);
        if($number > 0){
            echo "<script>alert('Brands Already Exists')</script>";
            echo "<script>window.location.href='insert_brands.php';</script>";
        }else{
            $insert_query = "INSERT INTO brands (brand_name) VALUES ('$brand_name')";
            $result = mysqli_query($con, $insert_query);
            if($result){
                echo "<script>alert('Brands Inserted Successfully')</script>";
            echo "<script>window.location.href='insert_brands.php';</script>";

            } else {
                echo "<script>alert('Brands Not Inserted: " . mysqli_error($con) . "')</script>";
            }
        }
    }
?>


<form action="" method="post" class="mb-2">
    <div class="input-group w-90 mb-2">
        <span class="input-group-text bg-info" id="basic-addon1">
            <i class='bx bxs-receipt' ></i>
        </span>
        <input type="text" class="form-control" name="brand_name" 
        placeholder="Insert Brands" 
        aria-label="Brands" aria-describedby="basic-addon1">
    </div>
    
    <div class="input-group w-10 mb-2 m-auto">
        <input type="submit" class="bg-info border-o p-2 my-3" name="insert_brand" 
        value="Insert Brands" aria-describedby="basic-addon1" >
        
    </div>
</form>