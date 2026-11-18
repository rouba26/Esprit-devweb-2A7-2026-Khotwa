<?php
namespace App\Models;

/** Association entity: RecoveryPlan N--N Goal (with a priority). */
class PlanGoal extends Model
{
    /** @return int[] */
    public static function goalIdsOf(int $planId): array
    {
        $s = self::db()->prepare('SELECT goal_id FROM plan_goal WHERE plan_id = ? ORDER BY priority');
        $s->execute([$planId]);
        return array_map('intval', $s->fetchAll(\PDO::FETCH_COLUMN));
    }

    /** Replace the goals of a plan; priority follows the submitted order. */
    public static function sync(int $planId, array $goalIds): void
    {
        $db = self::db();
        $db->beginTransaction();
        try {
            $db->prepare('DELETE FROM plan_goal WHERE plan_id = ?')->execute([$planId]);
            $ins = $db->prepare('INSERT INTO plan_goal (plan_id, goal_id, priority) VALUES (?,?,?)');
            foreach (array_values(array_unique($goalIds)) as $i => $goalId) {
                $ins->execute([$planId, $goalId, $i + 1]);
            }
            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
