<?php
namespace App\Models;

class RecoveryPlan extends Model
{
    public const STATUSES = ['active' => 'Actif', 'paused' => 'En pause', 'completed' => 'Terminé'];

    public static function all(): array
    {
        return self::db()->query(
            'SELECT p.*, (SELECT COUNT(*) FROM plan_goal pg WHERE pg.plan_id = p.id) AS goals_count
             FROM recovery_plan p ORDER BY p.created_at DESC, p.id DESC'
        )->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $s = self::db()->prepare('SELECT * FROM recovery_plan WHERE id = ?');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public static function save(?int $id, array $d): int
    {
        if ($id) {
            $s = self::db()->prepare(
                'UPDATE recovery_plan SET title=?, description=?, start_date=?, end_date=?, status=? WHERE id=?'
            );
            $s->execute([$d['title'], $d['description'], $d['start_date'], $d['end_date'], $d['status'], $id]);
            return $id;
        }
        $s = self::db()->prepare(
            'INSERT INTO recovery_plan (title, description, start_date, end_date, status) VALUES (?,?,?,?,?)'
        );
        $s->execute([$d['title'], $d['description'], $d['start_date'], $d['end_date'], $d['status']]);
        return (int) self::db()->lastInsertId();
    }

    public static function delete(int $id): void
    {
        self::db()->prepare('DELETE FROM recovery_plan WHERE id = ?')->execute([$id]);
    }

    public static function count(?string $status = null): int
    {
        if ($status === null) {
            return (int) self::db()->query('SELECT COUNT(*) FROM recovery_plan')->fetchColumn();
        }
        $s = self::db()->prepare('SELECT COUNT(*) FROM recovery_plan WHERE status = ?');
        $s->execute([$status]);
        return (int) $s->fetchColumn();
    }
}
