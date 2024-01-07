<?php
session_start();
require("../config.php");
require '../lib/session_user.php';
date_default_timezone_set('Asia/Jakarta');
$dt = date('Y-m-d H:i');
$dtobject = date_create($dt);
$dtobject->add(new DateInterval('P1D'));
$expdate = $dtobject->format('Y-m-d H:i');

        if (isset($_POST['buat'])) {
	        require '../lib/session_login.php';
        $jumlah = $_POST['jumlah'];
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $nama = $_SESSION['nama'];
    
        $ch = curl_init();
        $secret_key = "JDJ5JDEzJEFsRE1CZnl3dWJlRlBUcUZub1RmT3VtRGRsTDlWM2JUMkNiVVVrZE9xaEFMWHNaM3hLWnZL";
        
        curl_setopt($ch, CURLOPT_URL, "https://bigflip.id/big_sandbox_api/v2/pwf/bill");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        
        curl_setopt($ch, CURLOPT_POST, TRUE);
        
        $payloads = [
            "title" => "Deposit " . $sess_username,
            "amount" => $jumlah,
            "type" => "SINGLE",
            "expired_date" => $expdate,
            "redirect_url" => "https://digitalkreasigroup.com/indofazz",
            "is_address_required" => 0,
            "is_phone_number_required" => 0,
            "step" => 2,
            "sender_name" => $sess_username,
            "sender_email" => $sess_email
        ];
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payloads));
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/x-www-form-urlencoded"
        ));
        
        curl_setopt($ch, CURLOPT_USERPWD, $secret_key.":");
        
        $response = curl_exec($ch);
        curl_close($ch);
        $hasil = json_decode($response, true);
        var_dump($hasil);

        if ($response !== false) {
        if ($hasil) {
        $link_id = $hasil['link_id'];
        $link_url = $hasil['link_url'];
        $amount = $hasil['amount'];
        $status = $hasil['status'];
        $exp = $hasil['expired_date'];
        if (!preg_match("~^(?:f|ht)tps?://~i", $link_url)) {
        $link_url = "https://" . $link_url;
        }
        $query = "INSERT INTO deposit (kode_deposit, username, jumlah_transfer, status, exp_date, metode_isi_saldo, jenis, checkout_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt) {
          $m = 'saldo_top_up';
          $j = 'Otomatis';
          mysqli_stmt_bind_param($stmt, "ssssssss", $link_id, $sess_username, $amount, $status, $exp, $m, $j, $link_url);

        if (mysqli_stmt_execute($stmt)) {
            echo "Informasi deposit berhasil dimasukkan ke dalam database!";
            header("Location: " . $link_url);
            exit;
        } else {
            echo "Error saat memasukkan informasi deposit ke dalam database: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
        } else {
        echo "Error dalam persiapan statement: " . mysqli_error($conn);
        }
      }
    }
}    
	    require("../lib/header.php");
