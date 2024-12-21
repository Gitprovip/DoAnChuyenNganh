<?php
    // Kiểm tra xem có thông báo thành công nào chưa
    if(isset($success_msg))
    {
        // Duyệt qua từng thông báo thành công
        foreach ($success_msg as $success_msg)
        {
            // Sử dụng SweetAlert2 để hiển thị thông báo thành công
            echo '<script>Swal.fire("'.$success_msg.'", "","success");</script>';
        }
    }
    // Kiểm tra xem có thông báo cảnh báo nào chưa
    if(isset($warning_msg))
    {
        // Duyệt qua từng thông báo cảnh báo
        foreach ($warning_msg as $warning_msg)
        {
            // Sử dụng SweetAlert2 để hiển thị thông báo cảnh báo
            echo '<script>Swal.fire("'.$warning_msg.'", "","warning");</script>';
        }
    }
    // Kiểm tra xem có thông báo thông tin nào chưa
    if(isset($info_msg))
    {
        // Duyệt qua từng thông báo thông tin
        foreach ($info_msg as $info_msg)
        {
            // Sử dụng SweetAlert2 để hiển thị thông báo thông tin
            echo '<script>Swal.fire("'.$info_msg.'", "","info");</script>';
        }
    }
    // Kiểm tra xem có thông báo lỗi nào chưa
    if(isset($error_msg))
    {
        // Duyệt qua từng thông báo lỗi
        foreach ($error_msg as $error_msg)
        {
            // Sử dụng SweetAlert2 để hiển thị thông báo lỗi
            echo '<script>Swal.fire("'.$error_msg.'", "","error");</script>';
        }
    }
?>
