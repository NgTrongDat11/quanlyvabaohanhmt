<?php
/**
 * Pagination partial
 * 
 * Required variables:
 *   $pagTotal    - total number of items
 *   $pagPage     - current page (1-based)
 *   $pagPerPage  - items per page
 *   $pagBaseUrl  - base URL (query params will be appended)
 *   $pagParams   - (optional) extra query params to preserve, e.g. ['trang_thai' => 'xxx']
 */

$pagTotalPages = max(1, ceil($pagTotal / $pagPerPage));
$pagPage = max(1, min($pagPage, $pagTotalPages));
$pagParams = $pagParams ?? [];

// Build URL helper (define only once)
if (!function_exists('pagUrl')) {
    function pagUrl($page, $baseUrl, $params = []) {
        $params['trang'] = $page;
        $query = http_build_query($params);
        return $baseUrl . '?' . $query;
    }
}

if ($pagTotal > 0):
?>
<div class="pagination-wrapper">
    <div class="pagination-info">
        Hiển thị <?= (($pagPage - 1) * $pagPerPage) + 1 ?>–<?= min($pagPage * $pagPerPage, $pagTotal) ?> 
        trong tổng <strong><?= $pagTotal ?></strong> kết quả
    </div>
    <div class="pagination">
        <?php if ($pagPage > 1): ?>
            <a href="<?= pagUrl(1, $pagBaseUrl, $pagParams) ?>" class="page-link" title="Trang đầu">&laquo;</a>
            <a href="<?= pagUrl($pagPage - 1, $pagBaseUrl, $pagParams) ?>" class="page-link" title="Trang trước">&lsaquo;</a>
        <?php else: ?>
            <span class="page-link disabled">&laquo;</span>
            <span class="page-link disabled">&lsaquo;</span>
        <?php endif; ?>

        <?php
        // Show page numbers with ellipsis
        $range = 2; // pages around current page
        $startPage = max(1, $pagPage - $range);
        $endPage = min($pagTotalPages, $pagPage + $range);
        
        if ($startPage > 1): ?>
            <a href="<?= pagUrl(1, $pagBaseUrl, $pagParams) ?>" class="page-link">1</a>
            <?php if ($startPage > 2): ?><span class="page-link disabled">...</span><?php endif; ?>
        <?php endif;
        
        for ($i = $startPage; $i <= $endPage; $i++): ?>
            <?php if ($i == $pagPage): ?>
                <span class="page-link active"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= pagUrl($i, $pagBaseUrl, $pagParams) ?>" class="page-link"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor;
        
        if ($endPage < $pagTotalPages): ?>
            <?php if ($endPage < $pagTotalPages - 1): ?><span class="page-link disabled">...</span><?php endif; ?>
            <a href="<?= pagUrl($pagTotalPages, $pagBaseUrl, $pagParams) ?>" class="page-link"><?= $pagTotalPages ?></a>
        <?php endif; ?>

        <?php if ($pagPage < $pagTotalPages): ?>
            <a href="<?= pagUrl($pagPage + 1, $pagBaseUrl, $pagParams) ?>" class="page-link" title="Trang sau">&rsaquo;</a>
            <a href="<?= pagUrl($pagTotalPages, $pagBaseUrl, $pagParams) ?>" class="page-link" title="Trang cuối">&raquo;</a>
        <?php else: ?>
            <span class="page-link disabled">&rsaquo;</span>
            <span class="page-link disabled">&raquo;</span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
