<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran QRIS</title>
</head>
<body>

<h2>Memproses Pembayaran...</h2>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>

<script type="text/javascript">

    window.snap.pay('{{ $snapToken }}', {

        onSuccess: function(result){
            alert("Pembayaran berhasil!");
            window.location.href = "/admin/penjualans";
        },

        onPending: function(result){
            alert("Menunggu pembayaran!");
        },

        onError: function(result){
            alert("Pembayaran gagal!");
        }

    });

</script>

</body>
</html>