<?php
namespace App\Models;

class Goal extends Model
{
    public const STATUSES   = ['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Atteint'];
    public const CATEGORIES = [
        'physical' => 'Physique', 'mental' => 'Mental', 'social' => 'Social',
        'professional' => 'Professionnel', 'other' => 'Autre',
    ];

    public static function all(): array
    {
        return self::db()->query('SELECT * FROM goal ORDER BY created_at DESC, id DESC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $s = self::db()->prepare('SELECT * FROM goal WHERE id = ?');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public static function save(?int $id, array $d): int
    {
        if ($id) {
            $s = self::db()->prepare(
                'UPDATE goal SET title=?, description=?, category=?, target_date=?, status=? WHERE id=?'
            );
            $s->execute([$d['title'], $d['description'], $d['category'], $d['target_date'], $d['status'], $id]);
            return $id;
        }
        $s = self::db()->prepare(
            'INSERT INTO goal (title, description, category, target_date, status) VALUES (?,?,?,?,?)'
        );
        $s->execute([$d['title'], $d['description'], $d['category'], $d['target_date'], $d['status']]);
        return (int) self::db()->lastInsertId();
    }

    public static function delete(int $id): void
    {
        self::db()->prepare('DELETE FROM goal WHERE id = ?')->execute([$id]);
    }

    public static function count(?string $status = null): int
    {
        if ($status === null) {
            return (int) self::db()->query('SELECT COUNT(*) FROM goal')->fetchColumn();
        }
        $s = self::db()->prepare('SELECT COUNT(*) FROM goal WHERE status = ?');
        $s->execute([$status]);
        return (int) $s->fetchColumn();
    }

    /** @return int[] ids of existing goals */
    public static function ids(): array
    {
        return array_map('intval', self::db()->query('SELECT id FROM goal')->fetchAll(\PDO::FETCH_COLUMN));
    }
}
