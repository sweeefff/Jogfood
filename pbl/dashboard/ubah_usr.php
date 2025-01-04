<?php
// include database connection file
include 'koneksi.php';
    $id=$_POST['id'];
    $username=$_POST['username'];
    $password=$_POST['password'];
    $role=$_POST['role'];
    $result = mysqli_query($koneksi, "UPDATE user SET username='$username',password='$password',role='$role' WHERE id='$id'");
    // Redirect to homepage to display updated user in list
    header("Location: user.php");
?>
