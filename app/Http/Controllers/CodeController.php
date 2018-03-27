<?php

namespace App\Http\Controllers;


use App\Code;
use Illuminate\Http\Request;

use App\Http\Requests\Codes\StoreCode;
use App\Http\Requests\Codes\UpdateCode;

use App\Helpers\EasyuiPagination;

class CodeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); // allow creating through admin only
    }

    public function index()
    {
    	return view('codes.index');
    }

    public function list(Request $request)
    {
      $query = Code::select('id', 'name', 'category');

      if($request->has('filterRules')) {

        $filterRules = json_decode($request->filterRules);

        foreach ($filterRules as $filterRule) {
          $query->where($filterRule->field, 'like', '%'. $filterRule->value .'%');
        }
      }

    	return EasyuiPagination::paginageData($request, $query);
    }

    public function listCategories()
    {
      return Code::distinct('category')->get(['category']);
    }


    public function showInsert(Request $request)
    {
      return view('codes.insert_code');
    }

    /**
     * Create a new code instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Code
     */
    protected function insert(StoreCode $request)
    {
        $code = Code::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
        ]);

         $this->saveAttachment($request, $code);

        $this->saveActivity('create', $code);

        return ezReturnSuccessMessage('Code created successfully!');
    }

    public function showUpdate(Request $request)
    {
      $code = Code::findOrFail($request->id);
      return view('codes.update_code')->with('code', $code);
    }

    public function update(UpdateCode $request)
    {
    	$code = Code::findOrFail($request->id);

    	$code->name = $request->name;
    	$code->category = $request->category;
    	$code->description = $request->description;

    	$code->save();

      $this->saveAttachment($request, $code);

      $this->saveActivity('edit', $code);

      return ezReturnSuccessMessage('Code updated successfully!');
    }

    public function destroy(Request $request)
    {

      $code = Code::findOrFail($request->id);

      $code->delete();

      $code->clearMediaCollection();

      $this->saveActivity('delete()', $code);

      return ezReturnSuccessMessage('Code removed successfully!');

    }

    public function destroyMedia(Request $request)
    {

    	$code = Code::findOrFail($request->code_id);

      $media = $code->getMedia()->where('id', $request->media_id);

    	$media[0]->delete();

    	return ezReturnSuccessMessage('Media removed successfully!');

    }

    private function saveAttachment(Request $request, Code $code)
    {
        if ($request->hasFile('attachment')) {

          foreach ($request->attachment as $attachment) {

            $code->addMedia($attachment)->withResponsiveImages()->toMediaCollection();

          }

        }
    }

    private function saveActivity($operation, Code $code)
    {
      activity()->causedBy(\Auth::user())
                ->performedOn($code)
                ->withProperties(['model_activity_name' => $code::MODEL_ACTIVITY_NAME])
                ->log($operation);
    }


}
