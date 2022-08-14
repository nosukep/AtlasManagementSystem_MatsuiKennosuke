<?php

namespace App\Http\Controllers\Authenticated\Calendar\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\Admin\CalendarView;
use App\Calendars\Admin\CalendarSettingView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.admin.calendar', compact('calendar'));
    }

    public function reserveDetail($user_id = 0, $date, $part){
        $reservePersons = ReserveSettings::with('users')->where('setting_reserve', $date)->where('setting_part', $part)->get();
        return view('authenticated.calendar.admin.reserve_detail', compact('reservePersons', 'date', 'part'));
    }

    public function reserveSettings(){
        $calendar = new CalendarSettingView(time());
        return view('authenticated.calendar.admin.reserve_setting', compact('calendar'));
    }

    public function updateSettings(Request $request){
        // app/Calendars/Admin/CalendarSettingView.phpから送信される
        // $reserveDaysにapp/Calendars/Admin/CalendarSettingView.phpから渡される明日以降の日付ごとに1,2,3部の人数を配列に格納した配列を格納
        $reserveDays = $request->input('reserve_day');
        // foreachでもとの配列に「日付=>部」と名付けて回す
        foreach($reserveDays as $day => $parts){
            foreach($parts as $part => $frame){
                // foreachでもとのpart配列に「部=>人数」と名付けて回す
                // reserve_settingsテーブルを探索して、指定した値が指定したカラムに格納されているレコードがヒットすればupdate、ヒットしなかった場合はcreateを実行する。
                ReserveSettings::updateOrCreate([
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                ],[
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    'limit_users' => $frame,
                ]);
            }
        }
        return redirect()->route('calendar.admin.setting', ['user_id' => Auth::id()]);
    }
}
