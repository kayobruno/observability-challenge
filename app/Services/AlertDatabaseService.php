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
     * @param string $metricName
     * @return array
     */
    public function getAlertsGroupByMetrics(string $metricName): array
    {
        $sql = <<<SQL
SELECT alerts.alert_id, alerts.metric,
       COUNT(incidents.alert_id) AS total
FROM incidents
    INNER JOIN alerts
        ON incidents.alert_id = alerts.alert_id
WHERE alerts.metric = '{$metricName}'
GROUP BY incidents.alert_id
SQL;

        $data = [];
        $stmt = $this->getConnection()->query($sql);
        while ($row = $stmt->fetch()) {
            $data[] = [
                'alert_id' => $row['alert_id'],
                'metric' => $row['metric'],
                'total' => $row['total'],
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getAlertsGroupByAppName(): array
    {
        $sql = <<<SQL
SELECT alerts.alert_id, alerts.app_name,
	COUNT(incidents.alert_id) AS total
FROM incidents
INNER JOIN alerts
	ON incidents.alert_id = alerts.alert_id
GROUP BY alerts.app_name
SQL;

        $data = [];
        $stmt = $this->getConnection()->query($sql);
        while ($row = $stmt->fetch()) {
            $data[] = [
                'alert_id' => $row['alert_id'],
                'app_name' => $row['app_name'],
                'total' => $row['total'],
            ];
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
