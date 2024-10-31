<?php


namespace class;
require_once __DIR__ . '/../PhP-Files/vendor/setasign/fpdf/fpdf.php'; // Assurez-vous que le chemin est correct
     
/**
 * Cette classe va nous permettre de gérer la connexion d'un membre,
 * de vérifier la session et les cookies et enfin
 * d'accéder aux informations du membre courant.
 */
class Member
{
    /**
     * Grain de sel utilisé pour la création d'un hash de sécurité aux cookies
     *
     * @var string
     */
    private static $salt = '16kXI#g<:<p<j}wF@8OBP$q[';

    /**
     * Le visiteur est connecté. Par défaut, non
     *
     * @var bool
     */
    private $logged = false;

    /**
     * Le visiteur est administrateur. Par défaut, non
     * 
     * @var bool
     */
    private $admin = false;

    /**
     * Informations sur le membre connecté
     *
     * @var array
     */
    private $member = [];

    public function __construct()
    {
        // Tente de récupérer le membre par la SESSION
        $this->getFromSession();

        // Tente de récupérer le membre par les COOKIES
        $this->getFromCookie();
    }

    /**
     * Le visiteur est connecté ?
     *
     * @return bool
     */
    public function isLogged(): bool
    {
        return $this->logged;
    }

    public function isAdmin(): bool
    {
        return $this->get('role') === 'admin';
    }

    /**
     * Récupére une information sur le membre connecté
     *
     * @param string $key La clé à récupérer. Ex : pseudo, email, ...
     * @return mixed|null La valeur de la clé ou NULL s'il elle n'existe pas
     */
    public function get(string $key)
    {
        if ($this->isLogged() && array_key_exists($key, $this->member)) {
            return $this->member[$key];
        }

        return null;
    }

    /**
     * Méthode statique permettant de vérifier si un pseudo est déjà utilisé
     *
     * @param string $pseudo Le pseudo à vérifier
     * @return bool
     */
    public static function pseudoIsAlreadyTaken(string $pseudo): bool
    {
        $query = getPdo()->prepare('SELECT * FROM users WHERE pseudo = :pseudo LIMIT 1');
        $query->execute(['pseudo' => $pseudo]);

        return $query->fetch() !== false;
    }


    /**
     * Méthode statique permettant de vérifier si un email est déjà utilisé
     *
     * @param string $email L'email à vérifier
     * @return bool
     */
    public static function emailIsAlreadyTaken(string $email): bool
    {
        $query = getPdo()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $query->execute(['email' => $email]);

        return $query->fetch() !== false;
    }

    /**
     * Méthode statique permettant de créer une session depuis un
     * tableau d'information sur un membre
     *
     * @param array $infos
     */
    public static function createSession(array $infos): void
    {
        $_SESSION['id'] = $infos['id'];
        $_SESSION['pseudo'] = $infos['username'];
    }

    /**
     * Méthode statique permettant de créer les cookies de connexion automatique
     * depuis un tableau d'information sur un membre
     *
     * @param array $infos
     */
    public static function createCookie(array $infos): void
    {
        // Expiration du cookie dans 30 jours
        $duration = 60 * 60 * 24 * 30;
        $expiration = time() + $duration;

        // Provide default values for path and domain
        setcookie('member_id', $infos['id'], $expiration, '/', '', false, true);
        setcookie('member_hash', self::generateHash($infos), $expiration, '/', '', false, true);
    }


    /**
     * Méthode statique qui à partir des infos d'un membre et d'un grain de sel
     * va générer un hash unique pour la connexion automatique par cookie.
     * Si le grain de sel change, les cookies précédemment créés ne
     * fonctionneront plus.
     *
     * @param array $infos
     * @return string
     */
    protected static function generateHash(array $infos): string
    {
        // Explication : https://www.php.net/manual/fr/function.sha1.php
        return sha1($infos['id'] . $infos['username'] . self::$salt);
    }

    /**
     * Récupère un membre depuis ses infos de session
     */
    protected function getFromSession(): void
    {
        // Les variables de session existent
        if (! empty($_SESSION['id']) && ! empty($_SESSION['username'])) {
            $query = getPdo()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
            $success = $query->execute(['id' => $_SESSION['id']]);

            // Le membre existe en BDD
            if ($success) {
                $this->member = $query->fetch();
                $this->logged = true;
            }
        }
    }

