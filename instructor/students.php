
<?php 
  
  require_once '../includes/conn.php';
  require_once '../includes/func.php';
 
  sessionSet();
if($_SESSION['userType'] !== 'instructor') {
session_start();
session_unset();
session_destroy();
 header('location: ../login.php?user=instructor');
 exit();
}
  if(!isset($_SESSION['classId'])){
    header('location: index.php');
  }
  



?>



<?php   

 $students = $db->rawQuery('  SELECT * FROM student_tbl as student 
                              JOIN assign_student as a_s
                                  ON a_s.student_ref_id = student.sdt_ref_id
                              join block_subject  as b_s
                                    on a_s.block_ref_id =b_s.block_ref_id
                               WHERE b_s.bs_id = ?
 ', Array ($_SESSION['classId']));


 //var_dump($students);

?>




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
     
    <!-- online lng gumagana yung box icon -->
	<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
 
    <link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/sidebar.css">
	<link rel="stylesheet" href="../css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/index.css">
    

	<title>Manage Attendance</title>
</head>
<body>
 <?php 
 //echo $_SESSION['classId']; ?>


<?php
include 'includes/topnav.php';
include 'includes/sidenav.php';
?>



<div class="container-fluid">
<div class="body-content">  

<p><?php echo  $_SESSION['classId'] ?></p>
    <div class="row mt-3 mb-5">
        <div class="col">
            <div class="card card-title-banner">
                  <div class="card-body">
                    <div class="row">
                        <div class="col d-flex justify-content-center">
                            <h1 class="h1-card-title">Manage Students</h1>
                        </div>
                      </div>
                  </div>
            </div>
        </div>
    </div>
    
    <div class="row table">
      <table id="datatable" class="table display" >
        <thead>
            <tr>
                <th>Name</th>
                <th>Guardian Phone No.</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($students as $student){ ?>
                    <tr>
                        <td><?php  echo ucwords($student['sdt_fullname']) ?></td>
                        <td><?php  echo  $student['sdt_parentNumber'] ?> </td>
                        <td><?php  echo  $student['sdt_address'] ?> </td>
                        <td><?php  echo  $student['sdt_gender'] ?> </td>
                        <td>
                      
                            <?php
                    if ($student['sdt_active']==0) { ?>

                             <button type="button" class="btn btn-status tbl-action-btn-enable" id="<?php echo $student['sdt_id'] ;?>" data-status="<?php echo $student['sdt_active'] ;?>" >Enable</button>
                    <?php  }   
                   else  {  ?>
  
                           <button type="button" class="btn btn-status tbl-action-btn-disable" id="<?php echo $student['sdt_id'] ;?>" data-status="<?php echo $student['sdt_active'] ;?>" >Disable</button>

                    <?php   }    ?>                  
                           
                        </td>
                    </tr>

               <?php }
            ?>
          
          
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Guardian Phone No.</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
    </div>
	
</div>
</div>



<script src="../js/jquery.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/sidebar.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../js/buttons.print.min.js"></script>
<script type="text/javascript" src="../js/jszip.min.js"></script>
<script type="application/json" src="../js/pdfmake.min.js.map"></script>
<script type="text/javascript" src="../js/pdfmake.min.js"></script>

<script type="text/javascript" src="../js/vfs_fonts.js"></script>
<script type="text/javascript" src="../js/buttons.html5.min.js"></script>
<script type="text/javascript" src="../js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function () {
        $('#datatable').DataTable();
    });


   
</script>

<script>
     $(document).on('click', '.btn-status', function () {
        var id = this.id;
        var status = $(this).attr("data-status");
       $.ajax({
           type: 'POST',
           url: './query/manageStudentExe.php?action=toggle',
           dataType: 'JSON',
           data: {
             'id' : id,
             'status' :status,
           },
           success: function (response) {
               console.log (response);
            if(response.res){
              
               if(response.status==1)
                   
                 $("#" + response.id ).removeClass("tbl-action-btn-disable").addClass("tbl-action-btn-enable").attr("data-status",0).html('Enable');

               else
                  $("#" + response.id ).removeClass("tbl-action-btn-enable").addClass("tbl-action-btn-disable").attr("data-status",1).html('Disable');

               location.reload();
              

            }else{
               alert('Fail')
            }
            
      
           }


       });
        
      });
</script>

</body>
</html>