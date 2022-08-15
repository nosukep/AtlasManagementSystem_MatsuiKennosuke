<?php
namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarWeekDay{
  protected $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  function getClassName(){
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function render(){
    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function everyDay(){
    return $this->carbon->format("Y-m-d");
  }

  function dayPartCounts($ymd){
    // 配列を定義
    $html = [];
    // 1部がセッティングされていれば取得
    $one_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '1')->first();
    // 2部がセッティングされていれば取得
    $two_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first();
    // 3部がセッティングされていれば取得
    $three_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first();

    $html[] = '<div class="text-left">';
    if($one_part){
      // 1部がセッティングされていればhtmlを表示
      $html[] = '<p class="day_part m-0 pt-1"><a href="' . route('calendar.admin.detail',['id' => $one_part->id, 'data' => $one_part->setting_reserve, 'part' => $one_part->setting_part]) . '">1部</a><span style="margin-left: 22px">' . $one_part->users->count() . '</span></p>';
    }
    if($two_part){
      // 2部がセッティングされていればhtmlを表示
      $html[] = '<p class="day_part m-0 pt-1"><a href="' . route('calendar.admin.detail',['id' => $two_part->id, 'data' => $two_part->setting_reserve, 'part' => $two_part->setting_part]) . '">2部</a><span style="margin-left: 20px">' . $two_part->users->count() . '</span></p>';
    }
    if($three_part){
      // 3部がセッティングされていればhtmlを表示
      $html[] = '<p class="day_part m-0 pt-1"><a href="' . route('calendar.admin.detail',['id' => $three_part->id, 'data' => $three_part->setting_reserve, 'part' => $three_part->setting_part]) . '">3部</a><span style="margin-left: 20px">' . $three_part->users->count() . '</span></p>';
    }
    $html[] = '</div>';

    return implode("", $html);
  }


  function onePartFrame($day){
    $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first();
    if($one_part_frame){
      // 1部がセッティングされていれば人数を取得
      $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first()->limit_users;
    }else{
      // セッティングされていれば人数を20で表示
      $one_part_frame = "20";
    }
    return $one_part_frame;
  }
  function twoPartFrame($day){
    $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first();
    if($two_part_frame){
      // 2部がセッティングされていれば人数を取得
      $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first()->limit_users;
    }else{
      // セッティングされていれば人数を20で表示
      $two_part_frame = "20";
    }
    return $two_part_frame;
  }
  function threePartFrame($day){
    $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first();
    if($three_part_frame){
      // 3部がセッティングされていれば人数を取得
      $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first()->limit_users;
    }else{
      // セッティングされていれば人数を20で表示
      $three_part_frame = "20";
    }
    return $three_part_frame;
  }

  //
  function dayNumberAdjustment(){
    $html = [];
    $html[] = '<div class="adjust-area">';
    $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="1" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="2" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="3" type="text" form="reserveSetting"></p>';
    $html[] = '</div>';
    return implode('', $html);
  }
}
