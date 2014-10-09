<?php

class Zynas_View_Default extends Zynas_View {

    public function queryProfile($dbName) {

        $profiler = Zynas_Registry::get($dbName)->getProfiler();

        $totalTime = $profiler->getTotalElapsedSecs();
        $queryCount = $profiler->getTotalNumQueries();
        $longestTime = 0;
        $longestQuery = null;

        $debug = '<table cellpadding="3" cellspacing="3" style="border: 1px solid #666666; width: 96%; font-size: 10pt; margin-left: 2%; background-color: #FFF; margin: 20px;">';
            $debug .= '<tr>';
                $debug .= '<th colspan="3" style="text-align: center; border: 1px solid #666666; font-size: 14pt;">Query Profile: ' . $dbName . '</th>';
            $debug .= '</tr>';
            $debug .= '<tr>';
                $debug .= '<th style="text-align: center; border: 1px solid #666666;">Query</th>';
                $debug .= '<th style="text-align: center; border: 1px solid #666666;">Time</th>';
                $debug .= '<th style="text-align: center; border: 1px solid #666666;">Ratio</th>';
            $debug .= '</tr>';

        if ($queryCount > 0) {

            foreach ($profiler->getQueryProfiles() as $query) {

                $debug .= '<tr>';
                    $debug .= '<td style="border: 1px solid #666666;"><tt style="font-family: \'Courier New\', monospace;">' . nl2br(trim($query->getQuery())) . '</tt></td>';
                    $debug .= '<td style="border: 1px solid #666666;">' . round($query->getElapsedSecs() * 1000, 4) . 'ミリ秒</td>';
                    $debug .= '<td style="border: 1px solid #666666;">' . round(($query->getElapsedSecs() / $totalTime) * 100, 2) . '%</td>';
                $debug .= '<tr>';

                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime  = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                }

            }

            $debug .= '<tr>';
                $debug .= '<td style="border: 1px solid #666666;" colspan="3">';
                    $debug .= '全部で ' . $queryCount . ' 件のクエリが ' . round($totalTime * 1000, 4) . ' ミリ秒で実行されました<br />';
                    $debug .= '平均の所要時間: ' . round(($totalTime / $queryCount) * 1000, 4) . ' ミリ秒<br />';
                    $debug .= '1 秒あたりのクエリ実行数: ' . ($queryCount / $totalTime) . '<br />';
                    $debug .= '所要時間の最大値: ' . round($longestTime * 1000, 4) . ' ミリ秒<br />';
                    $debug .= '一番時間のかかっているクエリ:<br /><tt style="font-family: \'Courier New\', monospace;">' . nl2br(trim($longestQuery)) . '</tt>';
                $debug .= '</td>';
            $debug .= '</tr>';

        }

        $debug .= '</table>';

        return $debug;
    }

}

?>