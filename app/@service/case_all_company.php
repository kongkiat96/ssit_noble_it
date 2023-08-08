<?php
include_once 'procress/dataSave.php';
$card_detail = $getdata->my_sql_query($connect, NULL, "problem_list", "company ='" . htmlspecialchars($_GET['key']) . "'");
$getmenus = $getdata->my_sql_query($connect, null, 'menus', "menu_status ='1' AND menu_case = 'service' AND menu_key != 'c6c8729b45d1fec563f8453c16ff03b8'");

?>
<!-- Tab row -->
<div class="row">
   <div class="col-lg-12">
      <h3 class="page-header"><i class="fa fa-chart-bar fa-2x"></i> รายการแจ้งใช้บริการของ <u><?php echo @prefixConvertorCompany($card_detail->company); ?></u></h3>
   </div>
</div>

<nav aria-label="breadcrumb" class="mt-3 mb-3">
   <ol class="breadcrumb breadcrumb-inverse">
      <li class="breadcrumb-item">
         <a href="index.php">หน้าแรก</a>
      </li>
      <li class="breadcrumb-item">
         <a href="index.php?p=service"><?php echo '<span>' . $getmenus->menu_name . '</span>'; ?></a>
      </li>
      <li class="breadcrumb-item">
         <a href="index.php?p=show_work">สรุปการทำงาน</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">สรุปแยกเฉพาะบริษัท</li>
   </ol>
</nav>

<div class="modal fade" id="edit_case" role="dialog" aria-labelledby="edit_case" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate id="waitsave">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">ดำเนินการ</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="editcase">

            </div>
            <div class="modal-footer">

               <button class="ladda-button btn btn-primary btn-square btn-ladda bg-info" type="submit" name="save_editcase" data-style="expand-left">
                  <span class="fas fa-save"> บันทึก</span>
                  <span class="ladda-spinner"></span>
               </button>
            </div>
         </div>
      </form>
   </div>
</div>

