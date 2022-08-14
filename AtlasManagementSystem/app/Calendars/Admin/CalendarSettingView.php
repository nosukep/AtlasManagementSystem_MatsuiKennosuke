<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarSettingView{
  private $carbon;

  function __construct($date){
    //newで作ると、現在日付のインスタンスができる。
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    //インスタンスした現在月を表示形式を指定して表示する。
    return $this->carbon->format('Y年n月');
  }

  public function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border adjust-table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border">土</th>';
    $html[] = '<th class="border">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();

    foreach($weeks as $week){ // 週のforeach
      // クラス名取得"week-xx"
      $html[] = '<tr class="'.$week->getClassName().'">';
      // app/Calendars/General/CalendarWeek.php内のgetDaysメソッド
      $days = $week->getDays();
      foreach($days as $day){ // 日のforeach
        // 月初取得
        $startDay = $this->carbon->format("Y-m-01");
        // 今日取得
        $toDay = $this->carbon->format("Y-m-d");
        // 月初から今日までの日付はtdタグにクラス名「past-day border」を付与する。今日以降の日付はtdタグにクラス名「day-(曜日)」を付与する。
       if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
          $html[] = '<td class="past-day border">';
        }else{
          $html[] = '<td class="border '.$day->getClassName().'">';
        }
        // app/Calendars/General/CalendarWeekDay.phpで設定しているメソッド。日付取得してhtml(pタグ)表示。("d"にすると先頭に0が付く)
        $html[] = $day->render();
        $html[] = '<div class="adjust-area">';
        if($day->everyDay()){
          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
            // 月初から今日までの間の日付はinputタグを入力不可(disable属性付与)にする。
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][1]" type="text" form="reserveSetting" value="'.$day->onePartFrame($day->everyDay()).'" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][2]" type="text" form="reserveSetting" value="'.$day->twoPartFrame($day->everyDay()).'" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][3]" type="text" form="reserveSetting" value="'.$day->threePartFrame($day->everyDay()).'" disabled></p>';
          }else{
            // 明日以降の日付は日付ごとに1,2,3部の人数を配列に格納したものを配列化してapp/Http/Controllers/Authenticated/Calendar/Admin/CalendarsController.phpに送信する。
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][1]" type="text" form="reserveSetting" value="'.$day->onePartFrame($day->everyDay()).'"></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][2]" type="text" form="reserveSetting" value="'.$day->twoPartFrame($day->everyDay()).'"></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][3]" type="text" form="reserveSetting" value="'.$day->threePartFrame($day->everyDay()).'"></p>';
          }
        }
        $html[] = '</div>';
        $html[] = '</td>';
      } // 日のforeachここまで
      $html[] = '</tr>';
    } // 週のforeachここまで
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="'.route('calendar.admin.update').'" method="post" id="reserveSetting">'.csrf_field().'</form>';
    //implode(連結文字, 連結したい文字列（配列であること）);
    return implode("", $html);
  }

  //週カレンダーを一月分用意した配列$weeksを返却する
  protected function getWeeks(){
    // 配列を定義。
    $weeks = [];
    // 月初を取得。インスタンスのコピーを作って、日付操作をしても元のインスタンスに影響を与えないようにする。
    $firstDay = $this->carbon->copy()->firstOfMonth();
    // 月末を取得。
    $lastDay = $this->carbon->copy()->lastOfMonth();
    // 1週目作成。月初を指定してapp/Calendars/General/CalendarWeek.phpをインスタンス化。
    $week = new CalendarWeek($firstDay->copy());
    // 配列に格納
    $weeks[] = $week;
    // 作業用の日を作成。翌週の週頭が欲しいので、+7日した後、週の開始日に移動する
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    // 月末まで繰り返し処理
    while($tmpDay->lte($lastDay)){
      // 第2引数count($weeks)を指定。何週目かを週カレンダーオブジェクトに伝えるために設置する。
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      // 一週毎に+7日することで$tmpDayを翌週に移動
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
