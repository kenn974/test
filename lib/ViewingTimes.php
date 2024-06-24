<?php

//これで先ずは入力値を取得する → 完成
const SPLIT_LENGTH = 2;

function getInput(array $argv = []) {
    $arguments = (array_slice($argv, 1));
    return array_chunk($arguments, SPLIT_LENGTH);
}

function groupChannelViewingPeriods(array $inputs): array{
    //先ずは、データの入れ物を用意する
    //$channelViewingPeriodsは、空配列でこれに値を入れる配列を用意する
    //$inputsのそれぞれの値ごとに、全部処理していきたい → foreachでループを回す
    $channelViewingPeriods = [];

    foreach ($inputs as $input) {
        //先ずは、値を取り出す
        $chan = $input[0];
        $min = $input[1];
        //最終的に、視聴時間を配列で持ちたいので、ここで配列を定義しておく
        $mins = [$min];
        //ここで条件分岐が必要(チャンネルは複数回登場しうるので、それの複数回登場した用の条件分岐をしておく)
        /*どういう風に複数回登場しているかどうかを判断するかというと$channelViewingPeriodsのkey名である$chanが
        もうすでに存在していたら、1回登場しているということになる*/
        if (array_key_exists($chan, $channelViewingPeriods)) {
            $mins = array_merge($channelViewingPeriods[$chan], $mins);
        }
        //こうすることでチャンネルの番号がkeyで、値として視聴分数の配列が入っている配列になる
        $channelViewingPeriods[$chan] = $mins;
    }
        return $channelViewingPeriods;
}

function calculateTotalHour(array $channelViewingPeriods): float {
    $viewingTimes = [];
    foreach ($channelViewingPeriods as $period) {
        $viewingTimes = array_merge($viewingTimes, $period);
    }

    // 配列から文字列を取り除く
    $numbersOnly = array_filter($viewingTimes, 'is_numeric');

    $totalMin = array_sum($numbersOnly);
    return round($totalMin / 60, 1);
}

    function display(array $channelViewingPeriods): void {
    $totalHour = calculateTotalHour($channelViewingPeriods);
    echo $totalHour . PHP_EOL;
    foreach ($channelViewingPeriods as $chan => $mins) {
        $totalMinutes = array_sum($mins); // $minsは数値の配列なので、array_sumを適用できる
        echo $chan . ' ' . $totalMinutes . ' ' . count($mins) . PHP_EOL;
    }
}

$inputs = getInput($_SERVER['argv']);
$channelViewingPeriods = groupChannelViewingPeriods($inputs);
display($channelViewingPeriods);