?>
        <!-- Start Custom CSS -->
        <style>
        .saldo-container {
        padding: 10px;
        margin-bottom: 10px; /* Adjust the value as needed */
        margin-top: 10px;
        }
        </style>
        <!-- End Custom CSS -->
        <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <!-- Start Content -->
        <div class="kt-container">
        <div class="row">
            <div class="col-lg-7">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label d-flex align-items-center saldo-container">
                            <div class="kt-widget__media mr-3">
                                <img src="<?php echo $config['web']['url'] ?>assets/media/icon/deposit.png" style="width:80px; margin-right:12px;"alt="image">
                            </div>
                            <div>
                                <h3 class="kt-portlet__head-title">
                                    Saldo Anda Saat Ini
                                </h3>
                                <a><strong style="font-size: 15px">Rp. <?php echo number_format($data_user['saldo_top_up'], 0, ',', '.'); ?></strong></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="col-lg-7">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                <i class="flaticon-coins text-primary"></i>
                                Isi Saldo
                            </h3>
                        </div>
                    </div>
                <div class="kt-portlet__body">
                <form class="form-horizontal" role="form" method="POST">
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Jumlah</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text text-primary">Rp</span></div>
                                <input type="number" class="form-control" name="jumlah" placeholder="Masukkan Jumlah Isi Saldo" id="jumlah">
                            </div>
                            <span class="form-text text-muted"><?php echo ($error['jumlah']) ? $error['jumlah'] : '';?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Nominal</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="btn-group d-flex justify-content-center" role="group" aria-label="Nominal Buttons">
                                <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 70px; height: 40px;" onclick="setNominal(50000)">50.000</button>
                                <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 70px; height: 40px;" onclick="setNominal(100000)">100.000</button>
                                <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 70px; height: 40px;" onclick="setNominal(150000)">150.000</button>
                                <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 70px; height: 40px;" onclick="setNominal(200000)">200.000</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-9 offset-xl-3 col-xl-6 offset-lg-3">
                            <div class="btn-group d-flex justify-content-center" role="group" aria-label="Nominal Buttons">
                                <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 70px; height: 40px;" onclick="setNominal(250000)">250.000</button>
                                <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 70px; height: 40px;" onclick="setNominal(500000)">500.000</button>
                                <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 70px; height: 40px;" onclick="setNominal(750000)">750.000</button>
                                <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 70px; height: 40px;" onclick="setNominal(1000000)">1.000.000</button>
                            </div>
                        </div>
                    </div>
                </div>
            
                    <div class="kt-portlet__foot">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 ">
                                    <button type="submit" name="buat" class="btn btn-primary d-flex justify-content-center align-items-center" style="width: 100%; font-weight:600; font-size:15px;">TOP UP</button>
                                </div>
                            </div>
                    </div>
                </form>
</div>
</div>


<script>
    function setNominal(value) {
        document.getElementById("jumlah").value = value;
    }
</script>

        <!-- End Page Top Up Balance -->

    </div>
</div>
</div>
</div>
</div>
        <!-- End Content -->

        <!-- Start Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
		    <i class="fa fa-arrow-up"></i>
		</div>
		<!-- End Scrolltop -->

        <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript">
        $(document).ready(function() {
	$("#tipe").change(function() {
		var tipe = $("#tipe").val();
		$.ajax({
			url: '<?php echo $config['web']['url']; ?>ajax/provider-top-up-balance.php',
			data: 'tipe=' + tipe,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#provider").html(msg);
			},error(e){
			    console.log(e)
			}
		});
	});
	$("#provider").change(function() {
		var provider = $("#provider").val();
		console.log(provider)
		$.ajax({
			url: '<?php echo $config['web']['url']; ?>ajax/pembayaran-top-up-balance.php',
			data: 'provider=' + provider,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
			    console.log(msg)
				$("#pembayaran").html(msg);
			},error(e){
			    console.log(e)
			}
		});
	});
        $("#pembayaran").change(function(){
                var method = $("#pembayaran").val();
                $.ajax({
                        url : '<?php echo $config['web']['url']; ?>ajax/rate-top-up-balance.php',
                        type  : 'POST',
                        dataType: 'html',
                        data  : 'method='+method,
                        success : function(result){
                                 $("#rate").val(result);
                        }
                });
        });  
});
        document.getElementById("transfer_pulsa").style.display = "none";
        $("#tipe").change(function() {
		var selectedCountry = $("#tipe option:selected").text();
		if (selectedCountry.indexOf('Transfer Bank') !== -1) {
			document.getElementById("transfer_pulsa").style.display = "none";
	       } else {
			document.getElementById("transfer_pulsa").style.display = "block";
	       }
	});
        function get_total(jumlah) {
        var rate = $("#rate").val();
        var result = eval(jumlah) * rate;
        $('#total').val(result);
}
$(document).on('keyup', '#jumlah', function() {
    console.log("hei")
    var jumlah = $(this).val()
     var rate = $("#rate").val();
     if(rate == ''){
         rate = 1;
     }
        var result = eval(jumlah) * rate;
        $('#total').val(result);
})
	</script>

<?php
require ("../lib/footer.php");
?>