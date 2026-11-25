<?php
 
class UserModel
{
    private int $id = 0;
    private string $username = '';
    private string $email = '';
    private string $password = '';
    private string $role = 'user';
    private int $actif = 1;
    private int $valide = 1;
    private ?string $specialite = null;
    private ?string $ville = null;
    private ?string $telephone = null;
    private ?string $description = null;
    private ?string $numeroOrdre = null;
    private ?string $horaireDebut = null;
    private ?string $horaireFin = null;
    private ?string $photo = null;
    private int $tentatives = 0;
    private ?string $bloqueJusquA = null;
    private ?string $resetToken = null;
    private ?string $resetExpire = null;
    private ?string $derniereConnexion = null;
    private string $createdAt = '';

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): void { $this->username = $username; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function getRole(): string { return $this->role; }
    public function setRole(string $role): void { $this->role = $role; }
    public function getActif(): int { return $this->actif; }
    public function setActif(int $actif): void { $this->actif = $actif; }
    public function getValide(): int { return $this->valide; }
    public function setValide(int $valide): void { $this->valide = $valide; }
    public function getSpecialite(): ?string { return $this->specialite; }
    public function setSpecialite(?string $specialite): void { $this->specialite = $specialite; }
    public function getVille(): ?string { return $this->ville; }
    public function setVille(?string $ville): void { $this->ville = $ville; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(?string $telephone): void { $this->telephone = $telephone; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function getNumeroOrdre(): ?string { return $this->numeroOrdre; }
    public function setNumeroOrdre(?string $numeroOrdre): void { $this->numeroOrdre = $numeroOrdre; }
    public function getHoraireDebut(): ?string { return $this->horaireDebut; }
    public function setHoraireDebut(?string $horaireDebut): void { $this->horaireDebut = $horaireDebut; }
    public function getHoraireFin(): ?string { return $this->horaireFin; }
    public function setHoraireFin(?string $horaireFin): void { $this->horaireFin = $horaireFin; }
    public function getPhoto(): ?string { return $this->photo; }
    public function setPhoto(?string $photo): void { $this->photo = $photo; }
    public function getTentatives(): int { return $this->tentatives; }
    public function setTentatives(int $tentatives): void { $this->tentatives = $tentatives; }
    public function getBloqueJusquA(): ?string { return $this->bloqueJusquA; }
    public function setBloqueJusquA(?string $bloqueJusquA): void { $this->bloqueJusquA = $bloqueJusquA; }
    public function getResetToken(): ?string { return $this->resetToken; }
    public function setResetToken(?string $resetToken): void { $this->resetToken = $resetToken; }
    public function getResetExpire(): ?string { return $this->resetExpire; }
    public function setResetExpire(?string $resetExpire): void { $this->resetExpire = $resetExpire; }
    public function getDerniereConnexion(): ?string { return $this->derniereConnexion; }
    public function setDerniereConnexion(?string $derniereConnexion): void { $this->derniereConnexion = $derniereConnexion; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function setCreatedAt(string $createdAt): void { $this->createdAt = $createdAt; }

     
    public static function fromArray(array $row): self
    {
        $u = new self();
        $u->setId((int) ($row['id'] ?? 0));
        $u->setUsername((string) ($row['username'] ?? ''));
        $u->setEmail((string) ($row['email'] ?? ''));
        $u->setPassword((string) ($row['password'] ?? ''));
        $u->setRole((string) ($row['role'] ?? 'user'));
        $u->setActif((int) ($row['actif'] ?? 1));
        $u->setValide((int) ($row['valide'] ?? 1));
        $u->setSpecialite(isset($row['specialite']) ? (string) $row['specialite'] : null);
        $u->setVille(isset($row['ville']) ? (string) $row['ville'] : null);
        $u->setTelephone(isset($row['telephone']) ? (string) $row['telephone'] : null);
        $u->setDescription(isset($row['description']) ? (string) $row['description'] : null);
        $u->setNumeroOrdre(isset($row['numero_ordre']) ? (string) $row['numero_ordre'] : null);
        $u->setHoraireDebut(isset($row['horaire_debut']) ? (string) $row['horaire_debut'] : null);
        $u->setHoraireFin(isset($row['horaire_fin']) ? (string) $row['horaire_fin'] : null);
        $u->setPhoto(isset($row['photo']) ? (string) $row['photo'] : null);
        $u->setTentatives((int) ($row['tentatives'] ?? 0));
        $u->setBloqueJusquA(isset($row['bloque_jusqu_a']) ? (string) $row['bloque_jusqu_a'] : null);
        $u->setResetToken(isset($row['reset_token']) ? (string) $row['reset_token'] : null);
        $u->setResetExpire(isset($row['reset_expire']) ? (string) $row['reset_expire'] : null);
        $u->setDerniereConnexion(isset($row['derniere_connexion']) ? (string) $row['derniere_connexion'] : null);
        $u->setCreatedAt((string) ($row['created_at'] ?? ''));
        return $u;
    }

    public function isPro(): bool { return $this->role === 'professional'; }
}
