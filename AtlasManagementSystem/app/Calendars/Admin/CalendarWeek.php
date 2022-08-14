<?php
namespace App\Calendars\Admin;

use Carbon\Carbon;

class CalendarWeek{
  protected $carbon;
  protected $index = 0;

  function __construct($date, $index = 0){
    $this->carbon = new Carbon($date);
    $this->index = $index;
  }

  function getClassName(){
    return "week-" . $this->index;
  }

  function getDays(){
    // 配列を定義
    $days = [];
    // 週頭を取得。インスタンスのコピーを作って、日付操作をしても元のインスタンスに影響を与えないようにする。
    $startDay = $this->carbon->copy()->startOfWeek();
    // 週末を取得。インスタンスのコピーを作って、日付操作をしても元のインスタンスに影響を与えないようにする。
    $lastDay = $this->carbon->copy()->endOfWeek();
    // 作業用の日を作成。
    $tmpDay = $startDay->copy();

    while($tmpDay->lte($lastDay)){
      if($tmpDay->month != $this->carbon->month){
         // 今月に該当しない月はブランクする。
        $day = new CalendarWeekBlankDay($tmpDay->copy());
        // 配列に格納。
        $days[] = $day;
        //  次の日を取得。
        $tmpDay->addDay(1);
        // ブランクがなくなるまで繰り返す。
        continue;
       }
       // 詳細な日付の処理。app/Calendars/Admin/CalendarWeekDay.php
       $day = new CalendarWeekDay($tmpDay->copy());
       // 配列に格納
       $days[] = $day;
        //  次の日を取得。
       $tmpDay->addDay(1);
    }
    return $days;
  }
}
