<?php
/**
 * Base Controller - Tất cả controllers kế thừa từ đây
 */

class Controller
{
    /**
     * Load view với layout
     */
    protected function render($view, $data = [], $withLayout = true)
    {
        // Chuyển data thành biến
        extract($data);

        // Require view file
        $viewFile = ROOT_PATH . '/app/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            // Check if auth/home view - these have their own HTML structure
            $skipLayoutViews = ['auth/', 'home/'];
            $skipLayout = false;
            foreach ($skipLayoutViews as $prefix) {
                if (strpos($view, $prefix) === 0) {
                    $skipLayout = true;
                    break;
                }
            }
            
            if ($skipLayout || !$withLayout) {
                // Render without layout
                require $viewFile;
            } else {
                // Capture view content
                ob_start();
                require $viewFile;
                $content = ob_get_clean();
                
                // Load layout with content
                $layoutFile = ROOT_PATH . '/app/views/layouts/main.php';
                if (file_exists($layoutFile)) {
                    require $layoutFile;
                } else {
                    echo $content;
                }
            }
        } else {
            die("View không tồn tại: " . $view);
        }
    }

    /**
     * Alias cho render (tương thích ngược)
     */
    protected function view($view, $data = [])
    {
        $this->render($view, $data);
    }

    /**
     * Load model
     */
    protected function model($model)
    {
        $modelFile = ROOT_PATH . '/app/models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die("Model không tồn tại: " . $model);
        }
    }

    /**
     * Redirect
     */
    protected function redirect($path)
    {
        // Chỉ cho redirect nội bộ (chặn open redirect)
        $path = ltrim($path, '/');
        if (preg_match('/^https?:|^\/\//', $path)) {
            $path = '';
        }
        header('Location: ' . APP_URL . '/' . $path);
        exit;
    }

    /**
     * Kiểm tra đã đăng nhập chưa
     */
    protected function isLoggedIn()
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    /**
     * Yêu cầu đăng nhập
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Lấy thông tin user hiện tại
     */
    protected function currentUser()
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Kiểm tra quyền (hỗ trợ cả ChucVu và LoaiTK)
     */
    protected function hasRole($roles)
    {
        if (!$this->isLoggedIn()) return false;
        
        $user = $this->currentUser();
        if (is_array($roles)) {
            // Kiểm tra cả ChucVu và LoaiTK
            return in_array($user['ChucVu'] ?? '', $roles) 
                || in_array($user['LoaiTK'] ?? '', $roles);
        }
        return ($user['ChucVu'] ?? '') === $roles 
            || ($user['LoaiTK'] ?? '') === $roles;
    }
}
