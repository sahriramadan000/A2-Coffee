<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class OtherSettingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:other-setting', ['only' => ['getModal','update']]);
    }

    public function getModal()
    {
        $other_settings = OtherSetting::orderBy('id', 'ASC')->get()->first();

        if (!$other_settings) {
            $other_settings = [];
        }

        return View::make('admin.other-setting.modal')->with([
            'other_setting' => $other_settings
        ]);
    }

    public function update(Request $request, $otherSettingId)
    {
        $validate = $request->validate([
            'pb01' => 'nullable|integer|min:0|max:100|regex:/[0-9]/',
            'layanan' => 'nullable',
            'time_start' => 'nullable',
            'time_close' => 'nullable',
        ]);

        try {
            if ($otherSettingId == '0') {
                $other = new OtherSetting();
             } else {
                 $other = OtherSetting::findorFail($otherSettingId);
             }

             $other->pb01           = (int) str_replace('.', '', $validate['pb01']);
             $other->layanan        = (int) str_replace('.', '', $validate['layanan']);
             $other->time_start     = $validate['time_start'];
             $other->time_close     = $validate['time_close'];
             $other->save();

            $request->session()->flash('success', "Update data other setting successfully!");
            return redirect(route('dashboard'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to update data other setting!");
            return redirect(route('dashboard'));
        }
    }
}