    /**
     * Récupère un membre depuis les infos de cookie
     */
    protected function getFromCookie(): void
    {
        $id = $_COOKIE['member_id'] ?? null;
        $hash = $_COOKIE['member_hash'] ?? null;

        // L'ID et le Hash existent dans les cookies
        if (! empty($id) && ! empty($hash)) {
            $query = getPdo()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
            $success = $query->execute(['id' => $id]);
            $member = $query->fetch();

            // Le membre existe en BDD et le hash fourni est valide
            if ($success && $hash === self::generateHash($member)) {
                $this->member = $member;
                $this->logged = true;
            }
        }
    }

    public function register($email, $username, $password, $picture): bool|string
    {
        // Vérifier si l'email ou le nom d'utilisateur existe déjà
        $emailTaken = $this->emailIsAlreadyTaken($email);
        if ($emailTaken == true) {
            return "Cet email ou nom d'utilisateur est déjà utilisé.";
        }

        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insérer le nouvel utilisateur
        $query = getPdo()->prepare("INSERT INTO users (email, username, password, picture, role) VALUES (:email, :username, :password, :picture, 'user')");
        $result = $query->execute([
            ':email' => $email,
            ':username' => $username,
            ':password' => $hashedPassword,
            ':picture' => $picture
        ]);
        if ($result) {
            return true;
        } else {
            return "Une erreur est survenue lors de l'inscription.";
        }
    }

