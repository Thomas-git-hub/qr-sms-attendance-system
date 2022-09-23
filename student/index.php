
 <?php
    require_once '../includes/conn.php';
    require_once '../includes/func.php';

   sessionSet();
if($_SESSION['userType'] !== 'student') {
     session_start();
session_unset();
session_destroy();
 header('location: ../login.php?user=student');
 exit();
}
    
?>

<?php

    
        $db->orderBy("trans.trans_datetime","Desc");
        $trans = $db->rawQuery('SELECT * FROM `transaction_tbl` as trans 	
        JOIN  block_subject as bs 
            ON trans.bs_id = bs.bs_id
        JOIN subject_tbl as subj
            ON bs.subject_ref_id = subj.subject_ref_id
        JOIN  assign_sub as a_s 
            ON  bs.bs_id = a_s.bs_id 
        JOIN  instructor_tbl as i 
            ON i.ins_ref_id = a_s.ins_ref_id
            WHERE trans.std_ref_id  = ?
        order by  trans.trans_datetime DESC;
                                                                
                                
            ', Array ($_SESSION['userId']));



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
    
	<title>Student</title>
</head>
<body>


<?php
 include 'includes/topnav.php';
include 'includes/sidenav.php';
?>
<div class="container-fluid">
<div class="body-content">

  <?php  echo   $_SESSION['userId'] ?>
    
    <div class="row mt-3 mb-5">
        <div class="col">
            <div class="card card-title-banner">
                  <div class="card-body">
                    <div class="row">
                        <div class="col d-flex justify-content-center">
                            <h1 class="h1-card-title">View Attendance</h1>
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
                <th>Date</th>
                <th>Professor</th>
                <th>Subject</th>
                <th>Attendance Log</th>
             
                <th>Status</th>
            </tr>
        </thead>
        <tbody>

         <?php 

                foreach($trans as $tran){ ?>
                                
                        <tr>
                            <td data-sort="<?php   echo $tran['trans_datetime']?>"><?php echo date('M d , Y', strtotime($tran['trans_datetime'])) ?></td>
                            <td><?php   echo ucwords($tran['ins_fullname']) ?></td>
                            <td><?php  echo  strtoupper($tran['subject_name']) ?></td>
                            <td><?php echo date('h:s a', strtotime($tran['trans_datetime'])) ?></td>
                            <td>
                                <?php

                                    if($tran['type'] == 'ON-TIME') {
                                        echo ' <span class="badge rounded-pill text-bg-primary">On Time</span>';
                                    } else if($tran['type']== 'LATE') {
                                        echo ' <span class="badge rounded-pill text-bg-danger">LATE</span>';
                                    } elseif ($tran['type']=== 'EXCUSE'){
                                        echo ' <span class="badge rounded-pill text-bg-warning">EXCUSE</span>';
                                    }

                                 ?>
                            
                            </td>
                        </tr>
                <?php }



            ?>

         
        </tbody>
        <tfoot>
            <tr>
                <th>Date</th>
                <th>Professor</th>
                <th>Subject</th>
                <th>Attendance Log</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>
		</div>
	</div>

</div>


</div>


<script src="../js/sidebar.js"></script>
<script src="../js/jquery.min.js"></script>

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
        $('#datatable').DataTable({
            order: [[0, 'asc']],
        });
    });
</script>


</body>
</html>