<?php 

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;  
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdatePageRequest;
use App\Http\Requests\User\UpdatePasswordUserRequest;
use App\Models\Page; 
use App\Models\User; 
use App\Models\Role; 
use App\Http\Requests\Page\StorePageRequest;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller 
{   
    public function page()
    {
        $this->authorize('show-page', User::class);

        $pages = Page::paginate(15);

        return view('pages.index', compact('pages'));
    }
    public function index()
    { 
        $this->authorize('show-page', User::class);

        $users = User::paginate(15);

        return view('pages.index', compact('users'));
    }

    public function show($id)
    { 
    	$this->authorize('show-page', User::class);

    	$page = Page::find($id);

    	if(!$page){
        	$this->flashMessage('warning', 'User not found!', 'danger');            
            return redirect()->route('page');
        }  

        return view('pages.show',compact('page'));
    }

    public function create()
    {
        $this->authorize('create-page', User::class);

        $roles = Role::all();

        return view('pages.create',compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            // Handle the image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('uploads', 'public'); // Save in 'storage/app/public/uploads'
            }

            // Create the page with the image path
            $page = Page::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $imagePath ?? null, // Store the path in the DB, or null if no image
                'status_coll' => $request->status_coll,
            ]);

            // Return a JSON response
            return response()->json([
                'status' => 'success',
                'message' => 'Page successfully added!',
                'data' => $page
            ], 201); // 201 Created status code

        } catch (\Exception $e) {
            // Handle any errors that occur during the process
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add page',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error status code
        }
    }


    public function edit($id)
    { 
    	$this->authorize('edit-page', User::class);

    	$user = Page::find($id);

    	if(!$user){
        	$this->flashMessage('warning', 'User not found!', 'danger');            
            return redirect()->route('user');
        }    	               

        return view('pages.edit',compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit-page', User::class);

        $page = Page::find($id);

        if (!$page) {
            $this->flashMessage('warning', 'Page not found!', 'danger');
            return redirect()->route('page');
        }

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($page->image) {
                Storage::disk('public')->delete($page->image);
            }

            // Store the new image and update the image path in the database
            $imagePath = $request->file('image')->store('uploads', 'public');
            $page->image = $imagePath;
        }

        // Update other fields and save the changes
        $page->update($request->except('image')); // Update all other fields except the image

        // Save the updated page model to persist the image path if it was updated
        $page->save();

        $this->flashMessage('check', 'Page updated successfully!', 'success');

        return redirect()->route('page');
    }

    public function updatePassword(UpdatePasswordUserRequest $request,$id)
    {
    	$this->authorize('edit-page', User::class);

    	$user = User::find($id);

        if(!$user){
        	$this->flashMessage('warning', 'User not found!', 'danger');            
            return redirect()->route('user');
        }

        $request->merge(['password' => bcrypt($request->get('password'))]);

        $user->update($request->all());

        $this->flashMessage('check', 'User password updated successfully!', 'success');

        return redirect()->route('user');
    }

    public function editPassword($id)
    { 
    	$this->authorize('edit-page', User::class);

    	$user = User::find($id);

    	if(!$user){
        	$this->flashMessage('warning', 'User not found!', 'danger');            
            return redirect()->route('user');
        }              	               

        return view('users.edit_password',compact('user'));
    }

    public function destroy($id)
    {
        $this->authorize('destroy-page', User::class);

        $page = Page::find($id);

        
        if(!$page){
            $this->flashMessage('warning', 'Page not found!', 'danger');            
            return redirect()->route('page');
        }
        // Delete the image from storage if it exists
        if ($page->image) {
            Storage::disk('public')->delete($page->image);
        }
        $page->delete();

        $this->flashMessage('check', 'Page successfully deleted!', 'success');

        return redirect()->route('page');
    }
}