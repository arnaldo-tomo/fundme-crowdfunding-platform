<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminSettings;
use App\Models\Blogs;
use App\Models\User;
use App\Helper;
use Carbon\Carbon;
use Mail;

class BlogController extends Controller
{
  /**
	 * Display all Blogs Posts
	 *
	 * @return Response
	 */
  public function blog()
  {
    $blogs = Blogs::orderBy('id','desc')->paginate(10);

    $page = request()->get('page');

    if ($page > $blogs->lastPage()) {
			abort('404');
		}
    return view('index.blog', ['blogs' => $blogs]);
  }

  /**
	 * Display Posts Details
   *
	 * @param int  $id
	 * @return Response
	 */
  public function post($id)
  {
    $response = Blogs::whereId($id)->firstOrFail();
    $blogs    = Blogs::where('id','<>', $response->id)->inRandomOrder()->take(3)->get();

    return view('index.post', [
        'response' => $response,
        'blogs' => $blogs
      ]);

  }// End Method
}
