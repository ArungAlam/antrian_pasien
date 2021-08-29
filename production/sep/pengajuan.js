$(function() {
	var form = $('#form_pengajuan');
	form.submit(function(e) {
		e.preventDefault();
		$.ajax({
			url: uri+'sep-pengajuan.php',
			type: 'POST',
			data: form.serialize(),
			dataType: 'json',
			beforeSend: function () {
				$('.bpjs-loader').css('display', 'block');
			},
			success: function(rspns) {
				$('.bpjs-loader').css('display', 'none');
				if (rspns.metaData.code != 200) {
					new PNotify({
	                    title: 'Error',
	                    text: rspns.metaData.message,
	                    type: 'error',
	                	styling: 'bootstrap3'
	                });
				} else {
					rspn = rspns.response;
					new PNotify({
	                    title: 'Sukses',
	                    text: 'Pengajuan SEP Noka '+rspn.response+' berhasil',
	                    type: 'success',
	                	styling: 'bootstrap3',
	                	addclass: 'dark'
	                });
				}
			},
			error: function () {
				$('.bpjs-loader').css('display', 'none');
				new PNotify({
                    title: 'Error',
                    text: 'Sambungan gagal',
                    type: 'error',
                	styling: 'bootstrap3'
                });
			}
		});
	}) 
})


function cek_kepesertaan(noka) {
	var dt = new Date();
	var now = [ dt.getDate(), dt.getMonth()+1, dt.getFullYear() ].join('-');
	var datas = {param: noka, tglSep: now};
	if (tglSep != '') {
		$.ajax({
			url: uri+'cek-kepesertaan.php',
			type: 'GET',
			data: datas,
			dataType: 'json',
			beforeSend: function () {
				$('.bpjs-loader').css('display', 'block');
			},
			success: function(rspns) {
				$('.bpjs-loader').css('display', 'none');
				if (rspns.metaData.code != 200) {
					new PNotify({
	                    title: 'Error',
	                    text: rspns.metaData.message,
	                    type: 'error',
	                	styling: 'bootstrap3'
	                });
				} else {
					rspn = rspns.response.peserta;
					new PNotify({
	                    title: 'Sukses',
	                    text: 'Status peserta '+rspn.statusPeserta.keterangan+' atas nama '+rspn.nama,
	                    type: 'success',
	                	styling: 'bootstrap3',
	                	addclass: 'dark'
	                });

	                $('#nama_peserta').val(rspn.nama);
				}
			},
			error: function () {
				$('.bpjs-loader').css('display', 'none');
				new PNotify({
                    title: 'Error',
                    text: 'Sambungan gagal',
                    type: 'error',
                	styling: 'bootstrap3'
                });
			}
		});
	} else {
		new PNotify({
            title: 'Error',
            text: 'Tanggal Sep tidak boleh kosong',
            type: 'error',
        	styling: 'bootstrap3'
        });
	}
}