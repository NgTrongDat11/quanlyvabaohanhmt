<?php
/**
 * Helper Functions - Các hàm tiện ích
 */

/**
 * Lấy URL
 */
function url($path = '')
{
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Lấy asset (css, js, images)
 * NOTE: root .htaccess đã rewrite sang public/ rồi, nên không cần thêm /public/
 */
function asset($path)
{
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Escape HTML
 */
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Debug
 */
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * Get/Set Session
 */
function session($key = null, $value = null)
{
    if ($key === null) {
        return $_SESSION;
    }

    if ($value === null) {
        return $_SESSION[$key] ?? null;
    }

    $_SESSION[$key] = $value;
}

/**
 * Get old input (sau khi redirect)
 */
function old($key, $default = '')
{
    return $_SESSION['_old'][$key] ?? $default;
}

/**
 * Set flash message
 */
function flash($key, $message = null)
{
    if ($message === null) {
        $msg = $_SESSION['_flash'][$key] ?? '';
        unset($_SESSION['_flash'][$key]);
        return $msg;
    }

    $_SESSION['_flash'][$key] = $message;
}

/**
 * Redirect helper
 */
function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}

/**
 * Đọc số tiền bằng chữ tiếng Việt
 */
function docSoTienBangChu($so)
{
    $so = intval($so);
    if ($so === 0) return 'Không đồng';

    $chuSo = ['không','một','hai','ba','bốn','năm','sáu','bảy','tám','chín'];
    $donVi = ['','nghìn','triệu','tỷ'];

    $docBaChuSo = function ($n) use ($chuSo) {
        $tram = intval($n / 100);
        $chuc = intval(($n % 100) / 10);
        $dv = $n % 10;
        $s = '';
        if ($tram > 0) {
            $s .= $chuSo[$tram] . ' trăm ';
            if ($chuc === 0 && $dv > 0) $s .= 'lẻ ';
        }
        if ($chuc > 1) {
            $s .= $chuSo[$chuc] . ' mươi ';
            if ($dv === 1) $s .= 'mốt ';
            elseif ($dv === 5) $s .= 'lăm ';
            elseif ($dv > 0) $s .= $chuSo[$dv] . ' ';
        } elseif ($chuc === 1) {
            $s .= 'mười ';
            if ($dv === 5) $s .= 'lăm ';
            elseif ($dv > 0) $s .= $chuSo[$dv] . ' ';
        } elseif ($dv > 0) {
            $s .= $chuSo[$dv] . ' ';
        }
        return $s;
    };

    $parts = [];
    $temp = $so;
    while ($temp > 0) {
        $parts[] = $temp % 1000;
        $temp = intval($temp / 1000);
    }

    $result = '';
    for ($i = count($parts) - 1; $i >= 0; $i--) {
        if ($parts[$i] > 0) {
            $result .= $docBaChuSo($parts[$i]) . $donVi[$i] . ' ';
        }
    }

    $result = trim($result) . ' đồng';
    return mb_strtoupper(mb_substr($result, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($result, 1, null, 'UTF-8');
}