    public function handleProfilePicture($username) {
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
            $img_name = $_FILES['picture']['name'];
            $tmp_name = $_FILES['picture']['tmp_name'];
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $allowed_exs = ['jpg', 'jpeg', 'png'];

            if (in_array(strtolower($img_ex), $allowed_exs)) {
                $new_img_name = uniqid($username, true) . '.' . strtolower($img_ex);
                $img_upload_path = '/app/upload/' . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
                return $new_img_name;
            }
        }
        return '';
    }

    public function handleRegister($page, $member): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $picture = $this->handleProfilePicture($username);

            if (empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
                $error = "Tous les champs sont requis.";
            } elseif ($password !== $confirm_password) {
                $error = "Les mots de passe ne correspondent pas.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format d'email invalide.";
            } else {
                $result = $this->register($email, $username, $password, $picture);
                if ($result === true) {
                    header('Location: /login');
                    exit;
                } else {
                    $error = $result; 
                    ob_start();
                    function () use ($page, $member) {
                        require_once __DIR__ . '/../../views/layout.php';
                    };
                    ob_end_flush();
                }
            }
        }
    }

    public function login($email, $password): bool|string
    {
        // Récupérer l'utilisateur par son email
        $query = getPdo()->prepare("SELECT * FROM users WHERE (username=:email OR email=:email)");
        $query->execute(['email' => $email]);
        $member = $query->fetch();

        // Vérifier si l'utilisateur existe
        if ($member === false) {
            return "Cet email n'existe pas.";
        }

        // Vérifier le mot de passe
        if (!password_verify($password, $member['password'])) {
            return "Le mot de passe est incorrect.";
        }

        // Créer la session
        self::createSession($member);

        // Créer les cookies de connexion automatique
        self::createCookie($member);

        return true;
    }

    public function logs_connexion() {
        $member_id = $this->get('id');
        $member_username = $this->get('username');

        $insert_query = getPdo()->prepare("INSERT INTO logs_connexion (user_id, username) VALUES (:member_id, :member_username)");
        $execute_query = $insert_query->execute([
            ':member_id' => $member_id, 
            ':member_username' => $member_username
        ]);    
        return $execute_query;
    }

    public function handleLogin($page, $member)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $email = $_POST['usr'] ?? '';
            $password = $_POST['pwd'] ?? '';

            // Validate input
            if (empty($email) || empty($password)) {
                $error = "Veuillez remplir tous les champs.";
                function () use ($page, $member) {
                    require_once __DIR__ . '/../../views/layout.php';
                };
                return;
            }

            // Attempt to login
            $result = $this->login($email, $password);

            // Handle login result
            if ($result === true) {        
                // Enregistrer la connexion
                $this->logs_connexion();
                header('Location: /dashboard');
                exit;
            } else {
                print_r($result);
                $error = $result;
                function () use ($page, $member) {
                    require_once __DIR__ . '/../../views/layout.php';
                };
            }
        }
    }

    public function logout(): void
    {
        // Remove cookies
        setcookie('member_id', '', time() - 3600, '/', '', false, true);
        setcookie('member_hash', '', time() - 3600, '/', '', false, true);

        // Destroy the session
        session_destroy();

        // Redirect to the login page
        header('Location: /dashboard');
        exit;
    }

    public function getAllMembers(): array {
        $query = getPdo()->query('SELECT * FROM users');
        $members = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $members;
    }
    public function getUserById($userId) {
        $stmt = getPdo()->prepare("SELECT * FROM users WHERE id = :userId");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC); 
    }

    public function getLastConnexion($userId) {
        $stmt = getPdo()->prepare('SELECT connexion_time FROM logs_connexion WHERE user_id = :userId ORDER BY connexion_time DESC LIMIT 1');
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addResume($data) {
        $stmt = getPdo()->prepare('INSERT INTO resumes (user_id, full_name, email, phone, experience, education, skills, style) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['user_id'],
            $data['full_name'],
            $data['email'],
            $data['phone'],
            $data['experience'],
            $data['education'],
            $data['skills'],
            $data['style']
        ]);
    }
    

    public function getUserResumes($userId): array {
        $stmt = getPdo()->prepare("SELECT * FROM resumes WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



    public function deleteResume($resumeId): bool {
        $stmt = getPdo()->prepare("DELETE FROM resumes WHERE cv_id = :resumeId");
        $stmt->bindParam(':resumeId', $resumeId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function updateResume($resumeId, $full_name, $email, $phone, $experience, $education, $skills, $style): bool {
        $stmt = getPdo()->prepare("UPDATE resumes SET full_name = :full_name, email = :email, phone = :phone, 
                                    experience = :experience, education = :education, skills = :skills, style = :style
                                    WHERE id = :resumeId");
        $stmt->execute([
            ':full_name' => $full_name,
            ':email' => $email,
            ':phone' => $phone,
            ':experience' => $experience,
            ':education' => $education,
            ':skills' => $skills,
            ':style' => $style,
            ':resumeId' => $resumeId
        ]);
        return $stmt->rowCount() > 0;
    }    

    public function downloadResumeAsPdfForThisId($resume_id, $user_id) {
        if (!$this->isLogged()) {
            return;
        }

        $resume = $this->getUserResumeById($user_id, $resume_id);
    
        if ($resume) {
            $pdf = new \FPDF();
            $pdf->AddPage();
    
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, "Name: " . $resume['full_name'], 0, 1, 'C');
    
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, "Email: " . $resume['email'], 0, 1);
            $pdf->Cell(0, 10, "Phone: " . $resume['phone'], 0, 1);
            $pdf->Cell(0, 10, "Experience: " . $resume['experience'], 0, 1);
            $pdf->Cell(0, 10, "Education: " . $resume['education'], 0, 1);
            $pdf->Ln(10);
    
            $pdf->Output('D', $resume['full_name'] . '.pdf');
        }
    }
    
    
    
    
    public function getUserResumeById($userId, $resumeId): ?array {
        $stmt = getPdo()->prepare("SELECT * FROM resumes WHERE user_id = :userId AND cv_id = :resumeId");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':resumeId', $resumeId, \PDO::PARAM_INT);
        $stmt->execute();
        $resume = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($resume) {
            return $resume;
        } else {
            echo "<script>console.log('No resume found for User ID: " . $userId . ", Resume ID: " . $resumeId . "');</script>";
            return null;
        }
    }
    
    public function deleteUserById($id) {
        $pdo = getPdo();
    
        $deleteLogsStmt = $pdo->prepare("DELETE FROM logs_connexion WHERE user_id = :id");
        $deleteLogsStmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $deleteLogsStmt->execute();
    
        $deleteUserStmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $deleteUserStmt->bindParam(":id", $id, \PDO::PARAM_INT);
        return $deleteUserStmt->execute();
    }

    public function editUser($id, $username, $picture = null) {
        $query = "UPDATE users SET username = :username";
        
        if ($picture !== null) {
            $query .= ", picture = :picture";
        }
        
    
        $query .= " WHERE id = :id";
    
        $stmt = getPdo()->prepare($query);
    
        $stmt->bindParam(":username", $username, \PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        
        if ($picture !== null) {
            $stmt->bindParam(":picture", $picture, \PDO::PARAM_STR);
        }
    
        return $stmt->execute();
    }

}