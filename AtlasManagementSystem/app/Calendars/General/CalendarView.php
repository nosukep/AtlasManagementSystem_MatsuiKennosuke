<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    //newで作ると、現在日付のインスタンスができる。
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    //インスタンスした現在月を表示形式を指定して表示する。
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
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
        $startDay = $this->carbon->copy()->format("Y-m-01");
        // 今日取得
        $toDay = $this->carbon->copy()->format("Y-m-d");
        // 今日以降の日付はtdタグにクラス名「day-(曜日)」を付与する
        if($startDay <= $day->everyDay() && $toDay > $day->everyDay()){
          $html[] = '<td class="calendar-td past-day">';
        }else{
          $html[] = '<td class="calendar-td '.$day->getClassName().'">';
        }
        // app/Calendars/General/CalendarWeekDay.phpで設定しているメソッド。日付取得してhtml(pタグ)表示。("d"にすると先頭に0が付く)
        $html[] = $day->render();

        if ($startDay <= $day->everyDay() && $toDay > $day->everyDay()) {
          $html[] = '<p>受付終了</p>';
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
        }else {
          if(in_array($day->everyDay(), $day->authReserveDay())){
            // ログインユーザーが予約している日があれば予約日の部数を取得。
            $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
            if($reservePart == 1){
              $reservePart = "リモ1部";
            }else if($reservePart == 2){
              $reservePart = "リモ2部";
            }else if($reservePart == 3){
              $reservePart = "リモ3部";
            }
            // 月初から今日までの間の日付で予約している日の処理。
            if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
              $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px"></p>';
              $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            }else{
              // 今日以降の予約をキャンセルするボタン
              $html[] = '<button type="submit" class="cancel-modal-open btn btn-danger p-0 w-75" name="" style="font-size:12px" delete-date="'. $day->authReserveDate($day->everyDay())->first()->setting_reserve .'" delete-part="'. $reservePart .'">'. $reservePart .'</button>';
              $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            }
          }else{
            // 予約していなければapp/Calendars/General/CalendarWeekDay.phpで設定しているselectPartメソッドでselectボックスを表示。
            $html[] = $day->selectPart($day->everyDay());
          }
        }
        // app/Calendars/General/CalendarWeekDay.phpの82行目で設定しているinput(hidden)タグ。app/Http/Controllers/Authenticated/Calendar/Admin/CalendarsController.phpで予約する部数と結合して連想配列化する。
        $html[] = $day->getDate();
        $html[] = '</td>';
      } // 日のforeachここまで
      $html[] = '</tr>';
    } // 週のforeachここまで
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    //implode(連結文字, 連結したい文字列（配列であること）);
    return implode('', $html);
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
    // 配列に格納。
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
