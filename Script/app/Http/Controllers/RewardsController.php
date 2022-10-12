<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Rewards;
use App\Models\User;
use App\Helper;
use Carbon\Carbon;

class RewardsController extends Controller
{
  public function __construct( AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

  protected function validator(array $data, $countPledge = null)
  {

    if($countPledge) {
      $quantityMin = $countPledge;
    } else {
      $quantityMin = 1;
    }

    if($this->settings->currency_position == 'right') {
      $currencyPosition =  2;
    } else {
      $currencyPosition =  null;
    }

    $messages = array (
  		'amount.min' => trans('misc.amount_minimum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
		'amount.max' => trans('misc.amount_maximum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
	);

			return Validator::make($data, [
           'title'  => 'required|min:5|max:50',
		    	 'amount'        => 'required|integer|max:'.$this->settings->max_donation_amount.'|min:'.$this->settings->min_donation_amount,
           'description'  => 'required|min:20|max:200',
           'quantity'     => 'required|integer|min:'.$quantityMin,
		    	 'delivery'     => 'required',
         ],$messages);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $data = Campaigns::where('id', $this->request->id)
  		->where('user_id', Auth::user()->id)
  		->firstOrFail();

  		return view('campaigns.rewards')->withData($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $input      = $this->request->all();
      $validator = $this->validator($input);

       if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        } //<-- Validator

      $sql               = new Rewards;
      $sql->campaigns_id = $this->request->id;
      $sql->title        = strip_tags($this->request->title);
      $sql->amount       = $this->request->amount;
  		$sql->description  = strip_tags($this->request->description);
      $sql->quantity     = $this->request->quantity;
      $sql->delivery     = $this->request->delivery;

  		$sql->save();

	    return response()->json([
	        'success' => true,
          'rewards' => true,
	        'target' => url('campaign',$this->request->id),
	    ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $data = Rewards::findOrFail($id);

      if( $data->campaigns()->user_id != Auth::user()->id ){
  			abort('404');
  		}

  		return view('campaigns.edit-rewards')->with(['data' => $data, 'campaign' =>  $data->campaigns()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
      $sql     = Rewards::findOrFail($this->request->id);

      if( $sql->campaigns()->user_id != Auth::user()->id )
      {
  			abort('404');
  		}

      if( $sql->donations() ) {
        $countPledge = $sql->donations()->where('rewards_id',$sql->id)->count();
      } else {
        $countPledge = null;
      }

      $input      = $this->request->all();
      $validator = $this->validator($input,$countPledge);

       if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        } //<-- Validator


      $sql->campaigns_id = $sql->campaigns()->id;
      $sql->title        = strip_tags($this->request->title);
      $sql->amount       = $this->request->amount;
      $sql->description  = strip_tags($this->request->description);
      $sql->quantity     = $this->request->quantity;
      $sql->delivery     = $this->request->delivery;

      $sql->save();

      return response()->json([
          'success' => true,
          'target' => url('campaign',$sql->campaigns()->id),
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $sql = Rewards::findOrFail($this->request->id);

        if( $sql->campaigns()->user_id != Auth::user()->id ){
    			return redirect()->back();
    		}

        if( $sql->donations() ) {
          //$countPledge = $sql->donations()->where('rewards_id',$sql->id)->count();
          return redirect()->back();
        } else {
          $sql->delete();
          return redirect('campaign/'.$sql->campaigns()->id);
        }
    }
}
