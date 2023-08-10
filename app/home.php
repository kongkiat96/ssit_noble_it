<?php
require_once 'procress/save_user.php';
require_once 'procress/save_service_it.php';
require_once 'auto/getalert.php';
echo @$alert;
?>
<script>
    setInterval(function() {
        $('#get_sum_it').load('auto/sum_case_it.php');
        $('#get_table_it').load('auto/table_it_user.php');
        $('#list_approve').load('auto/list_approve.php');

    }, 1000) /* time in milliseconds (ie 2 seconds)*/
</script>

<!-- Modal Case IT -->
<div class="modal fade" id="newcase" role="dialog" aria-labelledby="AddNewCase" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แจ้งใช้บริการ IT Service : </h5>&nbsp;
                    <h4><span class="badge badge-success"><?php echo $runticket; ?></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12">
                            <label for="se_id">หมวดหมู่</label>
                            <select class="form-control select2bs4" style="width: 100%;" name="se_id" id="se_id" onchange="getServiceList(this.value)" required>
                                <option value="">--- เลือก หมวดหมู่ ---</option>
                                <?php
                                $getprefix = $getdata->my_sql_select($connect, NULL, "service", "se_id AND se_status ='1' ORDER BY se_sort");
                                while ($showservice = mysqli_fetch_object($getprefix)) {
                                    echo '<option value="' . $showservice->se_id . '">' . $showservice->se_name . '</option>';
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                เลือก หมวดหมู่ .
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="se_li">ปัญหาที่พบ</label>
                            <select class="form-control select2bs4" style="width: 100%;" name="se_li" id="se_li" required>
                                <option value="">--- เลือก ปัญหาที่พบ ---</option>
                            </select>
                            <div class="invalid-feedback">
                                เลือก ปัญหาที่พบ .
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="other">รายละเอียดเพิ่มเติม</label>
                            <textarea class="form-control" name="other" id="other" required></textarea>
                            <div class="invalid-feedback">
                                ระบุ รายละเอียด.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12">
                            <label for="se_asset">รหัสสินทรัพย์</label>
                            <input type="text" name="se_asset" id="se_asset" class="form-control input-sm" required>
                            <div class="invalid-feedback">
                                ระบุ รหัสสินทรัพย์.
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="pic">กรณีมีรูปภาพประกอบ Insert Pic (.PNG, .JPG).</label>
                            <input type="file" name="pic" id="pic" accept="image/png, image/jpg" class="form-control input-sm">

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="namecall">ผู้แจ้ง</label>
                            <!-- <input type="text" class="form-control input-sm" name="namecall" id="namecall" required> -->
                            <select name="namecall" id="namecall" class="form-control select2bs4" required style="width: 100%;">
                                <option value="">--- เลือกข้อมูล ---</option>
                                <?php $getuser = $getdata->my_sql_select($connect, NULL, "user", "user_status = '1'");
                                while ($showUser = mysqli_fetch_object($getuser)) {
                                    echo '<option value="' . $showUser->user_key . '">' .  getemployee($showUser->user_key) . '</option>';
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                ระบุ ผู้แจ้ง.
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="location">สาขา</label>
                            <select class="form-control select2bs4" style="width: 100%;" name="location" id="location" required>
                                <option value="">--- เลือก สาขา ---</option>
                                <?php
                                $getbranch = $getdata->my_sql_select($connect, NULL, "branch", "id AND status ='1' ORDER BY id ");
                                while ($showbranch = mysqli_fetch_object($getbranch)) {
                                    echo '<option value="' . $showbranch->id . '">' . $showbranch->branch_name . '</option>';
                                }
                                ?>
                            </select>

                            <div class="invalid-feedback">
                                ระบุ สาขา.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="approve">ผู้อนุมัติ</label>
                            <!-- <input type="text" class="form-control input-sm" name="approve" id="approve"> -->
                            <input type="text" class="form-control input-sm" id="approve" name="approve">
                        </div>
                    </div>
                    <input type="text" hidden name="name_em" id="name_em" value="<?php echo @getemployee($userdata->user_key); ?>" class="form-control" readonly>
                    <input type="text" hidden name="gt_department" id="gt_department" value="<?php echo @prefixConvertorDepartment($getemployee->user_department); ?>" class="form-control" readonly>
                    <input type="text" hidden name="department" id="department" value="<?php echo $getemployee->user_department; ?>" readonly>
                    <input type="text" hidden name="company" id="company" value="<?php echo $getemployee->department_id; ?>" readonly>
                </div>
                <div class="modal-footer">
                    <button class="ladda-button btn btn-primary btn-square btn-ladda bg-danger" type="submit" name="save_newcase" data-style="expand-left">
                        <span class="fas fa-paper-plane"> ส่งข้อมูล</span>
                        <span class="ladda-spinner"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
<!-- End Modal new Case -->

<!-- View -->
<div class="modal fade" id="show_case" role="dialog" aria-labelledby="show_case" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="show_case">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="show_case" role="dialog" aria-labelledby="show_case" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate id="waitsave">
            <div class="modal-content">

                <div class="show_case">

                </div>
            </div>
        </form>
    </div>
</div>
<!-- End View -->
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

<!-- Cancel -->
<div class="modal fade" id="off_case" role="dialog" aria-labelledby="off_case" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" enctype="multipart/form-data" class="was-validated" id="waitsave2">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เปลี่ยนแปลง</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="offcase">

                </div>

            </div>
        </form>
    </div>
</div>

<!-- End Cancel -->

<div class="modal fade" id="approve-frm" role="dialog" aria-labelledby="approve-frm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" enctype="multipart/form-data" class="was-validated" id="waitsave2">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เปลี่ยนแปลง</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="approve-frm">

                </div>

            </div>
        </form>
    </div>
</div>


<div class="bg-white border rounded">
    <div class="row no-gutters">
        <div class="col-lg-3 col-xl-3">
            <div class="profile-content-left pt-5 pb-3 px-3 px-xl-5">
                <div class="card text-center widget-profile px-0 border-0">
                    <div class="card-img mx-auto rounded-circle">
                        <img src="../assets/img/user/noimg.jpg" width="100%" alt="user image">
                    </div>
                    <div class="card-body">
                        <h4 class="py-2 text-dark"><?php echo @getemployee($userdata->user_key); ?></h4>
                        <p><?php echo @$userdata->email; ?></p>

                    </div>
                </div>

                <hr class="w-100">
                <div class="contact-info">
                    <h5 class="text-dark mb-1" style="text-transform: uppercase;">About Information</h5>
                    <p class="text-dark font-weight-medium pt-4 mb-2" style="text-decoration: underline;">Email address</p>
                    <p><?php echo @$userdata->email; ?></p>
                    <p class="text-dark font-weight-medium pt-4 mb-2" style="text-decoration: underline;">User ID</p>
                    <p><?php echo @$userdata->username; ?></p>
                    <p class="text-dark font-weight-medium pt-4 mb-2" style="text-decoration: underline;">แผนก</p>
                    <p><?php echo @getemployee_department($userdata->user_key); ?></p>
                </div>
                <hr class="w-100">
            </div>
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="profile-content-right py-5">


                <ul class="nav nav-pills  px-3 px-xl-5 nav-style-border" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="it-tab" data-toggle="tab" href="#it" role="tab" aria-controls="it" aria-selected="false">ฝ่ายเทคโนโลยีสารสนเทศ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="false">
                            ค้นหารายงานแจ้ง</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">เปลี่ยนแปลงข้อมูล</a>
                    </li>
                    <?php
                    $chkManager =  $getdata->my_sql_query($connect, NULL, "manager", "manager_user_key = '" . $userdata->user_key . "'");

                    if (COUNT($chkManager->id) >= 1) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" id="approve-list-tab" data-toggle="tab" href="#approve-list" role="tab" aria-controls="approve-list" aria-selected="false">
                                รายการอนุมัติ</a>
                        </li>
                    <?php } ?>
                </ul>
                <hr>
                <div class="tab-content px-3 px-xl-5" id="myTabContent">

                    <div class="tab-pane fade show active" id="it" role="tabpanel" aria-labelledby="it-tab">
                        <div class="mt-3">

                        </div>
                        <div class="mt-3">
                            <div class="row" id="get_sum_it">
                                <div class="card-body d-flex align-items-center justify-content-center" style="height: 160px">
                                    <div class="sk-cube-grid">
                                        <div class="sk-cube sk-cube1"></div>
                                        <div class="sk-cube sk-cube2"></div>
                                        <div class="sk-cube sk-cube3"></div>
                                        <div class="sk-cube sk-cube4"></div>
                                        <div class="sk-cube sk-cube5"></div>
                                        <div class="sk-cube sk-cube6"></div>
                                        <div class="sk-cube sk-cube7"></div>
                                        <div class="sk-cube sk-cube8"></div>
                                        <div class="sk-cube sk-cube9"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <button type="button" class="btn btn-danger mb-2 ml-3 btn-lg btn-pill" data-toggle="modal" data-target="#newcase"><span class="fas fa-laptop-medical"></span> แจ้งปัญหา</button>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="basic-data-table">
                                        <table class="table nowrap text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Case ID</th>
                                                    <th>Ticket</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                    <th>Date success</th>

                                                    <th>ประวัติ</th>
                                                </tr>
                                            </thead>

                                            <tbody id="get_table_it">

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="approve-list" role="tabpanel" aria-labelledby="approve-list-tab">
                        <div class="mt-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="basic-data-table">
                                        <table class="table nowrap text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Case ID</th>
                                                    <th>Ticket</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                    <th>Date success</th>

                                                    <th>จัดการ</th>
                                                </tr>
                                            </thead>

                                            <tbody id="list_approve">

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                        <div class="mt-5">
                            <div class="responsive-data-table-it">
                                <!-- <table id="responsive-data-table-it" class="table dt-responsive hover" style="width:100%"> -->
                                <table id="for-home" class="table dt-responsive nowrap hover" style="font-family: sarabun; font-size: 14px;
    text-align: center;" width="100%">
                                    <thead class="font-weight-bold text-center">
                                        <tr>
                                            <!-- <td>ลำดับ</td> -->
                                            <td>Tickets : </td>
                                            <td>ชื่อผู้แจ้ง : </td>
                                            <td>สาขา : </td>
                                            <td>วันที่แจ้ง : </td>
                                            <!-- <td>เวลา</td> -->
                                            <td>สถานะ : </td>
                                            <td>รายละเอียดการแจ้ง : </td>
                                            <!-- <td>ค่าใช้จ่าย</td> -->
                                            <td>วันที่แล้วเสร็จ : </td>
                                            <td>ผู้ดำเนินการ : </td>
                                            <td>ดำเนินการ : </td>




                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        if ($_SESSION['uclass'] == 3 || $_SESSION['uclass'] == 2) {
                                            $get_total = $getdata->my_sql_select($connect, NULL, "problem_list", "ID AND (date LIKE '%" . date("Y") . "%' ) AND card_status != 'wait_approve' AND manager_approve_status != 'N' ORDER BY ticket DESC LIMIT 500 ");
                                        } else {
                                            $get_total = $getdata->my_sql_select($connect, NULL, "problem_list", "ID AND (date LIKE '%" . date("Y") . "%' ) AND user_key = '" . $_SESSION['ukey'] . "' AND card_status != 'wait_approve' AND manager_approve_status != 'N' ORDER BY ticket");
                                        }

                                        while ($show_total = mysqli_fetch_object($get_total)) {
                                            $i++;
                                        ?>
                                            <tr>
                                                <!-- <td><?php echo @$i; ?></td> -->
                                                <td><?php echo @$show_total->ticket; ?></td>

                                                <!-- <td><?php echo @getemployee($show_total->user_key); ?></td> -->
                                                <td>
                                                    <!-- <?php echo $show_total->se_namecall; ?> -->
                                                    <?php
                                                    $search = $getdata->my_sql_query($connect, NULL, "employee", "card_key ='" . $show_total->se_namecall . "'");
                                                    if (COUNT($search) == 0) {
                                                        $chkName = $show_total->se_namecall;
                                                    } else {
                                                        $chkName = getemployee($show_total->se_namecall);
                                                    }

                                                    echo $chkName;
                                                    ?>
                                                </td>
                                                <!-- <td><?php echo @getemployee_department($show_total->user_key); ?></td> -->
                                                <td><?php echo @prefixbranch($show_total->se_location); ?></td>


                                                <td><?php echo @dateConvertor($show_total->date); ?></td>
                                                <!-- <td>
                                                    <?php
                                                    if (@$show_total->time_start != NULL & @$show_total->time_start != "00:00:00") {
                                                        echo @$show_total->time_start;
                                                    } else {
                                                        echo "-";
                                                    }
                                                    ?>
                                                </td> -->

                                                <td class="text-center">
                                                    <?php
                                                    if (@$show_total->card_status == NULL) {
                                                        echo '<span class="badge badge-warning">รอดำเนินการแก้ไข</span>';
                                                    } else {
                                                        echo @cardStatus($show_total->card_status);
                                                    }

                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $show_total->se_other; ?>
                                                </td>
                                                <!-- <td>
                                                    <?php
                                                    if ($show_total->se_price != NULL) {
                                                        echo $show_total->se_price;
                                                    } else {
                                                        echo '<strong class="badge badge-danger">ไม่มีข้อมูล</strong>';
                                                    }
                                                    ?>
                                                </td> -->
                                                <td class="text-center">
                                                    <?php
                                                    if ($show_total->date_update != "0000-00-00" && $show_total->card_status != "57995055c28df9e82476a54f852bd214") {
                                                        echo @dateConvertor($show_total->date_update);
                                                    } elseif ($show_total->card_status == "57995055c28df9e82476a54f852bd214") {
                                                        echo @cardStatus($show_total->card_status);
                                                    } else {
                                                        echo '<span class="badge badge-warning">รอดำเนินการแก้ไข</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if (@$show_total->admin_update == NULL) {
                                                        echo '<span class="badge badge-warning">รอดำเนินการแก้ไข</span>';
                                                    } else {
                                                        echo @getemployee($show_total->admin_update);
                                                    }

                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo '<a href="#" data-toggle="modal" data-target="#show_case" data-whatever="' . @$show_total->ticket . '" class="btn btn-sm btn-info" data-top="toptitle" data-placement="top" title="ตรวจสอบ"><i class="fa fa-search"></i></a>&nbsp';
                                                    echo '
                <a href="?p=case_all_service&key=' . @$show_total->ticket . '" target="_blank" class="btn btn-sm btn-success" data-top="toptitle" data-placement="top" title="ประวัติดำเนินงาน"><span class="fa fa-list-ul"></span></a>&nbsp';
                                                    ?>
                                                    <a href="service/print_work.php?key=<?php echo @$show_total->ticket; ?>" target="_blank" class="btn btn-sm btn-outline-danger" data-toggle="toptitle" data-placement="top" title="พิมพ์ใบงาน"><i class="fa fa-print"></i></a>
                                                    <?php if ($_SESSION['uclass'] == '3' || $_SESSION['uclass'] == '2') {
                                                        if ($show_total->card_status != '2376b33c92767c1437421a99bbc7164f') {
                                                            echo '<a href="#" data-toggle="modal" data-target="#edit_case" data-whatever="' . @$show_total->ticket . '" class="btn btn-sm btn-secondary  btn-outline" data-top="toptitle" data-placement="top" title="ดำเนินการ"><i class="fa fa-edit"></i></a>';
                                                        }
                                                    }
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

                    <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                        <div class="mt-5">
                            <form method="post" enctype="multipart/form-data">

                                <div class="row mb-2">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="firstName">ชื่อ</label>
                                            <input type="text" class="form-control" id="firstName" value="<?php echo @$userdata->name; ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="lastName">นามสกุล</label>
                                            <input type="text" class="form-control" id="lastName" value="<?php echo @$userdata->lastname; ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="userName">User ID</label>
                                    <input type="text" class="form-control" id="userName" value="<?php echo @$userdata->username; ?>" disabled>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" value="<?php echo @$userdata->email; ?>">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="old_password">Old password</label>
                                    <input type="password" class="form-control" name="old_password" id="old_password">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="new_password">New password</label>
                                    <input type="password" class="form-control" name="new_password" id="new_password">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="re_new_password">Confirm password</label>
                                    <input type="password" class="form-control" name="re_new_password" id="re_new_password">
                                </div>

                                <div class="d-flex justify-content-end mt-5">
                                    <button type="submit" class="btn btn-outline-primary mb-2 btn-pill" name="password_edit"><span class="fas fa-key"></span> Update Password</button>&nbsp;
                                    <button type="submit" class="btn btn-outline-warning mb-2 btn-pill" name="email_edit"><span class="fas fa-envelope"></span> Update Email</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#namecall").change(function() {
            var selectedValue = $(this).val();

            // ส่งค่าที่เลือกไปยัง PHP
            $.post("getmanager.php", {
                value: selectedValue
            }, function(data) {
                // แสดงผลลัพธ์ใน input
                $("#approve").val(data);
            });
        });
    });
</script>