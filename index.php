<?php
declare(strict_types=1);

 
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
 
session_name('KHOTWA_FINAL');
session_start();

 
$sessionLifetime = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionLifetime)) {
    $_SESSION = [];
    session_destroy();
    session_start();
    $_SESSION['flash'][] = ['type' => 'warning', 'msg' => 'Session expirée par inactivité, reconnecte-toi.'];
}
if (!empty($_SESSION['user_id'])) { $_SESSION['last_activity'] = time(); }

 
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src 'self'");

 
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/controllers/UserController.php';
 
 

$user = new UserController();

 
$action = is_string($_GET['action'] ?? null) ? $_GET['action'] : 'home';

switch ($action) {
     
    case 'info':
        $infoPage = $_GET['page'] ?? '';
        if (!is_string($infoPage) || !in_array($infoPage, ['explore','emotional-support','contact','recovery-journey','community','about','smart-orientation','privacy','prevention','medical-network'], true)) { $user->notFound(); break; }
        require __DIR__ . '/views/frontoffice/info.php'; break;
    case 'home':            $user->landing();          break;
    case 'register':        $user->register();         break;
    case 'login':           $user->login();            break;
    case 'logout':          $user->logout();           break;
    case 'forgot':          $user->forgot();           break;
    case 'reset':           $user->reset();            break;

     
    case 'dashboard':       $user->dashboard();        break;
    case 'profile':         $user->profile();          break;
    case 'profile_edit':    $user->profileEdit();      break;
    case 'password':        $user->changePassword();   break;
    case 'delete_account':  $user->deleteAccount();    break;

     
    case 'admin':               $user->adminDashboard();   break;
    case 'admin_users':         $user->adminUsers();       break;
    case 'admin_user_create':   $user->adminUserCreate();  break;
    case 'admin_user_edit':     $user->adminUserEdit();    break;
    case 'admin_user_toggle':   $user->adminUserToggle();  break;
    case 'admin_user_role':     $user->adminUserRole();    break;
    case 'admin_user_delete':   $user->adminUserDelete();  break;
    case 'admin_pros':          $user->adminPros();        break;
    case 'admin_pro_decision':  $user->adminProDecision(); break;
    case 'admin_logs':          $user->adminLogs();        break;
    case 'admin_export':        $user->adminExport();      break;

     

    default:                $user->notFound();
}
