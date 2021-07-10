<?php

namespace App\Services;

use App\Services\Integration\BaseDatabaseIntegrationService;

class AlertDatabaseService extends BaseDatabaseIntegrationService
{
    /**
     * @param string $appName
     * @param string $metric
     * @param int $value
     * @param string $condition
     * @return array
     */
    public function getAlertsByMetric(string $appName, string $metric, int $value, string $condition): array
    {
        $sql = <<<SQL
    SELECT * FROM alerts
    WHERE `app_name` = '{$appName}'
      AND `metric` = '{$metric}'
      AND `condition` = '{$condition}'
      AND {$value} {$condition} `threshold`
SQL;

        $data = [];
        $stmt = $this->getConnection()->query($sql);
        while ($row = $stmt->fetch()) {
            $data[] = $this->formatAlert($row);
        }

        return $data;
    }

    /**
     * @param array $row
     * @return array
     */
    private function formatAlert(array $row): array
    {
        return [
            'alert_id' => $row['alert_id'],
            'app_name' => $row['app_name'],
            'title' => $row['title'],
            'description' => $row['description'],
            'enabled' => $row['enabled'],
            'metric' => $row['metric'],
            'condition' => $row['condition'],
            'threshold' => $row['threshold'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        ];
    }
}