<div class="card card-default">
   <div class="card-header card-header-border-bottom">
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
         <li class="nav-item ">
            <a class="nav-link active" id="ipills-success-tab" data-toggle="pill" href="#ipills-success" role="tab" aria-controls="ipills-success" aria-selected="true">
               <i class="mdi mdi-file-chart mr-1"></i> ใช้งานได้ปกติ</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" id="ipills-wait-tab" data-toggle="pill" href="#ipills-wait" role="tab" aria-controls="ipills-wait" aria-selected="false">
               <i class="mdi mdi-chart-bar mr-1"></i> รอการดำเนินการ</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" id="ipills-cancel-tab" data-toggle="pill" href="#ipills-cancel" role="tab" aria-controls="ipills-cancel" aria-selected="false">
               <i class="mdi mdi-chart-bar mr-1"></i> ยกเลิกโดยผู้ใช้ / เจ้าหน้าที่</a>
         </li>
      </ul>
   </div>
   <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="ipills-success" role="tabpanel" aria-labelledby="ipills-success-tab">

         <div class="card-body">

            <div class="responsive-data-table">
               <table id="responsive-data-table-it-success-all-company" class="table dt-responsive nowrap" style="width:100%" data-order='[ 0, "DESC" ]'>
                  <thead class="font-weight-bold text-center">
                     <tr>
                        <td>Ticket</td>
                        <td>ผู้แจ้ง</td>
                        <td>แผนก</td>
                        <td>วันที่แจ้ง</td>
                        <td>สถานะ</td>
                        <td>วันที่แล้วเสร็จ</td>
                        <td>ตรวจสอบ</td>
                        <td>แก้ไขข้อมูล</td>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $get_total = $getdata->my_sql_select($connect, NULL, "problem_list", "company = '" . $card_detail->company . "' AND card_status = '2e34609794290a770cb0349119d78d21' AND (date LIKE '%" . date("Y") . "%') ORDER BY ID DESC");
                     while ($show_total = mysqli_fetch_object($get_total)) {
                     ?>
                        <tr>
                           <td>
                              <?php
                              echo $show_total->ticket;
                              ?>

                           </td>
                           <td>
                              <?php echo @getemployee($show_total->user_key); ?>
                           </td>
                           <td><?php echo @getemployee_department($show_total->user_key); ?></td>
                           <td><?php echo @dateConvertor($show_total->date); ?></td>
                           <td>
                              <?php
                              echo @cardStatus($show_total->card_status);
                              ?>
                           </td>
                           <td>
                              <?php
                              echo @dateConvertor($show_total->date_update);
                              ?>
                           </td>
                           <td class="text-center">
                              <?php
                              echo '<a href="?p=case_all_service&key=' . @$show_total->ticket . '" class="btn btn-sm btn-outline-success" data-top="toptitle" data-placement="top" title="รายละเอียด" target="_blank"><i class="fa fa-history"></i></a>';
                              ?>

                           </td>
                           <td class="text-center">
                              <?php
                              echo '<a href="#" data-toggle="modal" data-target="#edit_case" data-whatever="' . @$show_total->ticket . '" class="btn btn-sm btn-secondary  btn-outline" data-top="toptitle" data-placement="top" title="ดำเนินการ"><i class="fa fa-edit"></i></a>';
                              ?>
                           </td>
                        </tr>
                     <?php
                     }
                     ?>
                  </tbody>
               </table>
            </div>

         </div>


      </div>
      <div class=" tab-pane fade" id="ipills-wait" role="tabpanel" aria-labelledby="ipills-wait-tab">
         <div class="card-body">
            <div class="responsive-data-table">
               <table id="responsive-data-table-it-wait" class="table dt-responsive nowrap" style="width:100%" data-order='[ 3, "DESC" ]'>
                  <thead class="font-weight-bold text-center">
                     <tr>
                        <td>Ticket</td>
                        <td>ผู้แจ้ง</td>
                        <td>แผนก</td>
                        <td>วันที่แจ้ง</td>
                        <td>สถานะ</td>
                        <td>วันที่แล้วเสร็จ</td>
                        <td>ตรวจสอบ</td>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $get_total = $getdata->my_sql_select($connect, NULL, "problem_list", "company = '" . $card_detail->company . "' AND card_status IS NULL AND date LIKE '%" . date("Y") . "%'  ORDER BY ticket DESC");
                     while ($show_total = mysqli_fetch_object($get_total)) {
                     ?>
                        <tr>
                           <td>
                              <?php
                              echo $show_total->ticket;
                              ?>

                           </td>
                           <td>
                              <?php echo @getemployee($show_total->user_key); ?>
                           </td>
                           <td><?php echo @getemployee_department($show_total->user_key); ?></td>
                           <td><?php echo @dateConvertor($show_total->date); ?></td>
                           <td>
                              <?php
                              echo @cardStatus($show_total->card_status);
                              ?>
                           </td>
                           <td>
                              <?php
                              if ($show_total->date_update == 0000 - 00 - 00) {
                                 echo @cardStatus($show_total->card_status);
                              } else {
                                 echo @dateConvertor($show_total->date_update);
                              }

                              ?>
                           </td>
                           <td>
                              <?php
                              echo '<a href="?p=case_all_service&key=' . @$show_total->ticket . '" class="btn btn-sm btn-outline-success" data-top="toptitle" data-placement="top" title="รายละเอียด" target="_blank"><i class="fa fa-history"></i></a>';
                              ?>
                           </td>
                        </tr>
                     <?php
                     }
                     ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <div class="tab-pane fade" id="ipills-cancel" role="tabpanel" aria-labelledby="ipills-cancel-tab">
         <div class="card-body">
            <div class="responsive-data-table">
               <table id="responsive-data-table-it-cancel" class="table dt-responsive nowrap" style="width:100%" data-order='[ 3, "DESC" ]'>
                  <thead class="font-weight-bold text-center">
                     <tr>
                        <td>Ticket</td>
                        <td>ผู้แจ้ง</td>
                        <td>แผนก</td>
                        <td>วันที่แจ้ง</td>
                        <td>สถานะ</td>
                        <td>วันที่แล้วเสร็จ</td>
                        <td>ตรวจสอบ</td>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $get_total = $getdata->my_sql_select($connect, NULL, "problem_list", "company = '" . $card_detail->company . "' AND card_status = '57995055c28df9e82476a54f852bd214' AND date LIKE '%" . date("Y") . "%' ORDER BY ticket DESC");
                     while ($show_total = mysqli_fetch_object($get_total)) {
                     ?>
                        <tr>
                           <td>
                              <?php
                              echo $show_total->ticket;
                              ?>

                           </td>
                           <td>
                              <?php echo @getemployee($show_total->user_key); ?>
                           </td>
                           <td><?php echo @getemployee_department($show_total->user_key); ?></td>
                           <td><?php echo @dateConvertor($show_total->date); ?></td>
                           <td>
                              <?php
                              echo @cardStatus($show_total->card_status);
                              ?>
                           </td>
                           <td>
                              <?php
                              echo @dateConvertor($show_total->date_update);
                              ?>
                           </td>
                           <td>
                              <?php
                              echo '<a href="?p=case_all_service&key=' . @$show_total->ticket . '" class="btn btn-sm btn-outline-success" data-top="toptitle" data-placement="top" title="รายละเอียด" target="_blank"><i class="fa fa-history"></i></a>';
                              ?>
                           </td>
                        </tr>
                     <?php
                     }
                     ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="card-footer card-footer-border-bottom text-center">
      <a href="?p=show_work" class="btn btn-md btn-outline-primary"><i class="fas fa-arrow-circle-left"></i> กลับ</a>
   </div>
</div>