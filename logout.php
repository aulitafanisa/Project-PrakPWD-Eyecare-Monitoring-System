<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Logout</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
Swal.fire({
    title: 'Logout',
    text: "Yakin mau keluar?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#10367d',
    cancelButtonColor: '#aaa',
    confirmButtonText: 'Ya'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = "proses_logout.php";
    } else {
        window.history.back();
    }
});
</script>

</body>
</html>