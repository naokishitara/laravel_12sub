<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Profile;
use App\ProfileHistory;
use Carbon\Carbon;

class ProfileController extends Controller
{
  public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        $this->validate($request, Profile::$rules);

      $profile = new Profile;
      $form = $request->all();
      
      unset($form['_token']);

      // データベースに保存する
      $profile->fill($form);
      $profile->save();

        return redirect('admin/profile/create');
    }

    public function edit(Request $request)
    {
        $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
        return view('admin/profile/edit',['profile_form'=>$profile]);
    }

    public function update(Request $request)
    {
     $this->validate($request, Profile::$rules);

      $profile= Profile::find($request->id);
      $form = $request->all();

       // フォームから送信されてきた_tokenを削除する
      unset($form['_token']);

      // データベースに保存する
      $profile->fill($profile_form)->save();
      
      $profilehistory = new ProfileHistory();
      $profilehistory->profile_id = $profile->id;
      $profilehistory->edited_at = Carbon::now();
      $profilehistory->save();

      return redirect('admin/profile');

  }
}
