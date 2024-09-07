<?php 

namespace App\Http\Requests\Page; 

use App\Http\Requests\Request; 

class StorePageRequest extends Request 
{ 
	public function authorize() 
	{ 
		return true; 
	} 

	public function messages() 
	{ 
		return [ 
			'Title.unique'=>'Title already registered in the system.', 
		]; 
	} 

	public function rules() 
	{ 
		return [ 
			'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'status_coll' => 'required|string'
		]; 
	} 
}