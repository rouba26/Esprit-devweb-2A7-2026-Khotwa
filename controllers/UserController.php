<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

 
class UserController
{
    public const SPECIALITES = ['Psychologue', 'Addictologue', 'Psychiatre', 'Médecin généraliste',
                                'Infirmier(ère)', 'Assistant(e) social(e)', 'Autre'];
    private const ROLES          = ['user', 'professional', 'admin'];
    private const MAX_ATTEMPTS   = 5;      
    private const LOCK_MINUTES   = 15;     
    private const PER_PAGE       = 10;
    private const LOGS_PER_PAGE  = 15;

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

     
     
     
    public static function isAuthenticated(): bool
    {
        return !empty($_SESSION['user_id']);
    }

     
    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            flash('warning', 'Connecte-toi pour accéder à cette page.');
            redirect('login');
        }
        $stmt = Database::getConnexion()->prepare('SELECT role, actif FROM users WHERE id = :id');
        $stmt->execute(['id' => (int) $_SESSION['user_id']]);
        $row = $stmt->fetch();
        if (!$row || !(int) $row['actif']) {
            self::endSession();
            flash('error', "Ton compte n'est plus disponible.");
            redirect('login');
        }
        $_SESSION['role'] = $row['role'];
    }

    public static function requireRole(string ...$roles): void
    {
        self::requireAuth();
        if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
            http_response_code(403);
            require __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }

    private static function endSession(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        session_start();                
        session_regenerate_id(true);
    }

    public static function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private static function checkCsrf(): bool
    {
        $t = $_POST['csrf_token'] ?? '';
        return is_string($t) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $t);
    }

    private function isPost(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }

     
    private function requirePost(string $backTo): void
    {
        if (!$this->isPost() || !self::checkCsrf()) {
            flash('error', 'Requête invalide ou jeton de sécurité expiré.');
            redirect($backTo);
        }
    }

    private function render(string $view, array $data = []): void
    {
        if (str_starts_with($view, 'backoffice/')) {
            $data['pendingPros'] = $this->countPendingPros();    
        }
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    private function log(?int $userId, string $action, string $details = ''): void
    {
        $stmt = $this->db->prepare('INSERT INTO logs (user_id, action, details, ip) VALUES (:u, :a, :d, :ip)');
        $stmt->execute(['u' => $userId, 'a' => $action, 'd' => mb_substr($details, 0, 255), 'ip' => $_SERVER['REMOTE_ADDR'] ?? '']);
    }

    private function me(): int { return (int) $_SESSION['user_id']; }

    private function get(string $key): string
    {
        return is_string($_GET[$key] ?? null) ? trim($_GET[$key]) : '';
    }

    private function post(string $key): string
    {
        return is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }

     
     
     
    private function checkIdentity(string $username, string $email): array
    {
        $err = [];
        if (!preg_match('/^[a-zA-Z0-9_\-]{3,50}$/', $username)) {
            $err[] = 'Pseudo : 3 à 50 caractères (lettres, chiffres, _ ou -).';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            $err[] = 'Adresse e-mail invalide.';
        }
        return $err;
    }

    private function checkPassword(string $p): array
    {
        if (strlen($p) < 8 || strlen($p) > 72) {
            return ['Mot de passe : entre 8 et 72 caractères.'];
        }
        if (!preg_match('/[A-Za-z]/', $p) || !preg_match('/\d/', $p)) {
            return ['Mot de passe : au moins une lettre et un chiffre.'];
        }
        return [];
    }

    private function checkPro(array $p, bool $horaires): array
    {
        $err = [];
        if (!in_array($p['specialite'] ?? '', self::SPECIALITES, true)) { $err[] = 'Choisis une spécialité.'; }
        $ville = trim((string) ($p['ville'] ?? ''));
        if (mb_strlen($ville) < 2 || mb_strlen($ville) > 100) { $err[] = 'Ville : 2 à 100 caractères.'; }
        if (!preg_match('/^[0-9 +]{8,20}$/', trim((string) ($p['telephone'] ?? '')))) { $err[] = 'Téléphone invalide (chiffres, espaces ou +).'; }
        $ordre = trim((string) ($p['numero_ordre'] ?? ''));
        if (mb_strlen($ordre) < 3 || mb_strlen($ordre) > 50) { $err[] = "Numéro d'ordre / diplôme : 3 à 50 caractères."; }
        if (mb_strlen(trim((string) ($p['description'] ?? ''))) > 500) { $err[] = 'Description : 500 caractères maximum.'; }
        if ($horaires) {
            $re = '/^([01]\d|2[0-3]):[0-5]\d$/';
            $d = trim((string) ($p['horaire_debut'] ?? '')); $f = trim((string) ($p['horaire_fin'] ?? ''));
            if (!preg_match($re, $d) || !preg_match($re, $f) || $d >= $f) { $err[] = 'Horaires invalides (début < fin, format HH:MM).'; }
        }
        return $err;
    }

    private function applyPro(UserModel $u, array $p, bool $horaires): void
    {
        $u->setSpecialite((string) $p['specialite']);
        $u->setVille(trim((string) $p['ville']));
        $u->setTelephone(trim((string) $p['telephone']));
        $u->setNumeroOrdre(trim((string) $p['numero_ordre']));
        $u->setDescription(trim((string) ($p['description'] ?? '')) ?: null);
        if ($horaires) {
            $u->setHoraireDebut(trim((string) $p['horaire_debut']));
            $u->setHoraireFin(trim((string) $p['horaire_fin']));
        }
    }

    private function newCaptcha(): string
    {
        $a = random_int(1, 9); $b = random_int(1, 9);
        $_SESSION['captcha'] = $a + $b;
        return "$a + $b";
    }

    private function checkCaptcha(string $answer): bool
    {
        $ok = isset($_SESSION['captcha']) && $answer !== '' && ctype_digit($answer) && (int) $answer === $_SESSION['captcha'];
        unset($_SESSION['captcha']);
        return $ok;
    }

     
     
     
    public function findById(int $id): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    private function findByEmail(string $email): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :e LIMIT 1');
        $stmt->execute(['e' => $email]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    private function takenMessage(string $email, string $username, int $excludeId = 0): ?string
    {
        $stmt = $this->db->prepare('SELECT email, username FROM users WHERE (email = :e OR username = :u) AND id <> :id LIMIT 1');
        $stmt->execute(['e' => $email, 'u' => $username, 'id' => $excludeId]);
        $row = $stmt->fetch();
        if (!$row) { return null; }
        return strcasecmp($row['email'], $email) === 0 ? 'Cette adresse e-mail est déjà utilisée.' : 'Ce pseudo est déjà pris.';
    }

    private function insertUser(UserModel $u): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role, actif, valide, specialite, ville, telephone, description, numero_ordre, horaire_debut, horaire_fin)
             VALUES (:username, :email, :password, :role, :actif, :valide, :specialite, :ville, :telephone, :description, :ordre, :hd, :hf)'
        );
        $stmt->execute([
            'username' => $u->getUsername(), 'email' => $u->getEmail(), 'password' => $u->getPassword(),
            'role' => $u->getRole(), 'actif' => $u->getActif(), 'valide' => $u->getValide(),
            'specialite' => $u->getSpecialite(), 'ville' => $u->getVille(), 'telephone' => $u->getTelephone(),
            'description' => $u->getDescription(), 'ordre' => $u->getNumeroOrdre(),
            'hd' => $u->getHoraireDebut() ?? '09:00', 'hf' => $u->getHoraireFin() ?? '17:00',
        ]);
        return (int) $this->db->lastInsertId();
    }

    private function updateUser(UserModel $u): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET username = :username, email = :email, role = :role, actif = :actif, valide = :valide,
                    specialite = :specialite, ville = :ville, telephone = :telephone, description = :description,
                    numero_ordre = :ordre, horaire_debut = :hd, horaire_fin = :hf, photo = :photo
             WHERE id = :id'
        );
        $stmt->execute([
            'username' => $u->getUsername(), 'email' => $u->getEmail(), 'role' => $u->getRole(),
            'actif' => $u->getActif(), 'valide' => $u->getValide(), 'specialite' => $u->getSpecialite(),
            'ville' => $u->getVille(), 'telephone' => $u->getTelephone(), 'description' => $u->getDescription(),
            'ordre' => $u->getNumeroOrdre(), 'hd' => $u->getHoraireDebut(), 'hf' => $u->getHoraireFin(),
            'photo' => $u->getPhoto(), 'id' => $u->getId(),
        ]);
    }

    private function setPassword(int $id, string $plain): void
    {
        $stmt = $this->db->prepare('UPDATE users SET password = :p, tentatives = 0, bloque_jusqu_a = NULL, reset_token = NULL, reset_expire = NULL WHERE id = :id');
        $stmt->execute(['p' => password_hash($plain, PASSWORD_DEFAULT), 'id' => $id]);
    }

    private function countPendingPros(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'professional' AND valide = 0")->fetchColumn();
    }

    private function sendMail(string $to, string $subject, string $body): void
    {
         
        $line = '[' . date('Y-m-d H:i:s') . "] To: $to | Subject: $subject\n$body\n" . str_repeat('-', 60) . "\n";
        file_put_contents(__DIR__ . '/../storage/mails.log', $line, FILE_APPEND | LOCK_EX);
        if (!APP_DEBUG) { @mail($to, $subject, $body, 'Content-Type: text/plain; charset=UTF-8'); }
    }

     
     
     
    public function landing(): void { $this->render('frontoffice/index'); }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('errors/404');
    }

    public function register(): void
    {
        if (self::isAuthenticated()) { redirect('dashboard'); }
        $errors = [];
        if ($this->isPost()) {
            $errors = $this->processRegister();
            if (!$errors) {
                flash('success', $this->post('type') === 'professional'
                    ? "Demande envoyée ! Un administrateur va vérifier ton profil avant d'activer ton compte."
                    : 'Compte créé avec succès, tu peux te connecter.');
                redirect('login');
            }
        }
        $captcha = $this->newCaptcha();
        $this->render('frontoffice/user/register', compact('errors', 'captcha'));
    }

    private function processRegister(): array
    {
        if (!self::checkCsrf()) { return ['Jeton de sécurité invalide. Recharge la page.']; }
        $username = $this->post('username');
        $email    = mb_strtolower($this->post('email'));
        $pw       = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
        $pw2      = is_string($_POST['password_confirm'] ?? null) ? $_POST['password_confirm'] : '';
        $isPro    = $this->post('type') === 'professional';      

        $err = array_merge($this->checkIdentity($username, $email), $this->checkPassword($pw));
        if ($pw !== $pw2) { $err[] = 'Les deux mots de passe ne correspondent pas.'; }
        if ($isPro) { $err = array_merge($err, $this->checkPro($_POST, false)); }
        if (!$this->checkCaptcha($this->post('captcha'))) { $err[] = 'Réponse incorrecte à la question anti-robot.'; }
        if ($err) { return $err; }
        if ($msg = $this->takenMessage($email, $username)) { return [$msg]; }

        $u = new UserModel();
        $u->setUsername($username);
        $u->setEmail($email);
        $u->setPassword(password_hash($pw, PASSWORD_DEFAULT));
        $u->setRole($isPro ? 'professional' : 'user');
        $u->setValide($isPro ? 0 : 1);                           
        if ($isPro) { $this->applyPro($u, $_POST, false); }
        $id = $this->insertUser($u);
        $this->log($id, 'register', $u->getRole());
        return [];
    }

    public function login(): void
    {
        if (self::isAuthenticated()) { redirect('dashboard'); }
        $errors = [];
        if ($this->isPost()) {
            $errors = $this->processLogin();
            if (!$errors) { redirect('dashboard'); }
        }
        $this->render('frontoffice/user/login', compact('errors'));
    }

    private function processLogin(): array
    {
        if (!self::checkCsrf()) { return ['Jeton de sécurité invalide. Recharge la page.']; }
        $email = mb_strtolower($this->post('email'));
        $pw    = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
        if ($email === '' || $pw === '') { return ['Saisis ton e-mail et ton mot de passe.']; }

        $generic = ['E-mail ou mot de passe incorrect.'];      
        $user = $this->findByEmail($email);
        if (!$user) { $this->log(null, 'login_failed', 'email inconnu'); return $generic; }

         
        if ($user->getBloqueJusquA() !== null && strtotime($user->getBloqueJusquA()) > time()) {
            $min = (int) ceil((strtotime($user->getBloqueJusquA()) - time()) / 60);
            return ["Trop de tentatives. Réessaie dans $min minute(s)."];
        }
        if (!password_verify($pw, $user->getPassword())) {
            $this->registerFailure($user);
            return $generic;
        }
        if (!$user->getActif()) { return ["Ce compte a été désactivé. Contacte l'équipe Khotwa."]; }
        if ($user->isPro() && !$user->getValide()) { return ["Ton compte professionnel est en attente de validation par l'équipe."]; }

        session_regenerate_id(true);                            
        $_SESSION['user_id']       = $user->getId();
        $_SESSION['username']      = $user->getUsername();
        $_SESSION['role']          = $user->getRole();
        $_SESSION['last_activity'] = time();
        $stmt = $this->db->prepare('UPDATE users SET tentatives = 0, bloque_jusqu_a = NULL, derniere_connexion = :d WHERE id = :id');
        $stmt->execute(['d' => date('Y-m-d H:i:s'), 'id' => $user->getId()]);
        $this->log($user->getId(), 'login_success');
        return [];
    }

    private function registerFailure(UserModel $user): void
    {
        $n = $user->getTentatives() + 1;
        $lock = null;
        if ($n >= self::MAX_ATTEMPTS) { $lock = date('Y-m-d H:i:s', time() + self::LOCK_MINUTES * 60); $n = 0; }
        $stmt = $this->db->prepare('UPDATE users SET tentatives = :n, bloque_jusqu_a = :l WHERE id = :id');
        $stmt->execute(['n' => $n, 'l' => $lock, 'id' => $user->getId()]);
        $this->log($user->getId(), 'login_failed', 'mot de passe incorrect');
        if ($lock) { $this->log($user->getId(), 'account_locked', 'verrouille ' . self::LOCK_MINUTES . ' min'); }
    }

    public function logout(): void
    {
        if (self::isAuthenticated()) { $this->log($this->me(), 'logout'); }
        self::endSession();
        flash('success', 'Tu es déconnecté. À bientôt !');
        redirect('login');
    }

    public function forgot(): void
    {
        if (self::isAuthenticated()) { redirect('dashboard'); }
        $errors = []; $devLink = null; $sent = false;
        if ($this->isPost()) {
            $email = mb_strtolower($this->post('email'));
            if (!self::checkCsrf()) {
                $errors[] = 'Jeton de sécurité invalide.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Adresse e-mail invalide.';
            } else {
                $user = $this->findByEmail($email);
                if ($user && $user->getActif()) {
                    $token = bin2hex(random_bytes(32));
                    $stmt = $this->db->prepare('UPDATE users SET reset_token = :t, reset_expire = :x WHERE id = :id');
                    $stmt->execute(['t' => hash('sha256', $token), 'x' => date('Y-m-d H:i:s', time() + 3600), 'id' => $user->getId()]);
                    $link = BASE_URL . '/index.php?action=reset&token=' . $token;
                    $this->sendMail($email, 'Khotwa - Réinitialisation du mot de passe',
                        "Bonjour,\nClique sur ce lien (valable 1 heure) pour choisir un nouveau mot de passe :\n$link\nSi tu n'es pas à l'origine de cette demande, ignore ce message.");
                    $this->log($user->getId(), 'password_reset_request');
                    if (APP_DEBUG) { $devLink = $link; }
                }
                $sent = true;        
            }
        }
        $this->render('frontoffice/user/forgot', compact('errors', 'sent', 'devLink'));
    }

    public function reset(): void
    {
        $token = $this->get('token');
        $user = null;
        if (strlen($token) === 64 && ctype_xdigit($token)) {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE reset_token = :t AND reset_expire > :now LIMIT 1');
            $stmt->execute(['t' => hash('sha256', $token), 'now' => date('Y-m-d H:i:s')]);
            $row = $stmt->fetch();
            $user = $row ? UserModel::fromArray($row) : null;
        }
        $errors = [];
        if ($user && $this->isPost()) {
            $pw = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
            $pw2 = is_string($_POST['password_confirm'] ?? null) ? $_POST['password_confirm'] : '';
            if (!self::checkCsrf()) { $errors[] = 'Jeton de sécurité invalide.'; }
            $errors = array_merge($errors, $this->checkPassword($pw));
            if ($pw !== $pw2) { $errors[] = 'Les deux mots de passe ne correspondent pas.'; }
            if (!$errors) {
                $this->setPassword($user->getId(), $pw);         
                $this->log($user->getId(), 'password_reset');
                flash('success', 'Mot de passe modifié, tu peux te connecter.');
                redirect('login');
            }
        }
        $this->render('frontoffice/user/reset', ['valid' => $user !== null, 'token' => $token, 'errors' => $errors]);
    }

     
     
     
    public function dashboard(): void
    {
        self::requireAuth();
        if ($_SESSION['role'] === 'admin') { redirect('admin'); }
        $user = $this->findById($this->me());
        $this->render('frontoffice/user/dashboard', ['user' => $user, 'history' => $this->lastLogins($this->me(), 3)]);
    }

    private function lastLogins(int $userId, int $n): array
    {
        $stmt = $this->db->prepare("SELECT created_at, ip FROM logs WHERE user_id = :u AND action = 'login_success' ORDER BY id DESC LIMIT :n");
        $stmt->bindValue('u', $userId, PDO::PARAM_INT);
        $stmt->bindValue('n', $n, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function profile(): void
    {
        self::requireAuth();
        $user = $this->findById($this->me());
        $this->render('frontoffice/user/profile', ['user' => $user, 'history' => $this->lastLogins($this->me(), 5)]);
    }

    public function profileEdit(): void
    {
        self::requireAuth();
        $user = $this->findById($this->me());
        $errors = [];
        if ($this->isPost()) {
            $errors = $this->processProfile($user);
            if (!$errors) { flash('success', 'Profil mis à jour.'); redirect('profile'); }
        }
        $this->render('frontoffice/user/profile_edit', ['user' => $user, 'errors' => $errors, 'pwdErrors' => []]);
    }

    private function processProfile(UserModel $u): array
    {
        if (!self::checkCsrf()) { return ['Jeton de sécurité invalide.']; }
        $username = $this->post('username');
        $email = mb_strtolower($this->post('email'));
        $err = $this->checkIdentity($username, $email);
        if ($u->isPro()) { $err = array_merge($err, $this->checkPro($_POST, true)); }
        if (!$err && ($msg = $this->takenMessage($email, $username, $u->getId()))) { $err[] = $msg; }

        $newPhoto = null;
        if (!$err) {
            [$newPhoto, $photoErr] = $this->handlePhoto($_FILES['photo'] ?? [], $u->getPhoto());
            if ($photoErr) { $err[] = $photoErr; }
        }
        if ($err) { return $err; }

        $u->setUsername($username);
        $u->setEmail($email);
        if ($u->isPro()) { $this->applyPro($u, $_POST, true); }
        if ($newPhoto !== null) {
            $u->setPhoto($newPhoto);
        } elseif (!empty($_POST['remove_photo'])) {
            $this->deletePhotoFile($u->getPhoto());
            $u->setPhoto(null);
        }
        $this->updateUser($u);       
        $_SESSION['username'] = $username;
        $this->log($u->getId(), 'profile_update');
        return [];
    }

     
    private function handlePhoto(array $file, ?string $old): array
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) { return [null, null]; }
        if ($file['error'] !== UPLOAD_ERR_OK) { return [null, "Échec de l'envoi de la photo."]; }
        if ($file['size'] > 2 * 1024 * 1024) { return [null, 'Photo trop lourde (2 Mo maximum).']; }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);        
        $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
        if (!$ext || !@getimagesize($file['tmp_name'])) { return [null, 'Photo invalide (JPG, PNG ou WEBP uniquement).']; }
        $name = bin2hex(random_bytes(16)) . '.' . $ext;                          
        if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../img/uploads/' . $name)) { return [null, "Impossible d'enregistrer la photo."]; }
        $this->deletePhotoFile($old);
        return [$name, null];
    }

    private function deletePhotoFile(?string $name): void
    {
        if ($name) {
            $path = __DIR__ . '/../img/uploads/' . basename($name);
            if (is_file($path)) { @unlink($path); }
        }
    }

    public function changePassword(): void
    {
        self::requireAuth();
        $user = $this->findById($this->me());
        if (!$this->isPost()) { redirect('profile_edit'); }
        $old = is_string($_POST['old_password'] ?? null) ? $_POST['old_password'] : '';
        $new = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
        $new2 = is_string($_POST['password_confirm'] ?? null) ? $_POST['password_confirm'] : '';
        $err = [];
        if (!self::checkCsrf()) { $err[] = 'Jeton de sécurité invalide.'; }
        if (!password_verify($old, $user->getPassword())) { $err[] = 'Ancien mot de passe incorrect.'; }
        $err = array_merge($err, $this->checkPassword($new));
        if ($new !== $new2) { $err[] = 'Les deux nouveaux mots de passe ne correspondent pas.'; }
        if ($old === $new && $new !== '') { $err[] = "Le nouveau mot de passe doit être différent de l'ancien."; }
        if ($err) {
            $this->render('frontoffice/user/profile_edit', ['user' => $user, 'errors' => [], 'pwdErrors' => $err]);
            return;
        }
        $this->setPassword($user->getId(), $new);
        session_regenerate_id(true);
        $this->log($user->getId(), 'password_change');
        flash('success', 'Mot de passe modifié.');
        redirect('profile');
    }

     
    public function deleteAccount(): void
    {
        self::requireAuth();
        $this->requirePost('profile_edit');
        $user = $this->findById($this->me());
        if (!password_verify(is_string($_POST['password'] ?? null) ? $_POST['password'] : '', $user->getPassword())) {
            flash('error', 'Mot de passe incorrect : compte non supprimé.');
            redirect('profile_edit');
        }
        if ($user->getRole() === 'admin' && (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn() <= 1) {
            flash('error', 'Tu es le dernier administrateur : impossible de supprimer ce compte.');
            redirect('profile_edit');
        }
        $this->deletePhotoFile($user->getPhoto());
        $this->log(null, 'account_delete', 'compte #' . $user->getId() . ' (' . $user->getUsername() . ')');
        $this->db->prepare('DELETE FROM users WHERE id = :id')->execute(['id' => $user->getId()]);
        self::endSession();
        flash('success', 'Ton compte et tes données ont été supprimés.');
        redirect('login');
    }

     
     
     
    public function adminDashboard(): void
    {
        self::requireRole('admin');
        $this->render('backoffice/dashboard', ['stats' => $this->getStats(), 'user' => $this->findById($this->me())]);
    }

    private function getStats(): array
    {
        $one = fn(string $sql): int => (int) $this->db->query($sql)->fetchColumn();
        $byRole = ['user' => 0, 'professional' => 0, 'admin' => 0];
        foreach ($this->db->query('SELECT role, COUNT(*) c FROM users GROUP BY role') as $r) { $byRole[$r['role']] = (int) $r['c']; }

        $months = [];                                            
        for ($i = 5; $i >= 0; $i--) { $months[date('Y-m', strtotime("first day of -$i month"))] = 0; }
        $q = $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') m, COUNT(*) c FROM users
                               WHERE created_at >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), '%Y-%m-01')
                               GROUP BY DATE_FORMAT(created_at, '%Y-%m')");
        foreach ($q as $r) { if (isset($months[$r['m']])) { $months[$r['m']] = (int) $r['c']; } }

        $days = [];                                              
        for ($i = 6; $i >= 0; $i--) { $days[date('Y-m-d', strtotime("-$i day"))] = 0; }
        $q = $this->db->query("SELECT DATE(created_at) d, COUNT(*) c FROM logs
                               WHERE action = 'login_success' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                               GROUP BY DATE(created_at)");
        foreach ($q as $r) { if (isset($days[$r['d']])) { $days[$r['d']] = (int) $r['c']; } }

        return [
            'total'    => array_sum($byRole),
            'byRole'   => $byRole,
            'actifs'   => $one('SELECT COUNT(*) FROM users WHERE actif = 1'),
            'bloques'  => $one('SELECT COUNT(*) FROM users WHERE actif = 0'),
            'pending'  => $this->countPendingPros(),
            'months'   => $months,
            'days'     => $days,
            'echecs24' => $one("SELECT COUNT(*) FROM logs WHERE action = 'login_failed' AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)"),
        ];
    }

     
     
     
    private function readFilters(): array
    {
        $sorts = ['id', 'username', 'email', 'role', 'created_at', 'derniere_connexion'];
        return [
            'q'    => $this->get('q'),
            'role' => in_array($this->get('role'), self::ROLES, true) ? $this->get('role') : '',
            'etat' => in_array($this->get('etat'), ['actif', 'bloque', 'attente'], true) ? $this->get('etat') : '',
            'sort' => in_array($this->get('sort'), $sorts, true) ? $this->get('sort') : 'id',
            'dir'  => $this->get('dir') === 'desc' ? 'DESC' : 'ASC',
            'page' => max(1, (int) $this->get('page')),
        ];
    }

     
    private function listUsers(array $f, bool $paginate = true): array
    {
        $where = []; $params = [];
        if ($f['q'] !== '') {
            $where[] = '(username LIKE :q1 OR email LIKE :q2)';
            $like = '%' . addcslashes($f['q'], '%_\\') . '%';
            $params['q1'] = $like; $params['q2'] = $like;
        }
        if ($f['role'] !== '') { $where[] = 'role = :role'; $params['role'] = $f['role']; }
        if ($f['etat'] === 'actif')   { $where[] = 'actif = 1'; }
        if ($f['etat'] === 'bloque')  { $where[] = 'actif = 0'; }
        if ($f['etat'] === 'attente') { $where[] = "role = 'professional' AND valide = 0"; }
        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $count = $this->db->prepare("SELECT COUNT(*) FROM users $sqlWhere");
        $count->execute($params);
        $total = (int) $count->fetchColumn();

         
        $sql = "SELECT * FROM users $sqlWhere ORDER BY {$f['sort']} {$f['dir']}" . ($paginate ? ' LIMIT :lim OFFSET :off' : '');
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
        if ($paginate) {
            $stmt->bindValue('lim', self::PER_PAGE, PDO::PARAM_INT);
            $stmt->bindValue('off', ($f['page'] - 1) * self::PER_PAGE, PDO::PARAM_INT);
        }
        $stmt->execute();
        return [array_map(fn(array $r) => UserModel::fromArray($r), $stmt->fetchAll()), $total];
    }

    public function adminUsers(): void
    {
        self::requireRole('admin');
        $f = $this->readFilters();
        [$users, $total] = $this->listUsers($f);
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $this->render('backoffice/users/list', compact('users', 'total', 'pages', 'f'));
    }

    public function adminUserCreate(): void
    {
        self::requireRole('admin');
        $errors = [];
        if ($this->isPost()) {
            $errors = $this->processAdminSave(null);
            if (!$errors) { flash('success', 'Utilisateur créé.'); redirect('admin_users'); }
        }
        $this->render('backoffice/users/create', ['user' => null, 'errors' => $errors]);
    }

    public function adminUserEdit(): void
    {
        self::requireRole('admin');
        $user = $this->findById((int) $this->get('id'));
        if (!$user) { flash('error', 'Utilisateur introuvable.'); redirect('admin_users'); }
        $errors = [];
        if ($this->isPost()) {
            $errors = $this->processAdminSave($user);
            if (!$errors) { flash('success', 'Utilisateur modifié.'); redirect('admin_users'); }
        }
        $this->render('backoffice/users/edit', ['user' => $user, 'errors' => $errors]);
    }

     
    private function processAdminSave(?UserModel $existing): array
    {
        if (!self::checkCsrf()) { return ['Jeton de sécurité invalide.']; }
        $username = $this->post('username');
        $email = mb_strtolower($this->post('email'));
        $role = in_array($this->post('role'), self::ROLES, true) ? $this->post('role') : 'user';
        $actif = !empty($_POST['actif']) ? 1 : 0;
        $pw = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
        $isSelf = $existing && $existing->getId() === $this->me();

        $err = $this->checkIdentity($username, $email);
        if (!$existing || $pw !== '') { $err = array_merge($err, $this->checkPassword($pw)); }    
        if ($role === 'professional') { $err = array_merge($err, $this->checkPro($_POST, $existing !== null)); }
        if ($isSelf && ($role !== 'admin' || !$actif)) { $err[] = 'Tu ne peux pas modifier ton propre rôle ni te désactiver.'; }
        if (!$err && ($msg = $this->takenMessage($email, $username, $existing ? $existing->getId() : 0))) { $err[] = $msg; }
        if ($err) { return $err; }

        $u = $existing ?? new UserModel();
        $u->setUsername($username);
        $u->setEmail($email);
        $u->setRole($role);
        $u->setActif($actif);
        $u->setValide(1);                                         
        if ($role === 'professional') { $this->applyPro($u, $_POST, $existing !== null); }
        if ($existing) {
            $this->updateUser($u);
            if ($pw !== '') { $this->setPassword($u->getId(), $pw); }
            $this->log($this->me(), 'admin_user_update', '#' . $u->getId() . ' ' . $username);
        } else {
            $u->setPassword(password_hash($pw, PASSWORD_DEFAULT));
            $id = $this->insertUser($u);
            $this->log($this->me(), 'admin_user_create', '#' . $id . ' ' . $username . ' (' . $role . ')');
        }
        return [];
    }

     
    public function adminUserToggle(): void
    {
        self::requireRole('admin');
        $this->requirePost('admin_users');
        $id = (int) $this->post('id');
        if ($id === $this->me()) { flash('error', 'Tu ne peux pas bloquer ton propre compte.'); redirect('admin_users'); }
        $this->db->prepare('UPDATE users SET actif = 1 - actif WHERE id = :id')->execute(['id' => $id]);
        $this->log($this->me(), 'admin_user_toggle', '#' . $id);
        flash('success', "État du compte #$id modifié.");
        redirect('admin_users', array_filter(['q' => $this->post('q'), 'role' => $this->post('role_f'), 'etat' => $this->post('etat'), 'page' => $this->post('page')]));
    }

     
    public function adminUserRole(): void
    {
        self::requireRole('admin');
        $this->requirePost('admin_users');
        $id = (int) $this->post('id');
        $role = $this->post('role');
        if ($id === $this->me()) { flash('error', 'Tu ne peux pas changer ton propre rôle.'); redirect('admin_users'); }
        if (!in_array($role, self::ROLES, true)) { flash('error', 'Rôle invalide.'); redirect('admin_users'); }
        $this->db->prepare('UPDATE users SET role = :r WHERE id = :id')->execute(['r' => $role, 'id' => $id]);
        $this->log($this->me(), 'admin_user_role', "#$id => $role");
        flash('success', "Rôle du compte #$id : " . role_label($role) . '.');
        redirect('admin_users');
    }

    public function adminUserDelete(): void
    {
        self::requireRole('admin');
        $this->requirePost('admin_users');
        $id = (int) $this->post('id');
        if ($id === $this->me()) { flash('error', 'Tu ne peux pas supprimer ton propre compte ici.'); redirect('admin_users'); }
        $u = $this->findById($id);
        if ($u) {
            $this->deletePhotoFile($u->getPhoto());
            $this->db->prepare('DELETE FROM users WHERE id = :id')->execute(['id' => $id]);
            $this->log($this->me(), 'admin_user_delete', '#' . $id . ' ' . $u->getUsername());
            flash('success', 'Utilisateur supprimé.');
        }
        redirect('admin_users');
    }

     
     
     
    public function adminPros(): void
    {
        self::requireRole('admin');
        $rows = $this->db->query("SELECT * FROM users WHERE role = 'professional' AND valide = 0 ORDER BY created_at ASC")->fetchAll();
        $pending = array_map(fn(array $r) => UserModel::fromArray($r), $rows);
        $validated = (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'professional' AND valide = 1")->fetchColumn();
        $this->render('backoffice/professionals/validation', compact('pending', 'validated'));
    }

    public function adminProDecision(): void
    {
        self::requireRole('admin');
        $this->requirePost('admin_pros');
        $u = $this->findById((int) $this->post('id'));
        if (!$u || !$u->isPro() || $u->getValide()) { flash('error', 'Demande introuvable.'); redirect('admin_pros'); }
        if ($this->post('decision') === 'valider') {
            $this->db->prepare('UPDATE users SET valide = 1 WHERE id = :id')->execute(['id' => $u->getId()]);
            $this->sendMail($u->getEmail(), 'Khotwa - Compte validé', "Bonjour {$u->getUsername()},\nTon compte professionnel est validé : tu peux te connecter.");
            $this->log($this->me(), 'admin_pro_validate', '#' . $u->getId() . ' ' . $u->getUsername());
            flash('success', $u->getUsername() . ' est maintenant visible dans le réseau.');
        } else {
            $this->sendMail($u->getEmail(), 'Khotwa - Demande refusée', "Bonjour {$u->getUsername()},\nTa demande n'a pas pu être validée.");
            $this->deletePhotoFile($u->getPhoto());
            $this->db->prepare('DELETE FROM users WHERE id = :id')->execute(['id' => $u->getId()]);
            $this->log($this->me(), 'admin_pro_refuse', '#' . $u->getId() . ' ' . $u->getUsername());
            flash('success', 'Demande refusée et compte supprimé.');
        }
        redirect('admin_pros');
    }

    public function adminLogs(): void
    {
        self::requireRole('admin');
        $action = $this->get('type');
        $page = max(1, (int) $this->get('page'));
        $where = $action !== '' ? 'WHERE l.action = :a' : '';

        $c = $this->db->prepare("SELECT COUNT(*) FROM logs l $where");
        $action !== '' ? $c->execute(['a' => $action]) : $c->execute();
        $total = (int) $c->fetchColumn();

        $stmt = $this->db->prepare("SELECT l.*, u.username FROM logs l LEFT JOIN users u ON u.id = l.user_id $where ORDER BY l.id DESC LIMIT :lim OFFSET :off");
        if ($action !== '') { $stmt->bindValue('a', $action); }
        $stmt->bindValue('lim', self::LOGS_PER_PAGE, PDO::PARAM_INT);
        $stmt->bindValue('off', ($page - 1) * self::LOGS_PER_PAGE, PDO::PARAM_INT);
        $stmt->execute();
        $logs = $stmt->fetchAll();
        $types = $this->db->query('SELECT DISTINCT action FROM logs ORDER BY action')->fetchAll(PDO::FETCH_COLUMN);
        $pages = max(1, (int) ceil($total / self::LOGS_PER_PAGE));
        $this->render('backoffice/logs/list', compact('logs', 'types', 'total', 'pages', 'page', 'action'));
    }

    public function adminExport(): void
    {
        self::requireRole('admin');
        [$users] = $this->listUsers($this->readFilters(), false);       
        $this->log($this->me(), 'admin_export', count($users) . ' lignes');
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="khotwa_utilisateurs_' . date('Ymd_His') . '.csv"');
        echo "\xEF\xBB\xBF";                                             
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Pseudo', 'E-mail', 'Rôle', 'Actif', 'Validé', 'Spécialité', 'Ville', 'Inscription', 'Dernière connexion'], ';', '"', '\\');
        foreach ($users as $u) {
            fputcsv($out, array_map([$this, 'csvSafe'], [
                $u->getId(), $u->getUsername(), $u->getEmail(), $u->getRole(), $u->getActif() ? 'oui' : 'non',
                $u->getValide() ? 'oui' : 'non', $u->getSpecialite(), $u->getVille(), $u->getCreatedAt(), $u->getDerniereConnexion(),
            ]), ';', '"', '\\');
        }
        fclose($out);
        exit;
    }

     
    private function csvSafe($v): string
    {
        $v = (string) $v;
        return preg_match('/^[=+\-@\t\r]/', $v) ? "'" . $v : $v;
    }
}
