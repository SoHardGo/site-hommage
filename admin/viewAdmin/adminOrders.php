<?php
ob_start(); 
?>
<h1>Manage Orders</h1>
<div class="admin_orders">
        <?=$result_show?>
</div>
<div class="admin_list">
    <table class="admin_table">
        <tbody>
            <tr>
                <th>DATE</th>
                <th>USER</th>
                <th>CARDS</th>
                <th>FLOWERS</th>
                <th colspan="3"></th>
            </tr>
            <?=$content_orders?>
        </tbody>
    </table> 
</div>
<?php
$content_admin = ob_get_clean(); 
require 'template.php';
