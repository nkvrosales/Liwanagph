<div class="table-responsive glass-card p-3 shadow-lg">
    <table class="table table-dark table-hover align-middle mb-0">
        <thead class="bg-primary bg-opacity-10">
            <tr>
                <th class="ps-4">CUSTOMER</th>
                <th>UNITS (KW)</th>
                <th>MONTH</th>
                <th>BILL DATE</th>
                <th>DUE DATE</th>
                <th class="text-center pe-4">ACTION</th>                                        
            </tr>
        </thead>
        <tbody>
            <?php 
            $query1 = "SELECT COUNT(*) FROM user";
            $result1 = mysqli_query($con,$query1);
            $row1 = mysqli_fetch_row($result1);
            $numrows = $row1[0];
            include("paging1.php");                       
            $result = retrieve_bill_data($offset, $rowsperpage);

            while($row = mysqli_fetch_assoc($result)){
            ?>
                <tr>
                    <form action="generate_bill.php" method="post" name="form_gen_bill">
                    <?php
                        $query3 = "SELECT bdate as bdate1 from bill ,user WHERE user.id=bill.uid and user.id={$row['uid']} ORDER BY bill.id DESC ";
                        $result3 = mysqli_query($con,$query3);
                        $flag=0;
                        while($row2 = mysqli_fetch_assoc($result3)){
                            if($row2['bdate1']==$row['bdate']) $flag=1;
                        }
                        
                        if($flag==0)
                        {
                     ?>
                        <input type="hidden" name="uid" value="<?php echo $row['uid'] ?>">
                        <input type="hidden" name="uname" value="<?php echo $row['uname'] ?>">
                        
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-info bg-opacity-10 text-info rounded-circle p-2 me-3 text-center" style="width: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="fw-semibold"><?php echo $row['uname'] ?></span>
                            </div>
                        </td>
                        <td>                                                
                            <div class="input-group input-group-sm" style="max-width: 150px;">
                                <input class="form-control bg-transparent border-secondary text-white" type="number" name="units" placeholder="Units" required>
                                <span class="input-group-text bg-secondary bg-opacity-20 border-secondary text-muted">KW</span>
                            </div>
                        </td>
                        <td>
                            <select name="month" class="form-select form-select-sm bg-transparent border-secondary text-white" style="max-width: 130px;" required>
                                <option value="">Select Month</option>
                                <?php
                                $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                                foreach($months as $m) {
                                    $selected = (date('F') == $m) ? "selected" : "";
                                    echo "<option value='$m' $selected>$m</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td class="text-muted small">
                            <i class="far fa-calendar-alt me-1"></i> <?php echo $row['bdate'] ?> 
                        </td>
                        <td class="text-warning small">
                            <i class="far fa-clock me-1"></i> <?php echo $row['ddate'] ?>
                        </td>
                        <td class="text-center pe-4">
                            <button type="submit" name="generate_bill" class="btn btn-success btn-sm px-3 rounded-pill shadow-sm">
                                <i class="fas fa-magic me-1"></i> Generate
                            </button>
                        </td>
                    <?php 
                        } else {
                    ?>
                        <td colspan="5" class="text-center text-muted py-3 small italic">
                            <i class="fas fa-check-circle text-success me-2"></i> Bill already generated for this period
                        </td>
                    <?php } ?>
                    </form>
                </tr>                
            <?php } ?>
        </tbody>                
    </table>
    
    <div class="mt-4 px-3">
        <?php include("paging2.php"); ?>
    </div>
</div>