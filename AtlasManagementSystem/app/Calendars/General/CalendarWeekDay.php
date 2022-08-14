<?php
namespace App\Calendars\General;

use App\Models\Calendars\ReserveSettings;
use Carbon\Carbon;
use Auth;

class CalendarWeekDay{
  protected $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  function getClassName(){
    // 曜日を取得してHTMLのクラス名を追加。「strtolower」で小文字化
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function pastClassName(){
    return "";
  }

  /**
   * @return
   */

   function render(){
    // 日付取得してhtmlに表示。("d"にすると先頭に0が付く)
     return '<p class="day">' . $this->carbon->format("j"). '日</p>';
   }

   function selectPart($ymd){
    // reserve_settingsテーブルでセッティングされている指定日付の1部を取得
     $one_part_frame = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '1')->first();
     // reserve_settingsテーブルでセッティングされている指定日付の2部を取得
     $two_part_frame = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first();
     // reserve_settingsテーブルでセッティングされている指定日付の3部を取得
     $three_part_frame = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first();
     // reserve_settingsテーブルでセッティングされている指定日付の1部を取得できる場合、人数を取得。取得できない場合は"0"を渡す
     if($one_part_frame){
       $one_part_frame = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '1')->first()->limit_users;
     }else{
       $one_part_frame = '0';
     }
     // reserve_settingsテーブルでセッティングされている指定日付の2部を取得できる場合、人数を取得。取得できない場合は"0"を渡す
     if($two_part_frame){
       $two_part_frame = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first()->limit_users;
     }else{
       $two_part_frame = '0';
     }
     // reserve_settingsテーブルでセッティングされている指定日付の3部を取得できる場合、人数を取得。取得できない場合は"0"を渡す
     if($three_part_frame){
       $three_part_frame = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first()->limit_users;
     }else{
       $three_part_frame = '0';
     }

    //  セレクトボックスを作成。app/Calendars/General/CalendarView.phpの76行目で使われている
     $html = [];
     $html[] = '<select name="getPart[]" class="border-primary" style="width:70px; border-radius:5px;" form="reserveParts">';
     $html[] = '<option value="" selected></option>';
     if($one_part_frame == "0"){
       $html[] = '<option value="1" disabled>リモ1部(残り0枠)</option>';
     }else{
       $html[] = '<option value="1">リモ1部(残り'.$one_part_frame.'枠)</option>';
     }
     if($two_part_frame == "0"){
       $html[] = '<option value="2" disabled>リモ2部(残り0枠)</option>';
     }else{
       $html[] = '<option value="2">リモ2部(残り'.$two_part_frame.'枠)</option>';
     }
     if($three_part_frame == "0"){
       $html[] = '<option value="3" disabled>リモ3部(残り0枠)</option>';
     }else{
       $html[] = '<option value="3">リモ3部(残り'.$three_part_frame.'枠)</option>';
     }
     $html[] = '</select>';
     return implode('', $html);
   }

   function getDate(){
    // 各日付を渡す。app/Http/Controllers/Authenticated/Calendar/Admin/CalendarsController.phpで予約する部数と結合して連想配列化する。
     return '<input type="hidden" value="'. $this->carbon->format('Y-m-d') .'" name="getData[]" form="reserveParts">';
   }

  //  今日を取得
   function everyDay(){
     return $this->carbon->format('Y-m-d');
   }

   function authReserveDay(){
    // ログインユーザーが予約している日にちを取得して配列化
     return Auth::user()->reserveSettings->pluck('setting_reserve')->toArray();
   }

   function authReserveDate($reserveDate){
    // ログインユーザーが予約している日にちを抽出。データ取得クエリ文用。
     return Auth::user()->reserveSettings->where('setting_reserve', $reserveDate);
   }

}
