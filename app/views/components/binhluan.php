<!-- Component Bình Luận -->
<?php $controllerUrl = $controllerUrl ?? 'admin'; ?>
<div class="card mt-20 no-print" id="binhLuanSection">
    <div class="card-header">
        <h3 style="margin:0;">💬 Bình Luận</h3>
    </div>
    <div class="card-body">
        <textarea id="commentInput" class="form-control" rows="3"></textarea>
        <button onclick="themBinhLuan()" class="btn btn-primary mt-10">Gửi</button>
        <div id="commentList"></div>
    </div>
</div>
