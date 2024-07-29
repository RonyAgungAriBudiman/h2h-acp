
<div class="section-header">
  <h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
                <div class="card-body">
                	Selamat Datang<br>
					Untuk akses data accurate anda dapat menekan tombol ini
					<a href="<?php echo $oauthUrl?>">
						<input type="button" class="btn btn-primary" name="akses_accurate" Value="Akses Accurate">
					</a>
                    
                </div>
                
            </form>
        </div>
    </div>
</div>