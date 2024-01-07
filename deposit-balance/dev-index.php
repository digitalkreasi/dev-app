<?php
session_start();
require("../config.php");
require '../lib/session_user.php';

        if (isset($_POST['buat'])) {
	        require '../lib/session_login.php';
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
                        <div class="kt-portlet__head-label d-flex flex-column align-items-start saldo-container">
                            <h3 class="kt-portlet__head-title">
                                Saldo Anda Saat Ini
                            </h3>
                            <div>
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
                                        <input type="number" class="form-control" name="jumlah" placeholder="Masukkan Jumlah Isi Saldo" id="jumlah" >
                                    </div>
									<span class="form-text text-muted"><?php echo ($error['jumlah']) ? $error['jumlah'] : '';?></span>
								</div>
							</div>
                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <div class="row">
                                        <div class="col-lg-3 col-xl-3">
                                        </div>
                                        <div class="col-lg-9 col-xl-9">
                                            <button type="submit" name="buat" class="btn btn-primary btn-elevate btn-pill btn-elevate-air">Submit</button>
                                        </div>
                                    </div><br>
                                </div>
                            </div>
						</div>    
					</form>
				</div>
			</div>
		</div>
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