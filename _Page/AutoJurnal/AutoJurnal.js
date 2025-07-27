//Proses Auto Jurnal Simpan, Pinjam
$('#ProsesAutoJurnal').submit(function(){
    $('#NotifikasiSimpanAutoJurnal').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesAutoJurnal')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AutoJurnal/ProsesAutoJurnal.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiSimpanAutoJurnal').html(data);
            var NotifikasiSimpanAutoJurnalBerhasil=$('#NotifikasiSimpanAutoJurnalBerhasil').html();
            if(NotifikasiSimpanAutoJurnalBerhasil=="Success"){
                location.reload();
            }
        }
    });
});

//Proses Auto Jurnal Jual/Beli
$('#ProssesSimpanAutoJurnalJualBeli').submit(function(e){
    e.preventDefault();
    $('#NotifikasiSimpanAutoJurnalJualBeli').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    
    var form = $('#ProssesSimpanAutoJurnalJualBeli')[0];
    var data = new FormData(form);
    
    $.ajax({
        type: 'POST',
        url: '_Page/AutoJurnal/ProssesSimpanAutoJurnalJualBeli.php',
        data: data,
        cache: false,
        processData: false,
        contentType: false,
        success: function(response){
            // Perhatikan saya ganti 'data' menjadi 'response' untuk menghindari kebingungan
            $('#NotifikasiSimpanAutoJurnalJualBeli').html(response);
            
            // Cek apakah elemen dengan id tersebut ada di response
            if($('#NotifikasiSimpanAutoJurnalJualBeliBerhasil').length > 0 && 
               $('#NotifikasiSimpanAutoJurnalJualBeliBerhasil').html() == "Berhasil"){
               
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Proses berhasil disimpan',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan: ' + error,
                icon: 'error'
            });
        }
    });
});

//Proses Auto Jurnal SHU
$('#ProssesSimpanAutoJurnalShu').submit(function(){
    $('#NotifikasiSimpanAutoJurnalShu').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProssesSimpanAutoJurnalShu')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AutoJurnal/ProssesSimpanAutoJurnalShu.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiSimpanAutoJurnalShu').html(data);
            var NotifikasiSimpanAutoJurnalShuBerhasil=$('#NotifikasiSimpanAutoJurnalShuBerhasil').html();
            if(NotifikasiSimpanAutoJurnalShuBerhasil=="Berhasil"){
                location.reload();
            }
        }
    });
});